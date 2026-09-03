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
	'un_orden'	=> "un_orden.html"
	));

$t->set_var("titulo_modulo","Escuela, mesa y orden");



$variable_buscar = 		isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL;
//$buscar_cuitl=formatear_cuit($_POST['buscar_cuitl']);


$where = " ";	

if ( $variable_buscar!="" )
{	
	$variable_buscar=formatear_dni($variable_buscar);
	if (ctype_digit($variable_buscar)) 
	{	
		$where.=" where system_607_dni = '$variable_buscar' ";    	
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
	$tSQL = $mysqli -> consulta_SQL("Select COUNT(*) total_filas from system_607_mesa_orden $where_control ");
	if ($tSQL == TRUE)
	{		
		$total_filas = $tSQL[0]['total_filas'];
	}
	
	$row = $mysqli -> consulta_SQL("Select * from system_607_mesa_orden $where_control order by system_607_orden asc $LIMITE");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_607 = 		$row[$i]['id_system_607'];
			$system_607_dni =		$row[$i]['system_607_dni'];
			$system_607_mesa =		$row[$i]['system_607_mesa'];
			$system_607_orden =		$row[$i]['system_607_orden'];
										
			$t->set_var("id_system_607",		"$id_system_607");
			$t->set_var("system_607_dni",		"$system_607_dni");
			$t->set_var("system_607_mesa",		"$system_607_mesa");
			$t->set_var("system_607_orden",		"$system_607_orden");
			$t->set_var("escuela_direccion",	funcion_ver_escuela(2,$system_607_mesa,$mysqli));
			$t->set_var("escuela_localidad_cto",funcion_ver_escuela(3,$system_607_mesa,$mysqli));
		
			$t->parse("LISTADO","un_orden",true);
		}
	} 
	else 						
	{
	$t->SET_VAR("LISTADO",'');
	}	


	




	if (  optener_permisos('S',$id_system_01,$sesion_system_03,$mysqli) == '1' and  $root_candado == 'on' )
	{
		$url_exito="modulos/mesas/php/home.php";
		$id="content_seccion";
		$vars="id_system_01=$id_system_01&extraer_info=ok";
		$msj='Importar lista de mesas en csv';	
		$tipo_multiple="";	
		$t->set_var("funcion_cargar_archivo",funcion_cargar_elemento($url_exito,$id,$vars,$msj,$tipo_multiple));
						
	}
	else
	{
		$t->set_var("funcion_cargar_archivo","&nbsp;");
	}




	// buscador
	$urlb="'modulos/orden/php/home.php'";
	$idb="'content_seccion'";
	$varsb="'reset=go&variable_buscar='+busqueda.variable_buscar.value";
	$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
	$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");

	
	
	
$url="'modulos/orden/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01&mas=$num_filas'";	
$PAGINAR = funcion_paginar_siguiente($total_filas,$num_filas,$url,$id,$vars);
$t->set_var("PAGINAR","$PAGINAR");



	$name_archivo="mesas_$system_fecha";
	$pagExc="<a href=\"modulos/mesas/php/lista_escuelas_csv.php?name_archivo=$name_archivo\" target=\"news\"><img src=\"../image/iconos/page_excel.png\" border=\"0\"></a>&nbsp;&nbsp;";
	$t->set_var("EXCELES","");	


$t->pparse("OUT", "ver");
?>