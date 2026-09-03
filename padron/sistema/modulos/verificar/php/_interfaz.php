<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);


$system_2001_dni = 			isset($_POST['system_2001_dni']) ? $_POST['system_2001_dni'] : NULL;
$system_2003_dirigente = 	isset($_POST['system_2003_dirigente']) ? $_POST['system_2003_dirigente'] : NULL;
$system_2004_dni = 			isset($_POST['system_2004_dni']) ? $_POST['system_2004_dni'] : NULL;

$system_2005_nombre = 			isset($_POST['system_2005_nombre']) ? $_POST['system_2005_nombre'] : NULL;
$id_system_2005 = 			isset($_POST['id_system_2005']) ? $_POST['id_system_2005'] : NULL;

$nombre_funcion = 			isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;
$system_2001_dni =			formatear_dni($system_2001_dni);	
		
$system_05_detalles="";	
				
switch ($nombre_funcion)
{
	case "agregar_dirigente":	
	$system_05_mensaje = $mysqli -> agregar_dirigente(	$id_system_2005,
														$system_2005_nombre														
														);
	break;			

	case "nuevos_tramites":	
	$system_05_mensaje = $mysqli -> nuevos_tramites(	$system_2001_dni,
														$system_fecha
														);
	break;

	case "nuevos_aval":	
	$system_05_mensaje = $mysqli -> nuevos_aval(	formatear_dni($system_2004_dni),
														$system_fecha
														);
	break;
								
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;		
						
}
guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
