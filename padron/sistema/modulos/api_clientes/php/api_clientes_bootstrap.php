<?php
declare(strict_types=1);

// Servicios compartidos del modulo. Solo los administradores generales pueden
// emitir o modificar credenciales de proyectos consumidores.
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once dirname(__DIR__, 4).'/lib/mysql_conect.php';
header('Cache-Control: no-store');

const API_CLIENTES_SCOPES = [
    'sistema:salud' => 'Estado del servicio',
    'padron:consultar' => 'Padrón electoral y versión activa',
    'padron:mesas' => 'Mesas y escuelas',
    'partido:consultar' => 'Verificación partidaria',
    'partido:avales' => 'Historial de avales',
];

function api_clientes_pdo(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = new PDO('mysql:host='.HOST.';dbname='.BD.';charset=utf8mb4', USU, CLA, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}

function api_clientes_usuario(): int
{
    $id = (int) ($_SESSION['sesion_system_03'] ?? 0);
    $modo = (string) ($_SESSION['sesion_system_03_modo'] ?? '');
    if ($id <= 0 || !in_array($modo, ['0', '1'], true)) {
        http_response_code(403);
        echo '<div class="alert alert-danger m-4">Este módulo requiere acceso de administrador general.</div>';
        exit;
    }
    return $id;
}

function api_clientes_csrf(): string
{
    if (empty($_SESSION['api_clientes_csrf'])) $_SESSION['api_clientes_csrf'] = bin2hex(random_bytes(24));
    return (string) $_SESSION['api_clientes_csrf'];
}

function api_clientes_validar_csrf(): void
{
    if (!hash_equals(api_clientes_csrf(), (string) ($_POST['csrf'] ?? ''))) {
        throw new RuntimeException('La sesión del formulario venció. Recargá el módulo.');
    }
}

function api_clientes_token(): string
{
    return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
}

function api_clientes_uuid(): string
{
    $bytes = random_bytes(16);
    $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
    $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
}

function api_clientes_h(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function api_clientes_evento(PDO $pdo, int $clienteId, int $usuarioId, string $accion, ?string $detalle = null): void
{
    $stmt = $pdo->prepare('INSERT INTO padron_api_eventos(cliente_id,usuario_id,accion,detalle) VALUES(?,?,?,?)');
    $stmt->execute([$clienteId, $usuarioId, $accion, $detalle]);
}
