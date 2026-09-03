<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'		=> "escuelas.html",
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
	$tSQL = $mysqli -> consulta_SQL("Select COUNT(*) total_filas from system_503_escuelas   $where_control ");
	if ($tSQL == TRUE)
	{		
		$total_filas = $tSQL[0]['total_filas'];
	}
	
	$row = $mysqli -> consulta_SQL("Select * from system_503_escuelas  $where_control order by id_system_503 ASC ");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_503 = 					$row[$i]['id_system_503'];
			$system_503_escuela =				$row[$i]['system_503_escuela'];
			$system_503_circuito =				$row[$i]['system_503_circuito'];
			$system_503_direccion =				$row[$i]['system_503_direccion'];
			$system_503_ubicacion =				$row[$i]['system_503_ubicacion'];
						
			$t->set_var("id_system_503",		"$id_system_503");
			$t->set_var("system_503_escuela",	"$system_503_escuela");
			$t->set_var("system_503_direccion",	"$system_503_direccion");
			$t->set_var("system_503_circuito",	"$system_503_circuito");	
			$t->set_var("system_503_ubicacion",	"$system_503_ubicacion");				

			
			// modificar 
			if ( optener_permisos('M',$id_system_01,$sesion_system_03,$mysqli) == '1')
			{				
				$url="'modulos/mesas/php/escuela_abm.php'";
				$id="'content_$id_system_503'";
				$vars="'id_system_01=$id_system_01&id_system_503=$id_system_503'";	
				$t->set_var("funcion_editar","cargar_post($url,$id,$vars)");	
			}
			else
			{
				$t->set_var("funcion_editar","sin_permisos()");
			}	
			
			// elimiar
			if ( optener_permisos('B',$id_system_01,$sesion_system_03,$mysqli) == '1' and  $candado=='on' )
			{
				$url="'modulos/control/php/_interfaz.php'";
				$vars="'nombre_funcion=eliminar&";
				$vars.="id_system_03=$id_system_03'";
				$url_exito="'modulos/control/php/home.php'";
				$id="'content_seccion'";
				$vars_exito="'id_system_01=$id_system_01'";
				$atx="''";
				$msg="'Eliminar este registro?'";
				$t->set_var("funcion_borrar","eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);");
			}
			else
			{
				$t->set_var("funcion_borrar","sin_permisos()");
			}
			
			
						
			$t->parse("LISTADO","una_escuela",true);
		}
	} 
	else 						
	{
	$t->SET_VAR("LISTADO",'');
	}	


	// Funciuon de agregar
	if (  optener_permisos('A',$id_system_01,$sesion_system_03,$mysqli) == '1' )
	{
		$url="'modulos/mesas/php/escuela_abm.php'";
		$id="'content_'";
		$vars="'id_system_01=$id_system_01'";		
		$t->set_var("funcion_agregar","cargar_post($url,$id,$vars)");	
	}
	else
	{
		$t->set_var("funcion_agregar","sin_permisos()");
	}
	
	

$t->set_var("PAGINAR","$total_filas");


$url="'modulos/mesas/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01'";	
$t->set_var("funcion_volver","cargar_post($url,$id,$vars)");



$t->pparse("OUT", "ver");
?>