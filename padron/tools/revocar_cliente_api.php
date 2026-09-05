<?php
declare(strict_types=1);

/* Uso: php padron/tools/revocar_cliente_api.php CLIENT-ID */
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require_once dirname(__DIR__).'/lib/mysql_conect.php';
$clientId = trim((string) ($argv[1] ?? ''));
if (!preg_match('/^[0-9a-f-]{36}$/i', $clientId)) {
    fwrite(STDERR, "Debe indicar un Client-ID valido.\n");
    exit(1);
}
$pdo = new PDO('mysql:host='.HOST.';dbname='.BD.';charset=utf8mb4', USU, CLA, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$consulta = $pdo->prepare('UPDATE padron_api_clientes SET estado=0 WHERE client_id=? AND estado<>0');
$consulta->execute([$clientId]);
echo $consulta->rowCount() === 1 ? "Cliente revocado.\n" : "El cliente no estaba activo o no existe.\n";
