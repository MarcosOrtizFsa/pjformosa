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


$system_700_dni = 	isset($_POST['system_700_dni']) ? $_POST['system_700_dni'] : NULL;
$id_system_701 = 	isset($_POST['id_system_701']) ? $_POST['id_system_701'] : NULL;

	$row = $mysqli -> consulta_SQL("Select * from system_700_afiliados 
						where 
						system_700_dni = '$system_700_dni' 
						");				
	if($row == true)
	{			
		$id_system_700 =			$row[0]['id_system_700'];
		$rela_system_03 =			$row[0]['rela_system_03'];
		$system_700_dni = 			$row[0]['system_700_dni'];
		$system_700_apellido = 		$row[0]['system_700_apellido'];
		$system_700_nombre = 		$row[0]['system_700_nombre'];
		$system_700_circuito =		$row[0]['system_700_circuito'];
		$system_700_domicilio =		$row[0]['system_700_domicilio'];
		$system_700_estado =		$row[0]['system_700_estado'];
		$system_700_localidad =		$row[0]['system_700_localidad'];
		$system_700_dpto =			$row[0]['system_700_dpto'];
		$system_700_sexo =			$row[0]['system_700_sexo'];
	}
	else
	{
		$id_system_700 =			'';
		$rela_system_03 =			'';
		$system_700_dni = 			'';
		$system_700_apellido = 		'';
		$system_700_nombre = 		'';
		$system_700_circuito =		'';
		$system_700_domicilio =		'';
		$system_700_estado =		'0';
		$system_700_localidad =		'';
		$system_700_dpto =			'';
		$system_700_sexo =			'';
	}

 
	$t->set_var("id_system_700",$id_system_700);
	$t->set_var("system_700_dni",$system_700_dni);
	$t->set_var("system_700_domicilio",$system_700_domicilio);
	$t->set_var("system_700_dpto",$system_700_dpto);	
	$t->set_var("system_700_localidad",$system_700_localidad);	
	$t->set_var("system_700_sexo",$system_700_sexo);	
			



/*	id_system_700 	
	rela_system_03 	
	rela_system_701 	
	system_700_dni 	
	system_700_apellido 	
	system_700_nombre 	
	system_700_sexo 	
	system_700_domicilio 	
	system_700_circuito 	
	system_700_dpto 	
	system_700_localidad 	
	system_700_estado*/
		
$url="'modulos/afiliados/php/_interfaz.php'";
$vars="'nombre_funcion=agregar_nuevo_afiliado&";
$vars.="system_700_dni=$system_700_dni&rela_system_701=$id_system_701&";
$vars.="id_system_700=$id_system_700&";
$vars.="system_700_estado='+encodeURIComponent(abm2.system_700_estado.value)+'&";	
$vars.="system_700_apellido='+encodeURIComponent(abm2.system_700_apellido.value)+'&";	
$vars.="system_700_nombre='+encodeURIComponent(abm2.system_700_nombre.value)+'&";
//$vars.="system_700_circuito='+encodeURIComponent(abm2.system_700_circuito.value)+'&";	
$vars.="system_700_dpto='+encodeURIComponent(abm2.system_700_dpto.value)+'&";	
$vars.="system_700_localidad='+encodeURIComponent(abm2.system_700_localidad.value)+'&";	
$vars.="system_700_domicilio='+encodeURIComponent(abm2.system_700_domicilio.value)";

$url_exito="'modulos/afiliados/php/lista_planilla.php'";
$id="'content_seccion'";
$vars_exito="'id_system_01=$id_system_01&id_system_701=$id_system_701'";	
	
$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");


	$sino = array('1'=>"SI", '0'=>"NO"); 
	while ($select_sdb7 = current($sino)) 
	{
		if (key($sino) == $system_700_estado) 
		{
			$t->set_var("ID",key($sino)." SELECTED ");	
		}
		else
		{
			$t->set_var("ID",key($sino));	
		}
		$t->set_var("NOMBRE",$select_sdb7);	
		$t->parse("ESTADO","select",true);
		next($sino);
	}

	
	$url2="'modulos/afiliados/php/lista_planilla.php'";
	$id2="'content_seccion'";
	$vars2="'id_system_01=$id_system_01&id_system_701=$id_system_701'";
	$t->set_var("funcion_volver","cargar_post($url2,$id2,$vars2)");	

		
$t->pparse("OUT", "ver");
?>
