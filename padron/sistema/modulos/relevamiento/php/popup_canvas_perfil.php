<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
//Archivos comunes
$t->set_file(array(
	'ver'			=> "popup_canvas_perfil.html",
	'una_opcion'	=> "una_opcion.html"
	));

$system_607_dni = isset($_POST['system_607_dni']) ? $_POST['system_607_dni'] : NULL;
$un_digito_dni = formatear_dni($system_607_dni);
$digit_dni = substr("$un_digito_dni", -1);
		
		if ($digit_dni == 1)
		{
		$where = "Select * from system_2000_padron_1  where system_2000_dni = '$system_607_dni' ";
		}
		else
		if ($digit_dni == 2)
		{
		$where = "Select * from system_2000_padron_2  where system_2000_dni = '$system_607_dni' ";
		}
		else
		if ($digit_dni == 3)
		{
		$where ="Select * from system_2000_padron_3  where system_2000_dni = '$system_607_dni'  ";
		}
		else
		if ($digit_dni == 4)
		{
		$where = "Select * from system_2000_padron_4  where system_2000_dni  = '$system_607_dni' ";
		}
		else
		if ($digit_dni == 5)
		{
		$where = "Select * from system_2000_padron_5  where system_2000_dni  = '$system_607_dni'  ";
		}
		else
		if ($digit_dni == 6)
		{
		$where = "Select * from system_2000_padron_6  where system_2000_dni  = '$system_607_dni' ";
		}
		else
		if ($digit_dni == 7)
		{
		$where = "Select * from system_2000_padron_7  where system_2000_dni = '$system_607_dni'  ";
		}
		else
		if ($digit_dni == 8)
		{
		$where = "Select * from system_2000_padron_8  where system_2000_dni  = '$system_607_dni'  ";
		}
		else
		if ($digit_dni == 9)
		{
		$where = "Select * from system_2000_padron_9  where system_2000_dni  = '$system_607_dni' ";
		}
		else
		{
		$where = "Select * from system_2000_padron_0  where system_2000_dni  = '$system_607_dni'  ";
		}

	
		
	$_SESSION['where_control']=$where;
	$where_control=$_SESSION['where_control'];
	//echo $where_control;

	
	$row = $mysqli -> consulta_SQL("$where_control ");
	if ($row == TRUE)
	{
			$system_2000_dni = 		$row[0]['system_2000_dni'];
			$system_2000_apellido=	$row[0]['system_2000_apellido'];
			$system_2000_nombre=	$row[0]['system_2000_nombre'];
			$system_2000_sexo=		$row[0]['system_2000_sexo'];
			$system_2000_localidad =$row[0]['system_2000_localidad'];
			$system_2000_circuito =	$row[0]['system_2000_circuito'];
			$system_2000_barrio =	$row[0]['system_2000_barrio'];	
			$system_2000_domicilio =$row[0]['system_2000_domicilio'];

			
			$t->set_var("system_2000_dni","$system_2000_dni");
			$t->set_var("system_2000_apellido","$system_2000_apellido");
			$t->set_var("system_2000_nombre","$system_2000_nombre");
			$t->set_var("system_2000_sexo","$system_2000_sexo");
			$t->set_var("system_2000_localidad",consultar_localida($system_2000_localidad,$mysqli));
			$t->set_var("system_2000_circuito","$system_2000_circuito");
			$t->set_var("system_2000_barrio","$system_2000_barrio");	
			$t->set_var("system_2000_domicilio","$system_2000_domicilio");

			$t->set_var("title_canvas","$system_2000_dni");
	}
	

		

$t->pparse("OUT", "ver");
?>
