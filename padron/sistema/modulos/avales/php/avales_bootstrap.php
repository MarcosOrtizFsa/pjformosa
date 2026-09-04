<?php
declare(strict_types=1);

// Base compartida del modulo nuevo. Centraliza seguridad, conexion y formatos.
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once dirname(__DIR__, 4).'/lib/mysql_conect.php';
require_once dirname(__DIR__, 4).'/lib/PadronConsulta.php';

const AVALES_MODULO_ID = 110;
const AVALES_LIMITE_FOLIO = 15;

function avales_pdo(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = new PDO('mysql:host='.HOST.';dbname='.BD.';charset=utf8mb4', USU, CLA, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
    return $pdo;
}
function avales_usuario(): int
{
    $id=(int)($_SESSION['sesion_system_03']??0);
    if($id<=0){http_response_code(403);echo '<div class="alert alert-danger m-4">La sesión venció. Ingresá nuevamente.</div>';exit;}
    return $id;
}
function avales_permiso(PDO $pdo,string $accion): bool
{
    $mapa=['A'=>'system_02_A','B'=>'system_02_B','M'=>'system_02_M','E'=>'system_02_E','D'=>'system_02_D'];
    if(!isset($mapa[$accion])) return false;
    $stmt=$pdo->prepare("SELECT u.system_03_modo,p.{$mapa[$accion]} permiso FROM system_03_usuarios u LEFT JOIN system_02_permisos p ON p.rela_system_03=u.id_system_03 AND p.rela_system_01=? WHERE u.id_system_03=? LIMIT 1");
    $stmt->execute([AVALES_MODULO_ID,avales_usuario()]);$fila=$stmt->fetch();
    return $fila&&(in_array((string)$fila['system_03_modo'],['0','1'],true)||(int)$fila['permiso']===1);
}
function avales_csrf(): string
{
    if(empty($_SESSION['avales_csrf']))$_SESSION['avales_csrf']=bin2hex(random_bytes(24));
    return (string)$_SESSION['avales_csrf'];
}
function avales_validar_csrf(): void
{
    if(!hash_equals(avales_csrf(),(string)($_POST['csrf']??'')))throw new RuntimeException('La sesión del formulario venció. Recargá el módulo.');
}
function avales_h(mixed $valor): string{return htmlspecialchars((string)$valor,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function avales_fecha(?string $fecha): string
{
    if(!$fecha)return 'Sin fecha';$valor=DateTimeImmutable::createFromFormat('!Y-m-d',$fecha);return $valor?$valor->format('d/m/Y'):$fecha;
}
function avales_dni(string $valor): ?string{return PadronConsulta::normalizarDni($valor);}
