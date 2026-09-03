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
	'ver'		=> "nuevo_am.html",
	'select'	=> "una_opcion.html"
	));


$system_600_dni = 	isset($_POST['system_600_dni']) ? $_POST['system_600_dni'] : NULL;
$id_system_601 = 	isset($_POST['id_system_601']) ? $_POST['id_system_601'] : NULL;

$system_600_dni =	formatear_dni($system_600_dni);		
$digit_dni = substr("$system_600_dni", -1);
		
$system_2000_apellido=	'';
$system_2000_nombre=	'';
$system_2000_sexo=		'';
$system_2000_localidad ='';
$system_2000_circuito =	'';
$system_2000_barrio =	'';	
$system_2000_domicilio ='';
$t->set_var("disabled",	"");

 
$t->set_var("system_2000_nombre",	"");
$t->set_var("system_2000_apellido",	"");
$t->set_var("system_2000_dni",		"$system_600_dni");
$t->set_var("system_2000_barrio",	"");
$t->set_var("system_2000_domicilio","");
$t->set_var("system_2000_localidad",consultar_localida($system_2000_localidad,$mysqli).'');
		

	
$url="'modulos/padron/php/_interfaz.php'";
$vars="'nombre_funcion=agregar_nuevo_empadronado&";
$vars.="system_2000_dni=$system_600_dni&digit_dni=$digit_dni&id_system_601=$id_system_601&";
$vars.="rela_system_502='+abm2.rela_system_502.value+'&";
$vars.="system_2000_barrio='+encodeURIComponent(abm2.system_2000_barrio.value)+'&";	
$vars.="system_2000_nombre='+encodeURIComponent(abm2.system_2000_nombre.value)+'&";
$vars.="system_2000_apellido='+encodeURIComponent(abm2.system_2000_apellido.value)+'&";	
$vars.="system_2000_domicilio='+encodeURIComponent(abm2.system_2000_domicilio.value)";

$url_exito="'modulos/planillas/php/lista_planilla.php'";
$id="'content_seccion'";
$vars_exito="'id_system_01=$id_system_01&id_system_601=$id_system_601'";	
	
$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");



	$row4 = $mysqli -> consulta_SQL("Select * from system_502_municipios ORDER BY rela_system_501 asc");
	if ($row4 == true)
	{
		for ( $i=0; $i < count($row4); $i++)
		{
			$id_system_502 =			$row4[$i]['id_system_502'];
			$rela_system_501 =			$row4[$i]['rela_system_501'];
			$t->set_var("NOMBRE",		consultar_localida($rela_system_501,$mysqli).' ('.$row4[$i]['system_502_circuito'].')');
			$t->set_var("ID","\"$id_system_502\"");
			$t->parse("BARRIOS","select",true);
		}
	}


	
	$url2="'modulos/planillas/php/lista_planilla.php'";
	$id2="'content_seccion'";
	$vars2="'id_system_01=$id_system_01&id_system_601=$id_system_601'";
	$t->set_var("funcion_volver","cargar_post($url2,$id2,$vars2)");	

		
$t->pparse("OUT", "ver");
?>
