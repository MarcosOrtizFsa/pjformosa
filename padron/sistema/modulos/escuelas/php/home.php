<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'		=> "home.html",
	'una_escuela'	=> "una_escuela.html"
	));

$t->set_var("titulo_modulo","Escuelas");



$variable_buscar = 		trim(isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL);
//$buscar_cuitl=formatear_cuit($_POST['buscar_cuitl']);


$where = " ";	

if ( $variable_buscar != "" )
{	
	$where = " where  id_system_505 != '0' ";	

	if ($variable_buscar != '' ) 
	{	
		$where.=" and system_505_escuela  like '%$variable_buscar%' ";    	
    } 
	

		
	$_SESSION['where_control']=$where;
	
}
else
{	
	if ( $where_control!='' )
	{
	$_SESSION['where_control']=$where_control;
	}
	else
	{
	$_SESSION['where_control']=$where;
	}
}							
$where_control=$_SESSION['where_control'];
//echo $where_control;




	$total_filas = '0';		
	$tSQL = $mysqli -> consulta_SQL("Select COUNT(*) total_filas from system_505_escuelas  $where_control ");
	if ($tSQL == TRUE)
	{		
		$total_filas = $tSQL[0]['total_filas'];
	}
	
	$row = $mysqli -> consulta_SQL("Select * from system_505_escuelas  $where_control order by system_505_escuela ASC $LIMITE");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_505 = 					$row[$i]['id_system_505'];
			$rela_system_504 = 					$row[$i]['rela_system_504'];
			$system_505_escuela =				$row[$i]['system_505_escuela'];
			$system_505_circuito =				$row[$i]['system_505_circuito'];
			$system_505_direccion =				$row[$i]['system_505_direccion'];
			$system_505_googlemaps =			$row[$i]['system_505_googlemaps'];
			
			if ($system_505_googlemaps != '' )
			{
				$t->set_var("system_505_googlemaps",'<a href="'.$system_505_googlemaps.'" target="news"><i class="bi bi-geo-alt-fill"></i></a>');
			}
			else
			{
				$t->set_var("system_505_googlemaps","");
			}	
								
			$t->set_var("id_system_505",		"$id_system_505");
			$t->set_var("system_505_escuela",	"$system_505_escuela");
			$t->set_var("system_505_direccion",	"$system_505_direccion");			
			$t->set_var("system_505_circuito",	"$system_505_circuito");
			$t->set_var("rela_system_504",	pueblo_por_id(0,$rela_system_504,$mysqli));

			$url="'modulos/escuelas/php/home_am.php'";
			$id="'content_$id_system_505'";
			$vars="'id_system_01=$id_system_01&id_system_505=$id_system_505'";	
			$t->set_var("funcion_editar","cargar_post($url,$id,$vars)");
						
			$t->parse("LISTADO","una_escuela",true);
		}
	} 
	else 						
	{
	$t->SET_VAR("LISTADO",'');
	}	



// buscador
$urlb="'modulos/escuelas/php/home.php'";
$idb="'content_seccion'";
$varsb="'reset=go&variable_buscar='+busqueda.variable_buscar.value";
$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");


$url="'modulos/escuelas/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01&mas=$num_filas'";	
$PAGINAR = funcion_paginar_siguiente($total_filas,$num_filas,$url,$id,$vars);
$t->set_var("PAGINAR","$PAGINAR");



$t->pparse("OUT", "ver");
?>