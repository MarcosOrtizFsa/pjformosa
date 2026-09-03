<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t -> set_file(array(
	'ver' => "select_1.html",
	'select' 	=> "una_opcion.html"
	));


if ( $reset=='go' )
{
$_SESSION['sess_system_504']="";
}

$system_503_escuela =isset($_POST['system_503_escuela']) ? $_POST['system_503_escuela'] : NULL;




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
	
	// buscador3
	$url5="'modulos/cargador/php/home.php'";
	$id5="'content_seccion'";
	$vars5="'system_503_mesa='";
	$t->set_var("funcion_por_mesa","cargar_post($url5,$id5,$vars5+this.value); ");
	
$t->pparse("OUT", "ver");
?>
