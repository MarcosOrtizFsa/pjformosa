<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$id_system_502 = 		isset($_POST['id_system_502']) ? $_POST['id_system_502'] : NULL;
$rela_system_501 = 		isset($_POST['rela_system_501']) ? $_POST['rela_system_501'] : NULL;
$system_502_circuito = 	isset($_POST['system_502_circuito']) ? $_POST['system_502_circuito'] : NULL;
$system_502_localidades = isset($_POST['system_502_localidades']) ? $_POST['system_502_localidades'] : NULL;

$id_system_504 = 		isset($_POST['id_system_504']) ? $_POST['id_system_504'] : NULL;
$system_504_circuito = 	isset($_POST['system_504_circuito']) ? $_POST['system_504_circuito'] : NULL;
$system_504_pueblo = 	isset($_POST['system_504_pueblo']) ? $_POST['system_504_pueblo'] : NULL;
$system_504_mapsgoogle = isset($_POST['system_504_mapsgoogle']) ? $_POST['system_504_mapsgoogle'] : NULL;
	
		
$nombre_funcion = 		isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;
$system_05_detalles="";	
				
switch ($nombre_funcion)
{		
	case "agregar_modificar":	
	$system_05_mensaje = $mysqli -> agregar_modificar(
					$id_system_502,
					$rela_system_501,
					strtoupper($system_502_circuito)
					);

	break;
	
	case "eliminar":
	$system_05_mensaje = $mysqli -> eliminar($id_system_502);
	$system_05_detalles="$id_system_502";
	break;		


	case "agregar_modificar_pueblo":
		$system_05_mensaje = $mysqli -> agregar_modificar_pueblo($id_system_504,
																strtoupper($system_504_circuito),
																strtoupper($system_504_pueblo),
																$system_504_mapsgoogle
																);
	break;
	
	
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;								
}
guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
