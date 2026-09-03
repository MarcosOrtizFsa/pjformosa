<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$id_system_601 = 	isset($_GET['id_system_601']) ? $_GET['id_system_601'] : NULL;


header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=\"lista_novoto_".$id_system_601.".txt\";");


$LIMITE=" ";
$num_orden="1";

	$row = $mysqli -> consulta_SQL("Select * from system_601_planillas  where id_system_601 = '$id_system_601' ");				
	if ($row == true)
	{			
		$id_system_601=		$row[0]['id_system_601'];
		$rela_system_03=	$row[0]['rela_system_03'];
		//$system_601_num = $row['system_601_num'];
		$nombre_apellido_dirigente = consulto_perfil($rela_system_03,$mysqli);
	}

	$cadena='';
	$cadena.='LISTA DE VOTOS PENDIENTES DE: '.$nombre_apellido_dirigente;
	$cadena.=("\n");
	$cadena.=("\n");		

	$where="";				
	$row = $mysqli -> consulta_SQL("Select * from system_600_votos
									WHERE 
									rela_system_601 = '$id_system_601' 
									and 
									system_600_estado = '0' 
									and 
									system_600_disputa = '0'
									
									ORDER BY id_system_600 desc 
									$LIMITE
	");
	if ($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$system_600_dni = 		$row[$i]['system_600_dni'];
			$system_600_apellido_nombre=$row[$i]['system_600_apellido_nombre'];
			$system_600_domicilio=	$row[$i]['system_600_domicilio'];
			
			$cadena.= $system_600_dni.': ';
			$cadena.= utf8_decode($system_600_apellido_nombre).' | Domicilio: ';
			$cadena.= utf8_decode($system_600_domicilio);	

			$cadena.= ("\n");
			$system_600_dni= "";
			$system_600_apellido_nombre= "";
			$system_600_domicilio= "";
		
		}
	} 
	
	
	echo $cadena;	

// DESARROLLADO POR HOSTRED 
// FIRMA DIGITAL f9965928da2bfbd8a2cd2a090c9c7928da515f9e
// MAO-73
?> 
