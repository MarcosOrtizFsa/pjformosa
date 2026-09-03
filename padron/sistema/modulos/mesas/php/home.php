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
	'una_mesa'	=> "una_mesa.html"
	));

$t->set_var("titulo_modulo","Mesas y escuelas");



$variable_buscar = 		trim(isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL);
//$buscar_cuitl=formatear_cuit($_POST['buscar_cuitl']);


$where = " ";	

if ( $variable_buscar != "" )
{	
	$where = " where  system_504_mesa != '0' ";	

	if ($variable_buscar != '' ) 
	{	
		$where.=" and system_504_escuela  like '%$variable_buscar%' ";    	
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
	$tSQL = $mysqli -> consulta_SQL("Select COUNT(*) total_filas from system_504_mesas   $where_control ");
	if ($tSQL == TRUE)
	{		
		$total_filas = $tSQL[0]['total_filas'];
	}
	
	$row = $mysqli -> consulta_SQL("Select * from system_504_mesas   $where_control order by system_504_mesa ASC $LIMITE");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{					
			$t->set_var("system_504_mesa",		$row[$i]['system_504_mesa']);
			$t->set_var("system_504_escuela",	$row[$i]['system_504_escuela']);
			$t->set_var("system_504_dpto",		$row[$i]['system_504_dpto']);
			$t->set_var("system_504_localidad",	$row[$i]['system_504_localidad']);
					
			$t->parse("LISTADO","una_mesa",true);
		}
	} 
	else 						
	{
	$t->SET_VAR("LISTADO",'');
	}	



	if (  optener_permisos('A',$id_system_01,$sesion_system_03,$mysqli) == '1' and  $root_candado == 'on' )
	{
		$url="'modulos/mesas/php/cargador.php'";	
		$comando_camara = "abrir_popup($url)";
		$opciones_menu = '<button type="button" class="btn btn-success btn-sm" style="font-size:12px;  margin:0px; padding:4px 10px 4px 10px;" onclick="'.$comando_camara.'">ACTUALIZAR</button>';
		$t->set_var("opciones_menu",$opciones_menu);					
	}
	else
	{
		$t->set_var("opciones_menu","");
	}

// buscador
$urlb="'modulos/mesas/php/home.php'";
$idb="'content_seccion'";
$varsb="'reset=go&variable_buscar='+busqueda.variable_buscar.value";
$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");


$url="'modulos/mesas/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01&mas=$num_filas'";	
$PAGINAR = funcion_paginar_siguiente($total_filas,$num_filas,$url,$id,$vars);
$t->set_var("PAGINAR","$PAGINAR");



$t->pparse("OUT", "ver");
?>