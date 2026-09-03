<?php
session_start();
require_once __DIR__.'/legacy_api_guard.php';
header("Content-Type: text/html; charset=utf-8");
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";


$mesa =		isset($_POST['system_2004_mesa']) 	? $_POST['system_2004_mesa'] : NULL;
$alerta='';		
$funcion_guardar = '';
$funcion_volver = "";
$cadena='';
if ( $mesa == "" )
{
	$alerta = "Indique numero de mesa";
}
else
{	

	// boton volver		
	$url="'modulos/fiscales/php/home.php'";
	$id="'content_seccion'";
	$vars="''";
	$funcion_volver = "cargar_post($url,$id,$vars);";	
		
}

$data['data'][] = [
	'listado'  => 					"$cadena",
	'system_alerta'  => 			"$alerta",
	'funcion_guardar' =>			"$funcion_guardar",
	'funcion_volver' =>				"$funcion_volver"
];

echo json_encode( $data );
?>
