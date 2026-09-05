<?php
declare(strict_types=1);

/*
 * Herramienta exclusiva de consola para crear clientes de API.
 * Uso: php padron/tools/crear_cliente_api.php "Nombre" "scope1,scope2" [limite/minuto] [IP1,IP2]
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__).'/lib/mysql_conect.php';

$nombre = trim((string) ($argv[1] ?? ''));
$scopes = trim((string) ($argv[2] ?? 'padron:consultar,sistema:salud'));
$limite = filter_var($argv[3] ?? 120, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 10000]]);
$ips = trim((string) ($argv[4] ?? ''));
if ($nombre === '') {
    fwrite(STDERR, "Debe indicar un nombre para el cliente.\n");
    exit(1);
}
if ($limite === false) {
    fwrite(STDERR, "El limite debe ser un entero entre 0 y 10000.\n");
    exit(1);
}
$scopesPermitidos = ['sistema:salud', 'padron:consultar', 'padron:mesas', 'partido:consultar', 'partido:avales', '*'];
$listaScopes = array_values(array_unique(array_filter(array_map('trim', explode(',', $scopes)))));
if ($listaScopes === [] || array_diff($listaScopes, $scopesPermitidos) !== []) {
    fwrite(STDERR, "Scopes invalidos. Permitidos: ".implode(',', $scopesPermitidos)."\n");
    exit(1);
}
$scopes = implode(',', $listaScopes);

$uuid = static function (): string {
    $bytes = random_bytes(16);
    $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
    $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
};

$clientId = $uuid();
$token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
$pdo = new PDO('mysql:host='.HOST.';dbname='.BD.';charset=utf8mb4', USU, CLA, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
$consulta = $pdo->prepare(
    'INSERT INTO padron_api_clientes (nombre, client_id, token_hash, scopes, limite_por_minuto, ips_permitidas)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$consulta->execute([$nombre, $clientId, hash('sha256', $token), $scopes, $limite, $ips !== '' ? $ips : null]);

echo "Cliente creado. Guarde estos datos: el token no puede recuperarse después.\n";
echo "Client-ID: $clientId\n";
echo "Token: $token\n";
echo "Scopes: $scopes\n";
echo "Limite por minuto: $limite\n";
echo "IPs permitidas: ".($ips !== '' ? $ips : 'sin restriccion')."\n";
