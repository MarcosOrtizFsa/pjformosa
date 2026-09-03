<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'		=> "informe.html",
	'un_padron'			=> "un_padron.html"
	));


$t->set_var("titulo_modulo","Informe");
$variable_buscar = 		isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL;




		
	$cadena='';
	$row = $mysqli -> consulta_SQL("Select * from system_2003_nuevos_tramites ");				
	if($row == true)
	{
		$nume ='1';
		for ( $i=0; $i < count($row); $i++)
		{	
					
			$id_system_2003 = 			$row[$i]['id_system_2003'];
			$system_2003_dni = 			$row[$i]['system_2003_dni'];
			$system_2003_dirigente = 	$row[$i]['system_2003_dirigente'];
			//$system_2003_estado = 		$row[$i]['system_2003_estado'];
			$system_2003_fecha = 		$row[$i]['system_2003_fecha'];
			
			$cadena.= '	<div class="tabl">';
			$cadena.= '			<li class="fil-10 file-mov-100">';
			$cadena.= '			'.$nume;	
			$cadena.= '			</li>';	
			$cadena.= '			<li class="fil-20 file-mov-100">';
			$cadena.= '			'.$system_2003_dni;	
			$cadena.= '			</li>';		
			$cadena.= '			<li class="fil-70 file-mov-100">';
			$cadena.= '			'.$system_2003_dirigente;	
			$cadena.= '			</li>';
			$cadena.= '		</div>';	
			$nume++;
		}
	}
			
		
									
$t->set_var("LISTADO",$cadena);



// VERIFICAR DNI
$urlb="'modulos/verificar/php/home.php'";
$idb="'content_seccion'";
$varsb="'id_system_01=$id_system_01'";
$t->set_var("funcion_volver","cargar_post($urlb,$idb,$varsb)");


$pagExc='<a href="modulos/verificar/php/informe_csv.php" target="news"  ><img src="../image/iconos/page_excel.png" border="0"></a> ';	
$t->set_var("EXCELES",$pagExc);	


$t->pparse("OUT", "ver");
?>