<?php
require_once __DIR__.'/legacy_api_guard.php';
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";



$variable_buscar =	isset($_POST['variable_buscar']) ? 	$_POST['variable_buscar'] : NULL;
$where=" ";	
if ( $variable_buscar != "" )
{	
	$variable_buscar=formatear_dni(trim($variable_buscar));
	
	if (ctype_digit($variable_buscar)) 
	{	
		$digit_dni = substr("$variable_buscar", -1);
		
		if ($digit_dni == 1)
		{
		$where = "Select * from system_2000_padron_1  where system_2000_dni = '$variable_buscar' ";
		}
		else
		if ($digit_dni == 2)
		{
		$where = "Select * from system_2000_padron_2  where system_2000_dni = '$variable_buscar' ";
		}
		else
		if ($digit_dni == 3)
		{
		$where ="Select * from system_2000_padron_3  where system_2000_dni = '$variable_buscar' ";
		}
		else
		if ($digit_dni == 4)
		{
		$where = "Select * from system_2000_padron_4 where system_2000_dni = '$variable_buscar' ";
		}
		else
		if ($digit_dni == 5)
		{
		$where = "Select * from system_2000_padron_5 where system_2000_dni = '$variable_buscar' ";
		}
		else
		if ($digit_dni == 6)
		{
		$where = "Select * from system_2000_padron_6 where system_2000_dni = '$variable_buscar' ";
		}
		else
		if ($digit_dni == 7)
		{
		$where = "Select * from system_2000_padron_7 where system_2000_dni = '$variable_buscar' ";
		}
		else
		if ($digit_dni == 8)
		{
		$where = "Select * from system_2000_padron_8 where system_2000_dni = '$variable_buscar' ";
		}
		else
		if ($digit_dni == 9)
		{
		$where = "Select * from system_2000_padron_9 where system_2000_dni = '$variable_buscar' ";
		}
		else
		{
		$where = "Select * from system_2000_padron_0 where system_2000_dni = '$variable_buscar' ";
		}
   	
    } 
	else 
	{
      $where.= " where system_2000_apellido like '%$variable_buscar%'  ";
    }	
	
	
	
	
	$row = $mysqli -> consulta_SQL("$where");				
	if($row == true)
	{					
		$system_2000_dni = 		$row[0]['system_2000_dni'];
		$system_2000_apellido=	$row[0]['system_2000_apellido'];
		$system_2000_nombre=	$row[0]['system_2000_nombre'];
		$system_2000_sexo=		$row[0]['system_2000_sexo'];

		$dat = explode('@',donde_vota($system_2000_dni,$mysqli));
		$mesa = 				$dat[0];
		$orden = 				$dat[1];
		$tipo_dni = 			$dat[2];
		$clase = 				$dat[3];
		
		$system_505_circuito = 	mesa_circuito($mesa,$mysqli);
		$escuela = 		mesa_escuela($mesa,$mysqli);	
		$localidad_escuela= 	localidad_por_circuito($system_505_circuito,$mysqli);

		$dat2 = explode('@',donde_vive($system_2000_dni,$mysqli));
		$domicilio = 			$dat2[0];
		$localidad_depta = 		$dat2[1];
		$circuito = 			$dat2[2];			
		
	}
	
		$data['data'][] = [
			'system_2000_dni'	=> 	"$system_2000_dni",
			'system_2000_nombre'	=> 	"$system_2000_nombre",
			'system_2000_apellido'	=> 	"$system_2000_apellido",
			'system_2000_sexo'	=> 	"$system_2000_sexo",
			'mesa'	=> 				"$mesa",
			'orden'	=> 				"$orden",	
			'tipo_dni'	=> 			"$tipo_dni",
			'clase'	=> 				"$clase",
			'escuela'	=> 			"$escuela",
			'localidad_escuela'	=> 	"$localidad_escuela",
			'domicilio'	=> 			"$domicilio",
			'localidad_depta'	=> 	"$localidad_depta",
			'circuito'	=> 			"$circuito"			
		]; 		
}
else
{
	$data['data'][] = [
			'system_2000_dni'	=> 	"",
			'system_2000_nombre'	=> 	"",
			'system_2000_apellido'	=> 	"",
			'system_2000_sexo'	=> 	"",
			'mesa'	=> 				"",
			'orden'	=> 				"",	
			'tipo_dni'	=> 			"",
			'clase'	=> 				"",
			'escuela'	=> 			"",
			'localidad_escuela'	=> 	"",
			'domicilio'	=> 			"",
			'localidad_depta'	=> 	"",
			'circuito'	=> 			""	
		];
}
							


echo json_encode( $data );
?>
