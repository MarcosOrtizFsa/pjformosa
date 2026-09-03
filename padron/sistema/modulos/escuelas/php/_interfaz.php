<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$id_system_505 = 		isset($_POST['id_system_505']) ? $_POST['id_system_505'] : NULL;
$rela_system_504 = 		isset($_POST['rela_system_504']) ? $_POST['rela_system_504'] : NULL;
$system_505_escuela = 	isset($_POST['system_505_escuela']) ? $_POST['system_505_escuela'] : NULL;
$system_505_circuito = isset($_POST['system_505_circuito']) ? $_POST['system_505_circuito'] : NULL;
$system_505_direccion = isset($_POST['system_505_direccion']) ? $_POST['system_505_direccion'] : NULL;
$system_505_googlemaps = isset($_POST['system_505_googlemaps']) ? $_POST['system_505_googlemaps'] : NULL;
		
$nombre_funcion = 		isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;
$system_05_detalles="";	
				
switch ($nombre_funcion)
{		
	case "agregar_modificar_escuela":	
	$system_05_mensaje = $mysqli -> agregar_modificar_escuela(
					$id_system_505,
					$rela_system_504,
					strtoupper($system_505_circuito),
					strtoupper($system_505_escuela),
					$system_505_direccion,
					$system_505_googlemaps
					);

	break;
	
	case "eliminar":
	$system_05_mensaje = $mysqli -> eliminar($id_system_502);
	$system_05_detalles="$id_system_502";
	break;		
	

	
		
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;								
}
guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
