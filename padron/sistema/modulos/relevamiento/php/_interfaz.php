<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$system_607_mesa = isset($_POST['system_607_mesa']) ? $_POST['system_607_mesa'] : NULL;
$system_607_orden = isset($_POST['system_607_orden']) ? $_POST['system_607_orden'] : NULL;
$system_607_dni = isset($_POST['system_607_dni']) ? $_POST['system_607_dni'] : NULL;
$id_system_600 = isset($_POST['id_system_600']) ? $_POST['id_system_600'] : NULL;
$system_600_estado = isset($_POST['system_600_estado']) ? $_POST['system_600_estado'] : NULL;
$nombre_funcion = isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;
$system_607_dni =	formatear_dni($system_607_dni);	
$system_05_detalles="";	
				
switch ($nombre_funcion)
{
		
	case "voto_seguro":	
	$system_05_mensaje = $mysqli -> voto_seguro($system_607_mesa,$system_607_orden,$system_607_dni,$system_fecha,$hora_public,$sesion_system_03);
	//$system_05_detalles="U:$sesion_system_03, $system_607_dni $system_fecha $hora_public ";	
	break;
	
			
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;		
						
}
//guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
