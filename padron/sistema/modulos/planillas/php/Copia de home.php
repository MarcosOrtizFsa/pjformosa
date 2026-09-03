<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'			=> "home.html",
	'una_planilla'		=> "una_planilla.html"
	));
	
$t->set_var("titulo_modulo","Planillas");
$variable_buscar = 		isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL;
if ( $reset =='go' )
{
$_SESSION['where_control']="";
}

if ($sesion_system_07 == '5')// es un dirigente
{
$where="  WHERE rela_system_03 = '$sesion_system_03' and system_601_estado IN ('0','1') ";	
}
else
if ($sesion_system_07 == '6')// es un motoquero
{
$where="  WHERE rela_system_03 = '$sesion_system_03' and system_601_estado IN ('0','1') ";	
}
else
{
$where="  WHERE system_601_estado IN ('0','1') ";	
}

					
if ($variable_buscar != "")
{	
	
	$variable_buscar=formatear_dni(trim($variable_buscar));
	if (ctype_digit($variable_buscar)) 
	{	
		
		
		if (strlen($variable_buscar) < 4)
		{	
		$where.=" and id_system_601 = '$variable_buscar'";
		}
		else
		{
		
			$row2 = $mysqli -> consulta_SQL("Select * from system_04_perfil where system_04_dni = '$variable_buscar' ");
			if($row2 == true)
			{
				$rela_system_03 = $row2[0]['rela_system_03'];
				$where.=" and rela_system_03 = '$rela_system_03'";
			}

		}     	
    } 
	else 
	{
      		$row2 = $mysqli -> consulta_SQL("Select * from system_04_perfil where system_04_apellido = '$variable_buscar' ");
			if($row2 == true)
			{
				$in = "  ";
				for ( $i=0; $i < count($row2); $i++)
				{			
					$rela_system_03 =				$row2[$i]['rela_system_03'];
					$in.= " ' $rela_system_03 ', ";
				}
				$in.= " '0' ";
				
				$where.=" and rela_system_03 IN ( $in ) ";
			}
			
			
			
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
$total_disputa = '0';
$total_votos = '0';
$total_disputa_totales = '0';
$total_votos_totales = '0';

	$tSQL = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_601_planillas $where_control ");
	if ($tSQL == TRUE)
	{		
		$total_filas = $tSQL[0]['total_filas'];
	}
	else
	{
		$total_filas = '0';
	}	
	
	$row = $mysqli -> consulta_SQL("Select * from system_601_planillas
						$where_control
						
						ORDER BY id_system_601 DESC 
						$LIMITE
						");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_601=				$row[$i]['id_system_601'];
			$rela_system_03=			$row[$i]['rela_system_03'];
			$system_601_estado= 		$row[$i]['system_601_estado'];
			$system_apellido_nombre = 	consulto_perfil($rela_system_03,$mysqli);					
			$t->set_var("id_system_601",$id_system_601);
			$t->set_var("system_apellido_nombre",$system_apellido_nombre);
		
			$down = "<a href=\"modulos/planillas/php/planilla_csv.php?id_system_601=$id_system_601&rela_system_03=$rela_system_03\" target=\"news\"><img src=\"../image/iconos/page_excel.png\" border=\"0\"></a>&nbsp;&nbsp;";
			$t->set_var("DOWN","$down");	
		
			$row2 = $mysqli -> consulta_SQL("Select COUNT(*) total_votos from system_600_votos where rela_system_601='$id_system_601' and system_600_disputa='0' ");
			if($row2 == true)
			{
				$total_votos = $row2[0]['total_votos'];
			}
			
			$row3 = $mysqli -> consulta_SQL("Select COUNT(*) total_disputa from system_600_votos where rela_system_601='$id_system_601'  and system_600_disputa!='0' ");				
			if($row3 == true)
			{
				$total_disputa = $row3[0]['total_disputa'];		
			}
			
			$t->set_var("disputa",	$total_disputa);
			$t->set_var("total",	$total_votos);

			if ( optener_permisos('M',$id_system_01,$sesion_system_03,$mysqli) == '1')
			{				
				$url="'modulos/planillas/php/lista_planilla.php'";
				$id="'content_seccion'";
				$vars="'id_system_01=$id_system_01&id_system_601=$id_system_601'";				
				$t->set_var("funcion_entrar","cargar_post($url,$id,$vars)");	
				
				$t->set_var("funcion_entrar","cargar_post($url,$id,$vars)");
			}
			else
			{
				$t->set_var("funcion_entrar","sin_permisos()");
			}
			
			if ( optener_permisos('B',$id_system_01,$sesion_system_03,$mysqli) == '1' )
			{
				$url="'modulos/planillas/php/_interfaz.php'";
				$vars="'nombre_funcion=borrar_planilla&";
				$vars.="id_system_601=$id_system_601'";
				$url_exito="'modulos/planillas/php/home.php'";
				$id="'content_seccion'";
				$vars_exito="'id_system_01=$id_system_01'";
				$atx="''";
				$msg="'Estas a punto de eliminar este planilla!'";
				$t->set_var("funcion_borrar","eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);");	
			}
			else
			{
				$t->set_var("funcion_borrar","sin_permisos()");
			}
				
		$t->parse("LISTADO","una_planilla",true);
		}
	} 
	else 						
	{	
		$t->SET_VAR("LISTADO",'');
	}							
	
	
	//rela_system_03	
	//rela_system_601	
	//system_600_disputa 0=planilla, 1=repetido, 2=voto libre 	
	//system_600_estado 0=ok , 1=voto, 2=rechaz	
	
	$row5 = $mysqli -> consulta_SQL("Select COUNT(*) total_votos_totales from system_600_votos 
	where  
	rela_system_03 != '0'
	and
	system_600_disputa = '0' 
	");
	if($row5 == true)
	{
		$total_votos_totales = $row5[0]['total_votos_totales'];
	}

	$row6 = $mysqli -> consulta_SQL("Select COUNT(*) total_disputa_totales from system_600_votos 
	where  
	rela_system_03 != '0'
	and
	system_600_disputa = '1' 
	");				
	if($row6 == true)
	{
		$total_disputa_totales = $row6[0]['total_disputa_totales'];		
	}
	$t->set_var("total_disputa_totales",	$total_disputa_totales);
	$t->set_var("total_votos_totales",	$total_votos_totales);


	// buscador
	$urlb="'modulos/planillas/php/home.php'";
	$idb="'content_seccion'";
	$varsb="'id_system_01=$id_system_01&variable_buscar='+busqueda.variable_buscar.value";
	$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
	$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");

	// Funciuon de agregar
	if (  optener_permisos('A',$id_system_01,$sesion_system_03,$mysqli) == '1' )
	{
		$url="'modulos/planillas/php/home_am.php'";
		$id="'content_'";
		$vars="'id_system_01=$id_system_01'";
				
		$t->set_var("funcion_agregar_planilla","cargar_post($url,$id,$vars)");
	}
	else
	{
		$t->set_var("funcion_agregar_planilla","sin_permisos()");
	}



	
$pagExc='<a href="modulos/planillas/php/planillas_general_csv.php" target="news" ><img src="../image/iconos/page_excel.png" border="0"></a> ';	
$t->set_var("EXCELES","$pagExc");			

$pagExc1='<a href="modulos/planillas/php/lista_planillas_ok_csv.php" target="news" ><img src="../image/iconos/page_excel.png" border="0"></a>';	
$t->set_var("EXCELES1","$pagExc1");	

$pagExc2='<a href="modulos/planillas/php/lista_planillas_ok_csv.php?repet=1" target="news" ><img src="../image/iconos/page_excel_error.png" border="0"></a>';	
$t->set_var("EXCELES2","$pagExc2");	



$url="'modulos/planillas/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01&mas=$num_filas'";	
$PAGINAR = funcion_paginar_siguiente($total_filas,$num_filas,$url,$id,$vars);
$t->set_var("PAGINAR","$PAGINAR");


			
$t->pparse("OUT", "ver");
?>