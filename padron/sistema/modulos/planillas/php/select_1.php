<?php
include "../../../../../lib/template.inc";
include "../../../../../lib/mysql_conect.inc";
include "../../../../php/funciones.php";

$t = new Template('../templates');
$t->set_file(array(
	ver => "select_1.html",
	select 	=> "una_opcion.html"
));
$reset=$_POST["reset"];
if ( $reset=='go' )
{
$_SESSION['sess_system_504']="";
}

$rela_system_504=$_POST["rela_system_504"];

	$sql2=$mysqli->query("Select * from system_503_mesas where rela_system_504='$rela_system_504'");
	$t4 = $sql2->num_rows;
	if($t4 != '0') 	
	{
		while ($row = $sql2 -> fetch_array())
		{
			$id_system_503=$row['id_system_503'];
			$t->set_var("NOMBRE",$row['system_503_mesa']);				
				if ($id_system_503==$rela_system_503)
				{
					$t->set_var("ID","\"$id_system_503\" SELECTED ");
				}
				else
				{
					$t->set_var("ID","\"$id_system_503\"");		
				}
			$t->parse("MESAS","select",true);	
		}	
	}
	else
	{
	$t->set_var("MESAS","<option value=\"\"></option>");
	}
	

	// buscador3
	$url5="'templates/modulos/cargador/php/home.php'";
	$id5="'content_seccion'";
	$vars5="'rela_system_504=$rela_system_504&rela_system_503='";
	$t->set_var("funcion_por_mesa","cargar_post($url5,$id5,$vars5+this.value); ");



	
$t->pparse("OUT", "ver");
?>
