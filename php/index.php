<?php
header( 'Content-Type: text/html; charset=utf-8' );
header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );
session_start();
include "lib/template.inc";
include "lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";

$t = new _template('templates');
$t->set_file(array(
    '_INDEX'        => 		"index.html",
	'_HOME'			=> 		"home.html",
	'_INFO'			=> 		"informe.html",
	'un_fotos_epigrafe' => 	"un_fotos_epigrafe.html"
));



	// GALERIA DE FOTOS TOP
	$t->set_var("rotaimagen","image/bg.jpg");	
	$t->set_var("LISTA_FOTO_EPIGRAFE","");		
	$cargar_inicial="";
	$scrip_js="";
	
	
	switch ($pag)
	{				

		case "info":
			$pag = "_INFO";
			$t->SET_VAR("GALERIA_IMAGENES","");
			$t->set_var("scrip_js",""); 
			$t->set_var("ocult_carousel","hide");
			$cargar_inicial.=" cargar_info_externo_completo('".$rela_system_10."'); ";		
		break;
		
									
		default:
		$pag = "_HOME";
		
		$scrip_js.="<script type=\"text/javascript\">													";
		$scrip_js.="	var mas=3;																		";
		$scrip_js.="	$(window).scroll(function(){													";
		$scrip_js.="		if ($(window).scrollTop() == $(document).height() - $(window).height()){	";
		$scrip_js.="		 mas = mas + 3;																";
		$scrip_js.="		 cargar_info_externo('mas='+mas);											";
		$scrip_js.="		} 																			";                                     
		$scrip_js.="	});																				";
		$scrip_js.="</script>																			";
		$t->set_var("ocult_carousel","alt");	
		$cargar_inicial.=" cargar_info_externo('mas=0'); ";			
		break;
	}
	$t->set_var("cargar_inicial",$cargar_inicial); 
	$t->set_var("scrip_js",$scrip_js); 
	
	
	
	$script="";
	$t->set_var("metas","$script");

$t->set_var("DIR_WEB",$system_08_dominio."/");	
$t->set_var("titulo_site",$system_08_titulo_site);
$t->parse("CONTENT", $pag);	
$t->pparse("OUT", "_INDEX");
?>