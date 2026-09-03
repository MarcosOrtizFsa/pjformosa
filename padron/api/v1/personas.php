<?php
declare(strict_types=1);

require_once __DIR__.'/bootstrap.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    header('Allow: GET');
    api_responder(405, null, 'Método no permitido.', 'METHOD_NOT_ALLOWED');
}

api_autenticar('padron:consultar');

$dniRecibido = preg_replace('/\D+/', '', (string) ($_GET['dni'] ?? ''));
if (!preg_match('/^\d{7,8}$/', $dniRecibido)) {
    api_responder(422, null, 'El DNI debe contener entre siete y ocho dígitos.', 'DNI_INVALIDO');
}
$dni = str_pad($dniRecibido, 8, '0', STR_PAD_LEFT);

/*
 * La consulta separa los datos estables de la persona, su domicilio vigente y
 * la asignación de la elección activa. Al cambiar de elección no se sobrescribe
 * la historia anterior.
 */
$consulta = api_bd()->prepare(
    "SELECT
        p.id, p.dni, p.apellido, p.nombre, p.sexo, p.clase,
        d.domicilio, d.localidad, d.circuito AS domicilio_circuito,
        e.id AS eleccion_id, e.nombre AS eleccion_nombre, e.fecha AS eleccion_fecha,
        ae.mesa, ae.orden, ae.circuito AS electoral_circuito,
        esc.id AS escuela_id, esc.nombre AS escuela_nombre,
        esc.domicilio AS escuela_domicilio, esc.localidad AS escuela_localidad
     FROM padron_personas p
     LEFT JOIN padron_domicilios d
       ON d.id = (
           SELECT d2.id FROM padron_domicilios d2
           WHERE d2.persona_id = p.id AND d2.vigente_hasta IS NULL
           ORDER BY d2.vigente_desde DESC, d2.id DESC LIMIT 1
       )
     LEFT JOIN padron_elecciones e ON e.estado = 'activa'
     LEFT JOIN padron_asignaciones_electorales ae
       ON ae.eleccion_id = e.id AND ae.persona_id = p.id
     LEFT JOIN padron_escuelas esc ON esc.id = ae.escuela_id
     WHERE p.dni = :dni AND p.estado = 1
     ORDER BY e.fecha DESC
     LIMIT 1"
);
$consulta->execute(['dni' => $dni]);
$fila = $consulta->fetch();

if (!$fila) {
    api_responder(404, null, 'No se encontró una persona con ese DNI.', 'PERSONA_NO_ENCONTRADA');
}

api_responder(200, [
    'persona' => [
        'dni' => $fila['dni'],
        'apellido' => $fila['apellido'],
        'nombre' => $fila['nombre'],
        'sexo' => $fila['sexo'],
        'clase' => $fila['clase'] !== null ? (int) $fila['clase'] : null,
    ],
    'domicilio' => [
        'direccion' => $fila['domicilio'],
        'localidad' => $fila['localidad'],
        'circuito' => $fila['domicilio_circuito'],
    ],
    'votacion' => $fila['eleccion_id'] !== null ? [
        'eleccion' => [
            'id' => (int) $fila['eleccion_id'],
            'nombre' => $fila['eleccion_nombre'],
            'fecha' => $fila['eleccion_fecha'],
        ],
        'escuela' => $fila['escuela_id'] !== null ? [
            'id' => (int) $fila['escuela_id'],
            'nombre' => $fila['escuela_nombre'],
            'domicilio' => $fila['escuela_domicilio'],
            'localidad' => $fila['escuela_localidad'],
        ] : null,
        'mesa' => $fila['mesa'] !== null ? (int) $fila['mesa'] : null,
        'orden' => $fila['orden'] !== null ? (int) $fila['orden'] : null,
        'circuito' => $fila['electoral_circuito'],
    ] : null,
]);
