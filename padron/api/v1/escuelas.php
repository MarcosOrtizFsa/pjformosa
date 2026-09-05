<?php
declare(strict_types=1);

require_once __DIR__.'/bootstrap.php';
require_once dirname(__DIR__, 2).'/lib/PadronConsulta.php';

api_solo_get();
api_autenticar('padron:mesas');
$nombre = trim((string) ($_GET['nombre'] ?? ''));
if (mb_strlen($nombre, 'UTF-8') < 3 || mb_strlen($nombre, 'UTF-8') > 100) {
    api_responder(422, null, 'El nombre debe contener entre 3 y 100 caracteres.', 'ESCUELA_INVALIDA');
}
$version = PadronConsulta::versionActiva(api_bd());
if (!$version) {
    api_responder(404, null, 'No existe una version activa del padron.', 'VERSION_NO_DISPONIBLE');
}
$consulta = api_bd()->prepare(
    'SELECT esc.id,esc.nombre,esc.domicilio,esc.localidad,vp.mesa,COUNT(*) electores,
            MIN(vp.orden) orden_desde,MAX(vp.orden) orden_hasta
     FROM padron_escuelas esc
     INNER JOIN padron_version_personas vp ON vp.escuela_id=esc.id AND vp.version_id=?
     WHERE esc.nombre LIKE ?
     GROUP BY esc.id,esc.nombre,esc.domicilio,esc.localidad,vp.mesa
     ORDER BY esc.nombre,vp.mesa LIMIT 250'
);
$consulta->execute([(int) $version['id'], '%'.$nombre.'%']);
$escuelas = [];
foreach ($consulta->fetchAll() as $fila) {
    $id = (int) $fila['id'];
    if (!isset($escuelas[$id])) {
        if (count($escuelas) >= 25) continue;
        $escuelas[$id] = ['nombre' => $fila['nombre'], 'domicilio' => $fila['domicilio'], 'localidad' => $fila['localidad'], 'mesas' => []];
    }
    $escuelas[$id]['mesas'][] = [
        'numero' => (int) $fila['mesa'], 'electores' => (int) $fila['electores'],
        'orden_desde' => $fila['orden_desde'] !== null ? (int) $fila['orden_desde'] : null,
        'orden_hasta' => $fila['orden_hasta'] !== null ? (int) $fila['orden_hasta'] : null,
    ];
}
if ($escuelas === []) {
    api_responder(404, null, 'No se encontraron escuelas en el padron vigente.', 'ESCUELA_NO_ENCONTRADA');
}
api_responder(200, ['resultados' => array_values($escuelas), 'cantidad' => count($escuelas)]);
