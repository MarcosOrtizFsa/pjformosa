<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'			=> "lista_planilla.html",
	'un_cheked'		=> "un_cheked.html",
	'select'		=> "una_opcion.html"
	));
	
$t->set_var("titulo_modulo","");
$id_system_601 = 		isset($_POST['id_system_601']) ? $_POST['id_system_601'] : NULL;
$system_601_checked = 	isset($_POST['system_601_checked']) ? $_POST['system_601_checked'] : NULL;			


function funcion_save_mesa_orden($id_system_600,$system_600_dni,$mysqli)
{
	$system_607_mesa =	0;
	$system_607_orden =	0;
	$row = $mysqli -> consulta_SQL("Select * from system_607_mesa_orden where system_607_dni = '$system_600_dni' ");				
	if($row == true)
	{					
		$system_607_mesa =		$row[0]['system_607_mesa'];
		$system_607_orden =		$row[0]['system_607_orden'];
		
		$mysqli -> consulta_SQL("UPDATE system_600_votos SET 
		system_600_orden =		'$system_607_orden',
		system_600_mesa =		'$system_607_mesa'										
		WHERE 
		id_system_600 = '$id_system_600'
		");
	}
	return funcion_ver_escuela($system_607_mesa,$mysqli);
}


if ($system_601_checked != '')
{
	$system_601_checked=$_POST['system_601_checked'];
	$miwhere=" where system_601_checked='$system_601_checked' ";
}
else
{
	$miwhere=" where id_system_601='$id_system_601' ";
}

	$row = $mysqli -> consulta_SQL("Select * from system_601_planillas $miwhere");				
	if ($row == true)
	{			
		$id_system_601=$row[0]['id_system_601'];
		$rela_system_03=$row[0]['rela_system_03'];
		//$system_601_num = $row['system_601_num'];
		$system_apellido_nombre=consulto_perfil($rela_system_03,$mysqli);
		$t->set_var("id_system_601",$id_system_601);
		$t->set_var("system_apellido_nombre",$system_apellido_nombre);
	}
	else
	{
		$id_system_601='';
		$rela_system_03='';
	}
	
	$pagExc = "<a href=\"modulos/planillas/php/planilla_csv.php?id_system_601=$id_system_601\" target=\"news\"><img src=\"../image/iconos/page_excel.png\" border=\"0\"></a>&nbsp;&nbsp;";
	$t->set_var("EXCELES","$pagExc");		
	
	


	$where=" WHERE rela_system_601='$id_system_601' and system_600_estado IN ('0','1') ";			

							
	if ($variable_buscar != "")
	{	
		$variable_buscar=formatear_dni(trim($variable_buscar));
		if (ctype_digit($variable_buscar)) 
		{	
			if (strlen($variable_buscar) < 4)
			{
			$where.=" and system_600_orden = '$variable_buscar'";
			}
			else
			{
			$where.=" and system_600_dni = '$variable_buscar'";
			}     	
		} 
		else 
		{
		  //$where.=" and system_04_apellido like '%$variable_buscar%'  ";
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
	// parte del paginador
	$LIMITE = ' limit 1000';
	

			
	$row = $mysqli -> consulta_SQL("Select * from system_600_votos
						$where_control
						
						ORDER BY id_system_600 desc 
						$LIMITE
						");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{				
			$id_system_600 =			$row[$i]['id_system_600'];
			$rela_system_03 =			$row[$i]['rela_system_03'];
			$system_600_dni = 			$row[$i]['system_600_dni'];
			$system_600_apellido_nombre = $row[$i]['system_600_apellido_nombre'];
			$system_600_barrio =		$row[$i]['system_600_barrio'];
			$system_600_domicilio =		$row[$i]['system_600_domicilio'];
			$system_600_orden =			$row[$i]['system_600_orden'];
			$system_600_mesa =			$row[$i]['system_600_mesa'];
			$system_600_circuito =		$row[$i]['system_600_circuito'];
			$system_600_disputa =		$row[$i]['system_600_disputa'];
			$system_600_time_carga =	$row[$i]['system_600_time_carga'];
			$system_600_date_voto =		$row[$i]['system_600_date_voto'];
			$system_600_estado =		$row[$i]['system_600_estado'];	
	
			if ( $system_600_mesa == '0')
			{				
				$t->set_var("funcion_ver_escuela",	funcion_save_mesa_orden($id_system_600,$system_600_dni,$mysqli));	
			}
			else
			{
				$t->set_var("funcion_ver_escuela",	funcion_ver_escuela($system_600_mesa,$mysqli));
			}
				
			if ( optener_permisos('V',$id_system_01,$sesion_system_03,$mysqli) == '1' and $row[$i]['system_600_disputa'] == '1')
			{				
					$url2="'modulos/planillas/php/popup_canvas_disputas.php'";
					$id2="'popup_canvas_left'";
					$vars2="'id_system_01=$id_system_01&system_600_dni=$system_600_dni'";
					$t->set_var("funcion_pop_disputa","cargar_post($url2,$id2,$vars2)");
					$t->set_var("system_600_disputa",'<i class="bi bi-exclamation-triangle-fill"></i>');
					
			}
			else
			{
				$t->set_var("funcion_pop_disputa","");
				$t->set_var("system_600_disputa","");
			}
		
			if ( optener_permisos('B',$id_system_01,$sesion_system_03,$mysqli) == '1' )
			{
				$url="'modulos/planillas/php/_interfaz.php'";
				$vars="'nombre_funcion=remoner_dni&";
				$vars.="id_system_600=$id_system_600'";
				$url_exito="'modulos/planillas/php/lista_planilla.php'";
				$id="'content_seccion'";
				$vars_exito="'id_system_01=$id_system_01&id_system_601=$id_system_601'";
				$atx="''";
				$msg="'Remover este DNI de esta lista?'";
				$t->set_var("funcion_remover","eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);");

			}
			else
			{
				$t->set_var("funcion_remover","sin_permisos()");
			}
						
		
			if ( trim($system_600_apellido_nombre) == "No-existe")
			{
				$url="'modulos/planillas/php/nuevo_am.php'";
				$id="'content_$id_system_600'";
				$vars="'id_system_01=$id_system_01&id_system_601=$id_system_601&system_600_dni=$system_600_dni'";
				$funcion_nuevo_padronado = "cargar_post($url,$id,$vars)";
			
				//$t->set_var("system_600_apellido_nombre",'No existe en el padron! &nbsp;&nbsp;&nbsp;[<strong><a href="javascript:;" onclick="'.$funcion_nuevo_padronado.'">AGREGAR AHORA</a></strong>] ');
				$t->set_var("system_600_apellido_nombre",'No existe en el padron...');
			}
			else
			{
				$t->set_var("system_600_apellido_nombre",$system_600_apellido_nombre);
			}
			
			$t->set_var("system_600_estado","");
			

			$t->set_var("id_system_600",$id_system_600);
			$t->set_var("system_600_dni",$system_600_dni);
			$t->set_var("system_600_mesa",$system_600_mesa);
			$t->set_var("system_600_orden","$system_600_orden");
			//$t->set_var("system_600_domicilio",$system_600_domicilio);	
			$t->parse("LISTADO","un_cheked",true);
		
		}
	} 
	else 						
	{
		
		$t->SET_VAR("LISTADO",'<div class="tabl"><li class="fil-100 file-mov-100">No tiene votantes cargados a&uacute;n...</li></div>');

	}							

	
	

		
	$url="'modulos/planillas/php/home.php'";
	$id="'content_seccion'";
	$vars="'id_system_01=$id_system_01'";
	$t->set_var("funcion_volver","cargar_post($url,$id,$vars)");


	// buscador
	$urlb="'modulos/planillas/php/lista_planilla.php'";
	$idb="'content_seccion'";
	$varsb="'id_system_01=$id_system_01&id_system_601=$id_system_601&variable_buscar='+busqueda.variable_buscar.value";
	$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
	$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");


	// CARGAR DNIS	
	if ( optener_permisos('A',$id_system_01,$sesion_system_03,$mysqli) == '1' )
	{				
		$url="'modulos/planillas/php/popup_canvas.php'";
		$id="'popup_canvas'";
		$vars="'id_system_01=$id_system_01&id_system_601=$id_system_601'";
		$t->set_var("funcion_pop_canvas","cargar_post($url,$id,$vars)");		
	}
	else
	{
		$t->set_var("funcion_pop_canvas","sin_permisos()");	
	}
	
				
$t->pparse("OUT", "ver");
?>