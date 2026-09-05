<?php
declare(strict_types=1);

require_once __DIR__.'/bootstrap.php';
require_once dirname(__DIR__, 2).'/lib/PadronConsulta.php';

api_solo_get();
api_autenticar('partido:consultar');
$dni = PadronConsulta::normalizarDni((string) ($_GET['dni'] ?? ''));
if ($dni === null) {
    api_responder(422, null, 'El DNI debe contener entre seis y ocho digitos.', 'DNI_INVALIDO');
}

$consulta = api_bd()->prepare(
    "SELECT p.id,p.dni,p.apellido,p.nombre,p.sexo,p.clase,
            af.numero_afiliado,af.folio afiliacion_folio,af.fecha_afiliacion,af.estado afiliacion_estado,
            EXISTS(SELECT 1 FROM padron_version_personas vp
                   INNER JOIN padron_versiones v ON v.id=vp.version_id AND v.estado='activa'
                   WHERE vp.persona_id=p.id) en_padron
     FROM padron_personas p
     LEFT JOIN padron_afiliaciones af ON af.persona_id=p.id
     WHERE p.dni=? LIMIT 1"
);
$consulta->execute([$dni]);
$persona = $consulta->fetch();

$tramite = api_bd()->prepare(
    "SELECT anio,estado,fecha FROM padron_tramites_afiliacion
     WHERE dni=? ORDER BY fecha DESC,id DESC LIMIT 1"
);
$tramite->execute([$dni]);
$tramiteFila = $tramite->fetch() ?: null;

if (!$persona) {
    api_responder(200, [
        'dni' => $dni, 'persona' => null, 'en_padron_electoral' => false,
        'afiliacion' => ['estado' => 'no_afiliado'],
        'tramite' => $tramiteFila ? ['estado' => $tramiteFila['estado'], 'fecha' => $tramiteFila['fecha'], 'anio' => (int) $tramiteFila['anio']] : null,
        'aval' => null, 'documentos' => ['cantidad' => 0, 'tipos' => []],
        'estado_general' => $tramiteFila ? 'afiliacion_en_tramite' : 'fuera_del_padron',
    ]);
}

$personaId = (int) $persona['id'];
$ultimoAval = api_bd()->prepare(
    "SELECT a.estado,f.numero folio,f.fecha,c.nombre campana,c.anio
     FROM padron_avales a
     INNER JOIN padron_folios_avales f ON f.id=a.folio_id
     INNER JOIN padron_campanas_avales c ON c.id=a.campana_id
     WHERE a.persona_id=? AND a.estado<>'anulado'
     ORDER BY f.fecha DESC,a.id DESC LIMIT 1"
);
$ultimoAval->execute([$personaId]);
$aval = $ultimoAval->fetch() ?: null;
$documentos = api_bd()->prepare(
    'SELECT tipo,COUNT(*) cantidad FROM padron_documentos
     WHERE persona_id=? AND eliminado_en IS NULL GROUP BY tipo ORDER BY tipo'
);
$documentos->execute([$personaId]);
$tipos = [];
$cantidadDocumentos = 0;
foreach ($documentos->fetchAll() as $documento) {
    $tipos[] = $documento['tipo'];
    $cantidadDocumentos += (int) $documento['cantidad'];
}
$afiliacionEstado = $persona['afiliacion_estado'] ?: 'no_afiliado';
$estadoGeneral = !(bool) $persona['en_padron'] ? 'fuera_del_padron'
    : ($afiliacionEstado === 'activa' ? ($aval ? 'afiliado_avalado' : 'afiliado_sin_aval')
        : ($afiliacionEstado === 'pendiente' || $tramiteFila ? 'afiliacion_en_tramite' : 'no_afiliado'));

api_responder(200, [
    'dni' => $dni,
    'persona' => ['apellido' => $persona['apellido'], 'nombre' => $persona['nombre'], 'sexo' => $persona['sexo'], 'clase' => $persona['clase'] !== null ? (int) $persona['clase'] : null],
    'en_padron_electoral' => (bool) $persona['en_padron'],
    'afiliacion' => [
        'estado' => $afiliacionEstado, 'numero' => $persona['numero_afiliado'],
        'folio' => $persona['afiliacion_folio'], 'fecha' => $persona['fecha_afiliacion'],
    ],
    'tramite' => $tramiteFila ? ['estado' => $tramiteFila['estado'], 'fecha' => $tramiteFila['fecha'], 'anio' => (int) $tramiteFila['anio']] : null,
    'aval' => $aval ? ['estado' => $aval['estado'], 'folio' => (int) $aval['folio'], 'fecha' => $aval['fecha'], 'campana' => $aval['campana'], 'anio' => (int) $aval['anio']] : null,
    'documentos' => ['cantidad' => $cantidadDocumentos, 'tipos' => $tipos],
    'estado_general' => $estadoGeneral,
]);
