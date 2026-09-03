<?php
session_start();
require_once __DIR__.'/legacy_api_guard.php';
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";


$rela_system_501 = isset($_POST['rela_system_501']) ? $_POST['rela_system_501'] : NULL;
$progreso = isset($_POST['progreso']) ? $_POST['progreso'] : NULL;
	
	if ( $progreso >= '33' )
	{			
		$limite = " limit 0 ";
		$progreso = '';
	}
	else
	if ( $progreso == '0' )
	{			
		$limite = " limit 0,1 ";
		$progreso = 1;
	}
	else
	{
		$limite = " limit $progreso,1 ";
		$progreso = $progreso + 1;
	}

$nombre_archivo = $rela_system_501."_padron";
$num_orden="1";
$cadena='';

		/*where 
						 rela_system_501 = '$rela_system_501'
						*/

	$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_1 
						 
						 order by system_2000_apellido asc limit 10 
						"); 
	if ($row == TRUE)
	{	
		for ( $i=0; $i < count($row); $i++)
		{		
			
			$cadena.= $row[$i]['system_2000_dni'].';';
			$cadena.= utf8_decode($row[$i]['system_2000_apellido']).' '.utf8_decode($row[$i]['system_2000_nombre']).';';			
			$cadena.= utf8_decode($row[$i]['system_2000_circuito']).';';	
			$cadena.= utf8_decode($row[$i]['system_2000_domicilio']).';';	
			$cadena.= $row[$i]['system_2000_tipo_dni'].';';	
			$cadena.= utf8_decode($row[$i]['system_2000_clase']).';';	
			$cadena.= ("\n");
					
			
			
			$fichero = fopen("../../descargas/$nombre_archivo.csv","a+");
			fputs($fichero,$cadena); 
			fclose($fichero);
							
			$data['data'][] = [		
				'resultado' => 		"$limite",
				'progreso' => 		"$progreso"
			];
		}
	}
	else
	{
		$data['data'][] = [
			'resultado' => 			"0",
			'progreso' => 			""
		];
	}
		
		


echo json_encode( $data );
?>
