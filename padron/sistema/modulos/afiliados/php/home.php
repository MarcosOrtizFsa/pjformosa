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

$num_filas="50";	
if ( $mas=='' or $mas=='0' )
{
$LIMITE =" limit 0,$num_filas ";
}
else
{
$num_filas = $mas + $num_filas;
$LIMITE=" limit 0,$num_filas ";
}	

$variable_buscar = 		isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL;
$system_estado = 		isset($_POST['system_estado']) ? $_POST['system_estado'] : NULL;
$system_id = 		isset($_POST['system_id']) ? $_POST['system_id'] : NULL;
$respuesta = 'Avales digitales';
$system_apellido = '';
$system_nombre = '';
$system_circuito = 	'';
$system_sexo =	'';
$system_domicilio =	'';
$departamento='';
$system_dpto = 	'';
$system_localidad ='';
$system_702_estado='';

if ( $reset =='go' )
{
$_SESSION['where_control']="";
}


$where="  WHERE system_701_estado IN ('0','1') ";	


					
if ($variable_buscar != "" or $system_estado != ''  or $system_id != '' )
{		
	$variable_buscar=formatear_dni(trim($variable_buscar));
	if ( ctype_digit($variable_buscar) == true ) 
	{		
		
		if (strlen($variable_buscar) >= '7' and strlen($variable_buscar) <= '8')
		{
			$row = $mysqli -> consulta_SQL("Select * from system_700_avalados where system_700_dni = '$variable_buscar' ");				
			if($row == true)
			{
			 	$respuesta = '<span style="color: #336600;"><span class="dni">'.$variable_buscar.'</span> en FOLIO '.$row[0]['system_700_folio'].'</span>';
			}
			else
			{
				// system_2000_apellido system_2000_nombre @ system_2000_circuito @ system_2000_sexo @ system_2000_domicilio
				//$dni = 	str_pad($variable_buscar, 8, "0", STR_PAD_LEFT); // 8 digitos si o si
				$valu = explode('@', 			funcion_traer_datos_padron($variable_buscar,$mysqli));
				$system_apellido = 				$valu['0'];
				$system_nombre = 				$valu['1'];
				$system_sexo =					'';

				if ( $system_apellido != '' )
				{
					//donde_vive=  system_2002_domicilio  localidad_por_circuito	system_2002_circuito 
					//$valu1 = explode('@', 	donde_vive($variable_buscar,$mysqli));
					$system_domicilio = 	'';					
					$system_circuito = 		'';
					
					// system_506_dpto   system_506_localidad 
					//$valu2 = explode('@', 	funcion_traer_localidad_por_circuito($system_circuito,$mysqli));
					$system_dpto = 			'';
					$system_localidad = 	'';
					$departamento = 		'';
					

					
					if ( funcion_consulto_extras($variable_buscar,$mysqli) == '1' )
					{
					$respuesta = '<span style="color:#009966;"><span class="dni">'.$variable_buscar.'</span> AFILIADO NO AVALADO!</span>';
					$system_702_estado='1';
					}
					else
					{
					$respuesta = '<span style="color:#000;"><span class="dni">'.$variable_buscar.'</span> No esta afiliado...</span>';
					$system_702_estado='0';
					}	
				}
				else
				{
				$respuesta = '<span style="color:red;"><span class="dni">'.$variable_buscar.'</span> No empadronado!</span>';
				$system_702_estado='2';
				}
				
				/*0=no afiliado; 
				1=afiliado no evalado; 
				2=no existe en padron general */	
				$row2 = $mysqli -> consulta_SQL("Select * from system_702_no_afiliados where system_702_dni = '$variable_buscar' ");				
				if(!$row2 == true)
				{
					$mysqli -> consulta_SQL("INSERT INTO system_702_no_afiliados  
					( 							
						id_system_702, 	
						rela_system_03, 	
						system_702_dni, 	
						system_702_apellido, 	
						system_702_nombre, 	
						system_702_sexo, 	
						system_702_domicilio, 	
						system_702_dpto, 	
						system_702_localidad, 	
						system_702_estado 	  
					) 
					VALUES 
					(
						DEFAULT,
						'$sesion_system_03',
						'$variable_buscar',
						'$system_apellido',
						'$system_nombre',
						'$system_sexo',
						'$system_domicilio',
						'$departamento',
						'$system_localidad',
						'$system_702_estado'
					)");	
				}

				
			
			}	
		}
		else
		{
			$respuesta = '<span style="color:red;">No es un dni...</span>';
		}
			
	} 
	
	
	if ( $system_estado != '' )
	{
		if ( $system_estado == '2' )
		{
			$_SESSION['where_control']='';	
		}
		else
		{
			$where = " WHERE system_701_estado = '$system_estado' ";
			$_SESSION['where_control']=$where;	
		}
		
	}
	
	if ( $system_id != '' )
	{
		$where = " WHERE system_701_num = '$system_id' ";
		$_SESSION['where_control']=$where;		
	}
	
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
//$t->set_var("VERIFICACION","$respuesta");
$t->set_var("titulo_modulo",$respuesta);
											
$where_control=$_SESSION['where_control'];
//echo $where_control;
$total_afiliados = '0';
$total_votos = '0';
$total_disputa_totales = '0';
$total_votos_totales = '0';
$system_703_procedencia='';

	$tSQL = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_701_folio  $where_control ");
	if ($tSQL == TRUE)
	{		
		$total_filas = $tSQL[0]['total_filas'];
	}
	else
	{
		$total_filas = '0';
	}	
	
	$row = $mysqli -> consulta_SQL("Select * from system_701_folio
						$where_control
						
						ORDER BY id_system_701 DESC 
						$LIMITE
						");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_701=				$row[$i]['id_system_701'];
			$rela_system_03=			$row[$i]['rela_system_03'];
			$rela_system_703=			$row[$i]['rela_system_703'];
			$system_701_num= 			$row[$i]['system_701_num'];
			$system_701_estado= 		$row[$i]['system_701_estado'];
			$system_701_observaciones= 	$row[$i]['system_701_observaciones'];
			$system_apellido_nombre = 	consulto_perfil($rela_system_03,$mysqli);					
			$t->set_var("system_701_num",$system_701_num);
			$t->set_var("system_apellido_nombre",$system_apellido_nombre);
			$t->set_var("system_701_observaciones",$system_701_observaciones);
			
			$down = "<a href=\"modulos/afiliados/php/lista_planillas_ok_csv.php?rela_system_701=$id_system_701&system_701_num=$system_701_num\" target=\"news\"><img src=\"../image/iconos/page_excel.png\" border=\"0\"></a>&nbsp;&nbsp;";
			$t->set_var("DOWN","$down");	
		
			$row2 = $mysqli -> consulta_SQL("Select COUNT(*) total_afiliados from system_700_avalados  where rela_system_701='$id_system_701'  ");
			if($row2 == true)
			{
				$total_afiliados = $row2[0]['total_afiliados'];
			}
			
			$row3 = $mysqli -> consulta_SQL("Select * from system_703_sede where id_system_703='$rela_system_703'  ");
			if($row3 == true)
			{
				$system_703_procedencia = $row3[0]['system_703_procedencia'];	
			}
			$t->set_var("system_703_procedencia",$system_703_procedencia);
			
			$t->set_var("disputa",	'0');
			$t->set_var("total",	$total_afiliados);

			if ( optener_permisos('M',$id_system_01,$sesion_system_03,$mysqli) == '1')
			{				
				$url="'modulos/afiliados/php/lista_planilla.php'";
				$id="'content_seccion'";
				$vars="'id_system_01=$id_system_01&id_system_701=$id_system_701'";				
				$t->set_var("funcion_entrar","cargar_post($url,$id,$vars)");	
				
				$t->set_var("funcion_entrar","cargar_post($url,$id,$vars)");
			}
			else
			{
				$t->set_var("funcion_entrar","sin_permisos()");
			}
			
			if ( optener_permisos('B',$id_system_01,$sesion_system_03,$mysqli) == '1' and  $root_candado == 'on' )// solo root puedee eliminar
			{
				$url="'modulos/afiliados/php/_interfaz.php'";
				$vars="'nombre_funcion=borrar_folio&";
				$vars.="id_system_701=$id_system_701'";
				$url_exito="'modulos/afiliados/php/home.php'";
				$id="'content_seccion'";
				$vars_exito="'id_system_01=$id_system_01'";
				$atx="''";
				$msg="'Estas a punto de eliminar este folio con todo su contenido!'";
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
	

	// buscador
	$urlb="'modulos/afiliados/php/home.php'";
	$idb="'content_seccion'";
	$varsb="'id_system_01=$id_system_01&variable_buscar='+busqueda.variable_buscar.value";
	$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
	$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");

	// Funciuon de agregar
	if (  optener_permisos('A',$id_system_01,$sesion_system_03,$mysqli) == '1' )
	{
		$url="'modulos/afiliados/php/home_am.php'";
		$id="'content_n'";
		$vars="'id_system_01=$id_system_01'";
				
		$t->set_var("funcion_agregar_planilla","cargar_post($url,$id,$vars)");
	}
	else
	{
		$t->set_var("funcion_agregar_planilla","sin_permisos()");
	}



	$tot = $mysqli -> consulta_SQL("Select COUNT(*) as total_afiliados from system_700_avalados ");
	if ($tot == TRUE)
	{		
		$total_afiliados = $tot[0]['total_afiliados'];
	}
	$t->set_var("total_afiliados","$total_afiliados");	


	$url="'modulos/afiliados/php/afiliados.php'";
	$id="'content_seccion'";
	$vars="'id_system_01=$id_system_01'";
			
	$t->set_var("funcion_ver_afiliados","cargar_post($url,$id,$vars)");


$pagExc1='<a href="modulos/afiliados/php/lista_planillas_ok_csv.php" target="news" ><img src="../image/iconos/page_excel.png" border="0"></a>';	
$t->set_var("EXCELES","$pagExc1");	


// VERIFICAR DNI
$urlb="'modulos/afiliados/php/home.php'";
$idb="'content_seccion'";
$varsb="'id_system_01=$id_system_01&variable_buscar='+busqueda.variable_buscar.value";

$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");


	// buscador
	$url3="'modulos/afiliados/php/home.php'";
	$id3="'content_seccion'";
	$vars3="'id_system_01=$id_system_01&system_estado='";
	$t->set_var("funcion_selector_estado","cargar_post($url3,$id3,$vars3+this.value); ");	

$url="'modulos/afiliados/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01&mas=$num_filas'";	
$PAGINAR = funcion_paginar_siguiente($total_filas,$num_filas,$url,$id,$vars);
$t->set_var("PAGINAR","$PAGINAR");


			
$t->pparse("OUT", "ver");
?>