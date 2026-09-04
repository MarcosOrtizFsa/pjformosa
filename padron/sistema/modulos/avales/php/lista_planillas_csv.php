<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$name_archivo = 	isset($_GET['name_archivo']) ? $_GET['name_archivo'] : NULL;
$rela_system_03 = 	isset($_GET['rela_system_03']) ? $_GET['rela_system_03'] : NULL;

header("Content-type: application/vnd.ms-csv");
header("Content-Disposition: attachment; filename=\"$name_archivo.csv\";");

$LIMITE=" limit 0,300";
$num_orden="1";

$nombre_apellido_dirigente = funcion_nombre_apellido($rela_system_03,$mysqli);

	$cadena='';	
	$cadena.='DNI;';
	$cadena.='APELLIDO Y NOMBRE;';	
	$cadena.='CIR;';	
	$cadena.='ESCUELA;';	
	$cadena.='MESA;';
	$cadena.='ORDEN;';
	$cadena.='DIRECCION;';
	$cadena.='DIRIGENTE;';	
	$cadena.=("\n");		

	$where_control=$_SESSION['where_control'];
		
	$sql=$mysqli->query("Select * from system_600_votos
						$where_control
						
						ORDER BY id_system_600 desc 
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
			$system_600_estado=$row['system_600_estado'];

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
			$cadena.=$system_503_mesa.';';
			$cadena.=$system_04_orden.';';
			$cadena.=utf8_decode($system_503_direccion).';';
			$cadena.=utf8_decode($nombre_apellido_dirigente).';';
			$cadena.=("\n");
			
			$system_600_dni="";
			$system_apellido_nombre_ciudadano="";
			$system_503_circuito="";	
			$system_503_escuela="";
			$system_503_mesa="";
			$system_04_orden="";	
			$system_503_direccion="";
	
	}
	
	echo $cadena;	

// DESARROLLADO POR HOSTRED 
// FIRMA DIGITAL f9965928da2bfbd8a2cd2a090c9c7928da515f9e
// MAO-73
?> 
