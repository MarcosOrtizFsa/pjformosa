<?php
declare(strict_types=1);

// Servicios compartidos del padron de afiliados y su verificador integrado.
if(session_status()!==PHP_SESSION_ACTIVE)session_start();
require_once dirname(__DIR__,4).'/lib/mysql_conect.php';
require_once dirname(__DIR__,4).'/lib/PadronConsulta.php';
const PADRON_PJ_MODULO_ID=107;

function pj_pdo():PDO{
    static $pdo;if($pdo instanceof PDO)return $pdo;
    $pdo=new PDO('mysql:host='.HOST.';dbname='.BD.';charset=utf8mb4',USU,CLA,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);return $pdo;
}
function pj_usuario():int{
    $id=(int)($_SESSION['sesion_system_03']??0);if($id<=0){http_response_code(403);echo '<div class="alert alert-danger m-4">La sesión venció. Ingresá nuevamente.</div>';exit;}return $id;
}
function pj_permiso(PDO $pdo,string $accion):bool{
    $mapa=['A'=>'system_02_A','M'=>'system_02_M','V'=>'system_02_V'];if(!isset($mapa[$accion]))return false;
    $stmt=$pdo->prepare("SELECT u.system_03_modo,p.{$mapa[$accion]} permiso FROM system_03_usuarios u LEFT JOIN system_02_permisos p ON p.rela_system_03=u.id_system_03 AND p.rela_system_01=? WHERE u.id_system_03=? LIMIT 1");$stmt->execute([PADRON_PJ_MODULO_ID,pj_usuario()]);$fila=$stmt->fetch();
    return $fila&&(in_array((string)$fila['system_03_modo'],['0','1'],true)||(int)$fila['permiso']===1);
}
function pj_csrf():string{if(empty($_SESSION['padron_pj_csrf']))$_SESSION['padron_pj_csrf']=bin2hex(random_bytes(24));return(string)$_SESSION['padron_pj_csrf'];}
function pj_validar_csrf():void{if(!hash_equals(pj_csrf(),(string)($_POST['csrf']??'')))throw new RuntimeException('La sesión del formulario venció. Recargá el módulo.');}
function pj_h(mixed $valor):string{return htmlspecialchars((string)$valor,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function pj_fecha(?string $fecha):string{if(!$fecha)return 'Sin fecha';$f=DateTimeImmutable::createFromFormat('!Y-m-d',$fecha);return$f?$f->format('d/m/Y'):$fecha;}
