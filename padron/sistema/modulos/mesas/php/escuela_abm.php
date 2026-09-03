<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
//Archivos comunes
$t->set_file(array(
	'ver'		=> "escuela_abm.html"
	));

$id_system_503 = 	isset($_POST['id_system_503']) ? $_POST['id_system_503'] : NULL;	
			
	$row = $mysqli -> consulta_SQL("Select * from system_503_escuelas  where id_system_503 = '$id_system_503'");				
	if ($row == true)
	{			
		$id_system_503 = 					$row[0]['id_system_503'];
		$system_503_escuela =				$row[0]['system_503_escuela'];
		$system_503_circuito =				$row[0]['system_503_circuito'];
		$system_503_direccion =				$row[0]['system_503_direccion'];
		$system_503_ubicacion =				$row[0]['system_503_ubicacion'];	
	}
	else
	{
		$id_system_503 = 					'';
		$system_503_escuela =				'';
		$system_503_circuito =				'';
		$system_503_direccion =				'';
		$system_503_ubicacion =				'';	
	}
 
	$t->set_var("system_503_escuela",	"$system_503_escuela");
	$t->set_var("system_503_circuito",	"$system_503_circuito");
	$t->set_var("system_503_direccion",	"$system_503_direccion");
	$t->set_var("system_503_ubicacion","$system_503_ubicacion");

		
	
	$url="'modulos/mesas/php/_interfaz.php'"; // siempre va a abm_interfaz
	$vars="'nombre_funcion=agregar_modificar_escuela&";
	$vars.="id_system_503=$id_system_503&";
	$vars.="system_503_escuela='+encodeURIComponent(abm.system_503_escuela.value)+'&";	
	$vars.="system_503_circuito='+encodeURIComponent(abm.system_503_circuito.value)+'&";			
	$vars.="system_503_direccion='+encodeURIComponent(abm.system_503_direccion.value)+'&";			
	$vars.="system_503_ubicacion='+abm.system_503_ubicacion.value";	
	$url_exito="'modulos/mesas/php/escuelas.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01'";	

	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");

	
	$url2="'modulos/mesas/php/escuelas.php'";
	$id2="'content_seccion'";
	$vars2="'id_system_01=$id_system_01'";
	$t->set_var("funcion_volver","cargar_post($url2,$id2,$vars2)");	

		
$t->pparse("OUT", "ver");
?>
