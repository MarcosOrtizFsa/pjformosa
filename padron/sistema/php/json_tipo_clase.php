<?php
session_start();
require_once __DIR__.'/legacy_api_guard.php';
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";



$system_dni ='';
$tipo_dni =	'';
$clase='';
$respuesta='';
$mensage = 			'';
$extraccio_tipo = 	1;
$name_archivo = 	isset($_POST['name_archivo']) ? $_POST['name_archivo'] : NULL;	
$progreso = 		isset($_POST['progreso']) ? $_POST['progreso'] : NULL;
$cuento = '';
$error = '';



	
	$paht_dir = "../../archivos/".$name_archivo;
	
	if ( $name_archivo != '' )
	{
		$vars ='';
		$cadena ='';
		$fila_csv = fopen("$paht_dir", "r") or exit("Farmota no permitido...");
		while(!feof($fila_csv))
		{	
			$array = explode("\n", fgets($fila_csv));
			foreach ( $array as $value => $vars) 
			{				
				$row = explode(";", $vars);	
				if ( $row[0] !='' ) 
				{										
					$system_dni = 		str_pad(trim($row[0]), 8, "0", STR_PAD_LEFT);// dni
					$tipo_dni =			trim($row[1]);
					$clase =			trim($row[2]);
					
					$resultado = '';
					$digit_dni = substr("$system_dni", -1);
					$resultado = $system_dni.';'.$tipo_dni.';'.$clase.';'.$digit_dni.';';
					$resultado.= ("\n");
					$fichero = fopen("../../descargas/respaldo.csv","a+");
					fputs($fichero,$resultado); 
					fclose($fichero);
					
					
					$progreso = $progreso + 1;	
					
														
				}				
												
			}
			
							
		}
		fclose($fila_csv);	
	}
		
	$data['data'][] = [		
						'resultado' => 		"$system_dni",
						'progreso' => 		"$progreso"
	];


echo json_encode( $data );
?>
