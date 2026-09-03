<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

header("Content-type: application/vnd.ms-csv");
header("Content-Disposition: attachment; filename=\"lista_planillas_dirigentes.csv\";");


$LIMITE="";
$num_orden="1";

	$cadena='';	
	$cadena.='Nro Planilla;';
	$cadena.='Dirigente;';	
	$cadena.='Total;';	
	$cadena.='Disputa;';	
	$cadena.=("\n");		

	$where_control=$_SESSION['where_control'];
		
	$row = $mysqli -> consulta_SQL("Select * from system_601_planillas
						$where_control
						
						ORDER BY id_system_601 DESC 
						$LIMITE
						");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_601=				$row[$i]['id_system_601'];
			
			$cadena.= $id_system_601.';';
			$cadena.= utf8_decode(consulto_perfil($row[$i]['rela_system_03'],$mysqli)).';';
			$row2 = $mysqli -> consulta_SQL("Select COUNT(*) total_votos from system_600_votos where rela_system_601 = '$id_system_601' and system_600_disputa = '0' ");
			if($row2 == true)
			{
				$cadena.= $row2[0]['total_votos'].';';
			}
			$row3 = $mysqli -> consulta_SQL("Select COUNT(*) total_disputa from system_600_votos where rela_system_601 = '$id_system_601'  and system_600_disputa != '0' ");				
			if($row3 == true)
			{
				$cadena.= $row3[0]['total_disputa'].';';	
			}
			$cadena.=("\n");	
		}
	} 
	
	
	echo $cadena;	

// DESARROLLADO POR HOSTRED 
// FIRMA DIGITAL f9965928da2bfbd8a2cd2a090c9c7928da515f9e
// MAO-73
?> 
