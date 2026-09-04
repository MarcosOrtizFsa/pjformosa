<?php
declare(strict_types=1);

// Núcleo aislado del importador nuevo. No utiliza el constructor SQL heredado.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once dirname(__DIR__, 4).'/lib/mysql_conect.php';

function importador_usuario_id(): int
{
    return (int) ($_SESSION['sesion_system_03'] ?? 0);
}

function importador_es_administrador(): bool
{
    $modo = (string) ($_SESSION['sesion_system_03_modo'] ?? '');
    $privilegio = (string) ($_SESSION['sesion_system_07'] ?? '');
    return importador_usuario_id() > 0 && ($privilegio === '1' || in_array($modo, ['0', '1'], true));
}

function importador_exigir_acceso(bool $json = true): void
{
    if (importador_es_administrador()) {
        return;
    }

    http_response_code(403);
    if ($json) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'mensaje' => 'Se requiere una sesión administrativa.'], JSON_UNESCAPED_UNICODE);
    } else {
        echo '<!doctype html><meta charset="utf-8"><p>Se requiere una sesión administrativa.</p>';
    }
    exit;
}

function importador_pdo(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host='.HOST.';dbname='.BD.';charset=utf8mb4';
    $pdo = new PDO($dsn, USU, CLA, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function importador_csrf(): string
{
    if (empty($_SESSION['padron_importador_csrf'])) {
        $_SESSION['padron_importador_csrf'] = bin2hex(random_bytes(24));
    }
    return (string) $_SESSION['padron_importador_csrf'];
}

function importador_validar_csrf(): void
{
    $recibido = (string) ($_POST['csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    if (!hash_equals(importador_csrf(), $recibido)) {
        throw new RuntimeException('La sesión venció. Recargá el importador e intentá nuevamente.');
    }
}

function importador_responder(array $datos = [], int $estado = 200): never
{
    http_response_code($estado);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode(['ok' => $estado < 400] + $datos, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function importador_normalizar_texto(?string $valor, int $maximo): string
{
    $valor = trim((string) $valor);
    if (!mb_check_encoding($valor, 'UTF-8')) {
        $valor = mb_convert_encoding($valor, 'UTF-8', 'Windows-1252');
    }
    $valor = preg_replace('/\s+/u', ' ', $valor) ?? $valor;
    return mb_substr($valor, 0, $maximo);
}

function importador_normalizar_dni(?string $valor): string
{
    $dni = ltrim(preg_replace('/\D+/', '', (string) $valor) ?? '', '0');
    // La base usa ocho posiciones; completar a la izquierda evita diferencias
    // entre la carga y la posterior consulta de documentos antiguos.
    return preg_match('/^[0-9]{6,8}$/', $dni) ? str_pad($dni, 8, '0', STR_PAD_LEFT) : $dni;
}

function importador_columnas(): array
{
    return ['dni', 'tipo_dni', 'apellido', 'nombres', 'clase', 'sexo', 'domicilio', 'localidad', 'circuito', 'escuela', 'mesa', 'orden'];
}

function importador_columnas_anteriores(): array
{
    // Compatibilidad temporal con archivos que incluyeron departamento. El
    // valor oficial siempre se obtiene del circuito y del catalogo territorial.
    return ['dni', 'tipo_dni', 'apellido', 'nombres', 'clase', 'sexo', 'domicilio', 'localidad', 'circuito', 'departamento', 'escuela', 'mesa', 'orden'];
}

function importador_normalizar_encabezado(array $fila): array
{
    return array_map(static function ($valor): string {
        $valor = preg_replace('/^\xEF\xBB\xBF/', '', (string) $valor) ?? (string) $valor;
        return strtolower(trim($valor));
    }, $fila);
}

function importador_archivo(int $id, string $archivoInterno): string
{
    // basename evita que un valor alterado en la base salga del directorio autorizado.
    $ruta = dirname(__DIR__, 4).'/archivos/'.basename($archivoInterno);
    if (!is_file($ruta)) {
        throw new RuntimeException('No se encontró el archivo de esta importación.');
    }
    return $ruta;
}

function importador_obtener(PDO $pdo, int $id, bool $bloquear = false): array
{
    $sql = 'SELECT i.*, e.nombre AS eleccion_nombre, e.fecha AS eleccion_fecha, e.estado AS eleccion_estado,
                   v.tipo AS version_tipo, v.numero AS version_numero, v.estado AS version_estado
            FROM padron_importaciones i
            INNER JOIN padron_elecciones e ON e.id = i.eleccion_id
            LEFT JOIN padron_versiones v ON v.id = i.version_id
            WHERE i.id = ?'.($bloquear ? ' FOR UPDATE' : '');
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $fila = $stmt->fetch();
    if (!$fila) {
        throw new RuntimeException('La importación solicitada no existe.');
    }
    return $fila;
}
