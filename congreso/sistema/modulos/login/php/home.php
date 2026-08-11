<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$t = new _template('../templates');
$t->set_file(array(
	'ver'	=> 		"home.html"
	));



	
	$t->set_var("TITULO_LOGIN",''.$system_08_contactos_visibles.'');	


	$url="'modulos/login/php/registrate.php'";
	$id="'content_registrate'";
	$vars="'reset=go'";
	$t->set_var("funcion_registrate","cargar_post($url,$id,$vars)");


	$url="'modulos/login/php/recuperar.php'";
	$id="'content_registrate'";
	$vars="'reset=go'";
	$t->set_var("funcion_recuperar","cargar_post($url,$id,$vars)");
		
	
$t->pparse("OUT", "ver");
?>