<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
include "../lib/template.inc";
include "../lib/mysql_conect.php";
include "php/constructor_sql.php";
include "php/abm.php";
include "php/funciones.php";


$t = new _template('./templates/');
$t->set_file(array(
	'_INDEX'   	=> "index.html",
	'_HOME'		=> "home.html"
	
));



$print_menu="";
$print_opciones="";
$print_search="";
$cargar_inicial="";
$menu_i="";

// CREO LOS BOTONES DEL MENU SISTEMA, PERFIL, SALIR Y PUBLICO)

$row = $mysqli -> system_01_modulos();
if ($row == TRUE)
{
	for ( $i=0; $i < count($row); $i++)
	{	
		if (($mysqli -> permiso_modulo($row[$i]['id_system_01'],$sesion_system_03) == TRUE)  )
		{
		$print_menu.= 	'<li class="nav-item navbar-collapse"><a class="nav-link" href="'.$system_08_dominio.'/'.$modo_login.'/'.$row[$i]['id_system_01'].'/'.urls_amigables($row[$i]['system_01_modulo']).'">'.$row[$i]['system_01_modulo'].'</a></li>';
		}	
	}	
}


$print_opciones.= 	'<a class="btn btn-secondary space"  href="javascript:;" onclick="location.href=\'perfil\'"><i class="bi bi-person-fill"></i></a>';
$print_opciones.= 	'<a class="btn btn-danger "  href="javascript:;" onclick="logout();"><i class="bi bi-box-arrow-right"></i></a>';
$menu_i.= 			'<a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fs-4 bi bi-list"></i></a>';


        


if ( $sesion_system_03 !='' || $sesion_system_07 !='' || $sesion_system_06 !='' )
{		
	$pag = "_HOME";	
	
	$row = $mysqli -> open_modulo($id_system_01,'0');
	if ($mod == 'perfil')
	{
		$url="modulos/control/php/home_abm.php?id_system_01=2&id_system_03=".$sesion_system_03;
		$cargar_inicial=" cargar_post('".$url."','content_seccion','reset=go'); ";
	}
	else
	if ($row == TRUE)
	{
		$url="modulos/".trim($row[0]['system_01_path_home'])."/php/home.php?id_system_01=$id_system_01";		
		
		$cargar_inicial=" cargar_post('".$url."','content_seccion','reset=go'); ";
		
		

	}
	else
	{	
		
		if ( $sesion_system_07 == '3' )// es un fiscal. Ingresa por link
		{
			$url="modulos/cargador/php/home.php?id_system_01=7";
			$cargar_inicial=" cargar_post('".$url."','content_seccion',''); ";
		}
		else
		if ( $sesion_system_07 == '4' )// es un operador. Ingresa por link
		{
			$url="modulos/planillas/php/home.php?id_system_01=5";
			$cargar_inicial=" cargar_post('".$url."','content_seccion',''); ";
		}
		else
		{
			$url="modulos/home/php/home.php?id_system_01=107";
			$cargar_inicial=" cargar_post('".$url."','content_seccion','reset=go'); ";
		}

			
			
	}
				
	$titulo_page = '<h3 style="color:#fff;">'.$system_08_titulo_site.'&nbsp;&nbsp;&nbsp;</h3>';						
}
else
{
	$print_opciones = '';
	$menu_i = '';
	$print_search="";
	$pag = "_HOME";
	$url="modulos/login/php/home.php";
	$cargar_inicial=" cargar_post('".$url."','content_seccion','reset=go'); ";
	$t->set_var("TITULO_LOGIN",''.$system_08_contactos_visibles.'');
	$titulo_page = '';
}




$t->set_var("cargar_inicial","$cargar_inicial"); // TOMO LAS CARGAS INICIALES DEPENDIENDO DE LA SECCION;
$t->set_var("system_08_titulo_site","$system_08_titulo_site");
$t->set_var("print_opciones","$print_opciones");

$t->set_var("menu_i","$menu_i");
$t->set_var("print_menu","$print_menu");	
$t->set_var("dir_base",$system_08_dominio."/".$modo_login."/");
$t->set_var("titulo_page","$titulo_page");	

$contactos_titular="";
$redes_titular='';

$t->set_var("contactos_titular","$contactos_titular");
$t->set_var("redes_titular","$redes_titular");	
		


$script="";			
$script.='<meta property="og:locale" 		content="en_AR" />';
$script.=("\n");		
$script.='<meta property="og:type" 			content="article" />';
$script.=("\n");	
$script.='<meta property="og:image" 		content="https://pjformosa.com.ar/image/izo-whass.png" />';
$script.=("\n");	
$script.='<meta property="og:title" 		content="PJ Formosa" />';
$script.=("\n");
$script.='<meta property="og:description" 	content="Partido Justicialista de Formosa" />';
$script.=("\n");
$script.='<meta property="og:url" 			content="https://pjformosa.com.ar" />';
$script.=("\n");
$script.='<meta property="fb:app_id" 		content="" />';
$script.=("\n");
$script.='<meta property="og:image:secure_url" 	content="https://pjformosa.com.ar/image/izo-whass.png" />';
	
$t->set_var("script","$script");	


	
$t->parse("CONTENT", "$pag");
$t->pparse("OUT", "_INDEX");
?>
