<?php
declare(strict_types=1);

require_once __DIR__.'/importador_bootstrap.php';
importador_exigir_acceso();

const IMPORTADOR_LOTE_VALIDACION = 2000;
const IMPORTADOR_LOTE_APLICACION = 1000;
const IMPORTADOR_MAX_BYTES = 536870912; // 512 MiB: el límite real también depende de PHP y Apache.

try {
    importador_validar_csrf();
    $accion = (string) ($_POST['accion'] ?? '');
    $pdo = importador_pdo();

    switch ($accion) {
        case 'subir':
            importar_subir($pdo);
            break;
        case 'validar':
            importar_validar_lote($pdo, (int) ($_POST['importacion_id'] ?? 0));
            break;
        case 'aplicar':
            importar_aplicar_lote($pdo, (int) ($_POST['importacion_id'] ?? 0));
            break;
        case 'activar':
            importar_activar($pdo, (int) ($_POST['importacion_id'] ?? 0));
            break;
        case 'estado':
            importar_estado($pdo, (int) ($_POST['importacion_id'] ?? 0));
            break;
        default:
            throw new RuntimeException('Acción no reconocida.');
    }
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Importador padrón: '.$e->getMessage());
    importador_responder(['mensaje' => $e instanceof RuntimeException ? $e->getMessage() : 'No se pudo completar la operación.'], 422);
}

