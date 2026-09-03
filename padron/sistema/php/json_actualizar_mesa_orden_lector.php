<?php
session_start();
require_once __DIR__.'/legacy_api_guard.php';
header("Content-Type: text/html; charset=utf-8");
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";


$variable_buscar =		isset($_POST['system_600_dni']) 	? $_POST['system_600_dni'] : NULL;
$system_2000_dni =		'';
$system_2000_mesa =		'';
$system_2000_orden =	'';	
$system_504_escuela = 	'';
$system_504_dpto = 		'';
$system_504_localidad =	'';
$alerta = "";

if ( verifico_dni($variable_buscar) == "" )
{
	$alerta = "Verifique el DNI ".$variable_buscar."";
}
else
{	
	if ( ctype_digit($variable_buscar) == true ) 
	{	
		$digit_dni = substr("$variable_buscar", -1);
		
		if ($digit_dni == 1)
		{
			$where = "Select * from system_2000_padron_1  where system_2000_dni = '$variable_buscar' ";
		}
		else
		if ($digit_dni == 2)
		{
			$where = "Select * from system_2000_padron_2  where system_2000_dni = '$variable_buscar'  ";
		}
		else
		if ($digit_dni == 3)
		{
			$where ="Select * from system_2000_padron_3  where system_2000_dni = '$variable_buscar'  ";
		}
		else
		if ($digit_dni == 4)
		{
			$where = "Select * from system_2000_padron_4  where system_2000_dni = '$variable_buscar'  ";
		}
		else
		if ($digit_dni == 5)
		{
			$where = "Select * from system_2000_padron_5  where system_2000_dni = '$variable_buscar'  ";
		}
		else
		if ($digit_dni == 6)
		{
			$where = "Select * from system_2000_padron_6  where system_2000_dni = '$variable_buscar'  ";
		}
		else
		if ($digit_dni == 7)
		{
			$where = "Select * from system_2000_padron_7  where system_2000_dni = '$variable_buscar'  ";
		}
		else
		if ($digit_dni == 8)
		{
			$where = "Select * from system_2000_padron_8  where system_2000_dni = '$variable_buscar'  ";
		}
		else
		if ($digit_dni == 9)
		{
			$where = "Select * from system_2000_padron_9  where system_2000_dni = '$variable_buscar'  ";
		}
		else
		{
			$where = "Select * from system_2000_padron_0  where system_2000_dni = '$variable_buscar'  ";
		}   	
	}
	
	
	$row = $mysqli -> consulta_SQL("$where  ");				
	if($row == true)
	{					
		$system_2000_dni = 				$row[0]['system_2000_dni'];
		$system_2000_apellido_nombre =	$row[0]['system_2000_apellido_nombre'];
		$system_2000_domicilio =		$row[0]['system_2000_domicilio'];
		$system_2000_crto =				$row[0]['system_2000_crto'];
		$system_2000_mesa =				$row[0]['system_2000_mesa'];
		$system_2000_orden =			$row[0]['system_2000_orden'];	
		// MESA Y ORDEN DEL PADRON
		$dat = explode('@',mesa_escuela($system_2000_mesa,$mysqli));
		$system_504_escuela = 		$dat[1];
		$system_504_dpto = 			$dat[2];
		$system_504_localidad = 	$dat[3];
		
		if ( quito_0($system_2000_mesa) == '' )
		{	
			$alerta = "Datos no disponibles aun...";
		}
	}
	else
	{
		$alerta = "No se encontro el DNI ".convert_dni($variable_buscar);
	}
	
		
}


$data['data'][] = [
	'system_2000_dni' =>		$system_2000_dni,
	'system_2000_mesa' =>		$system_2000_mesa,
	'system_2000_orden' => 		$system_2000_orden,
	'system_504_escuela' => 	$system_504_escuela,
	'system_504_dpto' => 		$system_504_dpto,
	'system_504_localidad' => 	$system_504_localidad,
	'alerta' => 				$alerta
];	



echo json_encode( $data );
?>
