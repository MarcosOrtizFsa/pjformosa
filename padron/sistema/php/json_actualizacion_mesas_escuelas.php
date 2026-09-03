<?php
session_start();
require_once __DIR__.'/legacy_api_guard.php';
header("Content-Type: text/html; charset=utf-8");
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";


$id_system_09 = isset($_POST['id_system_09']) ? $_POST['id_system_09'] : NULL;
$system_09_tipo = isset($_POST['system_09_tipo']) ? $_POST['system_09_tipo'] : NULL;
$system_mesa = '';
$system_orden = '';
$system_dpto = 	'';
$system_nombre =	'';				
$system_domicilio =	'';
$system_circuito = 	'';

$row = $mysqli -> consulta_SQL("Select * from system_09_archivero where id_system_09 = '$id_system_09' ");				
if($row == true)
{				

	$system_09_archivo =		$row[0]['system_09_archivo'];
	
	

	if ( (isset($_POST['desde']) ? $_POST['desde'] : NULL) == '' )
	{
		$desde=0;
	}
	else
	{
		$desde = 			isset($_POST['desde']) ? $_POST['desde'] : NULL;
	}

	$activo = 'stop';
	$cadena='';
	$retorno='';
	$paht_dir = "../../archivos/$system_09_archivo";
	$vars ='';
	
	$cuento = '0';
	$cadena ='';
	
	$encontrados= 	'0';
	$hasta = 		$desde + 10;	
	// tipo: 0=datos personales 1=donde vota 
	
	
	$fila_csv = fopen("$paht_dir", "r") or exit("Farmota no permitido...");
	while(!feof($fila_csv))
	{	
		$array = explode("\n", fgets($fila_csv));	
		foreach ( $array as $value => $vars) 
		{		
			if ( $cuento >= $desde )
			{
				$row = explode(";", $vars);	
				if ( $row[0] !='' and $cuento <= $hasta  ) 
				{																					
					// 	system_504_mesa 	system_504_escuela 	system_504_dpto 	system_504_localidad 		
					$system_mesa =			$row[0];					
					$system_escuela =		utf8_encode($row[1]);// trim y utf8 estan deprecado en el php8 en adelante					
					$system_depto =			utf8_encode($row[2]);// trim y utf8 estan deprecado en el php8 en adelante
					$system_localidad = 	utf8_encode($row[3]);


					$row2 = $mysqli -> consulta_SQL("Select * from system_504_mesas where system_504_mesa = '$system_mesa' ");				
					if($row2 == true)
					{
							
					}
					else
					{
						$mysqli -> consulta_SQL("INSERT INTO system_504_mesas     
						( 
							 system_504_mesa,
							 system_504_escuela,
							 system_504_dpto,
							 system_504_localidad														
						) 
						VALUES 
						(
							'$system_mesa',
							'$system_escuela',
							'$system_depto',
							'$system_localidad'
						)");
					}
					

					
					
					$activo =  'go';

				}
			}				
		}
		$cuento++;				
	}
	fclose($fila_csv);		
	
	$data['data'][] = [
		'id_system_09' =>		$id_system_09,
		'resultado' => 			$activo,
		'progreso' => 			$hasta,
		'system_09_tipo' => 	$system_09_tipo
	];
	
}
else
{
	$data['data'][] = [
		'id_system_09' =>		'',
		'resultado' => 			'-',
		'progreso' => 			'-',
		'system_09_tipo' => 	''
	];
}
	







echo json_encode( $data );
?>


