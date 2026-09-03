<?php
session_start();
include "../../../../../lib/mysql_conect.inc";
include "../../../../php/funciones.php";
include "../../../../php/privilegios.php";
include "abm.php";

$id_system_04=$_POST['id_system_04'];
$id_system_600=$_POST['id_system_600'];
$system_600_estado=$_POST['system_600_estado'];
$nombre_funcion=$_POST['nombre_funcion'];
			
switch ($nombre_funcion)
{
    
		case "voto_seguro":		
		$mensaje=voto_seguro($id_system_04,$system_fecha,$hora_public,$sesion_system_03,$mysqli);		
		echo $mensaje;
		$system_05_detalles="$id_system_04 | $system_fecha | $hora_public	| $sesion_system_03";		
        break;		

	
		
		default:
		$mensaje="No hay funcion!";
		echo $mensaje;
		$system_05_detalles=""; 
		break;
						
}


// siempre acompaña las variables modulo y accion
$system_05_modulo = "CARGADOR";
$system_05_accion = "$nombre_funcion";
logistica($system_fecha,$system_hora,$sesion_system_03,$sesion_system_07,$sesion_system_07a,$system_05_modulo,$system_05_accion,$ip,$system_05_detalles,$mensaje,$mysqli);


?>
