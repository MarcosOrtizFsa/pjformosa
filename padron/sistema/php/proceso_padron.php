<?php
session_start();
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";

function salvar_dato_padron($system_dni,$system_nombre,$system_apellido,$system_sexo,$system_direccion,$tipo_dni,$clase,$system_circuito,$mysqli)
{
	$digit_dni = substr("$system_dni", -1);
	
	if ($digit_dni == 1)
	{
		$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_1 where system_2000_dni = '$system_dni' ");
		$insertar = "INSERT INTO system_2000_padron_1 ";
	}
	else
	if ($digit_dni == 2)
	{
		$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_2 where system_2000_dni = '$system_dni' ");
		$insertar = "INSERT INTO system_2000_padron_2 ";
	}
	else
	if ($digit_dni == 3)
	{
		$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_3 where system_2000_dni = '$system_dni' ");
		$insertar = "INSERT INTO system_2000_padron_3 ";
	}
	else
	if ($digit_dni == 4)
	{
		$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_4 where system_2000_dni = '$system_dni' ");
		$insertar = "INSERT INTO system_2000_padron_4 ";
	}
	else
	if ($digit_dni == 5)
	{
		$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_5 where system_2000_dni = '$system_dni' ");
		$insertar = "INSERT INTO system_2000_padron_5 ";
	}
	else
	if ($digit_dni == 6)
	{
		$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_6 where system_2000_dni = '$system_dni' ");
		$insertar = "INSERT INTO system_2000_padron_6 ";
	}
	else
	if ($digit_dni == 7)
	{
		$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_7 where system_2000_dni = '$system_dni' ");
		$insertar = "INSERT INTO system_2000_padron_7 ";
	}
	else
	if ($digit_dni == 8)
	{
		$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_8 where system_2000_dni = '$system_dni' ");
		$insertar = "INSERT INTO system_2000_padron_8 ";
	}
	else
	if ($digit_dni == 9)
	{
		$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_9 where system_2000_dni = '$system_dni' ");
		$insertar = "INSERT INTO system_2000_padron_9 ";
	}
	else
	{
		$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_0 where system_2000_dni = '$system_dni' ");
		$insertar = "INSERT INTO system_2000_padron_0 ";
	}

	if ($row == false)
	{	
		$mysqli -> consulta_SQL("$insertar   
		( 
		system_2000_dni,
		system_2000_apellido,
		system_2000_nombre,
		system_2000_sexo,
		system_2000_circuito,
		system_2000_domicilio,
		system_2000_tipo_dni,
		system_2000_clase														
		) 
		VALUES 
		(
		'$system_dni',
		'$system_apellido',
		'$system_nombre',
		'$system_sexo',
		'$system_circuito',
		'$system_direccion',
		'$tipo_dni',
		'$clase'
		)");
		
		
	}
	
}
$respuesta='';
$mensage = 			'';
$extraccio_tipo = 	isset($_POST['tipo']) ? $_POST['tipo'] : NULL;
$name_archivo = 	isset($_POST['name_archivo']) ? $_POST['name_archivo'] : NULL;	
$desde = 			isset($_POST['desde']) ? $_POST['desde'] : NULL;
$hasta = 			$desde + 100;	
$cuento = '';
$error = '';


$paht_dir = "../../archivos/".$name_archivo;

if ( $name_archivo != '' )
{
	if ( $extraccio_tipo == "1" )
	{
		$vars ='';
		$cuento = 0;
		$cadena ='';
		$encontrados= '0';
		$fila_csv = fopen("$paht_dir", "r") or exit("Farmota no permitido...");
		while(!feof($fila_csv))
		{	
			$array = explode("\n", fgets($fila_csv));
	
			foreach ( $array as $value => $vars) 
			{		
				if ( $cuento >= $desde  )
				{
					$row = explode(";", $vars);	
					if ( $row[0] !='' and $cuento <= $hasta ) 
					{										
						
						/*$system_dni = 		str_pad(trim($row[0]), 8, "0", STR_PAD_LEFT);// dni
						$system_apellido =	utf8_encode(trim($row[1]));
						$system_nombre =	utf8_encode(trim($row[2]));
						$system_sexo =		trim($row[3]);
						$system_circuito =	trim($row[4]);
						$system_direccion =	utf8_encode(trim($row[5]));	
						$tipo_dni =			trim($row[6]);
						$clase =			trim($row[7]);*/
						// salvar datos
						
						$system_dni = 		str_pad(trim($row[0]), 8, "0", STR_PAD_LEFT);// dni
						$tipo_dni =			trim($row[1]);
						$clase =			trim($row[2]);
						
						//$respuesta = salvar_dato_padron($system_dni,$system_nombre,$system_apellido,$system_sexo,$system_direccion,strtoupper($tipo_dni),$clase,strtoupper($system_circuito),$mysqli);
						/*if ( $respuesta == 'x' )
						{
							$error = 'E';
						}
						else
						if ( $respuesta == 1 )
						{
							$encontrados++;
						}
						else
						{
						
						}*/
						$encontrados++;
											
					}
				}				
			}
			$cuento++;				
		}
		fclose($fila_csv);	
		
	}
}

	

	$data['data'][] = [ 
		'error' => 			"$error",
		'encontrados' => 	"$encontrados",
		'cuento' => 		"$cuento",
		'desde' => 			"$hasta"
	];


echo json_encode( $data );	
?>