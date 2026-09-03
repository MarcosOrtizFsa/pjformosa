<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$name_archivo = isset($_GET['name_archivo']) ? $_GET['name_archivo'] : NULL;
$rela_system_501 = isset($_GET['rela_system_501']) ? $_GET['rela_system_501'] : NULL;

header("Content-type: application/vnd.ms-csv");
header("Content-Disposition: attachment; filename=\"$name_archivo.csv\";");



$num_orden="1";
$cadena='';

	$cadena.='DNI;';		
	$cadena.='APELLIDO Y NOMBRE;';		
	$cadena.='DOMICILIO;';	
	$cadena.='DEPATRAMENTO;';	
	$cadena.='CIRTO.;';	
	$cadena.='BARRIO;';	
	$cadena.=("\n");		

	
	
	
			
	$row = $mysqli -> consulta_SQL("Select * from system_04_padron
						 where 
						 rela_system_501 = '$rela_system_501'
						
						 order by id_system_04 asc  
						"); 
	if ($row == TRUE)
	{	
		for ( $i=0; $i < count($row); $i++)
		{		
			$id_system_04 = 		$row[$i]['id_system_04'];
			/*$system_04_sexo=		$row[$i]['system_04_sexo'];
			$system_04_orden=		$row[$i]['system_04_orden'];
			$system_04_mesa=		$row[$i]['system_04_mesa'];*/
	
				$cadena.= $row[$i]['system_04_dni'].';';
				$cadena.= utf8_decode($row[$i]['system_04_apellido']).' '.utf8_decode($row[$i]['system_04_nombre']).';';			
				$cadena.= utf8_decode($row[$i]['system_04_domicilio']).';';	
				$cadena.= utf8_decode(optengo_localidad($row[$i]['rela_system_501'],$mysqli)).';';	
				$cadena.= $row[$i]['system_04_circuito'].';';	
				$cadena.= utf8_decode($row[$i]['system_04_barrio']).';';	
				$cadena.= ("\n");
				
	
		
		}
	}
	
	echo $cadena;	

?>
