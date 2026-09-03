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
	'ver'		=> "home_am.html"
	));

$id_system_505 = 	isset($_POST['id_system_505']) ? $_POST['id_system_505'] : NULL;	
			
	$row = $mysqli -> consulta_SQL("Select * from system_505_escuelas  where id_system_505 = '$id_system_505'");				
	if ($row == true)
	{			
		$id_system_505 = 					$row[0]['id_system_505'];
		$rela_system_504 = 					$row[0]['rela_system_504'];
		$system_505_escuela =				$row[0]['system_505_escuela'];
		$system_505_circuito =				$row[0]['system_505_circuito'];
		$system_505_direccion =				$row[0]['system_505_direccion'];
		$system_505_googlemaps =			$row[0]['system_505_googlemaps'];	
	}
	else
	{
		$id_system_505 = 					'';
		$rela_system_504 =					'';
		$system_505_escuela =				'';
		$system_505_circuito =				'';
		$system_505_direccion =				'';
		$system_505_googlemaps =			'';	
	}
 
	$t->set_var("system_505_escuela",	"$system_505_escuela");
	$t->set_var("system_505_circuito",	"$system_505_circuito");
	$t->set_var("system_505_direccion",	"$system_505_direccion");
	$t->set_var("system_505_googlemaps","$system_505_googlemaps");
	$t->set_var("rela_system_504",		"$rela_system_504");
		
	
	$url="'modulos/escuelas/php/_interfaz.php'"; // siempre va a abm_interfaz
	$vars="'nombre_funcion=agregar_modificar_escuela&";
	$vars.="id_system_505=$id_system_505&";
	$vars.="rela_system_504='+encodeURIComponent(abm.rela_system_504.value)+'&";	
	$vars.="system_505_escuela='+encodeURIComponent(abm.system_505_escuela.value)+'&";	
	$vars.="system_505_circuito='+encodeURIComponent(abm.system_505_circuito.value)+'&";			
	$vars.="system_505_direccion='+encodeURIComponent(abm.system_505_direccion.value)+'&";			
	$vars.="system_505_googlemaps='+abm.system_505_googlemaps.value";	
	$url_exito="'modulos/escuelas/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01'";	

	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");
	
	$url="'modulos/escuelas/php/selector_circuito.php'";
	$id="'selector_pueblo'";
	$vars="'id_system_505=$id_system_505&rela_system_504=$rela_system_504&system_505_circuito='";
	$t->set_var("funcion_selector_pueblo","cargar_post($url,$id,$vars+this.value)");
	
	
	$url2="'modulos/escuelas/php/home.php'";
	$id2="'content_seccion'";
	$vars2="'id_system_01=$id_system_01'";
	$t->set_var("funcion_volver","cargar_post($url2,$id2,$vars2)");	

		
$t->pparse("OUT", "ver");
?>
