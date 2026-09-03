<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'		=> "home_reciente.html",
	'una_mesa'	=> "una_mesa.html"
	));




$id_system_503 = 		trim(isset($_POST['id_system_503']) ? $_POST['id_system_503'] : NULL);


$where = " ";	

	
	$row = $mysqli -> consulta_SQL("Select * from system_502_mesas WHERE rela_system_503 = '$id_system_503'  order by id_system_502 ASC ");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_502 	 = 				$row[$i]['id_system_502'];
			$rela_system_503 =				$row[$i]['rela_system_503'];			
			$system_502_mesa =				$row[$i]['system_502_mesa'];			
			$t->set_var("id_system_502",	"$id_system_502");
			$t->set_var("system_502_mesa",	"$system_502_mesa");
			$t->set_var("system_escuela",	traigo_escuela($rela_system_503,$mysqli));
						
			$t->parse("LISTADO_RECIENTE","una_mesa",true);
		}
	} 
	else 						
	{
	$t->SET_VAR("LISTADO_RECIENTE",'');
	}	





$t->pparse("OUT", "ver");
?>