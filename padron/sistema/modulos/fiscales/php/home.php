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
	'select'		=> "una_opcion.html"
	));
		


$id_system_503 = isset($_POST['id_system_503']) ? $_POST['id_system_503'] : NULL;
$reset_mesa = isset($_POST['reset_mesa']) ? $_POST['reset_mesa'] : NULL;
$sesion_system_03_mesa = isset($_SESSION['sesion_system_03_mesa']) ? $_SESSION['sesion_system_03_mesa'] : NULL;
$system_504_dpto = '';
$system_504_localidad = '';
$caja_formu = '';
$formu = '	<input type="text" class="form-control" name="" id="" value="'.$sesion_system_03_mesa.'"  placeholder="0000"  maxlength="4" onChange="{funcion_session_mesa}" style="width: 70px; font-size:22px; padding:0px 0px 0px 5px; float:left;" />';
$formu.= '	<h4 class="bi bi-caret-right-square-fill" style=" margin:5px 0px 5px 15px; float:left;"></h4>';
if ( $reset_mesa == 'go' )
{
	$_SESSION['sesion_system_03_mesa']="";
	$sesion_system_03_mesa ='';
}

// reset
$url6="'modulos/fiscales/php/home.php'";
$id6="'content_seccion'";
$vars6="'reset_mesa=go'";
$funcion_reset_mesa = " cargar_post($url6,$id6,$vars6); ";			
		
	

	if ( $sesion_system_03_mesa != '' )
	{	
		$_SESSION['sesion_system_03_mesa'] = $sesion_system_03_mesa;
		
		$t->set_var("reset_link",'<h3 onClick="'.$funcion_reset_mesa.'" class="bi bi-arrow-clockwise"  style=" margin:5px 0px 5px 5px; float:right;"></h3>');	
		
		$row = $mysqli -> consulta_SQL("Select * from system_2002_tabla_fiscales  where system_2002_mesa = '$sesion_system_03_mesa' ");
		if ($row == TRUE)
		{
			$system_2002_lectores = $row[0]['system_2002_lectores'];
			$titulo_modulo = '<h3>Escuela: '.$row[0]['system_2002_lectores'].'</h3>';
		}
		
		$row2 = $mysqli -> consulta_SQL("Select * from system_504_mesas  where system_504_mesa = '$sesion_system_03_mesa' ");
		if ($row2 == TRUE)
		{
			$titulo_modulo = '<h3>'.$row2[0]['system_504_escuela'].'</h3>';
			$system_504_dpto = $row2[0]['system_504_dpto'];
			$system_504_localidad = $row2[0]['system_504_localidad'];
		}	
		 
		 	
	}
	else
	{

		
		$url="'modulos/fiscales/php/_interfaz.php'";
		$vars="'nombre_funcion=cargar_mesas_votantes&";		
		$vars.="system_2002_mesa='";	
		$url_exito="'modulos/fiscales/php/home.php'";
		$id="'content_seccion'";
		$vars_exito="'id_system_01=$id_system_01'";	
	
		$funcion_guardar="guardar_mostrar($url,$vars+this.value,$url_exito,$id,$vars_exito)";
	
		//extraigo_votantes_de_mesa($mesa,$mysqli) 	 
			 
		$_SESSION['sesion_system_03_mesa']= '';
		$formu = '<input type="text" class="form-control  form-control-lg" aria-label="" aria-describedby="button-addon2" name="" id="" value=""  placeholder="N&deg; Mesa"  maxlength="4" onChange="'.$funcion_guardar.'" />';
		$formu.= '<button class="btn btn-outline-secondary" type="button" id="button-addon2" ><i class="bi bi-search"></i></button>';
		$caja_formu = $formu.'';
		$system_2002_lectores = '';
		$titulo_modulo="<h3>Indique N&deg; de Mesa : </h3>";
	}


$t->set_var("ver_opciones","alt");
if ( $sesion_system_03_mesa != '' and $sesion_system_07 == '3')
{
	$t->set_var("ver_opciones","hide");
}


$t->set_var("sesion_system_03_mesa",$sesion_system_03_mesa);
$t->set_var("titulo_modulo","$titulo_modulo");
$t->set_var("caja_formu","$caja_formu");




// parte del paginador
$tabla_lectores = '';
if ( $sesion_system_03_mesa != '' or $system_2002_lectores != '' )
{
	for ( $i=1; $i <= $system_2002_lectores; $i++)
	{	
		
		if ( $i == 1 )
		{
			$checked_box = "checked";	
		}
		else
		{
			$checked_box = "";		
		}
		$disabled_box = "";
		
		$url="'modulos/fiscales/php/_interfaz.php'";
		$vars="'nombre_funcion='";

		$tabla_lectores.= '<div class="tabl">';		
		$tabla_lectores.= '	<li class="fil-30">';
		$tabla_lectores.= '		<h3>'.$sesion_system_03_mesa.'</h3>';
		$tabla_lectores.= '	</li>';
		$tabla_lectores.= '	<li class="fil-40 align-center">';
		$tabla_lectores.= '		<h3>'.$i.'</h3>';
		$tabla_lectores.= '	</li>';
		$tabla_lectores.= '	<li class="fil-30 align-right">	';
		$tabla_lectores.= '		<input name="" type="checkbox" value="" onClick="guardar_vars('.$url.','.$vars.');"  style=" width:33px; height:33px;" '.$checked_box.' '.$disabled_box.'/>';
		$tabla_lectores.= '	</li>';
		$tabla_lectores.= '</div>';


	}
}
	$t->set_var("tabla_lectores",$tabla_lectores);
	$t->set_var("system_504_localidad",$system_504_localidad);
		
		


	
$t->pparse("OUT", "ver");
?>