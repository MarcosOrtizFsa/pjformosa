<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
/*header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );*/
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
		if (($mysqli -> permiso_modulo($row[$i]['id_system_01'],$sesion_system_03) == TRUE) || $sesion_system_03_modo =='0' )
		{
		$print_menu.= 	'<li class="nav-item navbar-collapse"><a class="nav-link" href="'.$system_08_dominio.'/'.$modo_login.'/'.$row[$i]['id_system_01'].'/'.urls_amigables($row[$i]['system_01_modulo']).'">'.$row[$i]['system_01_modulo'].'</a></li>';
		}	
	}	
}


$print_opciones.= 	'<a class="btn btn-secondary space"  href="javascript:;" onclick="location.href=\'perfil\'"><i class="bi bi-person-fill"></i></a>';
$print_opciones.= 	'<a class="btn btn-danger "  href="javascript:;" onclick="logout();"><i class="bi bi-box-arrow-right"></i></a>';
$menu_i.= 			'<a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fs-4 bi bi-list"></i></a>';


        


if ( trim($sesion_system_03) !='' || trim($sesion_system_07) !='' || trim($sesion_system_06) !='' )
{		
	//echo " $sesion_system_03 - $sesion_system_07 - $sesion_system_03_modo ";
	$row = $mysqli -> open_modulo($id_system_01,'0');
	if ($mod == 'perfil')
	{
		$url="modulos/control/php/home_abm.php?id_system_01=2&id_system_03=".$sesion_system_03;
	}
	else
	if ($row == TRUE)
	{
		$url="modulos/".trim($row[0]['system_01_path_home'])."/php/home.php?id_system_01=$id_system_01";		
	}
	else
	{
		$url="modulos/asistencia/php/home.php?id_system_01=5"; // DIRECTO AL ASISTENCIA

		

		if ( $sesion_system_07 == '5' )// VOY DIRECTO AL MONITOR
		{
			$url="modulos/home/php/home.php?id_system_01=3";
		}
				
	}
	
	
	if ( $sesion_perfil !='' )
	{
		$url="modulos/control/php/home_abm.php?id_system_01=2&id_system_03=".$sesion_perfil;
	}
	
	$pag = "_HOME";	
	$cargar_inicial=" cargar_post('".$url."','content_seccion','reset=go'); ";				
							
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
}


$t->set_var("cargar_inicial","$cargar_inicial"); // TOMO LAS CARGAS INICIALES DEPENDIENDO DE LA SECCION;
$t->set_var("system_08_titulo_site","$system_08_titulo_site");
$t->set_var("print_opciones","$print_opciones");

$t->set_var("menu_i","$menu_i");
$t->set_var("print_menu","$print_menu");	
$t->set_var("dir_base",$system_08_dominio."/".$modo_login."/");


$contactos_titular="";
$redes_titular='';
if ($system_08_titular!='')
{
$contactos_titular.='<br><i class="bi bi-person"></i> '.$system_08_titular;
}
if ($system_08_email!='')
{
$contactos_titular.='<br><b><a href="mailto:'.$system_08_email.'" target="news" class="link-dark"><i class="bi bi-envelope"></i> '.$system_08_email.'</a></b>';
}
if ($system_08_celular!='')
{
$contactos_titular.='<br><b><a href="whatsapp://send?phone='.prefijo_whatsapp($system_08_whatsapp).'&text=Deja tu mensaje..." data-action="share/whatsapp/share" target="news" class="link-dark"><i class="bi bi-whatsapp"></i> '.$system_08_celular.'</a></b>';
}
if ($system_08_telefono!='')
{
$contactos_titular.='<br><i class="bi bi-telephone"></i> '.$system_08_telefono;
}
if ($system_08_facebook!='')
{
$redes_titular.='<br><a href="'.$system_08_facebook.'" target="news" class="link-dark"><i class="bi bi-facebook"></i> @elbazarmayorista </a>';
}
if ($system_08_twitter!='')
{
$redes_titular.='<br><i class="bi bi-twitter"></i>';
}
if ($system_08_instagram!='')
{
$redes_titular.='<br><a href="'.$system_08_instagram.'" target="news" class="link-dark"><i class="bi bi-instagram"></i> subeldiaweb_bazar </a>';
}
if ($system_08_youtube!='')
{
$redes_titular.='<br><i class="bi bi-youtube"></i>';
}
$t->set_var("contactos_titular","$contactos_titular");
$t->set_var("redes_titular","$redes_titular");	
		
	
$t->parse("CONTENT", "$pag");
$t->pparse("OUT", "_INDEX");
?>