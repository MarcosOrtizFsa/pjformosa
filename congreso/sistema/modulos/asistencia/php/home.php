<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'	=> "home.html",
	'una_asistencia'=> "una_asistencia.html"
	));

$t->set_var("titulo_modulo","Asistencia");


$variable_buscar= isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL;

$where=" WHERE system_100_estado IN ('0','1') ";			
if ( $variable_buscar != "" )
{
	$variable_buscar=formatear_cuit($variable_buscar);
	
	if (ctype_digit($variable_buscar)) 
	{	
		$where.=" and system_100_dni = '$variable_buscar'  ";	
	} 
	else 
	{
		$where.=" and system_100_congresista like '%$variable_buscar%'  ";
	}

	$_SESSION['where_control']=$where;
}
else
{	

	if ( isset($where_control)!='' )
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
	$tSQL = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_100_congresistas
									");
	if ($tSQL == TRUE)
	{		
		$total_filas = $tSQL[0]['total_filas'];
	}
	else
	{
		$total_filas = '0';
	}


	$row = $mysqli -> consulta_SQL("Select * from system_100_congresistas 
						$where_control
						
						
						ORDER BY id_system_100 ASC
						$LIMITE
						");				
	$cuento="1";
	if ($row == TRUE)
	{
			for ( $i=0; $i < count($row); $i++)
			{			
			
			$id_system_100 = 			$row[$i]['id_system_100'];
			$rela_system_03 = 			$row[$i]['rela_system_03'];
			$system_100_orden = 		$row[$i]['system_100_orden'];
			$system_100_orden_seccion = $row[$i]['system_100_orden_seccion'];
			$system_100_congresista = 	$row[$i]['system_100_congresista'];	
			$system_100_dni = 			$row[$i]['system_100_dni'];	
			$system_100_departamento = 	$row[$i]['system_100_departamento'];
			$system_100_estado=			$row[$i]['system_100_estado'];
	
	
			if ( $system_100_estado == '1' )
			{	
				$checkedbox=" CHECKED ";
				$disabled="  ";
			}
			else
			{
				$checkedbox="";
				$disabled="";
			}	
			// siguiente estacion E= cambia ESTADO
			if ( optener_permisos('E',$id_system_01,$sesion_system_03,$mysqli) == '1' )
			{	
				// ENTREGAR O DEVOLVER
				$url = "'modulos/asistencia/php/_interfaz.php'";
				$vars="'nombre_funcion=on_off&id_system_100=$id_system_100&system_100_estado=$system_100_estado'";
				$funcion_presente = "guardar_solo($url,$vars);";															
			}
			else
			{
				$funcion_presente = "sin_permisos();";
				$disabled=" disabled ";
			}
			
			$boton_on_off =	'<input class="box_b" name="" type="checkbox" value=""  onclick="'.$funcion_presente.'" '.$checkedbox.' '.$disabled.' />';
			$t->set_var("boton_on_off",$boton_on_off);
			// editar
			if (  optener_permisos('M',$id_system_01,$sesion_system_03,$mysqli) == '1' )
			{
				$url="'modulos/asistencia/php/home_abm.php'";
				$id="'content_$id_system_100'";
				$vars="'id_system_01=$id_system_01&id_system_100=$id_system_100'";
				$funcion_editar="cargar_post($url,$id,$vars)";
				
				$t->set_var("funcion_editar",'<a href="javascript:;" onclick="'.$funcion_editar.'"><i class="bi bi-pencil-square"></i></a>');
			}
			else
			{
				$t->set_var("funcion_editar",'');
			}				
					
			
	
			// BORRAR
			if (  optener_permisos('B',$id_system_01,$sesion_system_03,$mysqli) == '1' )
			{
				$url="'modulos/asistencia/php/_interfaz.php'";
				$vars="'nombre_funcion=eliminar&id_system_01=$id_system_01&id_system_100=$id_system_100'";
				$url_exito="'modulos/asistencia/php/home.php'";
				$id="'content_seccion'";
				$vars_exito="'id_system_01=$id_system_01'";
				$msg="'Eliminar este registro?'";
				$atx="''";
				$funcion_borrar = "eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);";	
				$t->set_var("funcion_borrar",'<a href="javascript:;" onclick="'.$funcion_borrar.'"><i class="bi bi-trash-fill"></i></a>');
			}
			else
			{
				$t->set_var("funcion_borrar","");
			}
			
			
			$t->set_var("id_system_100",$id_system_100);
			$t->set_var("system_100_congresista",$system_100_congresista);
			$t->set_var("system_100_dni",$system_100_dni);
			$t->set_var("system_100_orden",$system_100_orden);
			$t->set_var("system_100_orden_seccion",$system_100_orden_seccion);			
			$t->set_var("system_100_departamento",$system_100_departamento);	
				
		$t->parse("LISTADO","una_asistencia",true);
		
		}
	} 
	else 						
	{
		$t->SET_VAR("LISTADO",'');
	}							

		
	
	// Funciuon de agregar
	if (  optener_permisos('A',$id_system_01,$sesion_system_03,$mysqli) == '1' )
	{
		$url="'modulos/asistencia/php/home_abm.php'";
		$id="'content_'";
		$vars="''";		
		$t->set_var("funcion_agregar","cargar_post($url,$id,$vars)");					
	}
	else
	{
		$t->set_var("funcion_agregar","acceso_denegado()");
	}

	if (  optener_permisos('S',$id_system_01,$sesion_system_03,$mysqli) == '1' and  $root_candado == 'on' )
	{
		$url_exito="modulos/asistencia/php/home.php";
		$id="content_seccion";
		$vars="id_system_01=$id_system_01&extraer_info=ok";
		$msj='Importar lista csv';	
		$tipo_multiple="";	
		$t->set_var("funcion_cargar_archivo",funcion_cargar_elemento($url_exito,$id,$vars,$msj,$tipo_multiple));
						
	}
	else
	{
		$t->set_var("funcion_cargar_archivo","");
	}



// LIMPIAR ASISTENCIA - SOLO ROOT
if (  optener_permisos('B',$id_system_01,$sesion_system_03,$mysqli) == '1' and $root_candado == 'on' )
{
	$url="'modulos/asistencia/php/_interfaz.php'";
	$vars="'nombre_funcion=limpiar_asistencias&id_system_01=$id_system_01'";
	$url_exito="'modulos/asistencia/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01'";
	$msg="'Limpiar todas las asistencias?'";
	$atx="''";
	$funcion_limpiar = "eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);";	
	$t->set_var("funcion_limpiar",'<a href="javascript:;" onclick="'.$funcion_limpiar.'" class="minitex">RESET</a>');
}
else
{
	$t->set_var("funcion_limpiar","");
}
			
			
// buscador
$url="'modulos/asistencia/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01&buscar=si&";		
$vars.="variable_buscar='+busqueda.variable_buscar.value";
$t->set_var("funcion_busqueda","cargar_post($url,$id,$vars)");

	
$url="'modulos/asistencia/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01&mas=$num_filas'";	
$PAGINAR = funcion_paginar_siguiente($total_filas,$num_filas,$url,$id,$vars);
$t->set_var("PAGINAR","$PAGINAR");

$pagExc="";
if ( optener_permisos('D',$id_system_01,$sesion_system_03,$mysqli) == '1' )
{
$pagExc.= '<a href="modulos/asistencia/php/listado_csv.php?name_archivo=lista_congresistas" target="news"><i class="bi bi-file-arrow-down"></i></a>';
}
$t->set_var("EXCELES","$pagExc");		

	
	
$t->pparse("OUT", "ver");
?>