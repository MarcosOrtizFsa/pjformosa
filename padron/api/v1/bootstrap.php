<?php
declare(strict_types=1);

/*
 * Núcleo común de la API privada v1.
 *
 * Todos los endpoints deben incluir este archivo, solicitar un scope y usar
 * api_responder(). De esta forma la autenticación, los errores y la auditoría
 * permanecen iguales en toda la API.
 */

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');

require_once dirname(__DIR__, 2).'/lib/mysql_conect.php';

const PADRON_API_VERSION = '1.0';

$apiInicio = microtime(true);
$apiRequestId = api_uuid();
$apiCliente = null;

function api_uuid(): string
{
    $bytes = random_bytes(16);
    $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
    $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
}

function api_bd(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host='.HOST.';dbname='.BD.';charset=utf8mb4';
    $pdo = new PDO($dsn, USU, CLA, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function api_ip_cliente(): string
{
    // REMOTE_ADDR es deliberado: no se confían cabeceras reenviadas sin un
    // proxy conocido configurado por la infraestructura.
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '');
}

function api_token_recibido(): string
{
    $authorization = (string) ($_SERVER['HTTP_AUTHORIZATION'] ?? '');
    if (preg_match('/^Bearer\s+(.+)$/i', $authorization, $coincidencia)) {
        return trim($coincidencia[1]);
    }
    return trim((string) ($_SERVER['HTTP_X_API_KEY'] ?? ''));
}

function api_autenticar(string $scopeRequerido): array
{
    global $apiCliente;

    $token = api_token_recibido();
    if ($token === '') {
        api_responder(401, null, 'Falta el token de acceso.', 'AUTH_REQUIRED');
    }

    $consulta = api_bd()->prepare(
        'SELECT id, nombre, client_id, scopes, ips_permitidas
         FROM padron_api_clientes
         WHERE token_hash = :token_hash
           AND estado = 1
           AND (expira_en IS NULL OR expira_en > NOW())
         LIMIT 1'
    );
    $consulta->execute(['token_hash' => hash('sha256', $token)]);
    $cliente = $consulta->fetch();

    if (!$cliente || !hash_equals((string) $cliente['client_id'], (string) ($_SERVER['HTTP_X_CLIENT_ID'] ?? $cliente['client_id']))) {
        api_responder(401, null, 'Token inválido o vencido.', 'AUTH_INVALID');
    }

    $scopes = array_filter(array_map('trim', explode(',', (string) $cliente['scopes'])));
    if (!in_array('*', $scopes, true) && !in_array($scopeRequerido, $scopes, true)) {
        api_responder(403, null, 'El cliente no posee el permiso requerido.', 'SCOPE_DENIED');
    }

    $ips = array_filter(array_map('trim', explode(',', (string) ($cliente['ips_permitidas'] ?? ''))));
    if ($ips !== [] && !in_array(api_ip_cliente(), $ips, true)) {
        api_responder(403, null, 'La dirección IP no está autorizada.', 'IP_DENIED');
    }

    $apiCliente = $cliente;
    api_bd()->prepare('UPDATE padron_api_clientes SET ultimo_uso_en = NOW() WHERE id = ?')->execute([$cliente['id']]);
    return $cliente;
}

function api_auditar(int $estadoHttp): void
{
    global $apiInicio, $apiRequestId, $apiCliente;

    try {
        $duracion = (int) round((microtime(true) - $apiInicio) * 1000);
        $consulta = api_bd()->prepare(
            'INSERT INTO padron_api_registros
             (cliente_id, request_id, metodo, ruta, ip, estado_http, duracion_ms)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $consulta->execute([
            $apiCliente['id'] ?? null,
            $apiRequestId,
            substr((string) ($_SERVER['REQUEST_METHOD'] ?? ''), 0, 10),
            substr((string) ($_SERVER['REQUEST_URI'] ?? ''), 0, 190),
            api_ip_cliente() ?: null,
            $estadoHttp,
            max(0, $duracion),
        ]);
    } catch (Throwable $error) {
        // La auditoría nunca debe alterar la respuesta principal de la API.
        error_log('PADRON API AUDIT: '.$error->getMessage());
    }
}

function api_responder(int $estadoHttp, mixed $datos = null, ?string $mensaje = null, ?string $codigo = null): never
{
    global $apiRequestId;

    http_response_code($estadoHttp);
    $respuesta = [
        'ok' => $estadoHttp >= 200 && $estadoHttp < 300,
        'version' => PADRON_API_VERSION,
        'request_id' => $apiRequestId,
    ];

    if ($datos !== null) {
        $respuesta['data'] = $datos;
    }
    if ($mensaje !== null) {
        $respuesta['error'] = ['code' => $codigo ?? 'ERROR', 'message' => $mensaje];
    }

    api_auditar($estadoHttp);
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

set_exception_handler(static function (Throwable $error): void {
    error_log('PADRON API: '.$error->getMessage());
    api_responder(500, null, 'Ocurrió un error interno.', 'INTERNAL_ERROR');
});
