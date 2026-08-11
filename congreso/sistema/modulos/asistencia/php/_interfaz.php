<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);


$id_system_100 = isset($_POST['id_system_100']) ? $_POST['id_system_100'] : NULL;
$system_100_estado = isset($_POST['system_100_estado']) ? $_POST['system_100_estado'] : NULL;
$system_100_orden = isset($_POST['system_100_orden']) ? $_POST['system_100_orden'] : NULL;
$system_100_orden_seccion = isset($_POST['system_100_orden_seccion']) ? $_POST['system_100_orden_seccion'] : NULL;
$system_100_congresista = isset($_POST['system_100_congresista']) ? $_POST['system_100_congresista'] : NULL;
$system_100_dni = isset($_POST['system_100_dni']) ? $_POST['system_100_dni'] : NULL;
$system_100_departamento = isset($_POST['system_100_departamento']) ? $_POST['system_100_departamento'] : NULL;

$nombre_funcion = isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;

$system_05_detalles="";	
				
switch ($nombre_funcion)
{
		
	case "agregar_modificar":
	$system_05_mensaje = $mysqli -> agregar_modificar(
										$sesion_system_03,
										$id_system_100,
										$system_100_orden,
										$system_100_orden_seccion,
										$system_100_congresista,
										formatear_dni($system_100_dni),
										$system_100_departamento
										);
	$system_05_detalles="";
	break;
	
	case "eliminar":
	$system_05_mensaje = $mysqli -> eliminar($id_system_100);
	$system_05_detalles="$id_system_03,$id_system_100";
	break;	

	case "limpiar_asistencias":
	$system_05_mensaje = $mysqli -> limpiar_asistencias($sesion_system_07);
	$system_05_detalles="$sesion_system_03";
	break;	
		
	case "on_off":
	$system_05_mensaje = $mysqli -> on_off($id_system_100,$system_100_estado);
	$system_05_detalles="";
	break;

				
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;		
						
}
guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
