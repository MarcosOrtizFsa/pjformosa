<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'		=> "totales_am.html"
	));

	$id_system_606 =		isset($_POST['id_system_606']) 	? $_POST['id_system_606'] : NULL;	
	$system_606_mesa =		isset($_POST['system_606_mesa']) 	? $_POST['system_606_mesa'] : NULL;		
			
	$row = $mysqli -> consulta_SQL("Select * from system_606_resumen_total where system_606_mesa='$system_606_mesa' and id_system_606='$id_system_606'");				
	if ($row == true)
	{			
		$id_system_606 = 		$row[0]['id_system_606'];
		$system_606_mesa=		$row[0]['system_606_mesa'];
		$system_606_nulos=		$row[0]['system_606_nulos'];
		$system_606_recurridos=	$row[0]['system_606_recurridos'];
		$system_606_impugnada=	$row[0]['system_606_impugnada'];
		$system_606_comando=	$row[0]['system_606_comando'];
		$system_606_blanco=		$row[0]['system_606_blanco'];
		$system_606_total = 	$row[0]['system_606_total'];
	}
	else
	{
		$id_system_606=			'';
		$system_606_nulos=		'0';
		$system_606_recurridos=	'0';
		$system_606_impugnada=	'0';
		$system_606_comando=	'0';
		$system_606_blanco=		'0';
		$system_606_total = 	'0';
	}
		
	$t->set_var("system_606_nulos",$system_606_nulos);
	$t->set_var("system_606_recurridos",$system_606_recurridos);
	$t->set_var("system_606_impugnada",$system_606_impugnada);
	$t->set_var("system_606_comando","$system_606_comando");
	$t->set_var("system_606_blanco","$system_606_blanco");
	$t->set_var("system_606_total","$system_606_total");

	$url="'modulos/home/php/_interfaz.php'";
	$vars="'nombre_funcion=totales_am&id_system_606=$id_system_606&system_606_mesa=$system_606_mesa&";
	$vars.="system_606_nulos='+abm2.system_606_nulos.value+'&";	
	$vars.="system_606_recurridos='+abm2.system_606_recurridos.value+'&";
	$vars.="system_606_impugnada='+abm2.system_606_impugnada.value+'&";
	$vars.="system_606_comando='+abm2.system_606_comando.value+'&";
	$vars.="system_606_blanco='+abm2.system_606_blanco.value+'&";
	$vars.="system_606_total='+abm2.system_606_total.value";

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
