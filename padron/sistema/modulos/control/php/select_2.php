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
$t -> set_file(array(
	'ver'	=> "select_2.html",
	'select'	=> "una_opcion.html"
	));

$id_system_03 =			isset($_POST['id_system_03']) ? $_POST['id_system_03'] : NULL;
$system_03_mesa =		isset($_POST['system_03_mesa']) ? $_POST['system_03_mesa'] : NULL;
$rela_system_501 = 		isset($_POST['rela_system_501']) ? $_POST['rela_system_501'] : NULL;
$where = "";
if (quito_0($rela_system_501) == '')
{
$where = " where rela_system_501 = '$rela_system_501' ";
}

	
	$row = $mysqli -> consulta_SQL("Select * from system_503_mesas $where order by system_503_mesa asc ");
	if($row == true) 	
	{
		for ( $i=0; $i < count($row); $i++)
		{				
			$system_503_mesa =	$row[$i]['system_503_mesa'];
			$t->set_var("NOMBRE",$system_503_mesa);
			
				if ( $system_503_mesa == $system_03_mesa )
				{
					$t->set_var("ID","\"$system_503_mesa\" SELECTED ");
				}
				else
				{
					$t->set_var("ID","\"$system_503_mesa\"");		
				}
						
			$t->parse("MESAFISCAL","select",true);	
		}		
	}
	else
	{
	$t->SET_VAR("MESAFISCAL","<option value=>No tiene mesas cargadas...</option>");
	}
	


$t->pparse("OUT", "ver");
?>
