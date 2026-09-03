<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$id_system_09 = 		isset($_POST['id_system_09']) ? $_POST['id_system_09'] : NULL;
$nombre_funcion = 		isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;

		
$system_05_detalles="";	
				
switch ($nombre_funcion)
{
	case "limpiar_sufragios":
	$system_05_mensaje = $mysqli -> limpiar_sufragios();
	$system_05_detalles="";
	break;	

	case "quitar_extraible":
	$system_05_mensaje = $mysqli -> quitar_extraible($id_system_09);
	$system_05_detalles="";
	break;
			
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;		
						
}
guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
