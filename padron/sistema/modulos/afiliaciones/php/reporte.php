<?php
session_start();
include "../../../../../lib/template.inc";
include "../../../../../lib/mysql_conect.inc";
include "../../../../php/privilegios.php";
include "../../../../php/funciones.php";


$t = new Template('../templates');

$t->set_file(array(
	'ver'		=> "reporte.html"
	));



	$id_system_04=$_POST['id_system_04'];

	$sql=$mysqli->query("Select * from system_04_perfil where id_system_04='$id_system_04' limit 1");				
	if ($row = $sql -> fetch_array ())
	{			
		$id_system_04 = $row['id_system_04'];
		$rela_system_501=$row['rela_system_501'];
		$rela_system_502=$row['rela_system_502'];
		$system_04_nombre=$row['system_04_nombre'];
		$system_04_apellido=$row['system_04_apellido'];
		$system_04_dni=$row['system_04_dni'];
		$system_04_circuito=$row['system_04_circuito'];	
		$system_04_domicilio=$row['system_04_domicilio'];
		$system_04_mesa=$row['system_04_mesa'];	
		$system_04_orden=$row['system_04_orden'];
	}	
	
	
	$sql0=$mysqli->query("Select * from system_503_mesas where system_503_mesa='$system_04_mesa' ");
	if ($row = $sql0 -> fetch_array())
	{
		$system_503_direccion=$row['system_503_direccion'];
		$system_503_mesa=$row['system_503_mesa'];
		$system_503_escuela=$row['system_503_escuela'];
	}
	
	$sql2=$mysqli->query("Select * from system_502_municipios where system_502_circuito='$system_04_circuito' ");
	if ($row = $sql2 -> fetch_array())
	{
		$id_system_502=$row['id_system_502'];
		$rela_system_501=$row['rela_system_501'];
		$system_502_circuito=$row['system_502_circuito'];
		
		$sql3=$mysqli->query("Select * from system_501_localidad where id_system_501='$rela_system_501'");
		if ($row = $sql3 -> fetch_array())
		{
			$system_501_departamento=$row['system_501_departamento'];
		}
	}
	
	$t->set_var("id_system_04","$id_system_04");
	$t->set_var("system_04_nombre","$system_04_nombre");
	$t->set_var("system_04_apellido","$system_04_apellido");
	$t->set_var("system_04_dni","$system_04_dni");
	$t->set_var("system_04_orden","$system_04_orden");
	$t->set_var("system_04_circuito","$system_04_circuito");
	$t->set_var("system_04_domicilio","$system_04_domicilio");
	$t->set_var("system_501_departamento","$system_501_departamento");
	$t->set_var("system_503_escuela","$system_503_escuela");
	$t->set_var("system_503_mesa","$system_503_mesa");
	$t->set_var("system_503_direccion","$system_503_direccion");
	
$t->pparse("OUT", "ver");
?>
