<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$t = new _template('../templates');
$t->set_file(array(
	'ver'	=> "home.html",
	'una_localidad'=> "una_localidad.html",
	'una_opcion'=> "una_opcion.html"
	));
$t->set_var("titulo_modulo","Ubicaci&oacute;n");

if ( $reset =='go' )
{
	$_SESSION['where_control']="";
}

$variable_buscar = trim(strtoupper(isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL));
$rela_system_501 = isset($_POST['rela_system_501']) ? $_POST['rela_system_501'] : NULL;
$where = "Select * from system_502_circuitos where rela_system_501 != '0' ";	

if ( $variable_buscar != "" or $rela_system_501 != '' )
{		
		if ( $variable_buscar!='' )
		{
			if (strlen($variable_buscar) <= '3') //  es menor a 3 digitos
			{
			  	$where.=" and system_502_circuito = '$variable_buscar'  ";
			}
			else
			{
			 	$cademini='';
				$row = $mysqli -> consulta_SQL("SELECT * FROM system_504_ubicacion WHERE system_504_pueblo like '%$variable_buscar%'    
				ORDER BY system_504_pueblo	ASC 				
				");
				if ($row == TRUE)
				{
					for ( $i=0; $i < count($row); $i++)
					{				
						$cademini.= " '".$row[$i]['system_504_circuito']."', ";		
					}
					$cademini.= "'0'";	
					
					$where.=" and system_502_circuito IN (".$cademini.") ";
				} 
				
				
				
			}
		}
		
		if ( $rela_system_501 != '' )
		{
			$where.=" and rela_system_501 = '$rela_system_501' ";	
		}
		
		$_SESSION['where_control']= $where;
}
else
{	

	if ( $where_control !='' )
	{
	$_SESSION['where_control']= $where_control;
	}
	else
	{
	$_SESSION['where_control']= $where;
	}
}


$where_control = isset($_SESSION['where_control']) ? $_SESSION['where_control'] : NULL;
//echo $where_control;



$cuento=1;					
$row = $mysqli -> consulta_SQL("$where_control ORDER BY id_system_502, system_502_circuito ASC  ");
if ($row == TRUE)
{
	for ( $i=0; $i < count($row); $i++)
	{				
		 	 	 	 
		$id_system_502 = 			$row[$i]['id_system_502'];
		$rela_system_501 = 			$row[$i]['rela_system_501'];
		$system_502_circuito = 		$row[$i]['system_502_circuito'];
		//$system_502_localidades = 	nl2br($row[$i]['system_502_localidades']);
		
		$t->set_var("cuento",		$cuento);
		$t->set_var("id_system_502",		"$id_system_502");
		$t->set_var("rela_system_501",		consultar_localida($rela_system_501,$mysqli));
		$t->set_var("system_502_circuito",	"$system_502_circuito");
		$t->set_var("system_502_localidades",extraer_pueblos($system_502_circuito,$mysqli));
		
		
		$url="'modulos/localidades/php/popup_canvas.php'";
		$id="'popup_canvas'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito'";
		$t->set_var("funcion_pop_canvas","cargar_post($url,$id,$vars)");
			
				
		// modificar 
		if ( optener_permisos('M',$id_system_01,$sesion_system_03,$mysqli) == '1')
		{				
			$url="'modulos/localidades/php/home_am.php'";
			$id="'fila_$id_system_502'";
			$vars="'id_system_01=$id_system_01&id_system_502=$id_system_502'";			
			$t->set_var("funcion_editar","cargar_post($url,$id,$vars)");	
		}
		else
		{
			$t->set_var("funcion_editar","sin_permisos()");
		}	
		
		// elimiar
		if ( optener_permisos('B',$id_system_01,$sesion_system_03,$mysqli) == '1' )
		{
			$url="'modulos/localidades/php/_interfaz.php'";
			$vars="'nombre_funcion=eliminar&";
			$vars.="id_system_502=$id_system_502'";
			$url_exito="'modulos/localidades/php/home.php'";
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

		$url="'modulos/localidades/php/ver_ciudadanos.php'";
		$id="'content_seccion'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&reset=go'";			
		$t->set_var("funcion_ver","cargar_post($url,$id,$vars)");
			
		$pagExc='<a href="modulos/localidades/php/localidad_csv.php?system_502_circuito='.$system_502_circuito.'" target="news"  ><i class="bi bi-file-earmark-spreadsheet"></i></a> ';	
		$t->set_var("EXCEL",$pagExc);	

		$t->parse("LISTADO","una_localidad",true);
		$cuento++;
	}
} 
else 						
{
	$t->set_var("LISTADO",'NO HAY REGISTROS...');
}						



	$row = $mysqli -> consulta_SQL("SELECT * FROM system_501_departamentos  
				ORDER BY id_system_501 
				ASC 				
				");
	if ($row == TRUE)
	{
		for ( $i=0; $i < count($row); $i++)
		{				
			$id_system_501 = 			$row[$i]['id_system_501'];
			$system_501_departamento = 	$row[$i]['system_501_departamento'];	
			$t->set_var("ID","$id_system_501");
			$t->set_var("NOMBRE",$system_501_departamento);
			$t->parse("DPTO","una_opcion",true);	
		}	
	} 
	else 	
	{
		$t->SET_VAR("DPTO","No hay departamentos...");
	}
	
	

	// Funciuon de agregar
	if (  optener_permisos('A',$id_system_01,$sesion_system_03,$mysqli) == '1' )
	{
		$url="'modulos/localidades/php/home_am.php'";
		$id="'content_nuevo'";
		$vars="'id_system_01=$id_system_01'";		
		$t->set_var("funcion_agregar","cargar_post($url,$id,$vars)");
	}
	else
	{
		$t->set_var("funcion_agregar","sin_permisos()");
	}
	


	// buscador
	$url="'modulos/localidades/php/home.php'";
	$id="'content_seccion'";
	$vars="'id_system_01=$id_system_01&reset=go&";		
	$vars.="variable_buscar='+busqueda.variable_buscar.value";
	$t->set_var("funcion_busqueda","cargar_post($url,$id,$vars)");


	// selector
	$url2="'modulos/localidades/php/home.php'";
	$id2="'content_seccion'";
	$vars2="'id_system_01=$id_system_01&rela_system_501='";
	$t->set_var("funcion_selector","cargar_post($url2,$id2,$vars2+this.value); ");	

	

function extraer_pueblos($system_502_circuito,$mysqli)
{
	$cadena='';
	$row = $mysqli -> consulta_SQL("SELECT * FROM  system_504_ubicacion 
				where system_504_circuito = '$system_502_circuito'  
				ORDER BY system_504_pueblo 
				ASC 				
				");
	if ($row == TRUE)
	{
		for ( $i=0; $i < count($row); $i++)
		{				 
			$id_system_504 = 		$row[$i]['id_system_504'];
			$system_504_pueblo = 	$row[$i]['system_504_pueblo'];
			$system_504_mapsgoogle =$row[$i]['system_504_mapsgoogle'];
				
			$cadena.= $system_504_pueblo.'<br>';	
		}	
	} 	
	return $cadena;
}

	
$t->pparse("OUT", "ver");
?>