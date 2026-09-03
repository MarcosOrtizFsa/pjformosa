<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'		=> "selector_circuito.html"
	));

$id_system_505 = 			isset($_POST['id_system_505']) ? $_POST['id_system_505'] : NULL;
$system_505_circuito = 		strtoupper(isset($_POST['system_505_circuito']) ? $_POST['system_505_circuito'] : NULL);
$rela_system_504 = 			isset($_POST['rela_system_504']) ? $_POST['rela_system_504'] : NULL;

	$cadena='';
	$row = $mysqli -> consulta_SQL("Select * from system_504_ubicacion  where  system_504_circuito = '$system_505_circuito'  order by system_504_pueblo ASC ");				
	if($row == true)
	{
		$cadena.="<option >Seleccione localidad</option>";	
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_504 = 			$row[$i]['id_system_504'];
			$system_504_pueblo = 		$row[$i]['system_504_pueblo'];
			
			if ($id_system_504 == $rela_system_504)
			{
				$cadena.="<option value='$id_system_504' SELECTED >$system_504_pueblo</option>";
			}
			else
			{
				$cadena.="<option value='$id_system_504'>$system_504_pueblo</option>";		
			}
		}
		$url_exito="'modulos/escuelas/php/home_am.php'";
		$id_exito="'content_$id_system_505'";
		$vars_exito="'id_system_01=$id_system_01&id_system_505=$id_system_505'";
		$cadena.='<option onmouseup="nuevo_circuito_js(\''.$system_505_circuito.'\','.$url_exito.','.$id_exito.','.$vars_exito.');"  value="+" style="background:#CCC;"> + Agregar Localidad o Barrio</option>';	
	} 
	else 						
	{
		$cadena.="<option value=''>".$system_505_circuito." no es un circuito...</option>";	
	}	
	
	$t->set_var("LISTADO_PUEBLOS",$cadena);



$t->pparse("OUT", "ver");
?>