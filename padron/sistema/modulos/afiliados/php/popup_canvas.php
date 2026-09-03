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
//Archivos comunes
$t->set_file(array(
	'ver'			=> "popup_canvas.html"
	));

$id_system_701 = isset($_POST['id_system_701']) ? $_POST['id_system_701'] : NULL;

	$row = $mysqli -> consulta_SQL("Select * from system_701_folio where id_system_701 = '$id_system_701'  ");				
	if ($row == true)
	{			
		$id_system_701 =			$row[0]['id_system_701'];
		$rela_system_03 =			$row[0]['rela_system_03'];
		//$system_701_num = 		$row['system_701_num'];
		$t->set_var("id_system_701",$id_system_701);
		$t->set_var("system_apellido_nombre",consulto_perfil($rela_system_03,$mysqli));
	}

 
	$url="'modulos/afiliados/php/_interfaz.php'";
	$vars="'nombre_funcion=nueva_lista&rela_system_701=$id_system_701&";
	$vars.="rela_system_03=$rela_system_03&";
	$vars.="lista_dnis='+abm.lista_dnis.value";

	$url_exito="'modulos/afiliados/php/popup_canvas.php'";
	$id="'popup_canvas'";
	$vars_exito="'id_system_01=$id_system_01&id_system_701=$id_system_701'";
	
	$url2="'modulos/afiliados/php/lista_planilla.php'";
	$id2="'content_seccion'";
	$vars2="'id_system_01=$id_system_01&id_system_701=$id_system_701'";
		
	$t->set_var("funcion_guardar_canvas","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito); cargar_post($url2,$id2,$vars2); ");		
	

$t->pparse("OUT", "ver");
?>
