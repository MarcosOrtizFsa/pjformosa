<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t -> set_file(array(
	'ver' => "select_3.html",
	'select' 	=> "una_opcion.html"
	));


if ( $reset=='go' )
{
$_SESSION['sess_system_504']="";
}

$system_503_escuela =isset($_POST['system_503_escuela']) ? $_POST['system_503_escuela'] : NULL;
$id_system_03 =isset($_POST['id_system_03']) ? $_POST['id_system_03'] : NULL;




	$row = $mysqli -> consulta_SQL("Select * from system_503_mesas where system_503_escuela = '$system_503_escuela'  order by id_system_503 ASC ");
	if ($row == TRUE) 	
	{
		for ( $i=0; $i < count($row); $i++)
		{	
			$system_503_mesa =		$row[$i]['system_503_mesa'];
			$t->set_var("NOMBRE",	$row[$i]['system_503_mesa']);				
			$t->set_var("ID","\"$system_503_mesa\"");		
			$t->parse("MESAS","select",true);	
		}	
	}
	else
	{
	$t->set_var("MESAS","<option value=\"\"></option>");
	}


$url="'modulos/control/php/_interfaz.php'";
$vars="'nombre_funcion=asignar_escuela&id_system_01=$id_system_01&id_system_03=$id_system_03&system_03_mesa='";					
$url_exito="'modulos/control/php/popup_canvas_asignar.php'";
$id="'popup_canvas_left'";
$vars_exito="'id_system_01=$id_system_01&id_system_03=$id_system_03'";
$funcion_asignar = " guardar_mostrar($url,$vars+this.value,$url_exito,$id,$vars_exito) ";	

			
$t->set_var("funcion_por_mesa","$funcion_asignar");
	
$t->pparse("OUT", "ver");
?>
