<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

header("Content-type: application/vnd.ms-csv");
header("Content-Disposition: attachment; filename=\"lista_planillas_ok_csv.csv\";");

$repet = 		isset($_GET['repet']) ? $_GET['repet'] : NULL;
$and = "";

if ($repet == 1)
{
$and = " and system_600_disputa = '1' ";
}

$where_control=$_SESSION['where_control'];
$LIMITE=" ";
$cadena='';	
$cadena.='Dirigente;';
$cadena.='DNI Voto Seg.;';
$cadena.='Apellido Nombre Voto Seg.;';		
$cadena.='Circuito;';	
$cadena.='Barrio;';	
$cadena.='Domicilio;';	
$cadena.='Escuela vota;';	
$cadena.='Mesa vota;';	
$cadena.='Nro Orden;';	
$cadena.='Estado;';
$cadena.='Voto/NoVoto;';


$cadena.=("\n");		
			
	$row = $mysqli -> consulta_SQL("Select * from system_600_votos
						where 
						rela_system_601 != '0' 
						$and
						
						ORDER BY id_system_600 desc 
						$LIMITE
						");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{				
			
			$system_600_mesa =	$row[$i]['system_600_mesa'];
			
			$cadena.=  	utf8_decode(consulto_perfil($row[$i]['rela_system_03'],$mysqli)).';';
			$cadena.= trim($row[$i]['system_600_dni']).';';
			$cadena.= utf8_decode($row[$i]['system_600_apellido_nombre']).';';	
			$cadena.= $row[$i]['system_600_circuito'].';';
			$cadena.= utf8_decode($row[$i]['system_600_barrio']).';';
			$cadena.= utf8_decode($row[$i]['system_600_domicilio']).';';				
			$cadena.= utf8_decode(funcion_ver_escuela($system_600_mesa,$mysqli)).';';	
			$cadena.= $system_600_mesa.';';
			$cadena.= $row[$i]['system_600_orden'].';';
			
			if ($row[$i]['system_600_disputa'] == '1')
			{
			$cadena.= 'REPETIDO;';
			}
			else
			{
			$cadena.= 'OK!;';
			}
			
			if ($row[$i]['system_600_estado'] == '1')
			{
			$cadena.= 'VOTO;';
			}
			else
			{
			$cadena.= '-;';
			}
			
			$cadena.=("\n");
		}
	} 

	
	echo $cadena;	

// DESARROLLADO POR HOSTRED 
// FIRMA DIGITAL f9965928da2bfbd8a2cd2a090c9c7928da515f9e
// MAO-73
?> 
