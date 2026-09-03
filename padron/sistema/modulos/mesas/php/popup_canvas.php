<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'		=> "popup_canvas.html"
	));

$id_system_505 = 			isset($_POST['id_system_505']) ? $_POST['id_system_505'] : NULL;
$system_505_circuito = 		strtoupper(isset($_POST['system_505_circuito']) ? $_POST['system_505_circuito'] : NULL);
$rela_system_504 = 			isset($_POST['rela_system_504']) ? $_POST['rela_system_504'] : NULL;
$cadena='';
	$row = $mysqli -> consulta_SQL("Select * from system_503_escuelas  order by id_system_503 ASC ");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_503 = 		$row[$i]['id_system_503'];
			
			$url="'modulos/mesas/php/_interfaz.php'";
			$vars="'nombre_funcion=agregar_mesas&rela_system_503=$id_system_503'";
			$url_exito="'modulos/mesas/php/home_reciente.php'";
			$id="'content_reciente'";
			$vars_exito="'id_system_01=$id_system_01&id_system_503=$id_system_503'";
			$funcion_cargar_mesa = "guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito);";	

			$cadena.='<div class="tabl">';
			$cadena.='		<li class="fil-40 file-mov-50">';
			$cadena.='			<i  onclick="'.$funcion_cargar_mesa.'" style="cursor: pointer;" class="bi bi-plus-circle"></i>&nbsp;<strong>'.$row[$i]['system_503_escuela'].'</strong>';
			$cadena.='		</li>';
			$cadena.='		<li class="fil-60 file-mov-50">';
			$cadena.='			'.$row[$i]['system_503_direccion'];
			$cadena.='		</li>';	 
			$cadena.='</div>';
		}

	} 
	
	
	$t->set_var("LISTADO_ESCUELAS",$cadena);
	$t->set_var("title_canvas","Agregar mesas segun escuela");


$t->pparse("OUT", "ver");
?>