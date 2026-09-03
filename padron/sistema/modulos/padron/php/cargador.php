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


	$cadena='';	
	$row = $mysqli -> consulta_SQL("Select * from system_09_archivero where system_09_album = 'PADRON' order by id_system_09 DESC LIMIT 10");				
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
			$cadena.='    	<td><button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick=" funcion_extraer_csv(0,'.$id_system_09.',0); ">'.$row[$i]['system_09_epigrafe'].'</button><i  onclick="'.$funcion_quitar.'" class="mi mi-trash-fill"></i></td>';	
			$cadena.='	</tr>';			
		}
	}	
	$t->set_var("optengo_archivo",				$cadena);
	$t->set_var("total_del_padron_perfiles",	total_del_padron($mysqli));
	

	$url="'modulos/padron/php/_interfaz.php'";
	$vars="'nombre_funcion=limpiar_sufragios'";
	$msg="'Limpiar DNI, mesa y orden de la BD?'";
	$funcion_seterear_sufragios = "eliminar_refrescar($url,$vars,$msg);";
	
	$t->set_var("reset_padron",		'<i onclick="" class="mi mi-trash-fill"></i>');
	$t->set_var("reset_sufrafios",	'<i onclick="'.$funcion_seterear_sufragios.'" class="mi mi-trash-fill"></i>');


		
	

	//$t->set_var("totales",total_del_padron($mysqli));
		
	$system_09_tipo = "0"; // 0=datos personales 1=donde vota 	
	$url_exito="modulos/padron/php/home.php";
	$id="content_seccion";
	$vars="system_09_tipo=$system_09_tipo&system_09_album=PADRON";
	$t->set_var("funcion_cargar_archivo",' down_files_padron(\''.$vars.'\'); ');
	
	
	//$t->set_var("ejecutor_down"," ejecutar_descarga_progreso('0'); ");
function total_del_padron($mysqli)
{
	$total_0 = 0;
	$total_1 = 0;
	$total_2 = 0;
	$total_3 = 0;
	$total_4 = 0;
	$total_5 = 0;
	$total_6 = 0;
	$total_7 = 0;
	$total_8 = 0;
	$total_9 = 0;

	
	$row0 = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_2000_padron_0 ");
	if ($row0 == TRUE)
	{		
		$total_0 = $row0[0]['total_filas'];
	}
	$row1 = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_2000_padron_1 ");
	if ($row1 == TRUE)
	{		
		$total_1 = $row1[0]['total_filas'];
	}
	$row2 = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_2000_padron_2 ");
	if ($row2 == TRUE)
	{		
		$total_2 = $row1[0]['total_filas'];
	}
	$row3 = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_2000_padron_3 ");
	if ($row3 == TRUE)
	{		
		$total_3 = $row3[0]['total_filas'];
	}

	$row4 = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_2000_padron_4 ");
	if ($row4 == TRUE)
	{		
		$total_4 = $row4[0]['total_filas'];
	}
	$row5 = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_2000_padron_5 ");
	if ($row5 == TRUE)
	{		
		$total_5 = $row5[0]['total_filas'];
	}
	$row6 = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_2000_padron_6 ");
	if ($row6 == TRUE)
	{		
		$total_6 = $row6[0]['total_filas'];
	}
	
	$row7 = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_2000_padron_7 ");
	if ($row7 == TRUE)
	{		
		$total_7 = $row7[0]['total_filas'];
	}
	$row8 = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_2000_padron_8 ");
	if ($row8 == TRUE)
	{		
		$total_8 = $row8[0]['total_filas'];
	}
	$row9 = $mysqli -> consulta_SQL("Select COUNT(*) as total_filas from system_2000_padron_9 ");
	if ($row9 == TRUE)
	{		
		$total_9 = $row9[0]['total_filas'];
	}
		
	$total_padron = $total_0 + $total_1 + $total_2 + $total_3 + $total_4 + $total_5 + $total_6 + $total_7 + $total_8 + $total_9;
	return $total_padron;
	
}



						
$t->pparse("OUT", "ver");
?>
