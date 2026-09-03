<?php
session_start();
include "../../../../../lib/template.inc";
include "../../../../../lib/mysql_conect.inc";
include "../../../../php/funciones.php";
include "../../../../php/privilegios.php";

$t = new Template('../templates');
//Archivos comunes
$t->set_file(array(
	'ver'		=> "home_abm.html",
	'select'	=> "una_opcion.html"
	));

$id_system_04=$_POST['id_system_04'];

	$sql=$mysqli->query("Select * from system_04_perfil where id_system_04='$id_system_04' limit 1");				
	if ($row = $sql -> fetch_array ())
	{			
		$id_system_04 = $row['id_system_04'];
		$rela_system_501=$row['rela_system_501'];
		$rela_system_502=$row['rela_system_502'];
		$system_04_nombre=$row['system_04_nombre'];
		$system_04_apellido=$row['system_04_apellido'];
		$system_04_dni=$row['system_04_dni'];
		$system_04_circuito=$row['system_04_circuito'];		
	}
	else
	{
		$id_system_04 = '';
		$rela_system_502='';
		$rela_system_501='';
		$system_04_nombre='';
		$system_04_apellido='';
		$system_04_dni='';
	}
	
	
	$sqli1=$mysqli->query("Select * from system_502_municipios");
	while ($row = $sqli1 -> fetch_array())
	{
		$id_system_502=$row['id_system_502'];
		$rela_system_501=$row['rela_system_501'];
		$system_502_circuito=$row['system_502_circuito'];
		$sql2=$mysqli->query("Select * from system_501_localidad where id_system_501='$rela_system_501'");
		if ($row = $sql2 -> fetch_array())
		{
			$system_501_departamento=$row['system_501_departamento'];
		}
		
		$t->set_var("NOMBRE",$system_502_circuito.' de '.$system_501_departamento);
		if (trim($system_04_circuito) == trim($system_502_circuito))
		{
			$t->set_var("ID","\"$id_system_502\" SELECTED ");
		}
		else
		{
			$t->set_var("ID","\"$id_system_502\"");		
		}
		$t->parse("CIRCUITO","select",true);
	}
	
			
	$t->set_var("id_system_04","$id_system_04");
	$t->set_var("system_04_nombre","$system_04_nombre");
	$t->set_var("system_04_apellido","$system_04_apellido");
	$t->set_var("system_04_dni","$system_04_dni");
	$t->set_var("system_501_departamento","...");
		
	
	$url="'templates/modulos/padron/php/abm_interfaz.php'";
	$vars="'nombre_funcion=padron_am&id_system_04=$id_system_04&";
	$vars.="rela_system_502='+abm2.rela_system_502.value+'&";	
	$vars.="system_04_nombre='+encodeURIComponent(abm2.system_04_nombre.value)+'&";
	$vars.="system_04_apellido='+encodeURIComponent(abm2.system_04_apellido.value)+'&";
	$vars.="system_04_dni='+abm2.system_04_dni.value";

	$url_exito="'templates/modulos/padron/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="''";	
	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");

	
	$url2="'templates/modulos/padron/php/home.php'";
	$id2="'content_seccion'";
	$vars2="''";
	$t->set_var("funcion_volver","cargar_post($url2,$id2,$vars2)");	

		
$t->pparse("OUT", "ver");
?>
