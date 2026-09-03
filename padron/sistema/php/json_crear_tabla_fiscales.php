<?php
session_start();
require_once __DIR__.'/legacy_api_guard.php';
header("Content-Type: text/html; charset=utf-8");
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";


$system_2002_mesa = isset($_POST['system_2002_mesa']) ? $_POST['system_2002_mesa'] : NULL;
$lectores_0 = '';
$lectores_1 = '';
$lectores_2 = '';
$lectores_3 = '';
$lectores_4 = '';
$lectores_5 = '';
$lectores_6 = '';
$lectores_7 = '';
$lectores_8 = '';
$lectores_9 = '';
$system_2002_lectores = '';

/*$row = $mysqli -> consulta_SQL("Select * from system_504_mesas order by system_504_mesa ASC ");				
if($row == true)
{				
	for ( $i=0; $i < count($row); $i++)
	{	
		$system_504_mesa =		$row[$i]['system_504_mesa'];		
	}
}
*/


$row = $mysqli -> consulta_SQL("Select * from system_2002_tabla_fiscales where system_2002_mesa = '$system_2002_mesa' ");				
if($row == true)
{
		// si existe la tabla regreso la siguiente mesa numerada
		$system_2002_lectores = $row[0]['system_2002_lectores'];
		$system_2002_mesa = 	$row[0]['system_2002_mesa'] + 1;
		
		$data['data'][] = [
			'system_2002_mesa' =>		$system_2002_mesa,
			'system_2002_lectores' => 	$system_2002_lectores
		];	
}
else
{	
	
	$row0 = $mysqli -> consulta_SQL("SELECT COUNT(*) AS total_filas FROM system_2000_padron_0  WHERE system_2000_mesa = '$system_2002_mesa'  ");
	if ($row0 == true)
	{		
		$lectores_0 = $row0[0]['total_filas'];
	}
	$row1 = $mysqli -> consulta_SQL("SELECT COUNT(*) AS total_filas FROM system_2000_padron_1  WHERE system_2000_mesa = '$system_2002_mesa'  ");
	if ($row1 == true)
	{		
		$lectores_1 = $row1[0]['total_filas'];
	}				
	$row2 = $mysqli -> consulta_SQL("SELECT COUNT(*) AS total_filas FROM system_2000_padron_2  WHERE system_2000_mesa = '$system_2002_mesa'  ");
	if ($row2 == true)
	{		
		$lectores_2 = $row2[0]['total_filas'];
	}				
	$row3 = $mysqli -> consulta_SQL("SELECT COUNT(*) AS total_filas FROM system_2000_padron_3  WHERE system_2000_mesa = '$system_2002_mesa'  ");
	if ($row3 == true)
	{		
		$lectores_3 = $row3[0]['total_filas'];
	}		
	$row4 = $mysqli -> consulta_SQL("SELECT COUNT(*) AS total_filas FROM system_2000_padron_4  WHERE system_2000_mesa = '$system_2002_mesa'  ");
	if ($row4 == true)
	{		
		$lectores_4 = $row4[0]['total_filas'];
	}		
	$row5 = $mysqli -> consulta_SQL("SELECT COUNT(*) AS total_filas FROM system_2000_padron_5  WHERE system_2000_mesa = '$system_2002_mesa'  ");
	if ($row5 == true)
	{		
		$lectores_5 = $row5[0]['total_filas'];
	}
	$row6 = $mysqli -> consulta_SQL("SELECT COUNT(*) AS total_filas FROM system_2000_padron_6  WHERE system_2000_mesa = '$system_2002_mesa'  ");
	if ($row6 == true)
	{		
		$lectores_6 = $row6[0]['total_filas'];
	}
	$row7 = $mysqli -> consulta_SQL("SELECT COUNT(*) AS total_filas FROM system_2000_padron_7  WHERE system_2000_mesa = '$system_2002_mesa'  ");
	if ($row7 == true)
	{		
		$lectores_7 = $row7[0]['total_filas'];
	}
	$row8 = $mysqli -> consulta_SQL("SELECT COUNT(*) AS total_filas FROM system_2000_padron_8  WHERE system_2000_mesa = '$system_2002_mesa'  ");
	if ($row8 == true)
	{		
		$lectores_8 = $row8[0]['total_filas'];
	}
	$row9 = $mysqli -> consulta_SQL("SELECT COUNT(*) AS total_filas FROM system_2000_padron_9  WHERE system_2000_mesa = '$system_2002_mesa'  ");
	if ($row9 == true)
	{		
		$lectores_9 = $row9[0]['total_filas'];
	}
	
	$system_2002_lectores = $lectores_0 + $lectores_1 + $lectores_2 + $lectores_3 + $lectores_4 + $lectores_5 + $lectores_6 + $lectores_7 + $lectores_8 + $lectores_9;
	
	
	

	
	$row = $mysqli -> consulta_SQL("Select COUNT(*) as total_mesas from system_504_mesas  ");
	if ($row == TRUE)
	{		
		$total_mesas = $row[0]['total_mesas'];
	}
	
	if ( $total_mesas > $system_2002_mesa )
	{
	
			$mysqli -> consulta_SQL("INSERT INTO system_2002_tabla_fiscales      
			( 
				 system_2002_mesa,
				 system_2002_lectores 															
			) 
			VALUES 
			(
				'$system_2002_mesa',
				'$system_2002_lectores'
			)");
		
			$system_2002_mesa = 	$system_2002_mesa + 1; // sumo la siguebnte mesa
	}
	else
	{
	
			$system_2002_mesa = 	''; 
			$system_2002_lectores = ''; 
	
	}
	
	
	
	$data['data'][] = [
			'system_2002_mesa' =>		$system_2002_mesa,
			'system_2002_lectores' => 	$system_2002_lectores
	];
	

}










echo json_encode( $data );
?>


