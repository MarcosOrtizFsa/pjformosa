<?php 
session_start();
include "../../../../../lib/mysql_conect.inc";
include "../../../../php/funciones.php";
$name_archivo=$_GET['name_archivo'];
header("Content-type: application/vnd.ms-csv");
header("Content-Disposition: attachment; filename=\"$name_archivo.csv\";");


$LIMITE=" limit 0,1500";



$cadena='';
	
	$cadena.='MESA;';
	$cadena.='ESCUELA;';	
	$cadena.='DEPARTAMENTO;';		
	$cadena.='CIRCUITO;';
	$cadena.='DIRECCION;';
	$cadena.=("\n");		

	
	$sql=$mysqli->query("Select * from system_503_mesas
						order by system_503_mesa asc 
						$LIMITE
						"); 
	while ($row = $sql -> fetch_array())
	{			
			$id_system_503 = $row['id_system_503'];
			$system_503_mesa=$row['system_503_mesa'];
			$system_503_escuela=$row['system_503_escuela'];
			$system_503_direccion=$row['system_503_direccion'];
			$system_503_circuito=$row['system_503_circuito'];

			$sql2=$mysqli->query("Select * from system_502_municipios where	system_502_circuito='$system_503_circuito' ");
			if ($row = $sql2 -> fetch_array())
			{
				$rela_system_501=$row['rela_system_501'];
				$system_502_circuito=$row['system_502_circuito'];
				
				$sql1=$mysqli->query("Select * from system_501_localidad where id_system_501='$rela_system_501'");
				if ($row = $sql1 -> fetch_array())
				{
					$system_501_departamento=$row['system_501_departamento'];
				}
			
			}
			else
			{
				$system_502_circuito="";
				$system_501_departamento="";
			}
			
			$cadena.=$system_503_mesa.';';
			$cadena.=utf8_decode($system_503_escuela).';';	
			$cadena.=utf8_decode($system_501_departamento).';';
			$cadena.=$system_502_circuito.';';
			$cadena.=utf8_decode($system_503_direccion).';';
			$cadena.=("\n");
			
			$system_503_mesa="";
			$system_503_escuela="";
			$system_501_departamento="";	
			$system_502_circuito="";
			$system_503_direccion="";
	
	}
	
	echo $cadena;	

// DESARROLLADO POR HOSTRED 
// FIRMA DIGITAL f9965928da2bfbd8a2cd2a090c9c7928da515f9e
// MAO-73
?> 
