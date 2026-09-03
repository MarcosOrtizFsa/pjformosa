<?php
session_start();
require_once __DIR__.'/legacy_api_guard.php';
header("Content-Type: text/html; charset=utf-8");
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";

$rela_system_601 =		isset($_POST['rela_system_601']) 	? $_POST['rela_system_601'] : NULL;
$rela_system_03 =		isset($_POST['rela_system_03']) 	? $_POST['rela_system_03'] : NULL;
$rela_system_03_coor =	isset($_POST['rela_system_03_coor']) 	? $_POST['rela_system_03_coor'] : NULL;
$variable_buscar =		isset($_POST['variable_buscar']) 	? $_POST['variable_buscar'] : NULL;
$variable_buscar = 		formatear_dni($variable_buscar);
$mesa = 		'';
$orden = 		'';
$tipo_dni = 	'';
$clase = 		'';
$where = 		'';	
$system_2000_dni = 	$variable_buscar;
$system_2000_apellido=	'';
$system_2000_nombre=	'';
$system_2000_apellido_nombre='';
$system_2000_sexo=	'';
$system_505_circuito = 	'';
$system_escuela = 		'';	
$localidad_escuela = 	'';
$domicilio = 		'';
$localidad_depta = 	'';
$system_2000_mesa =	'';
$system_2000_orden ='';	
$system_600_email = 	'';
$system_600_data_1 = 	'';
$system_600_data_2 = 	'';
$system_600_data_3 = 	'';
$system_600_data_4 = 	'';
$circuito = 		'';
$alerta='';		
$funcion_guardar = '';
$funcion_volver = "";

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
			$system_600_localidad = 		mesa_circuito($system_2000_crto,$mysqli);
			
			// MESA Y ORDEN DEL PADRON
			$dat = explode('@',mesa_escuela($system_2000_mesa,$mysqli));
			$system_504_escuela = 		$dat[1];
			$system_504_dpto = 			$dat[2];
			$system_504_localidad = 	$dat[3];

		
		}
		else
		{
			$alerta = "No se pudo encontrar datos con el DNI ".convert_dni($variable_buscar);
		}
		
		
			$vars="'nombre_funcion=agregar_modificar_registros&";
			$vars.="rela_system_601=$rela_system_601&";
			$vars.="rela_system_03=$rela_system_03&";
			$vars.="rela_system_03_coor=$rela_system_03_coor&";
			$vars.="system_600_mesa=$system_2000_mesa&";
			$vars.="system_600_orden=$system_2000_orden&";
			$vars.="system_600_escuela=$system_504_escuela&";
			$vars.="system_600_data_1='+abm.system_600_data_1.value+'&";
			$vars.="system_600_data_2='+abm.system_600_data_2.value+'&";
			$vars.="system_600_data_3='+abm.system_600_data_3.value+'&";
			$vars.="system_600_data_4='+abm.system_600_data_4.value+'&";
			$vars.="system_600_apellido_nombre='+encodeURIComponent(abm.system_600_apellido_nombre.value)+'&";
			$vars.="system_600_localidad='+abm.system_600_localidad.value+'&";
			$vars.="system_600_domicilio_electoral='+encodeURIComponent(abm.system_600_domicilio_electoral.value)+'&";
			$vars.="system_600_circuito_electoral='+encodeURIComponent(abm.system_600_circuito_electoral.value)+'&";			
			$vars.="system_600_domicilio_real='+encodeURIComponent(abm.system_600_domicilio_real.value)+'&";
			$vars.="system_600_circuito_real='+abm.system_600_circuito_real.value+'&";
			$vars.="system_600_celular='+encodeURIComponent(abm.system_600_celular.value)+'&";
			$vars.="system_600_email='+encodeURIComponent(abm.system_600_email.value)+'&";
			$vars.="system_600_dni='+abm.system_600_dni.value";
			// la funcion guardar_mostrar_de_api se ejecuta en la unidad de peticion. en el json de peticion esta.
			$funcion_guardar = "guardar_mostrar_de_api($rela_system_03,$rela_system_601,$vars);";
			
			
			// boton volver		
			$url2="'modulos/planillas/php/lista_planilla.php'";
			$id2="'content_seccion'";
			$vars2="'id_system_601=$rela_system_601'";
			$funcion_volver = "cargar_post($url2,$id2,$vars2);";	
	}
	else
	{
		$alerta = "Ups... se encotr&oacute; un error";
	}		
}

$data['data'][] = [
	'rela_system_03'  => 					"$rela_system_03",
	'rela_system_03_coor' =>				"$rela_system_03_coor",
	'rela_system_601' =>					"$rela_system_601",
	'system_600_dni'  => 					"$system_2000_dni",
	'system_600_apellido_nombre' =>			"$system_2000_apellido_nombre",	
	'system_600_domicilio_electoral'  => 	"$system_2000_domicilio",
	'system_600_circuito_electoral'  => 	"$system_2000_crto",
	'system_600_domicilio_real'  => 		"$system_2000_domicilio",
	'system_600_circuito_real'  => 			"$system_2000_crto",
	'system_600_localidad'  => 				"$system_600_localidad",
	'system_600_email'  => 					"$system_600_email",
	'system_600_data_1'  => 				"$system_600_data_1",
	'system_600_data_2'  => 				"$system_600_data_2",
	'system_600_data_3'  => 				"$system_600_data_3",
	'system_600_data_4'  => 				"$system_600_data_4",
	'system_alerta'  => 					"$alerta",
	'funcion_guardar' =>					"$funcion_guardar",
	'funcion_volver' =>						"$funcion_volver"
];

echo json_encode( $data );
?>
