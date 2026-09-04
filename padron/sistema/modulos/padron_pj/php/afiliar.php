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
	'ver'		=> "afiliar.html"
	));

$system_2001_dni = 	isset($_POST['system_2001_dni']) ? $_POST['system_2001_dni'] : NULL;
$system_2001_dni =	formatear_dni($system_2001_dni);		

$url2="'modulos/afiliaciones/php/afiliar.php'";
$id2="'content_nuevo'";
$vars2="'system_2001_dni=$system_2001_dni'";
$funcion_refrescar = "cargar_post($url2,$id2,$vars2)";	

	$row = $mysqli -> consulta_SQL("Select * from system_2001_extras where system_2001_dni = '$system_2001_dni' ");				
	if ($row == true)
	{			
		$system_2001_dni = 			$row[0]['system_2001_dni'];
		$system_2001_ima_frente = 	$row[0]['system_2001_ima_frente'];
		$system_2001_ima_dorso = 	$row[0]['system_2001_ima_dorso'];
		$system_2001_estado = 		$row[0]['system_2001_estado'];
		// 	0=afiliado; 1=nuevo en proceso; 2=ficha creada; 3=Afiliado/a 	
		
		$t->set_var("disabled",	"disabled");		
		
		$valu = explode('@', 			funcion_traer_datos_full($system_2001_dni,$mysqli));
		$system_nombre_apellido = 		$valu['0'];
		$system_circuito = 				$valu['1'];	
		$system_domicilio = 			$valu['2'];		
		

		// system_506_dpto   system_506_localidad 
		$valu2 = explode('@', 	funcion_traer_localidad_por_circuito($system_circuito,$mysqli));
		$system_dpto = 			$valu2['0'];
		$system_localidad = 	$valu2['1'];
		$system_dpto = 			consultar_departamento($system_dpto,$mysqli);

		
	}
						
	$t->set_var("system_nombre_apellido",	"$system_nombre_apellido");
	$t->set_var("system_circuito",	"$system_circuito");
	$t->set_var("system_dni",		"$system_2001_dni");
	$t->set_var("system_domicilio",	"$system_domicilio");
	$t->set_var("system_dpto",		"$system_dpto");
	$t->set_var("system_localidad",	"$system_localidad");
	$t->set_var("system_circuito",	"$system_circuito"); 
	$file = '../dnis/';	
	
	if ( trim($system_2001_ima_frente) != '' )
	{	
		$url="'modulos/afiliaciones/php/_interfaz.php'";
		$vars="'nombre_funcion=eliminar&";
		$vars.="system_2001_dni=$system_2001_dni&cara=1'";
		$url_exito="'modulos/afiliaciones/php/afiliar.php'";
		$id="'content_nuevo'";
		$vars_exito="'system_2001_dni=$system_2001_dni'";
		$atx="''";
		$msg="'Eliminar esta imagen?'";
		$funcion_borrar="eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);";
		
		// HERRAMIENTA DE CORTE
		$funcion_cortar = "javascript:abrir_popup_g('crop/index.php?system_2001_ima=$system_2001_ima_frente')";
		
		$vars2 ="'elemento=$system_2001_ima_frente'";
		$funcion_rotar = "rotate_ima_post($vars2,$system_2001_dni);";
				
		$t->set_var("imagen_frente",	'<h4><img src="'.$file.''.$system_2001_ima_frente.'" style=" width:280px;" /><i onclick="'.$funcion_borrar.'" class="bi bi-trash-fill"></i><i onClick="'.$funcion_cortar.'" class="bi bi-bounding-box" style="margin:5px 15px 5px 1px;"></i><i onClick="'.$funcion_rotar.'" class="bi bi-arrow-90deg-right" style=""></i><i onClick="'.$funcion_refrescar.'" class="bi bi-arrow-clockwise" style="margin:5px 15px 5px 1px;"></i></h4>'); 
	}
	else
	{
		$t->set_var("imagen_frente",""); 
	}

	if ( trim($system_2001_ima_dorso) != '' )
	{
		$url2="'modulos/afiliaciones/php/_interfaz.php'";
		$vars2="'nombre_funcion=eliminar&";
		$vars2.="system_2001_dni=$system_2001_dni&cara=2'";
		$url_exito2="'modulos/afiliaciones/php/afiliar.php'";
		$id2="'content_nuevo'";
		$vars_exito2="'system_2001_dni=$system_2001_dni'";
		$atx2="''";
		$msg2="'Eliminar esta imagen?'";
		$funcion_borrar2="eliminar_mostrar($url2,$vars2,$url_exito2,$id2,$vars_exito2,$msg2,$atx2);";
		
		// HERRAMIENTA DE CORTE
		$funcion_cortar2 = "javascript:abrir_popup_g('crop/index.php?system_2001_ima=$system_2001_ima_dorso')";
		
		$vars3 ="'elemento=$system_2001_ima_dorso'";
		$funcion_rotar2 = "rotate_ima_post($vars3,$system_2001_dni);";
		
		$t->set_var("imagen_dorso",	'<h4><img src="'.$file.''.$system_2001_ima_dorso.'" style=" width:280px;" /><i onclick="'.$funcion_borrar2.'" class="bi bi-trash-fill"></i><i onClick="'.$funcion_cortar2.'" class="bi bi-bounding-box" style="margin:5px 15px 5px 1px;"></i><i onClick="'.$funcion_rotar2.'" class="bi bi-arrow-90deg-right" style=""></i><i onClick="'.$funcion_refrescar.'" class="bi bi-arrow-clockwise" style="margin:5px 15px 5px 1px;"></i></h4>'); 
	}
	else
	{	
		$t->set_var("imagen_dorso",	""); 
	}

	
					
	

	

$url_exito="modulos/afiliaciones/php/afiliar.php";
$id="content_nuevo";
$vars="system_2001_dni=$system_2001_dni";// tipo 1 es una imagen foto
$msj='Fotos de frente y dorso del DNI';	
$style =' style="width: 110px;" ';	
$t->set_var("funcion_cargar_archivo",funcion_cargar_dni($url_exito,$id,$vars,$msj,$style));



$t->set_var("funcion_refrescar",$funcion_refrescar);	

	
		
$t->pparse("OUT", "ver");
?>
