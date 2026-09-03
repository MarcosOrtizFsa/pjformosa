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
	'ver'		=> "confi.html"
	));


	
	$t->set_var("system_08_tema",	"$system_08_tema");
	$t->set_var("system_08_total_objetivo",	"$system_08_total_objetivo");

	
	$url="'modulos/home/php/_interfaz.php'";
	$vars="'nombre_funcion=configurar_sistema&";
	$vars.="system_08_tema='+abm3.system_08_tema.value+'&";	
	$vars.="system_08_total_objetivo='+abm3.system_08_total_objetivo.value";

	$url_exito="'modulos/home/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01'";	
	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");


	
$url2="'modulos/home/php/home.php'";
$id2="'content_seccion'";
$vars2="'id_system_01=$id_system_01'";
$t->set_var("funcion_volver","cargar_post($url2,$id2,$vars2)");

$t->pparse("OUT", "ver");
?>
