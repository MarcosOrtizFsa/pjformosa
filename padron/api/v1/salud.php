<?php
declare(strict_types=1);

require_once __DIR__.'/bootstrap.php';

api_solo_get();
api_autenticar('sistema:salud');
api_bd()->query('SELECT 1');

api_responder(200, ['servicio' => 'padron-api', 'estado' => 'operativo', 'hora' => date(DATE_ATOM)]);
