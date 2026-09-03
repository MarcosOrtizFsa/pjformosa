<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

header("Content-type: application/vnd.ms-csv");
header("Content-Disposition: attachment; filename=\"lista_planillas_ok_csv.csv\";");


$where = "";
$cadena='';
$numero = "1";
$LIMITE=" LIMIT 0,100000 ";
	
$cadena.='APELLIDO Y NOMBRE;';
$cadena.='DOCUMENTO;';		
$cadena.='CALLE Y NUMERO;';	
$cadena.='LOCALIDAD;';	
$cadena.='DEPARTAMENTO;';
$cadena.='PROVINCIA;';	
$cadena.='ESTADO;';	
$cadena.=("\n");		
			
	$row = $mysqli -> consulta_SQL("Select * from system_702_no_afiliados  
						
						ORDER BY id_system_702 desc 
						$LIMITE
						");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{				

			/*id_system_702, 	
						rela_system_03, 	
						system_702_dni, 	
						system_702_apellido, 	
						system_702_nombre, 	
						system_702_sexo, 	
						system_702_domicilio, 	
						system_702_dpto, 	
						system_702_localidad, 	
						system_702_estado*/
	
			
			$cadena.= utf8_decode($row[$i]['system_702_apellido']).', '.utf8_decode($row[$i]['system_702_nombre']).';';
			$cadena.= utf8_decode($row[$i]['system_702_dni']).';';	
			$cadena.= utf8_decode($row[$i]['system_702_domicilio']).';';
			$cadena.= utf8_decode($row[$i]['system_702_localidad']).';';
			$cadena.= utf8_decode($row[$i]['system_702_dpto']).';';
			$cadena.= 'FORMOSA;';

			if ( $row[$i]['system_702_estado'] == '2' )
			{
				$cadena.= 'NO PADRON G.';
			}
			else
			if ( $row[$i]['system_702_estado'] == '1' )
			{
				$cadena.= 'AFILIADO NO AVALADO';
			}
			else
			{
				$cadena.= 'NO AFILIADO';
			}	
		
			$cadena.=("\n");

			
		}
	} 

	
	echo $cadena;	

// DESARROLLADO POR HOSTRED 
// FIRMA DIGITAL f9965928da2bfbd8a2cd2a090c9c7928da515f9e
// MAO-73
?> 
