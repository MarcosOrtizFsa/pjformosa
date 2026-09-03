<?php 
session_start();
include "../../../../../lib/mysql_conect.inc";
include "../../../../php/funciones.php";

header("Content-type: application/vnd.ms-csv");
header("Content-Disposition: attachment; filename=\"planilla_de_errores.csv\";");


$LIMITE=" limit 0,300";
$num_orden="1";

$cadena='';
	
	$cadena.='DNI;';
	$cadena.='APELLIDO Y NOMBRE;';	
	$cadena.='CIR;';	
	$cadena.='ESCUELA;';	
	$cadena.='DIRIGENTE;';
	$cadena.='FECHA CARGA;';
	$cadena.='ERROR;';	
	$cadena.=("\n");		

	
	$sql=$mysqli->query("Select * from system_600_votos
						where 
						system_600_disputa IN ('1','2','3')
						
						ORDER BY id_system_600 ASC 
						$LIMITE
						"); 
	while ($row = $sql -> fetch_array())
	{			
		$id_system_600=$row['id_system_600'];
		$rela_system_03=$row['rela_system_03'];
		$system_600_dni = $row['system_600_dni'];
		$system_600_orden=$row['system_600_orden'];
		$system_600_circuito=$row['system_600_circuito'];
		$system_600_disputa=$row['system_600_disputa'];
		$system_600_time_carga=$row['system_600_time_carga'];
		$system_600_date_voto=$row['system_600_date_voto'];
		
		if ($row['system_600_disputa']==2)
		{
		$system_600_disputa="NO PADRON";
		}
		else
		if ($row['system_600_disputa']==1)
		{
		$system_600_disputa="REPETIDO";
		}
		else
		{
		$system_600_disputa="NO EXISTE";
		}
		
		$nombre_apellido_dirigente = funcion_nombre_apellido($rela_system_03,$mysqli);
		
		$valu = explode('@', funcion_traer_datos($system_600_dni,$mysqli));
		$system_apellido_nombre_ciudadano= 	$valu['0'];
		$system_503_circuito= 				$valu['1'];
		$system_04_orden=					$valu['2'];
		$system_503_mesa=					$valu['3'];
		$system_503_escuela=				$valu['4'];
		$system_503_direccion=				$valu['5'];
		
		$cadena.=$system_600_dni.';';
		$cadena.=utf8_decode($system_apellido_nombre_ciudadano).';';	
		$cadena.=$system_503_circuito.';';		
		$cadena.=utf8_decode($system_503_escuela).';';
		$cadena.=utf8_decode($nombre_apellido_dirigente).';';
		$cadena.=$system_600_time_carga.';';
		$cadena.=$system_600_disputa.';';
		
		$cadena.=("\n");
		
		$system_600_dni="";
		$system_apellido_nombre_ciudadano="";
		$system_503_circuito="";	
		$system_503_escuela="";
		$system_503_mesa="";
		$system_600_time_carga="";	
		$system_600_disputa="";
	
	}
	
	echo $cadena;	

// DESARROLLADO POR HOSTRED 
// FIRMA DIGITAL f9965928da2bfbd8a2cd2a090c9c7928da515f9e
// MAO-73
?> 
