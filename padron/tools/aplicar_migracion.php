<?php
declare(strict_types=1);

/*
 * Ejecutor de migraciones exclusivo de consola.
 * Uso: php padron/tools/aplicar_migracion.php 012_servicios_api_v1.sql
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once dirname(__DIR__).'/lib/mysql_conect.php';

$archivo = basename((string) ($argv[1] ?? ''));
if (!preg_match('/^[0-9]{3}_[a-z0-9_]+\.sql$/', $archivo)) {
    fwrite(STDERR, "Debe indicar un archivo de migracion valido.\n");
    exit(1);
}
$ruta = dirname(__DIR__).'/database/migrations/'.$archivo;
if (!is_file($ruta)) {
    fwrite(STDERR, "La migracion no existe.\n");
    exit(1);
}

try {
    $pdo = new PDO('mysql:host='.HOST.';dbname='.BD.';charset=utf8mb4', USU, CLA, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => true,
    ]);
    $pdo->exec((string) file_get_contents($ruta));
    echo "Migracion aplicada: {$archivo}\n";
} catch (Throwable $error) {
    fwrite(STDERR, "No se pudo aplicar la migracion: {$error->getMessage()}\n");
    exit(1);
}
