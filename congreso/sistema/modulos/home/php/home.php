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
	'ver'	=> "home.html"
	));
	
	

function traer_datos_estadisticos($tema,$vars,$mysqli)
{
	$porciento="100";
	$total="0";
	$presentes="0";
	
	if ($vars !='' )
	{
	$and =" and system_100_departamento = '$vars' ";
	}
	else
	{
	$and ="";
	}
	
	// total
	$row = $mysqli -> consulta_SQL("Select COUNT(*) total_filas from system_100_congresistas  where system_100_estado IN ('0','1') $and ");
	if ($row == TRUE)
	{
		$total = $row[0]['total_filas'];
	}	
	
	// presentes
	$row2 = $mysqli -> consulta_SQL("Select COUNT(*) total_presentes from system_100_congresistas  where system_100_estado = '1' $and ");
	if ($row2 == TRUE)
	{
		$presentes = $row2[0]['total_presentes'];
	}	
	
	
	$barratotal = round(($presentes * $porciento) / $total, 0); 
	$cadena="";
	$cadena.='<div style=" background:#cccccc; width:100%; height: 40px;">';
	$cadena.='<div style="width:'.$barratotal.'%; background: #0099CC;  height: 40px; margin:0px; text-align:right; color:#000; font-size:16px; padding-top:10px;">'.$barratotal.'%&nbsp;</div>';
	$cadena.='</div>';
	$cadena.=$tema.' '.$presentes.' de '.$total;			
				
	return $cadena;
}


if ( optener_permisos('V',$id_system_01,$sesion_system_03,$mysqli) == '1' )
{	
	
	$vars="";
	$tema="Totales: ";
	$t->set_var("barra_general",traer_datos_estadisticos($tema,$vars,$mysqli));
	$vars="Formosa";
	$tema="Formosa: ";
	
	$t->set_var("barra_formosa",traer_datos_estadisticos($tema,$vars,$mysqli));
	$vars="BERMEJO";
	$tema="Bermejo: ";
	$t->set_var("barra_bermejo",traer_datos_estadisticos($tema,$vars,$mysqli));

	$vars="LAISHI";
	$tema="Laishi: ";
	$t->set_var("barra_LAISHI",traer_datos_estadisticos($tema,$vars,$mysqli));
	
	$vars="MATACOS";
	$tema="Matacos: ";
	$t->set_var("barra_MATACOS",traer_datos_estadisticos($tema,$vars,$mysqli));

	$vars="PATINO";
	$tema="Pati&ntilde;o: ";
	$t->set_var("barra_PATINO",traer_datos_estadisticos($tema,$vars,$mysqli));

	$vars="PILAGAS";
	$tema="Pilagas: ";
	$t->set_var("barra_PILAGAS",traer_datos_estadisticos($tema,$vars,$mysqli));
	
	$vars="PILCOMAYO";
	$tema="Pilcomayo: ";
	$t->set_var("barra_PILCOMAYO",traer_datos_estadisticos($tema,$vars,$mysqli));
	
	
	$vars="PIRANE";
	$tema="Pirane: ";
	$t->set_var("barra_PIRANE",traer_datos_estadisticos($tema,$vars,$mysqli));
	
	
	$vars="RAMON LISTA";
	$tema="Ramon Lista: ";
	$t->set_var("barra_RAMONLISTA",traer_datos_estadisticos($tema,$vars,$mysqli));
	
					
/*	LAISHI
	MATACOS
	PATIÑO
	PILAGAS
	PILCOMAYO
	PIRANE
	RAMON LISTA*/
	
														
}
else
{

	$t->set_var("barra_general",'');
}
				







$url="'modulos/home/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01'";
$t->set_var("funcion_actualiar","cargar_post($url,$id,$vars); ");



$t->pparse("OUT", "ver");
?>