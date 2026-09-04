<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'			=> "lista_pj.html"
	));


$t->set_var("titulo_modulo","Fichas de afiliaciones");


$variable_buscar = 		trim(isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL);
//$buscar_cuitl=formatear_cuit($_POST['buscar_cuitl']);


$where = " where system_2001_estado != '0'  ";	

if ( $variable_buscar != "" )
{	
	$variable_buscar=formatear_dni(trim($variable_buscar));
	if ( ctype_digit($variable_buscar) == true ) 
	{		
		if (strlen($variable_buscar) >= '7' and strlen($variable_buscar) <= '8')
		{
			$where.=" and system_2001_dni  like '%$variable_buscar%' ";  		
		}
		else
		{
			$where.=""; 
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



	$tSQL = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_2001_extras $where_control ");
	if ($tSQL == TRUE)
	{		
		$total_filas = $tSQL[0]['total_filas'];
	}
	else
	{
		$total_filas = '0';
	}	
	$cadena='';
	$apellido =	'';
	$nombre =	'';
	$cuento =	'1';		
	$row = $mysqli -> consulta_SQL("Select * from  system_2001_extras 
						$where_control
						
						ORDER BY system_2001_estado DESC 
						$LIMITE
						");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$system_2001_dni =				$row[$i]['system_2001_dni'];
			$system_2001_estado =			$row[$i]['system_2001_estado'];
			$system_2001_ima_frente =		$row[$i]['system_2001_ima_frente'];
			$system_2001_ima_dorso = 		$row[$i]['system_2001_ima_dorso'];
			// 	0=afiliado; 1=nuevo en proceso; 2=ficha creada; 3=Afiliado/a 
			
			if ( $system_2001_estado == '3' )
			{	
				$system_estado = 'Tramite terminado'; 
			}
			else
			if ( $system_2001_estado == '2' )
			{	
				$system_estado = 'Ficha creada'; 
			}
			else
			if ( $system_2001_estado == '1' )
			{	
				$system_estado = 'Listo para afiliar'; 
			}
			else
			{
				$system_estado = 'Afiliado'; 
			}
			
			$file = '../dnis/';	
			
			if ( trim($system_2001_ima_frente) != '' )
			{	
				$imagen_frente = '<img src="'.$file.''.$system_2001_ima_frente.'" style=" height: 120px;" />'; 
			}
			else
			{
				$imagen_frente = '-'; 
			}
			
			if ( trim($system_2001_ima_dorso) != '' )
			{	
				$imagen_dorso = '<img src="'.$file.''.$system_2001_ima_dorso.'" style=" height: 120px;" />'; 
			}
			else
			{
				$imagen_dorso = '-'; 
			}
	
			$data = explode('@',funcion_traer_datos_padron($system_2001_dni,$mysqli));
			$apellido =		$data[0];
			$nombre =		$data[1];
			
			$url="'modulos/afiliaciones/php/afiliar.php'";
			$id="'content_nuevo'";
			$vars="'system_2001_dni=$system_2001_dni'";								
			$funcion_editar = "cargar_post($url,$id,$vars)";
						
						
			$cadena.='	<div class="tabl" onclick="'.$funcion_editar.'">';
			$cadena.='		<li class="fil-10  file-mov-100">';
			$cadena.='			'.$cuento.'';
			$cadena.='		</li>';	
			$cadena.='		<li class="fil-10  file-mov-100">';
			$cadena.='			<span class="dni">'.$system_2001_dni.'</span>';
			$cadena.='		</li>';	
			$cadena.='		<li class="fil-20  file-mov-100">';
			$cadena.='			'.$apellido.' '.$nombre;
			$cadena.='		</li>';	
			$cadena.='		<li class="fil-25  file-mov-100">';
			$cadena.='			'.$imagen_frente;
			$cadena.='		</li>';
			$cadena.='		<li class="fil-25  file-mov-100">';
			$cadena.='			'.$imagen_dorso;
			$cadena.='		</li>';
			$cadena.='		<li class="fil-10  file-mov-100 align-right">';
			$cadena.='			'.$system_estado;
			$cadena.='		</li>';
			$cadena.='	</div>';
			$cuento++;

		}
	} 
	
	$t->SET_VAR("LISTADO",$cadena);
								
	
	
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



	$tot = $mysqli -> consulta_SQL("Select COUNT(*) as total_afiliados from system_2001_extras ");
	if ($tot == TRUE)
	{		
		$total_afiliados = $tot[0]['total_afiliados'];
	}
	$t->set_var("total_afiliados","$total_afiliados");	


	$url="'modulos/afiliados/php/afiliados.php'";
	$id="'content_seccion'";
	$vars="'id_system_01=$id_system_01'";
			
	$t->set_var("funcion_ver_afiliados","cargar_post($url,$id,$vars)");


$pagExc1='<a href="modulos/afiliados/php/lista_afiliaciones_csv.php" target="news" ><img src="../image/iconos/page_excel.png" border="0"></a>';	
$t->set_var("EXCELES","$pagExc1");	


// BUSCAR DNI
$urlb="'modulos/afiliados/php/lista_pj.php'";
$idb="'content_seccion'";
$varsb="'id_system_01=$id_system_01&variable_buscar='+busqueda.variable_buscar.value";
$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");



$url="'modulos/afiliados/php/lista_pj.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01&mas=$num_filas'";	
$PAGINAR = funcion_paginar_siguiente($total_filas,$num_filas,$url,$id,$vars);
$t->set_var("PAGINAR","$PAGINAR");


// buscador
$url3="'modulos/afiliaciones/php/home.php'";
$id3="'content_seccion'";
$vars3="'id_system_01=$id_system_01'";
$t->set_var("funcion_volver","cargar_post($url3,$id3,$vars3); ");
			
$t->pparse("OUT", "ver");
?>