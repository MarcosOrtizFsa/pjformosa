<?php
session_start();
require_once __DIR__.'/legacy_api_guard.php';
header("Content-Type: text/html; charset=utf-8");
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";

$variable_buscar =		trim(isset($_POST['variable_buscar']) 	? $_POST['variable_buscar'] : NULL);
$rela_system_07 =		isset($_POST['rela_system_07']) 	? $_POST['rela_system_07'] : NULL;
$modo =		            isset($_POST['modo']) 	? $_POST['modo'] : NULL;// es el id de la tabla que regresa
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
	
		}
		else
		{
			$alerta = "No se pudo encontrar datos con el DNI ".convert_dni($variable_buscar);
		}
		
		
			$vars="'nombre_funcion=agregar_modificar_registros&";
			$vars.="rela_system_07='+abm.rela_system_07.value+'&";
			$vars.="system_apellido_nombre='+encodeURIComponent(abm.system_apellido_nombre.value)+'&";
			$vars.="system_04_email='+abm.system_04_email.value+'&";
			$vars.="system_04_f_nacimiento='+abm.system_04_f_nacimiento.value+'&";
			$vars.="system_04_celular='+encodeURIComponent(abm.system_04_celular.value)+'&";
			$vars.="system_04_direccion='+encodeURIComponent(abm.system_04_direccion.value)+'&";
			$vars.="system_04_localidad='+encodeURIComponent(abm.system_04_localidad.value)+'&";
			$vars.="system_04_jurisdiccion='+abm.system_04_jurisdiccion.value+'&";
			$vars.="system_04_dni='+abm.system_04_dni.value";
			$modo="'$modo'";
			// la funcion guardar_mostrar_de_api se ejecuta en la unidad de peticion. en el json de peticion esta.
			$funcion_guardar = "guardar_mostrar_de_api($modo,$vars);";
	}
	else
	{
		$alerta = "Ups... se encotr&oacute; un error";
	}		
}

$data['data'][] = [
	'rela_system_07'  => 			"$rela_system_07",
	'system_2000_dni'  => 			"$system_2000_dni",
	'system_2000_apellido_nombre' =>"$system_2000_apellido_nombre",
	'system_2000_domicilio'  => 	"$system_2000_domicilio",
	'localidad_depta_vive'  => 		"$localidad_depta_vive",
	'system_2000_crto'  => 			"$system_2000_crto",
	'system_alerta'  => 			"$alerta",
	'funcion_guardar' =>			"$funcion_guardar"
];

echo json_encode( $data );
?>
