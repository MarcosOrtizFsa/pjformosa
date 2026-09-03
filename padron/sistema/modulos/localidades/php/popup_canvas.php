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
//Archivos comunes
$t->set_file(array(
	'ver'			=> "popup_canvas.html"
	));

$system_502_circuito = isset($_POST['system_502_circuito']) ? $_POST['system_502_circuito'] : NULL;

$t->set_var("title_canvas",'Circuito '.$system_502_circuito);
$t->set_var("tema",$system_502_circuito);

	$row = $mysqli -> consulta_SQL("Select * from system_504_ubicacion 
									where 
									system_504_circuito = '$system_502_circuito'
							
									ORDER BY id_system_504 ASC
									");
	$cadena= "";
	if ($row == TRUE)
	{
		for ( $i=0; $i < count($row); $i++)
		{
			$id_system_504 = 		$row[$i]['id_system_504'];
			$system_504_circuito = 	$row[$i]['system_504_circuito'];
			$system_504_pueblo = 	$row[$i]['system_504_pueblo'];			
			$system_504_mapsgoogle =$row[$i]['system_504_mapsgoogle'];
			
			$url="'modulos/localidades/php/pop_am.php'";
			$id="'content_pop_$id_system_504'";
			$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&id_system_504=$id_system_504'";			
			$funcion_editar = "cargar_post($url,$id,$vars)";
			
			
			$cadena.= '<div class="tabl" id="content_pop_'.$id_system_504.'">';
			$cadena.= '		<li class="fil-75">';
			$cadena.= '			'.$system_504_pueblo;
			$cadena.= '		</li>';	
			$cadena.= '		<li class="fil-25 align-right">';
			$cadena.= '			<a href="javascript:;" onclick="'.$funcion_editar.'"><i class="bi bi-pencil-square"></i></a>';
			$cadena.= '	</li>';  
			$cadena.= '</div>';
		}
		
	}
	$t->set_var("LISTA_PUEBLOS",$cadena);




$url="'modulos/localidades/php/pop_am.php'";
$id="'content_pop'";
$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito'";		
$t->set_var("funcion_agregar","cargar_post($url,$id,$vars)");

		
$t->pparse("OUT", "ver");
?>
