<?php
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
//Archivos comunes
$t->set_file(array(
	'ver'			=> "cargador.html"
	));

$t->set_var("dir_base",$system_08_dominio."/".$modo_login."/");
$total_mesas = '0';

	$cadena='';	
	$row = $mysqli -> consulta_SQL("Select * from system_09_archivero where system_09_album = 'MESAS' order by id_system_09 DESC LIMIT 10");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_09 = 			$row[$i]['id_system_09'];
			$system_09_tipo =			$row[$i]['system_09_tipo'];
			$system_09_archivo =		$row[$i]['system_09_archivo'];
			
			$url="'modulos/padron/php/_interfaz.php'";
			$vars="'nombre_funcion=quitar_extraible&id_system_09=$id_system_09'";
			$msg="'Quitar este este extraible?'";
			$funcion_quitar = "eliminar_refrescar($url,$vars,$msg);";
	
			$cadena.='	<tr>';	
			$cadena.='    	<td><button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick=" funcion_extraer_csv_mesas_escuelas(1,'.$id_system_09.',0); ">'.$row[$i]['system_09_epigrafe'].'</button><i  onclick="'.$funcion_quitar.'" class="mi mi-trash-fill"></i></td>';	
			$cadena.='	</tr>';			
		}
	}	
	$t->set_var("optengo_archivo",	$cadena);
	


	$row0 = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_504_mesas  ");
	if ($row0 == TRUE)
	{		
		$total_mesas = $row0[0]['total_filas'];
	}
	$t->set_var("total_mesas",	$total_mesas);
	
	$mesa_desde = 1;// indico desde que mesa inicio la creacion de la tabla de fiscales
	$funcion_crear_tablas_fiscales = " funcion_crear_tablas_fiscales($mesa_desde); ";
	$t->set_var("funcion_crear_tablas_fiscales",	$funcion_crear_tablas_fiscales);
	
	$url="'modulos/mesas/php/_interfaz.php'";
	$vars="'nombre_funcion=limpiar_mesas_escuelas'";
	$msg="'Limpiar mesas y escuelas de la BD?'";
	$funcion_seterear_bd = "eliminar_refrescar($url,$vars,$msg);";
	

	$t->set_var("reset_bd",	'<i onclick="'.$funcion_seterear_bd.'" class="mi mi-trash-fill"></i>');


	$system_09_tipo = "0"; 
	$url_exito="modulos/padron/php/home.php";
	$id="content_seccion";
	$vars="system_09_tipo=$system_09_tipo&system_09_album=MESAS";
	$t->set_var("funcion_cargar_archivo",' down_files_padron(\''.$vars.'\'); ');
	
				
$t->pparse("OUT", "ver");
?>
