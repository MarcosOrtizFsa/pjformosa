<?php
ini_set("session.cache_expire",30);
ini_set("session.gc_maxlifetime",9000);
session_start();
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";

$usu = 		formatear_dni($_POST['usu']); /*perfil del usuario*/
//$usu = 		formatear_cuit($_POST['usu']); /*perfil del usuario*/
$cla = 		sha1($_POST['cla'], false);
$cuir =		$_POST['cuir'];
$rr = isset($_GET['rr']) ? $_GET['rr'] : NULL;
$cc = isset($_GET['cc']) ? $_GET['cc'] : NULL;
$tt = isset($_GET['tt']) ? $_GET['tt'] : NULL;

if($rr!='' and $cc!='' and $tt!='')// SE TRATA DE UN INGRESO POR LINK
{
	if ( $tt < $system_checked )
	{
	echo "<h3>El v&iacute;nculo ha expirado...</h3>";
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=../../../sistema\">";
	exit;
	}
	$row = $mysqli -> login_pasaport($rr,$cc);
	$tipo_login="log_link";
}
else
{
	$row = $mysqli -> login_validar($usu,$cla);
	$tipo_login="log_analogo";
}

if ($row == TRUE)
{
	$id_system_03 =		$row[0]['id_system_03'];
	$rela_system_07 =	$row[0]['rela_system_07']; // PRIVILEGIOS
	$rela_system_06 =	$row[0]['rela_system_06']; 
	$system_03_modo =	$row[0]['system_03_modo']; // 0=root, 1=gerente, 2=administracion, 3=publico
	$system_03_estado =	$row[0]['system_03_estado'];

	if ($system_03_estado=='0')
	{
		$res_login= "0:espera";
	}
	else
	if ($system_03_estado=='2')
	{
		$res_login= "0:suspendido";
	}
	else
	if ($system_03_estado=='3')
	{
		$res_login= "0:rechazado";
	}
	else
	{
		$_SESSION['sesion_system_03'] = 		$id_system_03;
		$_SESSION['sesion_system_07'] = 		$rela_system_07; 
		$_SESSION['sesion_system_06'] = 		$rela_system_06;	
		$_SESSION['sesion_system_03_modo'] = 	$system_03_modo;// 0=root, 1=gerente, 2=administracion, 3=publico 															
		
		// VERIFICAMOS SI COMPETA LOS DATOS DEL USUARIO
		$row = $mysqli -> system_04_perfil($id_system_03);
		if ($row == TRUE)
		{
			$rela_system_01="";
			$system_05_mensaje="";
			$system_05_detalles="modo ".$system_03_modo;
			
			if( $row[0]['system_04_apellido']=='' || $row[0]['system_04_email']==''  || $row[0]['system_04_celular']=='' )
			{
				$_SESSION['sesion_perfil'] = $id_system_03;// DATOS OBLIGATORIOS DEL PERFIL
				$system_05_mensaje="sesion_perfil";	
				$rela_system_01="2";
			}			
			
			guardar_logistica($id_system_03,$rela_system_07,$rela_system_06,$rela_system_01,$tipo_login,$system_05_detalles,$system_05_mensaje,$mysqli);
	
			$res_login= "1:ok";	
			
		}
		else
		{
			$res_login= "0:incompleto";
		}

	}			
}
else
{
	$res_login= "0:restringido";
}		

if($tt!='')// SE TRATA DE UN INGRESO POR LINK
{
	header("Location: ../../../sistema");
}
else
{
	echo $res_login;
}	
?>
