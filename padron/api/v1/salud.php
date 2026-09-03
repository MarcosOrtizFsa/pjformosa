<?php
declare(strict_types=1);

require_once __DIR__.'/bootstrap.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    header('Allow: GET');
    api_responder(405, null, 'Método no permitido.', 'METHOD_NOT_ALLOWED');
}

api_autenticar('sistema:salud');
api_bd()->query('SELECT 1');

api_responder(200, [
    'servicio' => 'padron-api',
    'estado' => 'operativo',
    'hora' => date(DATE_ATOM),
]);
