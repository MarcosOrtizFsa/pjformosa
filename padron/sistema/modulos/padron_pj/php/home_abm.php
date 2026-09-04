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
	'ver'		=> "home_abm.html",
	'select'	=> "una_opcion.html"
	));

$system_704_dni = 	isset($_POST['system_704_dni']) ? $_POST['system_704_dni'] : NULL;
$system_704_dni =	formatear_dni($system_704_dni);		


	$row = $mysqli -> consulta_SQL("Select * from system_2001_extras   where system_2001_dni = '$system_704_dni' ");				
	if ($row == true)
	{			
		$system_2001_dni = 		$row[0]['system_2001_dni'];
		$t->set_var("disabled",	"disabled");
	}
	else
	{
		$system_2001_dni =		'';	
		$t->set_var("disabled",	"");
		
		
		$valu = explode('@', 			funcion_traer_datos_padron($system_704_dni,$mysqli));
		$system_apellido = 				$valu['0'];
		$system_nombre = 				$valu['1'];
		$system_sexo =					$valu['2'];
		
		//donde_vive=  system_2002_domicilio  localidad_por_circuito	system_2002_circuito 
		$valu1 = explode('@', 	donde_vive($system_704_dni,$mysqli));
		$system_domicilio = 	$valu1['0'];					
		$system_circuito = 		$valu1['2'];
		
		// system_506_dpto   system_506_localidad 
		$valu2 = explode('@', 	funcion_traer_localidad_por_circuito($system_circuito,$mysqli));
		$system_dpto = 			$valu2['0'];
		$system_localidad = 	$valu2['1'];
		$departamento = 		consultar_departamento($system_dpto,$mysqli);

		$system_704_apellido=	$system_apellido;
		$system_704_nombre=		$system_nombre;
		$system_704_sexo=		$system_sexo;
		$system_704_circuito =	$system_circuito;
		$system_704_domicilio =	$system_domicilio;
		$system_704_dpto =		$departamento;	
		$system_704_localidad =	$system_localidad;	
		$t->set_var("disabled",	"disabled");


			
			
	}
 
	$t->set_var("system_704_nombre",	"$system_704_nombre");
	$t->set_var("system_704_apellido",	"$system_704_apellido");
	$t->set_var("system_704_dni",		"$system_704_dni");
	$t->set_var("system_704_sexo",		"$system_704_sexo");
	$t->set_var("system_704_domicilio",	"$system_704_domicilio");
	$t->set_var("system_704_dpto",		"$system_704_dpto");
	$t->set_var("system_704_localidad",	"$system_704_localidad");
	$t->set_var("system_704_circuito",	"$system_704_circuito");			
	
	$url="'modulos/afiliaciones/php/_interfaz.php'";
	$vars="'nombre_funcion=agregar_modificar&";
	$vars.="system_704_dni=$system_704_dni&";
	$vars.="system_704_sexo='+abm2.system_704_sexo.value+'&";
	$vars.="system_704_circuito='+abm2.system_704_circuito.value+'&";
	$vars.="system_704_dpto='+encodeURIComponent(abm2.system_704_dpto.value)+'&";
	$vars.="system_704_localidad='+encodeURIComponent(abm2.system_704_localidad.value)+'&";
	$vars.="system_704_apellido='+encodeURIComponent(abm2.system_704_apellido.value)+'&";
	$vars.="system_704_nombre='+encodeURIComponent(abm2.system_704_nombre.value)+'&";
	$vars.="system_704_domicilio='+encodeURIComponent(abm2.system_704_domicilio.value)";

	$url_exito="'modulos/afiliaciones/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01&system_704_dni=$system_704_dni'";
	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");

	
	

	
	$sexx = array('M'=>"M", 'F'=>"F"); 
	while ($select_s = current($sexx)) 
	{
		if ( key($sexx) == $system_704_sexo ) 
		{
			$t->set_var("ID",key($sexx)." SELECTED ");	
		}
		else
		{
			$t->set_var("ID",key($sexx));	
		}
		$t->set_var("NOMBRE",$select_s);	
		$t->parse("SEXO","select",true);
		next($sexx);
	}
	
		
	$url2="'modulos/afiliaciones/php/home_abm.php'";
	$id2="'content_nuevo'";
	$vars2="'system_704_dni=$system_704_dni'";
	$t->set_var("funcion_volver","cargar_post($url2,$id2,$vars2)");	

		
$t->pparse("OUT", "ver");
?>