function importar_subir(PDO $pdo): never
{
    $archivo = $_FILES['archivo'] ?? null;
    if (!is_array($archivo) || (int) ($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Seleccioná un archivo CSV válido.');
    }
    if ((int) $archivo['size'] <= 0 || (int) $archivo['size'] > IMPORTADOR_MAX_BYTES) {
        throw new RuntimeException('El archivo debe pesar menos de 512 MB.');
    }
    $original = basename((string) $archivo['name']);
    if (strtolower(pathinfo($original, PATHINFO_EXTENSION)) !== 'csv') {
        throw new RuntimeException('El formato permitido es CSV.');
    }

    $nombreEleccion = importador_normalizar_texto((string) ($_POST['eleccion_nombre'] ?? ''), 160);
    $fecha = (string) ($_POST['eleccion_fecha'] ?? '');
    $tipo = importador_normalizar_texto((string) ($_POST['eleccion_tipo'] ?? ''), 60);
    $eleccionExistente = (int) ($_POST['eleccion_id'] ?? 0);
    $tipoVersion = (string) ($_POST['version_tipo'] ?? '');
    $alcance = (string) ($_POST['alcance'] ?? 'parcial');
    if (!in_array($tipoVersion, ['anual', 'provisorio', 'definitivo'], true)) {
        throw new RuntimeException('Seleccioná el tipo de versión del padrón.');
    }
    if (!in_array($alcance, ['provincial_completo', 'parcial', 'prueba'], true)) {
        throw new RuntimeException('Selecciona el alcance real del archivo.');
    }
    $fechaValida = DateTimeImmutable::createFromFormat('!Y-m-d', $fecha);
    if ($eleccionExistente <= 0 && ($nombreEleccion === '' || !$fechaValida || $fechaValida->format('Y-m-d') !== $fecha)) {
        throw new RuntimeException('Indicá el nombre y una fecha válida para la elección.');
    }

    $directorio = dirname(__DIR__, 4).'/archivos';
    if (!is_dir($directorio) || !is_writable($directorio)) {
        throw new RuntimeException('El directorio privado de importaciones no está disponible.');
    }
    $interno = bin2hex(random_bytes(16)).'.csv';
    $destino = $directorio.'/'.$interno;
    if (!move_uploaded_file((string) $archivo['tmp_name'], $destino)) {
        throw new RuntimeException('No se pudo guardar el archivo subido.');
    }

    try {
        $handle = fopen($destino, 'rb');
        if ($handle === false) {
            throw new RuntimeException('No se pudo leer el CSV.');
        }
        $encabezado = fgetcsv($handle, 0, ';', '"', '\\');
        $posicion = ftell($handle);
        fclose($handle);
        $columnasRecibidas = is_array($encabezado) ? importador_normalizar_encabezado($encabezado) : [];
        if ($columnasRecibidas !== importador_columnas() && $columnasRecibidas !== importador_columnas_anteriores()) {
            throw new RuntimeException('Las columnas no coinciden con el modelo requerido. Usá punto y coma como separador.');
        }

        $hash = hash_file('sha256', $destino);
        $pdo->beginTransaction();
        if ($eleccionExistente > 0) {
            $stmt = $pdo->prepare('SELECT id FROM padron_elecciones WHERE id=?');
            $stmt->execute([$eleccionExistente]);
            $eleccionId = (int) $stmt->fetchColumn();
            if ($eleccionId <= 0) throw new RuntimeException('La elección seleccionada no existe.');
        } else {
            $stmt = $pdo->prepare('INSERT INTO padron_elecciones (nombre, fecha, tipo, estado) VALUES (?, ?, ?, ?)');
            $stmt->execute([$nombreEleccion, $fecha, $tipo ?: null, 'borrador']);
            $eleccionId = (int) $pdo->lastInsertId();
        }

        $stmt = $pdo->prepare('SELECT COALESCE(MAX(numero),0)+1 FROM padron_versiones WHERE eleccion_id=? AND tipo=?');
        $stmt->execute([$eleccionId, $tipoVersion]);
        $numeroVersion = (int) $stmt->fetchColumn();
        $stmt = $pdo->prepare('INSERT INTO padron_versiones (eleccion_id,tipo,numero,alcance,estado) VALUES (?,?,?,?,?)');
        $stmt->execute([$eleccionId, $tipoVersion, $numeroVersion, $alcance, 'preparando']);
        $versionId = (int) $pdo->lastInsertId();

        $stmt = $pdo->prepare('INSERT INTO padron_importaciones
            (eleccion_id, version_id, archivo_original, archivo_interno, hash_sha256, delimitador, posicion_bytes, siguiente_fila, estado, iniciado_por)
            VALUES (?, ?, ?, ?, ?, ?, ?, 2, ?, ?)');
        $stmt->execute([$eleccionId, $versionId, $original, $interno, $hash, ';', $posicion, 'subido', importador_usuario_id()]);
        $id = (int) $pdo->lastInsertId();
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        @unlink($destino);
        throw $e;
    }

    importador_responder(['mensaje' => 'Archivo recibido. Comienza la validación.', 'importacion' => importar_resumen($pdo, $id)]);
}

function importar_validar_lote(PDO $pdo, int $id): never
{
    if ($id <= 0) {
        throw new RuntimeException('Importación inválida.');
    }
    $importacion = importador_obtener($pdo, $id);
    if (!in_array($importacion['estado'], ['subido', 'validando'], true)) {
        importar_estado($pdo, $id);
    }
    $ruta = importador_archivo($id, $importacion['archivo_interno']);
    $handle = fopen($ruta, 'rb');
    if ($handle === false || fseek($handle, (int) $importacion['posicion_bytes']) !== 0) {
        throw new RuntimeException('No se pudo continuar leyendo el CSV.');
    }

    $pdo->beginTransaction();
    $pdo->prepare("UPDATE padron_importaciones SET estado = 'validando' WHERE id = ?")->execute([$id]);
    $insert = $pdo->prepare('INSERT INTO padron_importacion_filas
        (importacion_id, numero_fila, dni, tipo_documento, apellido, nombre, sexo, clase, domicilio, localidad, circuito, departamento, escuela, mesa, orden, es_valida, errores, nivel_completitud, advertencias)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

    $numero = (int) $importacion['siguiente_fila'];
    $procesadas = 0;
    while ($procesadas < IMPORTADOR_LOTE_VALIDACION && ($fila = fgetcsv($handle, 0, ';', '"', '\\')) !== false) {
        // Las líneas completamente vacías se ignoran, pero conservamos el número físico para diagnosticar el archivo.
        if (count($fila) === 1 && trim((string) $fila[0]) === '') {
            $numero++;
            continue;
        }
        [$datos, $errores, $advertencias, $nivel] = importar_validar_fila($fila);
        $insert->execute([
            $id, $numero, $datos['dni'], $datos['tipo_dni'], $datos['apellido'], $datos['nombres'],
            $datos['sexo'], $datos['clase'], $datos['domicilio'], $datos['localidad'],
            $datos['circuito'], $datos['departamento'], $datos['escuela'], $datos['mesa'], $datos['orden'],
            $errores === [] ? 1 : 0, $errores === [] ? null : implode(' ', $errores),
            $nivel, $advertencias === [] ? null : implode(' ', $advertencias),
        ]);
        $numero++;
        $procesadas++;
    }
    $fin = feof($handle);
    $posicion = ftell($handle);
    fclose($handle);

    $pdo->prepare('UPDATE padron_importaciones SET posicion_bytes = ?, siguiente_fila = ?, total_filas = total_filas + ? WHERE id = ?')
        ->execute([$posicion, $numero, $procesadas, $id]);

    if ($fin) {
        // Los duplicados se conservan: la última aparición actualizará a la anterior.
        // Se advierten para que el operador pueda revisar diferencias accidentales.
        $pdo->prepare("UPDATE padron_importacion_filas f
            INNER JOIN (
                SELECT dni FROM padron_importacion_filas
                WHERE importacion_id = ? AND dni <> '' GROUP BY dni HAVING COUNT(*) > 1
            ) d ON d.dni = f.dni
            SET f.advertencias = CONCAT_WS(' ', NULLIF(f.advertencias, ''), 'DNI repetido; prevalecerá la última fila.')
            WHERE f.importacion_id = ?")->execute([$id, $id]);
        importar_recontar($pdo, $id);
        $pdo->prepare("UPDATE padron_importaciones SET estado = 'validado', mensaje = 'Validación finalizada.' WHERE id = ?")->execute([$id]);
        $pdo->prepare("UPDATE padron_elecciones e INNER JOIN padron_importaciones i ON i.eleccion_id=e.id SET e.estado='validando' WHERE i.id=? AND e.estado='borrador'")->execute([$id]);
    } else {
        importar_recontar($pdo, $id);
    }
    $pdo->commit();
    importador_responder(['continuar' => !$fin, 'importacion' => importar_resumen($pdo, $id)]);
}

function importar_validar_fila(array $fila): array
{
    $cantidadColumnas = count($fila);
    $tieneDepartamento = $cantidadColumnas >= 13;
    $fila = array_pad($fila, 13, '');
    $datos = [
        'dni' => importador_normalizar_dni((string) $fila[0]),
        'tipo_dni' => strtoupper(importador_normalizar_texto((string) $fila[1], 12)),
        'apellido' => importador_normalizar_texto((string) $fila[2], 100),
        'nombres' => importador_normalizar_texto((string) $fila[3], 120),
        'clase' => trim((string) $fila[4]),
        'sexo' => strtoupper(importador_normalizar_texto((string) $fila[5], 10)),
        'domicilio' => importador_normalizar_texto((string) $fila[6], 190),
        'localidad' => importador_normalizar_texto((string) $fila[7], 120),
        'circuito' => importador_normalizar_texto((string) $fila[8], 12),
        'departamento' => $tieneDepartamento ? importador_normalizar_texto((string) $fila[9], 120) : '',
        'escuela' => importador_normalizar_texto((string) $fila[$tieneDepartamento ? 10 : 9], 190),
        'mesa' => trim((string) $fila[$tieneDepartamento ? 11 : 10]),
        'orden' => trim((string) $fila[$tieneDepartamento ? 12 : 11]),
    ];
    $errores = [];
    $advertencias = [];
    if (!in_array($cantidadColumnas, [12, 13], true)) $advertencias[] = 'La cantidad de columnas no coincide con el modelo.';
    if (!preg_match('/^[0-9]{8}$/', $datos['dni'])) $errores[] = 'DNI inválido.';
    if ($datos['apellido'] === '') $errores[] = 'Falta apellido.';
    if ($datos['nombres'] === '') $errores[] = 'Faltan nombres.';
    $anio = (int) date('Y');
    if ($datos['clase'] !== '' && (!preg_match('/^[0-9]{4}$/', $datos['clase']) || (int) $datos['clase'] < 1900 || (int) $datos['clase'] > $anio)) {
        $advertencias[] = 'Clase inválida; se guardará vacía.';
        $datos['clase'] = '';
    }
    if ($datos['mesa'] !== '' && (!ctype_digit($datos['mesa']) || (int) $datos['mesa'] <= 0)) {
        $advertencias[] = 'Mesa inválida; se guardará vacía.';
        $datos['mesa'] = '';
    }
    if ($datos['orden'] !== '' && (!ctype_digit($datos['orden']) || (int) $datos['orden'] <= 0)) {
        $advertencias[] = 'Orden inválido; se guardará vacío.';
        $datos['orden'] = '';
    }

    $opcionales = ['tipo_dni', 'clase', 'sexo', 'domicilio', 'localidad', 'circuito', 'departamento', 'escuela', 'mesa', 'orden'];
    $completos = count(array_filter($opcionales, static fn(string $campo): bool => $datos[$campo] !== ''));
    $nivel = $completos === count($opcionales) ? 1 : ($completos === 0 ? 3 : 2);
    if ($nivel > 1) {
        $advertencias[] = $nivel === 3 ? 'Sólo contiene los datos mínimos.' : 'La fila tiene datos opcionales incompletos.';
    }
    return [$datos, $errores, $advertencias, $nivel];
}

function importar_recontar(PDO $pdo, int $id): void
{
    $pdo->prepare('UPDATE padron_importaciones i SET
        filas_validas = (SELECT COUNT(*) FROM padron_importacion_filas f WHERE f.importacion_id=i.id AND f.es_valida=1),
        filas_rechazadas = (SELECT COUNT(*) FROM padron_importacion_filas f WHERE f.importacion_id=i.id AND f.es_valida=0),
        nivel_1 = (SELECT COUNT(*) FROM padron_importacion_filas f WHERE f.importacion_id=i.id AND f.es_valida=1 AND f.nivel_completitud=1),
        nivel_2 = (SELECT COUNT(*) FROM padron_importacion_filas f WHERE f.importacion_id=i.id AND f.es_valida=1 AND f.nivel_completitud=2),
        nivel_3 = (SELECT COUNT(*) FROM padron_importacion_filas f WHERE f.importacion_id=i.id AND f.es_valida=1 AND f.nivel_completitud=3)
        WHERE i.id=?')->execute([$id]);
}

function importar_aplicar_lote(PDO $pdo, int $id): never
{
    $pdo->beginTransaction();
    $importacion = importador_obtener($pdo, $id, true);
    if (!in_array($importacion['estado'], ['validado', 'importando'], true)) {
        $pdo->commit();
        importar_estado($pdo, $id);
    }

    $stmt = $pdo->prepare('SELECT * FROM padron_importacion_filas
        WHERE importacion_id=? AND es_valida=1 AND id>? ORDER BY id LIMIT '.IMPORTADOR_LOTE_APLICACION);
    $stmt->execute([$id, (int) $importacion['ultima_fila_id']]);
    $filas = $stmt->fetchAll();
    $eleccionId = (int) $importacion['eleccion_id'];
    $versionId = (int) $importacion['version_id'];
    if ($versionId <= 0) throw new RuntimeException('La importación no tiene una versión de destino.');
    $insertadas = $actualizadas = $asignaciones = 0;
    $ultima = (int) $importacion['ultima_fila_id'];

    $buscarPersona = $pdo->prepare('SELECT id, apellido, nombre, sexo, clase, tipo_documento FROM padron_personas WHERE dni=?');
    $insertarPersona = $pdo->prepare('INSERT INTO padron_personas (dni, apellido, nombre, sexo, clase, tipo_documento) VALUES (?, ?, ?, ?, ?, ?)');
    // Los valores opcionales vacíos nunca borran información confiable existente.
    $actualizarPersona = $pdo->prepare('UPDATE padron_personas SET apellido=?, nombre=?, sexo=COALESCE(?,sexo), clase=COALESCE(?,clase), tipo_documento=COALESCE(?,tipo_documento), estado=1 WHERE id=?');
    $buscarEscuela = $pdo->prepare('SELECT id FROM padron_escuelas WHERE clave_importacion=?');
    $insertarEscuela = $pdo->prepare('INSERT INTO padron_escuelas (clave_importacion, nombre, localidad) VALUES (?, ?, ?)');
    $buscarAsignacion = $pdo->prepare('SELECT id FROM padron_version_personas WHERE version_id=? AND persona_id=?');
    $buscarTerritorio = $pdo->prepare('SELECT id FROM padron_territorios WHERE circuito=? AND activo=1');
    $guardarAsignacion = $pdo->prepare('INSERT INTO padron_version_personas
        (version_id,persona_id,domicilio,localidad,circuito,departamento,territorio_id,escuela_id,mesa,orden,nivel_completitud)
        VALUES (?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE
        domicilio=COALESCE(VALUES(domicilio),domicilio), localidad=COALESCE(VALUES(localidad),localidad),
        circuito=COALESCE(VALUES(circuito),circuito), departamento=COALESCE(VALUES(departamento),departamento),
        territorio_id=COALESCE(VALUES(territorio_id),territorio_id),
        escuela_id=COALESCE(VALUES(escuela_id),escuela_id),
        mesa=COALESCE(VALUES(mesa),mesa), orden=COALESCE(VALUES(orden),orden), nivel_completitud=LEAST(nivel_completitud,VALUES(nivel_completitud))');

    foreach ($filas as $fila) {
        $buscarPersona->execute([$fila['dni']]);
        $persona = $buscarPersona->fetch();
        if (!$persona) {
            $insertarPersona->execute([$fila['dni'], $fila['apellido'], $fila['nombre'], $fila['sexo'] ?: null, $fila['clase'] !== '' ? (int) $fila['clase'] : null, $fila['tipo_documento'] ?: null]);
            $personaId = (int) $pdo->lastInsertId();
            $insertadas++;
        } else {
            $personaId = (int) $persona['id'];
            $actualizarPersona->execute([$fila['apellido'], $fila['nombre'], $fila['sexo'] ?: null, $fila['clase'] !== '' ? (int) $fila['clase'] : null, $fila['tipo_documento'] ?: null, $personaId]);
            $actualizadas++;
        }

        $escuelaId = null;
        if ($fila['escuela'] !== '') {
            // La localidad del CSV corresponde al domicilio del votante; no se
            // usa para identificar la escuela porque generaría duplicados.
            $claveEscuela = hash('sha256', mb_strtoupper(trim($fila['escuela']), 'UTF-8'));
            $buscarEscuela->execute([$claveEscuela]);
            $escuelaId = $buscarEscuela->fetchColumn();
            if (!$escuelaId) {
                $insertarEscuela->execute([$claveEscuela, $fila['escuela'], null]);
                $escuelaId = (int) $pdo->lastInsertId();
            }
        }
        $mesa = $fila['mesa'] !== '' ? (int) $fila['mesa'] : null;
        $orden = $fila['orden'] !== '' ? (int) $fila['orden'] : null;
        $territorioId = null;
        if ($fila['circuito'] !== '') {
            // Circuito es la clave oficial unica del catalogo territorial.
            $buscarTerritorio->execute([mb_strtoupper(trim($fila['circuito']), 'UTF-8')]);
            $territorioId = $buscarTerritorio->fetchColumn() ?: null;
        }
        // Toda persona válida integra la fotografía, incluso sin datos electorales.
        $buscarAsignacion->execute([$versionId, $personaId]);
        if (!$buscarAsignacion->fetchColumn()) $asignaciones++;
        $guardarAsignacion->execute([
            $versionId, $personaId, $fila['domicilio'] ?: null, $fila['localidad'] ?: null,
            $fila['circuito'] ?: null, $fila['departamento'] ?: null, $territorioId, $escuelaId, $mesa, $orden, (int) $fila['nivel_completitud'],
        ]);
        $ultima = (int) $fila['id'];
    }

    $cantidad = count($filas);
    $pdo->prepare("UPDATE padron_importaciones SET estado='importando', ultima_fila_id=?, filas_importadas=filas_importadas+?, personas_insertadas=personas_insertadas+?, personas_actualizadas=personas_actualizadas+?, asignaciones_insertadas=asignaciones_insertadas+? WHERE id=?")
        ->execute([$ultima, $cantidad, $insertadas, $actualizadas, $asignaciones, $id]);
    $continuar = $cantidad === IMPORTADOR_LOTE_APLICACION;
    if (!$continuar) {
        $pdo->prepare("UPDATE padron_importaciones SET estado='completado', mensaje='Importación aplicada. Pendiente de activación.', finalizado_en=NOW() WHERE id=?")->execute([$id]);
        $pdo->prepare("UPDATE padron_versiones SET estado='lista', total_personas=(SELECT COUNT(*) FROM padron_version_personas WHERE version_id=?) WHERE id=?")->execute([$versionId, $versionId]);
        // Las filas válidas ya están en la fotografía definitiva y sólo duplicarían espacio.
        $pdo->prepare('DELETE FROM padron_importacion_filas WHERE importacion_id=? AND es_valida=1')->execute([$id]);
    }
    $pdo->commit();
    importador_responder(['continuar' => $continuar, 'importacion' => importar_resumen($pdo, $id)]);
}

function importar_activar(PDO $pdo, int $id): never
{
    // Se recopilan antes de borrar las filas; los archivos físicos se quitan
    // únicamente después de confirmar la transacción de activación.
    $archivosStmt = $pdo->query("SELECT archivo_interno FROM padron_importaciones WHERE estado='completado'");
    $archivosParaEliminar = $archivosStmt->fetchAll(PDO::FETCH_COLUMN);
    $pdo->beginTransaction();
    $importacion = importador_obtener($pdo, $id, true);
    if ($importacion['estado'] !== 'completado') {
        throw new RuntimeException('La importación debe estar completada antes de activar la elección.');
    }
    $eleccionId = (int) $importacion['eleccion_id'];
    $versionId = (int) $importacion['version_id'];
    if ($versionId <= 0 || !in_array($importacion['version_estado'], ['lista', 'activa'], true)) {
        throw new RuntimeException('La importación no tiene una versión completa lista para activar.');
    }
    $pdo->prepare("UPDATE padron_versiones SET estado='archivada' WHERE estado='activa' AND id<>?")->execute([$versionId]);
    $pdo->prepare("UPDATE padron_versiones SET estado='activa', activado_en=NOW() WHERE id=?")->execute([$versionId]);
    $pdo->prepare("UPDATE padron_elecciones SET estado='archivada' WHERE estado='activa' AND id<>?")->execute([$eleccionId]);
    $pdo->prepare("UPDATE padron_elecciones SET estado='activa' WHERE id=?")->execute([$eleccionId]);
    // Publicamos el domicilio solo al activar la fotografia completa del lote.
    $pdo->prepare("DELETE d FROM padron_domicilios d
        INNER JOIN padron_version_personas vp ON vp.persona_id=d.persona_id
        WHERE vp.version_id=? AND d.vigente_hasta IS NULL")->execute([$versionId]);
    $pdo->prepare("INSERT INTO padron_domicilios
        (persona_id,domicilio,territorio_id,vigente_desde,fuente)
        SELECT persona_id,domicilio,territorio_id,CURDATE(),CONCAT('version:',?)
        FROM padron_version_personas WHERE version_id=?")->execute([$versionId, $versionId]);
    $pdo->exec("DELETE f FROM padron_importacion_filas f INNER JOIN padron_importaciones i ON i.id=f.importacion_id WHERE i.estado='completado'");
    $pdo->prepare("UPDATE padron_importaciones SET mensaje='Versión activada y temporales eliminados.' WHERE id=?")->execute([$id]);
    // Sólo se conserva la fotografía activa; las anteriores ya tienen su resumen de auditoría.
    $pdo->prepare("DELETE FROM padron_versiones WHERE estado='archivada'")->execute();
    $pdo->exec("DELETE e FROM padron_escuelas e LEFT JOIN padron_version_personas vp ON vp.escuela_id=e.id WHERE vp.id IS NULL");
    // Se retiran personas ausentes del padrón vigente, salvo las vinculadas a
    // afiliaciones, avales, documentos o asignaciones electorales heredadas.
    $pdo->exec("DELETE p FROM padron_personas p
        LEFT JOIN padron_version_personas vp ON vp.persona_id=p.id
        LEFT JOIN padron_afiliaciones af ON af.persona_id=p.id
        LEFT JOIN padron_avales av ON av.persona_id=p.id
        LEFT JOIN padron_documentos doc ON doc.persona_id=p.id
        LEFT JOIN padron_asignaciones_electorales ae ON ae.persona_id=p.id
        WHERE vp.id IS NULL AND af.id IS NULL AND av.id IS NULL AND doc.id IS NULL AND ae.id IS NULL");
    $pdo->commit();
    foreach ($archivosParaEliminar as $archivoInterno) {
        $ruta = dirname(__DIR__, 4).'/archivos/'.basename((string) $archivoInterno);
        if (is_file($ruta)) @unlink($ruta);
    }
    importador_responder(['mensaje' => 'La elección quedó activa.', 'importacion' => importar_resumen($pdo, $id)]);
}

function importar_estado(PDO $pdo, int $id): never
{
    importador_responder(['importacion' => importar_resumen($pdo, $id)]);
}

function importar_resumen(PDO $pdo, int $id): array
{
    $fila = importador_obtener($pdo, $id);
    $errores = $pdo->prepare('SELECT numero_fila, dni, errores, advertencias FROM padron_importacion_filas WHERE importacion_id=? AND (es_valida=0 OR advertencias IS NOT NULL) ORDER BY es_valida ASC, numero_fila LIMIT 20');
    $errores->execute([$id]);
    $niveles = [1 => (int) $fila['nivel_1'], 2 => (int) $fila['nivel_2'], 3 => (int) $fila['nivel_3']];
    return [
        'id' => (int) $fila['id'],
        'estado' => $fila['estado'],
        'archivo' => $fila['archivo_original'],
        'eleccion' => $fila['eleccion_nombre'],
        'fecha' => $fila['eleccion_fecha'],
        'eleccion_estado' => $fila['eleccion_estado'],
        'version_id' => (int) $fila['version_id'],
        'version_tipo' => $fila['version_tipo'],
        'version_numero' => (int) $fila['version_numero'],
        'version_estado' => $fila['version_estado'],
        'total' => (int) $fila['total_filas'],
        'validas' => (int) $fila['filas_validas'],
        'rechazadas' => (int) $fila['filas_rechazadas'],
        'importadas' => (int) $fila['filas_importadas'],
        'personas_insertadas' => (int) $fila['personas_insertadas'],
        'personas_actualizadas' => (int) $fila['personas_actualizadas'],
        'asignaciones_insertadas' => (int) $fila['asignaciones_insertadas'],
        'niveles' => $niveles,
        'mensaje' => $fila['mensaje'],
        'errores' => $errores->fetchAll(),
    ];
}
