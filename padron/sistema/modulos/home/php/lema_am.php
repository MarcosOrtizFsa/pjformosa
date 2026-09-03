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
	'ver'		=> "lema_am.html"
	));

$id_system_503 =		isset($_POST['id_system_503']) 	? $_POST['id_system_503'] : NULL;	
$id_system_602 =		isset($_POST['id_system_602']) 	? $_POST['id_system_602'] : NULL;	


	$row = $mysqli -> consulta_SQL("Select * from system_602_escrutinio where id_system_602 = '$id_system_602'");				
	if ($row == true)
	{					
		$id_system_602= 		$row[0]['id_system_602'];
		$rela_system_603= 		$row[0]['rela_system_603'];
		$rela_system_604=		$row[0]['rela_system_604'];
		$system_602_sublema =	$row[0]['system_602_sublema'];
		$system_602_orden =		$row[0]['system_602_orden'];
		
		
		// elimiar
		if ( optener_permisos('B',$id_system_01,$sesion_system_03,$mysqli) == '1' and  $candado=='on' )
		{
			$url="'modulos/home/php/_interfaz.php'";
			$vars="'nombre_funcion=eliminar_lema&";
			$vars.="id_system_602=$id_system_602'";
			$url_exito="'modulos/home/php/home.php'";
			$id="'content_seccion'";
			$vars_exito="'id_system_01=$id_system_01'";
			$atx="''";
			$msg="'Eliminar este lema/sublema?'";
			$funcion_borrar_lema = "eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);";
			$t->set_var("funcion_borrar_lema",'<a href="javascript:;" onclick="'.$funcion_borrar_lema.'"><i class="bi bi-trash-fill"></i></a>');
			
		}
		else
		{
			$t->set_var("funcion_borrar_lema",'<a href="javascript:;" onclick="sin_permisos()"><i class="bi bi-trash-fill"></i></a>');
		}
			
	}
	else
	{
		$rela_system_603= 		'0';
		$rela_system_604= 		'0';
		$system_602_sublema =	'';
		$system_602_orden =		'';
		$t->set_var("funcion_borrar_lema","&nbsp;");
	}
	
	
	$t->set_var("system_602_sublema",	"$system_602_sublema");
	$t->set_var("system_602_orden",		"$system_602_orden");
	
	$url="'modulos/home/php/_interfaz.php'";
	$vars="'nombre_funcion=agregar_modificar_lema&id_system_602=$id_system_602&rela_system_603=$rela_system_603&rela_system_604=$rela_system_604&";	
	$vars.="system_602_sublema='+abm.system_602_sublema.value+'&";	
	$vars.="system_602_orden='+abm.system_602_orden.value";

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
