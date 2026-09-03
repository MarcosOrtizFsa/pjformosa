<?php
declare(strict_types=1);

/*
 * Protección temporal de endpoints JSON heredados.
 *
 * Los módulos internos continúan usándolos con la sesión del usuario. Los
 * proyectos externos deben migrar a /padron/api/v1 y autenticarse con token.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$usuarioId = $_SESSION['sesion_system_03'] ?? null;
$privilegioId = $_SESSION['sesion_system_07'] ?? null;
if (empty($usuarioId) || empty($privilegioId)) {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    http_response_code(401);
    echo json_encode([
        'ok' => false,
        'error' => [
            'code' => 'SESSION_REQUIRED',
            'message' => 'Se requiere una sesión válida.',
        ],
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
