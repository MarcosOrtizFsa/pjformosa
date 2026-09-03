<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

header("Content-type: application/vnd.ms-csv");
header("Content-Disposition: attachment; filename=\"lista_planillas_ok_csv.csv\";");

$system_701_num = 		isset($_GET['system_701_num']) ? $_GET['system_701_num'] : NULL;
$rela_system_701 = 		isset($_GET['rela_system_701']) ? $_GET['rela_system_701'] : NULL;
$where = "";
$cadena='';
$numero = "1";
$LIMITE=" LIMIT 0,100000 ";
	
if ($rela_system_701 != '')
{
	$where = " where  rela_system_701 = '$rela_system_701' ";
	
	$cadena.='FOLIO '.$system_701_num.';';	
	$cadena.=("\n");
}

$cadena.='FOLIO;';
$cadena.='APELLIDO Y NOMBRE;';
$cadena.='DNI;';		
$cadena.='CALLE Y NUMERO;';	
$cadena.='LOCALIDAD;';	
$cadena.='DEPARTAMENTO;';	
$cadena.='AFILIADO;';	
$cadena.=("\n");		
			
	$row = $mysqli -> consulta_SQL("Select * from system_700_avalados 
						$where 
						ORDER BY id_system_700 desc 
						$LIMITE
						");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{				

			/*id_system_700 	
			rela_system_03 	
			rela_system_701 	
			system_700_folio 	
			system_700_dni 	
			system_700_apellido 	
			system_700_nombre 	
			system_700_sexo 	
			system_700_domicilio 	
			system_700_circuito 	
			system_700_dpto 	
			system_700_localidad 	
			system_700_estado*/
	
			
			$cadena.= $row[$i]['system_700_folio'].';';
			$cadena.= utf8_decode($row[$i]['system_700_apellido']).', '.utf8_decode($row[$i]['system_700_nombre']).';';
			$cadena.= utf8_decode($row[$i]['system_700_dni']).';';	
			$cadena.= utf8_decode($row[$i]['system_700_domicilio']).';';
			$cadena.= utf8_decode($row[$i]['system_700_localidad']).';';
			$cadena.= utf8_decode($row[$i]['system_700_dpto']).';';
			if ($row[$i]['system_700_estado'] == 1)
			{
				$cadena.= 'SI;';
			}
			else
			{
				$cadena.= 'NO;';	
			}
					
			$cadena.=("\n");
			
			if ($numero == 15)
			{
				$numero =1;
			}
			else
			{
				$numero++;
			}
			
		}
	} 

	
	echo $cadena;	

// DESARROLLADO POR HOSTRED 
// FIRMA DIGITAL f9965928da2bfbd8a2cd2a090c9c7928da515f9e
// MAO-73
?> 
