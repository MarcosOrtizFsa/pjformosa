<?php
declare(strict_types=1);

require_once __DIR__.'/bootstrap.php';
require_once dirname(__DIR__, 2).'/lib/PadronConsulta.php';

api_solo_get();
api_autenticar('padron:consultar');

$dni = PadronConsulta::normalizarDni((string) ($_GET['dni'] ?? ''));
if ($dni === null) {
    api_responder(422, null, 'El DNI debe contener entre seis y ocho digitos.', 'DNI_INVALIDO');
}

// Solo la fotografia activa es visible para los proyectos consumidores.
$fila = PadronConsulta::buscarPorDni(api_bd(), $dni);
if (!$fila) {
    api_responder(404, null, 'No se encontro una persona con ese DNI en el padron vigente.', 'PERSONA_NO_ENCONTRADA');
}

api_responder(200, [
    'persona' => [
        'dni' => $fila['dni'], 'tipo_documento' => $fila['tipo_documento'],
        'apellido' => $fila['apellido'], 'nombre' => $fila['nombre'],
        'sexo' => $fila['sexo'], 'clase' => $fila['clase'] !== null ? (int) $fila['clase'] : null,
    ],
    'domicilio' => [
        'direccion' => $fila['domicilio'], 'localidad' => $fila['localidad'],
        'circuito' => $fila['circuito'], 'departamento' => $fila['departamento'],
    ],
    'votacion' => [
        'eleccion' => ['nombre' => $fila['eleccion_nombre'], 'fecha' => $fila['eleccion_fecha']],
        'version' => ['tipo' => $fila['version_tipo'], 'numero' => (int) $fila['version_numero'], 'alcance' => $fila['version_alcance']],
        'escuela' => $fila['escuela_id'] !== null ? [
            'nombre' => $fila['escuela_nombre'], 'domicilio' => $fila['escuela_domic'],
            'localidad' => $fila['escuela_localidad'],
        ] : null,
        'mesa' => $fila['mesa'] !== null ? (int) $fila['mesa'] : null,
        'orden' => $fila['orden'] !== null ? (int) $fila['orden'] : null,
        'nivel_completitud' => (int) $fila['nivel_completitud'],
    ],
]);
