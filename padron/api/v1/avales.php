<?php
declare(strict_types=1);

require_once __DIR__.'/bootstrap.php';
require_once dirname(__DIR__, 2).'/lib/PadronConsulta.php';

api_solo_get();
api_autenticar('partido:avales');
$dni = PadronConsulta::normalizarDni((string) ($_GET['dni'] ?? ''));
if ($dni === null) {
    api_responder(422, null, 'El DNI debe contener entre seis y ocho digitos.', 'DNI_INVALIDO');
}
$consulta = api_bd()->prepare(
    "SELECT p.apellido,p.nombre,a.estado,a.posicion,f.numero folio,f.fecha,f.estado folio_estado,
            c.nombre campana,c.anio,s.nombre sede
     FROM padron_personas p
     INNER JOIN padron_avales a ON a.persona_id=p.id
     INNER JOIN padron_folios_avales f ON f.id=a.folio_id
     INNER JOIN padron_campanas_avales c ON c.id=a.campana_id
     LEFT JOIN padron_sedes_avales s ON s.id=f.sede_id
     WHERE p.dni=? AND a.estado<>'anulado'
     ORDER BY f.fecha DESC,a.id DESC"
);
$consulta->execute([$dni]);
$filas = $consulta->fetchAll();
if ($filas === []) {
    api_responder(404, null, 'No se encontraron avales para el DNI indicado.', 'AVALES_NO_ENCONTRADOS');
}
$avales = array_map(static fn(array $fila): array => [
    'campana' => $fila['campana'], 'anio' => (int) $fila['anio'],
    'folio' => (int) $fila['folio'], 'fecha' => $fila['fecha'], 'sede' => $fila['sede'],
    'posicion' => $fila['posicion'] !== null ? (int) $fila['posicion'] : null,
    'estado' => $fila['estado'], 'folio_estado' => $fila['folio_estado'],
], $filas);
api_responder(200, [
    'persona' => ['dni' => $dni, 'apellido' => $filas[0]['apellido'], 'nombre' => $filas[0]['nombre']],
    'avales' => $avales, 'cantidad' => count($avales),
]);
