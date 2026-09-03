<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$id_system_01 = isset($_POST['id_system_01']) ? $_POST['id_system_01'] : NULL;
$id_system_04 = isset($_POST['id_system_04']) ? $_POST['id_system_04'] : NULL;
$system_04_nombre = isset($_POST['system_04_nombre']) ? $_POST['system_04_nombre'] : NULL;
$system_04_apellido = isset($_POST['system_04_apellido']) ? $_POST['system_04_apellido'] : NULL;
$system_04_email = isset($_POST['system_04_email']) ? $_POST['system_04_email'] : NULL;
$system_04_celular = isset($_POST['system_04_celular']) ? $_POST['system_04_celular'] : NULL;


$nombre_funcion = isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;
$system_04_dni =	formatear_dni($system_04_dni);	
$system_04_cuil = 	formatear_cuit($system_04_cuil);
$system_05_detalles="";	
				
switch ($nombre_funcion)
{
		
	case "agregar_modificar":	
	$system_05_mensaje = $mysqli -> agregar_modificar();
	break;
	
					
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;		
						
}
guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
