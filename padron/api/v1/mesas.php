<?php
declare(strict_types=1);

require_once __DIR__.'/bootstrap.php';
require_once dirname(__DIR__, 2).'/lib/PadronConsulta.php';

api_solo_get();
api_autenticar('padron:mesas');
$mesaTexto = trim((string) ($_GET['mesa'] ?? ''));
if (!ctype_digit($mesaTexto) || (int) $mesaTexto <= 0) {
    api_responder(422, null, 'Debe indicar un numero de mesa valido.', 'MESA_INVALIDA');
}
$version = PadronConsulta::versionActiva(api_bd());
if (!$version) {
    api_responder(404, null, 'No existe una version activa del padron.', 'VERSION_NO_DISPONIBLE');
}
$consulta = api_bd()->prepare(
    'SELECT vp.mesa,COUNT(*) electores,MIN(vp.orden) orden_desde,MAX(vp.orden) orden_hasta,
            esc.nombre escuela,esc.domicilio,esc.localidad
     FROM padron_version_personas vp
     LEFT JOIN padron_escuelas esc ON esc.id=vp.escuela_id
     WHERE vp.version_id=? AND vp.mesa=?
     GROUP BY vp.mesa,esc.id,esc.nombre,esc.domicilio,esc.localidad'
);
$consulta->execute([(int) $version['id'], (int) $mesaTexto]);
$fila = $consulta->fetch();
if (!$fila) {
    api_responder(404, null, 'La mesa no integra el padron vigente.', 'MESA_NO_ENCONTRADA');
}
api_responder(200, [
    'mesa' => (int) $fila['mesa'], 'electores' => (int) $fila['electores'],
    'orden_desde' => $fila['orden_desde'] !== null ? (int) $fila['orden_desde'] : null,
    'orden_hasta' => $fila['orden_hasta'] !== null ? (int) $fila['orden_hasta'] : null,
    'escuela' => ['nombre' => $fila['escuela'], 'domicilio' => $fila['domicilio'], 'localidad' => $fila['localidad']],
]);
