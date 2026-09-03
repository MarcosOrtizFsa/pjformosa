<?php
session_start();
require_once __DIR__.'/legacy_api_guard.php';
header("Content-Type: text/html; charset=utf-8");
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";


$system_2002_mesa =		isset($_POST['system_2002_mesa']) 	? $_POST['system_2002_mesa'] : NULL;
$system_504_escuela = 	'';
$system_504_dpto = 		'';
$system_504_localidad =	'';

$row = $mysqli -> consulta_SQL("Select * from system_2002_tabla_fiscales where system_2002_mesa = '$system_2002_mesa' ");				
if($row == true)
{
		$system_2002_mesa = 	$row[0]['system_2002_mesa'];
		$system_2002_lectores = $row[0]['system_2002_lectores'];
		
		
		$dat = explode('@',mesa_escuela($system_2002_mesa,$mysqli));
		$system_504_escuela = 		$dat[1];
		$system_504_dpto = 			$dat[2];
		$system_504_localidad = 	$dat[3];
		
		$data['data'][] = [
			'system_2002_mesa' =>		$system_2002_mesa,
			'system_2002_lectores' => 	$system_2002_lectores,
			'system_504_escuela' => 	$system_504_escuela,
			'system_504_dpto' => 		$system_504_dpto,
			'system_504_localidad' => 	$system_504_localidad
		];	
}
else
{
	$data['data'][] = [
		'system_2002_mesa' =>		'',
		'system_2002_lectores' => 	'',
		'system_504_escuela' => 	'',
		'system_504_dpto' => 		'',
		'system_504_localidad' =>	''
	];
}



echo json_encode( $data );
?>
