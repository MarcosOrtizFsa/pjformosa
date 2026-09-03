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
				
					//MATRICULA,	APELLIDO NOMBRE,	DOMICILIO,	CIRCUITO,	MESA,	ORDEN
					$system_dni =			str_pad($row[0],8, "0", STR_PAD_LEFT);									
					$system_nombre =		utf8_encode($row[1].' '.$row[2]);// trim y utf8 estan deprecado en el php8 en adelante					
					//$system_domicilio =		mb_convert_encoding($row[2], 'ISO-8859-1', 'UTF-8');
					$system_domicilio =		$row[3];// trim y utf8 estan deprecado en el php8 en adelante
					$system_circuito = 		$row[4];
					$system_mesa = 			$row[5];
					$system_orden = 		$row[6];					
					$system_domicilio = str_replace("'"," ",$system_domicilio); 
					$system_domicilio =	utf8_encode($system_domicilio);
					


					$digit_dni = substr("$system_dni", -1);
	
					if ($digit_dni == 1)
					{
	
							$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_1 where system_2000_dni = '$system_dni' ");				
							if($row == true)
							{								
								// GUARDO SUFRAGIO DEL VOITANTE
								$mysqli -> consulta_SQL("UPDATE system_2000_padron_1 SET 
								system_2000_domicilio = 	'$system_domicilio',
								system_2000_crto = 			'$system_circuito',
								system_2000_mesa = 			'$system_mesa',
								system_2000_orden = 		'$system_orden'
								WHERE 
								system_2000_dni = '$system_dni'
								");								
							}
							else
							{		
								$mysqli -> consulta_SQL("INSERT INTO system_2000_padron_1     
								( 
									 system_2000_dni,
									 system_2000_apellido_nombre,
									 system_2000_domicilio,
									 system_2000_crto,
									 system_2000_mesa,
									 system_2000_orden															
								) 
								VALUES 
								(
									'$system_dni',
									'$system_nombre',
									'$system_domicilio',
									'$system_circuito',
									'$system_mesa',
									'$system_orden'
								)");	
							}
							
							
							
						
					}
					else
					if ($digit_dni == 2)
					{
							$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_2 where system_2000_dni = '$system_dni' ");				
							if($row == true)
							{								
								// GUARDO SUFRAGIO DEL VOITANTE
								$mysqli -> consulta_SQL("UPDATE system_2000_padron_2 SET 
								system_2000_domicilio = 	'$system_domicilio',
								system_2000_crto = 			'$system_circuito',
								system_2000_mesa = 			'$system_mesa',
								system_2000_orden = 		'$system_orden'
								WHERE 
								system_2000_dni = '$system_dni'
								");								
							}
							else
							{		
								$mysqli -> consulta_SQL("INSERT INTO system_2000_padron_2     
								( 
									 system_2000_dni,
									 system_2000_apellido_nombre,
									 system_2000_domicilio,
									 system_2000_crto,
									 system_2000_mesa,
									 system_2000_orden															
								) 
								VALUES 
								(
									'$system_dni',
									'$system_nombre',
									'$system_domicilio',
									'$system_circuito',
									'$system_mesa',
									'$system_orden'
								)");	
							}
					}
					else
					if ($digit_dni == 3)
					{
							$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_3 where system_2000_dni = '$system_dni' ");				
							if($row == true)
							{								
								// GUARDO SUFRAGIO DEL VOITANTE
								$mysqli -> consulta_SQL("UPDATE system_2000_padron_3 SET 
								system_2000_domicilio = 	'$system_domicilio',
								system_2000_crto = 			'$system_circuito',
								system_2000_mesa = 			'$system_mesa',
								system_2000_orden = 		'$system_orden'
								WHERE 
								system_2000_dni = '$system_dni'
								");								
							}
							else
							{		
								$mysqli -> consulta_SQL("INSERT INTO system_2000_padron_3     
								( 
									 system_2000_dni,
									 system_2000_apellido_nombre,
									 system_2000_domicilio,
									 system_2000_crto,
									 system_2000_mesa,
									 system_2000_orden															
								) 
								VALUES 
								(
									'$system_dni',
									'$system_nombre',
									'$system_domicilio',
									'$system_circuito',
									'$system_mesa',
									'$system_orden'
								)");	
							}
					}
					else
					if ($digit_dni == 4)
					{
							$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_4 where system_2000_dni = '$system_dni' ");				
							if($row == true)
							{								
								// GUARDO SUFRAGIO DEL VOITANTE
								$mysqli -> consulta_SQL("UPDATE system_2000_padron_4 SET 
								system_2000_domicilio = 	'$system_domicilio',
								system_2000_crto = 			'$system_circuito',
								system_2000_mesa = 			'$system_mesa',
								system_2000_orden = 		'$system_orden'
								WHERE 
								system_2000_dni = '$system_dni'
								");								
							}
							else
							{		
								$mysqli -> consulta_SQL("INSERT INTO system_2000_padron_4     
								( 
									 system_2000_dni,
									 system_2000_apellido_nombre,
									 system_2000_domicilio,
									 system_2000_crto,
									 system_2000_mesa,
									 system_2000_orden															
								) 
								VALUES 
								(
									'$system_dni',
									'$system_nombre',
									'$system_domicilio',
									'$system_circuito',
									'$system_mesa',
									'$system_orden'
								)");	
							}
					}
					else
					if ($digit_dni == 5)
					{
							$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_5 where system_2000_dni = '$system_dni' ");				
							if($row == true)
							{								
								// GUARDO SUFRAGIO DEL VOITANTE
								$mysqli -> consulta_SQL("UPDATE system_2000_padron_5 SET 
								system_2000_domicilio = 	'$system_domicilio',
								system_2000_crto = 			'$system_circuito',
								system_2000_mesa = 			'$system_mesa',
								system_2000_orden = 		'$system_orden'
								WHERE 
								system_2000_dni = '$system_dni'
								");								
							}
							else
							{		
								$mysqli -> consulta_SQL("INSERT INTO system_2000_padron_5     
								( 
									 system_2000_dni,
									 system_2000_apellido_nombre,
									 system_2000_domicilio,
									 system_2000_crto,
									 system_2000_mesa,
									 system_2000_orden															
								) 
								VALUES 
								(
									'$system_dni',
									'$system_nombre',
									'$system_domicilio',
									'$system_circuito',
									'$system_mesa',
									'$system_orden'
								)");	
							}
					}
					else
					if ($digit_dni == 6)
					{
							$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_6 where system_2000_dni = '$system_dni' ");				
							if($row == true)
							{								
								// GUARDO SUFRAGIO DEL VOITANTE
								$mysqli -> consulta_SQL("UPDATE system_2000_padron_6 SET 
								system_2000_domicilio = 	'$system_domicilio',
								system_2000_crto = 			'$system_circuito',
								system_2000_mesa = 			'$system_mesa',
								system_2000_orden = 		'$system_orden'
								WHERE 
								system_2000_dni = '$system_dni'
								");								
							}
							else
							{		
								$mysqli -> consulta_SQL("INSERT INTO system_2000_padron_6     
								( 
									 system_2000_dni,
									 system_2000_apellido_nombre,
									 system_2000_domicilio,
									 system_2000_crto,
									 system_2000_mesa,
									 system_2000_orden															
								) 
								VALUES 
								(
									'$system_dni',
									'$system_nombre',
									'$system_domicilio',
									'$system_circuito',
									'$system_mesa',
									'$system_orden'
								)");	
							}
					}
					else
					if ($digit_dni == 7)
					{
							$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_7 where system_2000_dni = '$system_dni' ");				
							if($row == true)
							{								
								// GUARDO SUFRAGIO DEL VOITANTE
								$mysqli -> consulta_SQL("UPDATE system_2000_padron_7 SET 
								system_2000_domicilio = 	'$system_domicilio',
								system_2000_crto = 			'$system_circuito',
								system_2000_mesa = 			'$system_mesa',
								system_2000_orden = 		'$system_orden'
								WHERE 
								system_2000_dni = '$system_dni'
								");								
							}
							else
							{		
								$mysqli -> consulta_SQL("INSERT INTO system_2000_padron_7     
								( 
									 system_2000_dni,
									 system_2000_apellido_nombre,
									 system_2000_domicilio,
									 system_2000_crto,
									 system_2000_mesa,
									 system_2000_orden															
								) 
								VALUES 
								(
									'$system_dni',
									'$system_nombre',
									'$system_domicilio',
									'$system_circuito',
									'$system_mesa',
									'$system_orden'
								)");	
							}
					}
					else
					if ($digit_dni == 8)
					{
							$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_8 where system_2000_dni = '$system_dni' ");				
							if($row == true)
							{								
								// GUARDO SUFRAGIO DEL VOITANTE
								$mysqli -> consulta_SQL("UPDATE system_2000_padron_8 SET 
								system_2000_domicilio = 	'$system_domicilio',
								system_2000_crto = 			'$system_circuito',
								system_2000_mesa = 			'$system_mesa',
								system_2000_orden = 		'$system_orden'
								WHERE 
								system_2000_dni = '$system_dni'
								");								
							}
							else
							{		
								$mysqli -> consulta_SQL("INSERT INTO system_2000_padron_8     
								( 
									 system_2000_dni,
									 system_2000_apellido_nombre,
									 system_2000_domicilio,
									 system_2000_crto,
									 system_2000_mesa,
									 system_2000_orden															
								) 
								VALUES 
								(
									'$system_dni',
									'$system_nombre',
									'$system_domicilio',
									'$system_circuito',
									'$system_mesa',
									'$system_orden'
								)");	
							}
					}
					else
					if ($digit_dni == 9)
					{
							$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_9 where system_2000_dni = '$system_dni' ");				
							if($row == true)
							{								
								// GUARDO SUFRAGIO DEL VOITANTE
								$mysqli -> consulta_SQL("UPDATE system_2000_padron_9 SET 
								system_2000_domicilio = 	'$system_domicilio',
								system_2000_crto = 			'$system_circuito',
								system_2000_mesa = 			'$system_mesa',
								system_2000_orden = 		'$system_orden'
								WHERE 
								system_2000_dni = '$system_dni'
								");								
							}
							else
							{		
								$mysqli -> consulta_SQL("INSERT INTO system_2000_padron_9     
								( 
									 system_2000_dni,
									 system_2000_apellido_nombre,
									 system_2000_domicilio,
									 system_2000_crto,
									 system_2000_mesa,
									 system_2000_orden															
								) 
								VALUES 
								(
									'$system_dni',
									'$system_nombre',
									'$system_domicilio',
									'$system_circuito',
									'$system_mesa',
									'$system_orden'
								)");	
							}
					}
					else
					if ($digit_dni == 0)
					{
							$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_0 where system_2000_dni = '$system_dni' ");				
							if($row == true)
							{								
								// GUARDO SUFRAGIO DEL VOITANTE
								$mysqli -> consulta_SQL("UPDATE system_2000_padron_0 SET 
								system_2000_domicilio = 	'$system_domicilio',
								system_2000_crto = 			'$system_circuito',
								system_2000_mesa = 			'$system_mesa',
								system_2000_orden = 		'$system_orden'
								WHERE 
								system_2000_dni = '$system_dni'
								");								
							}
							else
							{		
								$mysqli -> consulta_SQL("INSERT INTO system_2000_padron_0     
								( 
									 system_2000_dni,
									 system_2000_apellido_nombre,
									 system_2000_domicilio,
									 system_2000_crto,
									 system_2000_mesa,
									 system_2000_orden															
								) 
								VALUES 
								(
									'$system_dni',
									'$system_nombre',
									'$system_domicilio',
									'$system_circuito',
									'$system_mesa',
									'$system_orden'
								)");	
							}
					}
					else
					{
	
						
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


