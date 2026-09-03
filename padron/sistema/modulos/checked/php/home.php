<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'		=> "home.html",
	'un_cheked'			=> "un_cheked.html"
	));

$t->set_var("titulo_modulo","Control de progreso");

function estructura_estadistica($rela_system_03,$mysqli)
{
	$totales = '0';
	$restante = '0';
	$barrita = '';				
	//totales de planilla
	$row = $mysqli -> consulta_SQL("Select COUNT(*) as totales from system_600_votos 
	where 
	rela_system_03 = '$rela_system_03' 
	and 
	system_600_estado IN ('0','1')	
	and
	system_600_disputa = '0'													
	");
	if ($row == TRUE)
	{		
		$totales = $row[0]['totales'];
	}
	
	// votaron
	$row2 = $mysqli -> consulta_SQL("Select COUNT(*) as restante from system_600_votos 
	where 
	rela_system_03 = '$rela_system_03' 
	and 
	system_600_estado = '1'	
	and
	system_600_disputa = '0'													
	");
	if ($row2 == TRUE)
	{		
		$restante = $row2[0]['restante'];
	}
	
	if ($restante != '0' and $totales !='0' )
	{
	$barrita = porcentual($restante,$totales);
	}
	else
	{
	$barrita = 0;
	}
	//
	

	
	$cadena='<div style="width:100%; float:none; display: table;">';
	$cadena.='	<div style="width:75%; float:left; display: table;">';
	$cadena.='		<div style="background: #AED7FF; width:100%; height: 14px; border:0px solid #999;" align="left">';
	$cadena.='			<div style="background:#3366ff; width:'.$barrita.'%; height: 14px; padding:0px; text-align:right; font-size:9px; color:#fff;">'.$barrita.'%&nbsp;</div>';
	$cadena.='		</div>';
	$cadena.='	</div>';
	$cadena.='	<div style="width:25%; float:left; display: table; text-align:right;">';
	$cadena.='		'.$restante.' de '.$totales.'';
	$cadena.='	</div>';
	$cadena.='</div>';
	
	
	return $cadena;
}






if ( $sesion_system_07 == '5' or $sesion_system_07 == '6')// es un dirigente
{
	$and = "  and rela_system_03 = '$sesion_system_03' ";	
}
else
{
	$and = " ";	
}

$down = "";
$si_voto="";
$no_voto="";						
$PAGINAR="";			
$num_filas="100";	
if ( $mas=='' or $mas=='0' )
{
	$LIMITE =" limit 0 , $num_filas ";
	$numerador = '1';	
}
else
{
	$numerador = $mas;	
	$LIMITE=" limit $mas , $num_filas ";
	$num_filas = $mas + $num_filas;
}
								

	$tSQL = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_601_planillas where	system_601_estado IN ('0','1') $and ");
	if ($tSQL == TRUE)
	{		
		$total_filas = $tSQL[0]['total_filas'];
	}
	else
	{
		$total_filas = '0';
	}	
	
	$cadena='';
	$row = $mysqli -> consulta_SQL("Select * from system_601_planillas
						where
						system_601_estado IN ('0','1') 
						$and
						
						ORDER BY id_system_601 asc 
						$LIMITE
						");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_601=				$row[$i]['id_system_601'];
			$rela_system_03=			$row[$i]['rela_system_03'];
		
		
			$url="'modulos/checked/php/reporte.php'";
			$id="'content_seccion'";
			$vars="'rela_system_03=$rela_system_03&id_system_601=$id_system_601'";
			$funcion_reporte = "cargar_post($url,$id,$vars)";


			$down = "<a href=\"modulos/checked/php/lista_novoto_txt.php?id_system_601=$id_system_601\" target=\"news\"><img src=\"../image/iconos/page_white_word.png\" border=\"0\"></a>";
			
	
			$cadena.= '<div class="tabl" >';
			$cadena.= '		<li class="fil-10  file-mov-20">';
			$cadena.= $down.' '.$id_system_601;
			$cadena.= '		</li>';
			$cadena.= '		<li class="fil-20  file-mov-80" onClick="'.$funcion_reporte.'">';
			$cadena.= 		consulto_perfil($rela_system_03,$mysqli);				
			$cadena.= '		<div class="minitex">'.consulto_contacto_perfil($rela_system_03,$mysqli).'</div>';
			$cadena.= '		</li>';
			$cadena.= '		<li class="fil-70  file-mov-100" onClick="'.$funcion_reporte.'">';
			$cadena.= 		estructura_estadistica($rela_system_03,$mysqli);				
			$cadena.= '		</li>';
			$cadena.= '</div>';
			
		}
	} 
							
	$t->set_var("LISTADO",$cadena);

	// buscador
	$urlb="'modulos/checked/php/home.php'";
	$idb="'content_seccion'";
	$varsb="'variable_buscar='+busqueda.variable_buscar.value";
	$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
	$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");

$url="'modulos/checked/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01'";
$t->set_var("funcion_refres","cargar_post($url,$id,$vars); ");



$url="'modulos/checked/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01&mas=$num_filas'";	
$PAGINAR = funcion_paginar_siguiente($total_filas,$num_filas,$url,$id,$vars);
$t->set_var("PAGINAR","$PAGINAR");

	
$t->pparse("OUT", "ver");
?>