<?php
declare(strict_types=1);

require_once __DIR__.'/bootstrap.php';
require_once dirname(__DIR__, 2).'/lib/PadronConsulta.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    header('Allow: GET');
    api_responder(405, null, 'Método no permitido.', 'METHOD_NOT_ALLOWED');
}

api_autenticar('padron:consultar');

$dni = PadronConsulta::normalizarDni((string) ($_GET['dni'] ?? ''));
if ($dni === null) {
    api_responder(422, null, 'El DNI debe contener entre seis y ocho dígitos.', 'DNI_INVALIDO');
}

/*
 * La consulta usa exclusivamente la fotografía activa. Una versión en carga
 * nunca altera lo que ven las aplicaciones consumidoras.
 */
$fila = PadronConsulta::buscarPorDni(api_bd(), $dni);

if (!$fila) {
    api_responder(404, null, 'No se encontró una persona con ese DNI.', 'PERSONA_NO_ENCONTRADA');
}

api_responder(200, [
    'persona' => [
        'dni' => $fila['dni'],
        'tipo_documento' => $fila['tipo_documento'],
        'apellido' => $fila['apellido'],
        'nombre' => $fila['nombre'],
        'sexo' => $fila['sexo'],
        'clase' => $fila['clase'] !== null ? (int) $fila['clase'] : null,
    ],
    'domicilio' => [
        'direccion' => $fila['domicilio'],
        'localidad' => $fila['localidad'],
        'circuito' => $fila['circuito'],
    ],
    'votacion' => $fila['eleccion_id'] !== null ? [
        'eleccion' => [
            'id' => (int) $fila['eleccion_id'],
            'nombre' => $fila['eleccion_nombre'],
            'fecha' => $fila['eleccion_fecha'],
        ],
        'version' => [
            'id' => (int) $fila['version_id'],
            'tipo' => $fila['version_tipo'],
            'numero' => (int) $fila['version_numero'],
        ],
        'escuela' => $fila['escuela_id'] !== null ? [
            'id' => (int) $fila['escuela_id'],
            'nombre' => $fila['escuela_nombre'],
            'domicilio' => $fila['escuela_domicilio'],
            'localidad' => $fila['escuela_localidad'],
        ] : null,
        'mesa' => $fila['mesa'] !== null ? (int) $fila['mesa'] : null,
        'orden' => $fila['orden'] !== null ? (int) $fila['orden'] : null,
        'nivel_completitud' => (int) $fila['nivel_completitud'],
    ] : null,
]);
