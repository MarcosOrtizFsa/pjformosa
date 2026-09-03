<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$system_502_circuito = isset($_GET['system_502_circuito']) ? $_GET['system_502_circuito'] : NULL;
header("Content-type: application/vnd.ms-csv");
header("Content-Disposition: attachment; filename=\"padron_cto_".$system_502_circuito.".csv\";");

$num_orden="1";
$cadena='';
$cadena.='DNI;';
$cadena.='TIPO;';		
$cadena.='APELLIDO;';
$cadena.='NOMBRE;';
$cadena.='GENERO;';
$cadena.='CLASE;';	
$cadena.='CTO.;';		
$cadena.='DOMICILIO;';	
//$cadena.='LOCALIDAD O BARRIO;';
//$cadena.='MESA - ORDEN - ESCUELA DONDE VOTA;';	
$cadena.=("\n");		

	
			
$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_0  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{	
		$cadena.=	convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=	$row[$i]['system_2000_tipo_dni'].';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_nombre']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_sexo']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_clase']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.= 	utf8_decode($row[$i]['system_2000_domicilio']).';';		
		//$cadena.= 	utf8_decode(pueblo_por_id(0,$row[$i]['rela_system_504'],$mysqli)).';';
		//$cadena.= 	utf8_decode(donde_vota_x_dni(1,$row[$i]['system_2000_dni'],$mysqli)).';';
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_1  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=	convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=	$row[$i]['system_2000_tipo_dni'].';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_nombre']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_sexo']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_clase']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.= 	utf8_decode($row[$i]['system_2000_domicilio']).';';		
		//$cadena.= 	utf8_decode(pueblo_por_id(0,$row[$i]['rela_system_504'],$mysqli)).';';
		//$cadena.= 	utf8_decode(donde_vota_x_dni(1,$row[$i]['system_2000_dni'],$mysqli)).';';
		$cadena.=("\n");
	}			
}	

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_2  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=	convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=	$row[$i]['system_2000_tipo_dni'].';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_nombre']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_sexo']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_clase']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.= 	utf8_decode($row[$i]['system_2000_domicilio']).';';		
		//$cadena.= 	utf8_decode(pueblo_por_id(0,$row[$i]['rela_system_504'],$mysqli)).';';
		//$cadena.= 	utf8_decode(donde_vota_x_dni(1,$row[$i]['system_2000_dni'],$mysqli)).';';
		$cadena.=("\n");
	}			
}	

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_3  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=	convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=	$row[$i]['system_2000_tipo_dni'].';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_nombre']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_sexo']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_clase']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.= 	utf8_decode($row[$i]['system_2000_domicilio']).';';		
		//$cadena.= 	utf8_decode(pueblo_por_id(0,$row[$i]['rela_system_504'],$mysqli)).';';
		//$cadena.= 	utf8_decode(donde_vota_x_dni(1,$row[$i]['system_2000_dni'],$mysqli)).';';
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_4  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=	convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=	$row[$i]['system_2000_tipo_dni'].';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_nombre']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_sexo']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_clase']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.= 	utf8_decode($row[$i]['system_2000_domicilio']).';';		
		//$cadena.= 	utf8_decode(pueblo_por_id(0,$row[$i]['rela_system_504'],$mysqli)).';';
		//$cadena.= 	utf8_decode(donde_vota_x_dni(1,$row[$i]['system_2000_dni'],$mysqli)).';';
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_5  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=	convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=	$row[$i]['system_2000_tipo_dni'].';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_nombre']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_sexo']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_clase']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.= 	utf8_decode($row[$i]['system_2000_domicilio']).';';		
		//$cadena.= 	utf8_decode(pueblo_por_id(0,$row[$i]['rela_system_504'],$mysqli)).';';
		//$cadena.= 	utf8_decode(donde_vota_x_dni(1,$row[$i]['system_2000_dni'],$mysqli)).';';
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_6  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=	convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=	$row[$i]['system_2000_tipo_dni'].';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_nombre']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_sexo']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_clase']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.= 	utf8_decode($row[$i]['system_2000_domicilio']).';';		
		//$cadena.= 	utf8_decode(pueblo_por_id(0,$row[$i]['rela_system_504'],$mysqli)).';';
		//$cadena.= 	utf8_decode(donde_vota_x_dni(1,$row[$i]['system_2000_dni'],$mysqli)).';';
		$cadena.=("\n");
	}			
}


$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_7  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=	convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=	$row[$i]['system_2000_tipo_dni'].';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_nombre']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_sexo']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_clase']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.= 	utf8_decode($row[$i]['system_2000_domicilio']).';';		
		//$cadena.= 	utf8_decode(pueblo_por_id(0,$row[$i]['rela_system_504'],$mysqli)).';';
		//$cadena.= 	utf8_decode(donde_vota_x_dni(1,$row[$i]['system_2000_dni'],$mysqli)).';';
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_8  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=	convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=	$row[$i]['system_2000_tipo_dni'].';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_nombre']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_sexo']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_clase']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.= 	utf8_decode($row[$i]['system_2000_domicilio']).';';		
		//$cadena.= 	utf8_decode(pueblo_por_id(0,$row[$i]['rela_system_504'],$mysqli)).';';
		//$cadena.= 	utf8_decode(donde_vota_x_dni(1,$row[$i]['system_2000_dni'],$mysqli)).';';
		$cadena.=("\n");
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_9  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$cadena.=	convert_dni($row[$i]['system_2000_dni']).';';
		$cadena.=	$row[$i]['system_2000_tipo_dni'].';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_apellido']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_nombre']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_sexo']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_clase']).';';
		$cadena.= 	utf8_decode($row[$i]['system_2000_circuito']).';'; 
		$cadena.= 	utf8_decode($row[$i]['system_2000_domicilio']).';';		
		//$cadena.= 	utf8_decode(pueblo_por_id(0,$row[$i]['rela_system_504'],$mysqli)).';';
		//$cadena.= 	utf8_decode(donde_vota_x_dni(1,$row[$i]['system_2000_dni'],$mysqli)).';';
		$cadena.=("\n");
	}			
}
	
echo $cadena;	

?>
