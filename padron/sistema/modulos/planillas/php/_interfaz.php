<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);


$lista_dnis = 			isset($_POST['lista_dnis']) ? $_POST['lista_dnis'] : NULL;
$id_system_600 = 		isset($_POST['id_system_600']) ? $_POST['id_system_600'] : NULL;
$rela_system_03 = 		isset($_POST['rela_system_03']) ? $_POST['rela_system_03'] : NULL;
$rela_system_602 = 		isset($_POST['rela_system_602']) ? $_POST['rela_system_602'] : NULL;
$system_601_num = 		isset($_POST['system_601_num']) ? $_POST['system_601_num'] : NULL;
$id_system_601 = 		isset($_POST['id_system_601']) ? $_POST['id_system_601'] : NULL;
$rela_system_601 = 		isset($_POST['rela_system_601']) ? $_POST['rela_system_601'] : NULL;
$system_601_checked = 	isset($_POST['system_601_checked']) ? $_POST['system_601_checked'] : NULL;
$system_600_disputa = 		isset($_POST['system_600_disputa']) ? $_POST['system_600_disputa'] : NULL;

$nombre_funcion = 		isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;

$system_05_detalles="";	
				
switch ($nombre_funcion)
{
	case "votar":		
	$system_05_mensaje = $mysqli -> votar($id_system_600,$mysqli);	
	$system_05_detalles="U:$sesion_system_03, id:$id_system_600";	
	break;		
	
	case "nueva_lista":		
	$system_05_mensaje = $mysqli -> nueva_lista($lista_dnis,$rela_system_03,$rela_system_601,$mysqli);		
	$system_05_detalles="U:$sesion_system_03, id:$rela_system_03, $rela_system_601 ";	
	break;	
	
	case "nueva_planilla":		
	$system_05_mensaje = $mysqli -> nueva_planilla($id_system_601,$system_601_num,$rela_system_03,$system_601_checked,$mysqli);		
	$system_05_detalles="U:$sesion_system_03, id:$rela_system_03, $rela_system_601, $system_601_checked ";
	break;	
	
	case "remoner_dni":		
	$system_05_mensaje = $mysqli -> remoner_dni($id_system_600,$mysqli);
	$system_05_detalles="U:$sesion_system_03, id:$id_system_600 ";
	break;	

	case "borrar_planilla":		
	$system_05_mensaje = $mysqli -> borrar_planilla($id_system_601,$mysqli);
	$system_05_detalles="U:$sesion_system_03, id:$id_system_601 ";		
	break;	

	case "disputa_ganada":		
	$system_05_mensaje = $mysqli -> disputa_ganada($id_system_600,$system_600_disputa,$mysqli);
	$system_05_detalles="U:$sesion_system_03, id:$id_system_600 ";
	break;	
			
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;		
						
}
guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
