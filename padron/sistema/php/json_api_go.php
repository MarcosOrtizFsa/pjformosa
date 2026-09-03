<?php
session_start();
require_once __DIR__.'/legacy_api_guard.php';
header("Content-Type: text/html; charset=utf-8");
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";

$variable_buscar =		trim(isset($_POST['variable_buscar']) 	? $_POST['variable_buscar'] : NULL);
$variable_buscar = 		formatear_dni($variable_buscar);
$mesa = 		'';
$orden = 		'';
$tipo_dni = 	'';
$clase = 		'';
$where = 		'';	
$system_2000_dni = 	$variable_buscar;
$system_2000_apellido=	'';
$system_2000_nombre=	'';
$system_2000_sexo=	'';
$system_505_circuito = 	'';
$system_escuela = 		'';	
$localidad_escuela = 	'';
$domicilio = 		'';
$localidad_depta = 	'';
$circuito = 		'';
$alerta='';		
$system_04_apellido ='';
$system_04_nombre='';
$funcion_guardar = '';

if ( verifico_dni($variable_buscar) == "" )
{
	$alerta = "El DNI ".$variable_buscar." debe contener 8 digitos";
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
	
	if ( $where != '' )
	{	
		$row = $mysqli -> consulta_SQL("$where  ");				
		if($row == true)
		{					
			$system_2000_dni = 				$row[0]['system_2000_dni'];
			$system_2000_apellido_nombre =	$row[0]['system_2000_apellido_nombre'];
			$system_2000_domicilio =		$row[0]['system_2000_domicilio'];
			$system_2000_crto =				$row[0]['system_2000_crto'];
			$system_2000_mesa =				$row[0]['system_2000_mesa'];
			$system_2000_orden =			$row[0]['system_2000_orden'];	
			$localidad_depta_vive = 		localidad_por_circuito($system_2000_crto,$mysqli);
			// quieto el apellido del nonmbre
			$partes = preg_split('/\s+/', $system_2000_apellido_nombre);			
			$system_04_apellido = array_shift($partes);
			$system_04_nombre = implode(" ", $partes);
	
		}

	}
		
}


$data['data'][] = [
	'system_alerta'  => 			"$alerta",
	'system_04_dni'  => 			"$system_2000_dni",
	'system_04_apellido' =>			"$system_04_apellido",
	'system_04_nombre' =>			"$system_04_nombre",
	'system_04_celular' =>			"",
	'system_04_email' =>			"",
	'system_04_localidad' =>		"$localidad_depta_vive",
	'system_04_direccion' =>		"$system_2000_domicilio",
	'system_04_detalles' =>			"",
	'system_04_delegado' =>			"",
	'registros_previos' =>			""
];



echo json_encode( $data );
?>
