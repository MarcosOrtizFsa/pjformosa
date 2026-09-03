<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$system_502_circuito = trim(isset($_GET['system_502_circuito']) ? $_GET['system_502_circuito'] : NULL);

header('Content-type: application/vnd.ms-csv');
header('Content-Disposition: attachment; filename="circuito_'.$system_502_circuito.'_.csv";');

$LIMITE = "";
$total=0;			
$cadena='';
$cadena.='	CIRCUITO;';
$cadena.='	DNI;';
$cadena.='	NOMRE Y APELLIDO;';
$cadena.='	DIRECCION;';
$cadena.='	DPTO.;';
$cadena.=("\n");	

			
$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_0  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=''.utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.=''.convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_nombre']).' '.utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_domicilio']).';'; 
		$cadena.=''.utf8_decode($row[$i]['system_2000_localidad']).';'; 		
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_1  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=''.utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.=''.convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_nombre']).' '.utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_domicilio']).';'; 
		$cadena.=''.utf8_decode($row[$i]['system_2000_localidad']).';'; 		
		$cadena.=("\n");
	}			
}	

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_2  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=''.utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.=''.convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_nombre']).' '.utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_domicilio']).';'; 
		$cadena.=''.utf8_decode($row[$i]['system_2000_localidad']).';'; 		
		$cadena.=("\n");
	}			
}	

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_3  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=''.utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.=''.convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_nombre']).' '.utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_domicilio']).';'; 
		$cadena.=''.utf8_decode($row[$i]['system_2000_localidad']).';'; 		
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_4  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=''.utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.=''.convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_nombre']).' '.utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_domicilio']).';'; 
		$cadena.=''.utf8_decode($row[$i]['system_2000_localidad']).';'; 		
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_5  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=''.utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.=''.convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_nombre']).' '.utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_domicilio']).';'; 
		$cadena.=''.utf8_decode($row[$i]['system_2000_localidad']).';'; 		
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_6  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=''.utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.=''.convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_nombre']).' '.utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_domicilio']).';'; 
		$cadena.=''.utf8_decode($row[$i]['system_2000_localidad']).';'; 		
		$cadena.=("\n");
	}			
}


$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_7  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=''.utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.=''.convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_nombre']).' '.utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_domicilio']).';'; 
		$cadena.=''.utf8_decode($row[$i]['system_2000_localidad']).';'; 		
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_8  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=''.utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.=''.convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_nombre']).' '.utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_domicilio']).';'; 
		$cadena.=''.utf8_decode($row[$i]['system_2000_localidad']).';'; 		
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_9  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=''.utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.=''.convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_nombre']).' '.utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.=''.utf8_decode($row[$i]['system_2000_domicilio']).';'; 
		$cadena.=''.utf8_decode($row[$i]['system_2000_localidad']).';'; 		
		$cadena.=("\n");
	}			
}

echo $cadena;			
?>
