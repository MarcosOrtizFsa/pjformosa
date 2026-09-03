<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$system_2004_dni = isset($_POST['system_2004_dni']) ? $_POST['system_2004_dni'] : NULL;
$system_2004_estado = isset($_POST['system_2004_estado']) ? $_POST['system_2004_estado'] : NULL;
$system_2002_mesa = isset($_POST['system_2002_mesa']) ? $_POST['system_2002_mesa'] : NULL;
$nombre_funcion = isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;
$system_05_detalles="";	
				
switch ($nombre_funcion)
{
	case "cargar_mesas_votantes":	
	$system_05_mensaje = $mysqli -> cargar_mesas_votantes($system_2002_mesa);
	//$system_05_detalles="U:$sesion_system_03, $system_607_dni $system_fecha $hora_public ";	
	break;
	
		
	case "marcar_voto":	
	$system_05_mensaje = $mysqli -> marcar_voto($sesion_system_03,$system_2004_dni,$system_2004_estado,$system_fecha,$hora_public);
	//$system_05_detalles="U:$sesion_system_03, $system_607_dni $system_fecha $hora_public ";	
	break;
	
			
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;		
						
}
//guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
