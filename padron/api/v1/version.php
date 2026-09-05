<?php
declare(strict_types=1);

require_once __DIR__.'/bootstrap.php';
require_once dirname(__DIR__, 2).'/lib/PadronConsulta.php';

api_solo_get();
api_autenticar('padron:consultar');
$version = PadronConsulta::versionActiva(api_bd());
if (!$version) {
    api_responder(404, null, 'No existe una version activa del padron.', 'VERSION_NO_DISPONIBLE');
}
api_responder(200, [
    'tipo' => $version['tipo'], 'numero' => (int) $version['numero'], 'alcance' => $version['alcance'],
    'total_personas' => (int) $version['total_personas'], 'activado_en' => $version['activado_en'],
    'eleccion' => ['nombre' => $version['eleccion_nombre'], 'fecha' => $version['eleccion_fecha']],
]);
