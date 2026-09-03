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
	'ver'				=> "pop_am.html"
	));


$id_system_504 = isset($_POST['id_system_504']) ? $_POST['id_system_504'] : NULL;
$system_502_circuito = isset($_POST['system_502_circuito']) ? $_POST['system_502_circuito'] : NULL;

$row = $mysqli -> consulta_SQL("Select * from system_504_ubicacion 
								where 
								id_system_504 = '$id_system_504'");
if ($row == TRUE)
{
	$id_system_504 = 			$row[0]['id_system_504'];
	$system_504_circuito = 		$row[0]['system_504_circuito'];
	$system_504_pueblo = 		$row[0]['system_504_pueblo'];
	$system_504_mapsgoogle = 	$row[0]['system_504_mapsgoogle'];
		
	$titulo_modulo="Editar Pueblo/Localidad";	
	$boton_modulo="Salvar";
}
else
{
	$id_system_504 = 			'';
	$system_504_circuito = 		$system_502_circuito;
	$system_504_pueblo = 		'';
	$system_504_mapsgoogle = 	'';
	$titulo_modulo="Agregar Pueblo/Localidad";	
	$boton_modulo="Guardar";
}
	
	$t->set_var("id_system_504",			"$id_system_504");
	$t->set_var("system_504_circuito",		"$system_504_circuito");
	$t->set_var("system_504_pueblo",		"$system_504_pueblo");
	$t->set_var("system_504_mapsgoogle",	"$system_504_mapsgoogle");
	$t->set_var("titulo_modulo","$titulo_modulo");	
	$t->set_var("boton_modulo","$boton_modulo");


	$url="'modulos/localidades/php/_interfaz.php'"; // siempre va a abm_interfaz
	$vars="'nombre_funcion=agregar_modificar_pueblo&";
	$vars.="id_system_504=$id_system_504&";
	$vars.="system_504_circuito=$system_504_circuito&";		
	$vars.="system_504_pueblo='+encodeURIComponent(abm2.system_504_pueblo.value)+'&";			
	$vars.="system_504_mapsgoogle='+abm2.system_504_mapsgoogle.value";	
	$url_exito="'modulos/localidades/php/popup_canvas.php'";
	$id="'popup_canvas'";
	$vars_exito="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito'";	

	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");
	


$url="'modulos/localidades/php/popup_canvas.php'";
$id="'popup_canvas'";
$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito'";
$t->set_var("funcion_volver","cargar_post($url_exito,$id,$vars_exito)");



	
$t->pparse("OUT", "ver");
?>
