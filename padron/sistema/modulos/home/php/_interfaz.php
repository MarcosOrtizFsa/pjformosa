<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$id_system_605 =		isset($_POST['id_system_605']) 	? $_POST['id_system_605'] : NULL;
$rela_system_602 =		isset($_POST['rela_system_602']) 	? $_POST['rela_system_602'] : NULL;
$system_605_mesa =		isset($_POST['system_605_mesa']) ? $_POST['system_605_mesa'] : NULL;

$system_605_1ro =		isset($_POST['system_605_1ro']) 	? $_POST['system_605_1ro'] : NULL;
$system_605_2do =		isset($_POST['system_605_2do']) 	? $_POST['system_605_2do'] : NULL;
$system_605_3ro =		isset($_POST['system_605_3ro']) 	? $_POST['system_605_3ro'] : NULL;
$system_605_4to =		isset($_POST['system_605_4to']) 	? $_POST['system_605_4to'] : NULL;
$system_605_5to =		isset($_POST['system_605_5to']) 	? $_POST['system_605_5to'] : NULL;
$system_605_6to =		isset($_POST['system_605_6to']) 	? $_POST['system_605_6to'] : NULL;
$system_605_7mo =		isset($_POST['system_605_7mo']) 	? $_POST['system_605_7mo'] : NULL;
$system_605_8vo =		isset($_POST['system_605_8vo']) 	? $_POST['system_605_8vo'] : NULL;

$system_08_tema =		isset($_POST['system_08_tema']) 	? $_POST['system_08_tema'] : NULL;
$system_08_total_objetivo =		isset($_POST['system_08_total_objetivo']) 	? $_POST['system_08_total_objetivo'] : NULL;
$system_602_orden =		isset($_POST['system_602_orden']) 	? $_POST['system_602_orden'] : NULL;
$system_602_sublema =		isset($_POST['system_602_sublema']) 	? $_POST['system_602_sublema'] : NULL;
$id_system_602 =		isset($_POST['id_system_602']) 	? $_POST['id_system_602'] : NULL;
$rela_system_603=		isset($_POST['rela_system_603']) 	? $_POST['rela_system_603'] : NULL;
$rela_system_604=		isset($_POST['rela_system_604']) 	? $_POST['rela_system_604'] : NULL;

$id_system_606=		isset($_POST['id_system_606']) 	? $_POST['id_system_606'] : NULL;
$system_606_mesa=		isset($_POST['system_606_mesa']) 	? $_POST['system_606_mesa'] : NULL;
$system_606_nulos=		isset($_POST['system_606_nulos']) 	? $_POST['system_606_nulos'] : NULL;
$system_606_recurridos=		isset($_POST['system_606_recurridos']) 	? $_POST['system_606_recurridos'] : NULL;
$system_606_impugnada=		isset($_POST['system_606_impugnada']) 	? $_POST['system_606_impugnada'] : NULL;
$system_606_comando=		isset($_POST['system_606_comando']) 	? $_POST['system_606_comando'] : NULL;
$system_606_blanco=		isset($_POST['system_606_blanco']) 	? $_POST['system_606_blanco'] : NULL;
$system_606_total=		isset($_POST['system_606_total']) 	? $_POST['system_606_total'] : NULL;
	
$nombre_funcion =		isset($_POST['nombre_funcion']) 	? $_POST['nombre_funcion'] : NULL;

$system_05_detalles="";		
switch ($nombre_funcion)
 {
 	case "agregar_modificar":	
	$system_05_mensaje = $mysqli -> agregar_modificar(
					$sesion_system_03,
					$id_system_605,
					$rela_system_602,
					$system_605_mesa,
					$system_605_1ro,
					$system_605_2do,
					$system_605_3ro,
					$system_605_4to,
					$system_605_5to,
					$system_605_6to,
					$system_605_7mo,
					$system_605_8vo
					);
	$system_05_detalles="";
	break;

 	case "agregar_modificar_lema":	
	$system_05_mensaje = $mysqli -> agregar_modificar_lema(
					$id_system_602,
					$rela_system_603,
					$rela_system_604,
					$system_602_sublema,	
					$system_602_orden
					);
	$system_05_detalles="";
	break;
	
 	case "configurar_sistema":	
	$system_05_mensaje = $mysqli -> configurar_sistema(
					$rela_system_06,
					$system_08_tema,
					$system_08_total_objetivo
					);
	$system_05_detalles="";
	break;

	case "totales_am":
	$system_05_mensaje = $mysqli -> totales_am(
												$id_system_606,
												$system_606_mesa,
												$system_606_nulos,
												$system_606_recurridos,
												$system_606_impugnada,
												$system_606_comando,
												$system_606_blanco,
												$system_606_total
												);
	$system_05_detalles="";
	break;	
			
	case "eliminar_lema":
	$system_05_mensaje = $mysqli -> eliminar_lema($id_system_602);
	$system_05_detalles="$id_system_03";
	break;	
														
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;	
}
guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
