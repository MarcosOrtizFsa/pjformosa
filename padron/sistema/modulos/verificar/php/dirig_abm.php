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
	'ver'		=> "dirig_abm.html"
	));

$id_system_2005 = 	isset($_POST['id_system_2005']) ? $_POST['id_system_2005'] : NULL;

	$row = $mysqli -> consulta_SQL("Select * from system_2005_lista_dirigentes    where id_system_2005 = '$id_system_2005' ");				
	if ($row == true)
	{			
		$system_2005_nombre = 	$row[0]['system_2005_nombre'];
		$id_system_2005 =	$row[0]['id_system_2005'];
	}
	else
	{
		$id_system_2005 =		'';	
		$system_2005_nombre 	 = '';		
	}
 
	$t->set_var("system_2005_nombre",	"$system_2005_nombre");		
	
	$url="'modulos/verificar/php/_interfaz.php'";
	$vars="'nombre_funcion=agregar_dirigente&";
	$vars.="id_system_2005=$id_system_2005&";
	$vars.="system_2005_nombre='";

	$url_exito="'modulos/verificar/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01'";
	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars+this.value,$url_exito,$id,$vars_exito)");

	
	

	
		
	$url2="'modulos/verificar/php/home.php'";
	$id2="'content_seccion'";
	$vars2="'id_system_01=$id_system_01'";
	$t->set_var("funcion_volver","cargar_post($url2,$id2,$vars2)");	

		
$t->pparse("OUT", "ver");
?>
