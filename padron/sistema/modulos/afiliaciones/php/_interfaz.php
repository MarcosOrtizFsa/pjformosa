<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$system_2000_dni = 			isset($_POST['system_2000_dni']) ? $_POST['system_2000_dni'] : NULL;
$system_2001_dni = 			isset($_POST['system_2001_dni']) ? $_POST['system_2001_dni'] : NULL;
$cara = 			isset($_POST['cara']) ? $_POST['cara'] : NULL;
$nombre_funcion = 			isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;
$system_2001_dni =			formatear_dni($system_2001_dni);	
		
$system_05_detalles="";	
				
switch ($nombre_funcion)
{
			
	case "agregar_afiliacion":	
	$system_05_mensaje = $mysqli -> agregar_afiliacion(	$sesion_system_03,	
														$system_2001_dni,
														$system_fecha
														);
	break;

	case "eliminar":
	$system_05_mensaje = $mysqli -> eliminar($system_2001_dni,$cara);
	$system_05_detalles="";
	break;
						
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;		
						
}
guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
