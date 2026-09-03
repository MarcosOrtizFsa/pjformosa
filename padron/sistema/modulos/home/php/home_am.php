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

$id_system_503 =		isset($_POST['id_system_503']) 	? $_POST['id_system_503'] : NULL;	
$id_system_602 =		isset($_POST['id_system_602']) 	? $_POST['id_system_602'] : NULL;	

/*	$sql=$mysqli->query("Select * from system_503_mesas where id_system_503='$id_system_503'");				
	if ($row = $sql -> fetch_array ())
	{			
		$system_503_mesa=$row['system_503_mesa'];
	}*/
	$system_503_mesa=0;// MESA CARGA CON LA MISMA MESA
	
	$row = $mysqli -> consulta_SQL("Select * from system_602_escrutinio where id_system_602='$id_system_602'");				
	if ($row == true)
	{					
		$system_602_sublema=	$row[0]['system_602_sublema'];
		$system_602_orden=		$row[0]['system_602_orden'];	
	}
	
	$row2 = $mysqli -> consulta_SQL("Select * from system_605_totales  where system_605_mesa='$system_503_mesa' and rela_system_602='$id_system_602' ");				
	if ($row2 == true)
	{			
		$id_system_605 = 	$row2[0]['id_system_605'];
		$system_605_1ro =	$row2[0]['system_605_1ro'];
		$system_605_2do =	$row2[0]['system_605_2do'];
		$system_605_3ro =	$row2[0]['system_605_3ro'];
		$system_605_4to =	$row2[0]['system_605_4to'];
		$system_605_5to =	$row2[0]['system_605_5to'];
		$system_605_6to =	$row2[0]['system_605_6to'];
		$system_605_7mo =	$row2[0]['system_605_7mo'];
		$system_605_8vo =	$row2[0]['system_605_8vo'];
	}
	else
	{
		// AGREGO EL ESCRUTINIO SI NO EXISTE ESTE SUBLEMA Y MESA
		$id_system_605 = 		'';
		$system_605_1ro =	'0';
		$system_605_2do =	'0';
		$system_605_3ro =	'0';
		$system_605_4to =	'0';
		$system_605_5to =	'0';
		$system_605_6to =	'0';
		$system_605_7mo =	'0';
		$system_605_8vo =	'0';
	}
	
	$t->set_var("system_602_sublema",	"$system_602_sublema");
	$t->set_var("system_602_orden",		"$system_602_orden");
	$t->set_var("system_605_1ro",		"$system_605_1ro");
	$t->set_var("system_605_2do",		"$system_605_2do");
	$t->set_var("system_605_3ro",		"$system_605_3ro");
	$t->set_var("system_605_4to",		"$system_605_4to");
	$t->set_var("system_605_5to",		"$system_605_5to");
	$t->set_var("system_605_6to",		"$system_605_6to");
	$t->set_var("system_605_7mo",		"$system_605_7mo");
	$t->set_var("system_605_8vo",		"$system_605_8vo");
	
	$url="'modulos/home/php/_interfaz.php'";
	$vars="'nombre_funcion=agregar_modificar&id_system_605=$id_system_605&rela_system_602=$id_system_602&system_605_mesa=$system_503_mesa&";
	/*$vars.="system_605_8vo='+abm.system_605_8vo.value+'&";
	$vars.="system_605_7mo='+abm.system_605_7mo.value+'&";	
	$vars.="system_605_6to='+abm.system_605_6to.value+'&";	
	$vars.="system_605_5to='+abm.system_605_5to.value+'&";*/	
	$vars.="system_605_4to='+abm.system_605_4to.value+'&";	
	$vars.="system_605_3ro='+abm.system_605_3ro.value+'&";	
	$vars.="system_605_2do='+abm.system_605_2do.value+'&";	
	$vars.="system_605_1ro='+abm.system_605_1ro.value";

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
