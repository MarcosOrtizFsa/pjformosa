<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$id_system_601 = 	isset($_GET['id_system_601']) ? $_GET['id_system_601'] : NULL;

header("Content-type: application/vnd.ms-csv");
header("Content-Disposition: attachment; filename=\"planilla_nro_".$id_system_601.".csv\";");


$LIMITE=" limit 0,300";
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
	$cadena.='DNI;';
	$cadena.='APELLIDO Y NOMBRE;';
	$cadena.='DOMICILIO;';		
	$cadena.='CIR;';	
	$cadena.='BARRIO;';	
	$cadena.='MESA;';
	$cadena.='ORDEN;';
	$cadena.='DIRIGENTE;';
	$cadena.='VOTO;';	
	$cadena.=("\n");		

	$where="";				
	$row = $mysqli -> consulta_SQL("Select * from system_600_votos
									WHERE 
									rela_system_601 = '$id_system_601' 
									and 
									system_600_estado IN ('0','1') 
									and 
									system_600_disputa = '0'
									
									ORDER BY id_system_600 desc 
									$LIMITE
	");
	if ($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_600=			$row[$i]['id_system_600'];
			$rela_system_03=		$row[$i]['rela_system_03'];
			$system_600_dni = 		$row[$i]['system_600_dni'];
			$system_600_apellido_nombre=$row[$i]['system_600_apellido_nombre'];
			$system_600_barrio=		$row[$i]['system_600_barrio'];
			$system_600_domicilio=	$row[$i]['system_600_domicilio'];
			$system_600_orden=		$row[$i]['system_600_orden'];
			$system_600_mesa=		$row[$i]['system_600_mesa'];
			$system_600_circuito=	$row[$i]['system_600_circuito'];
			$system_600_disputa=	$row[$i]['system_600_disputa'];
			$system_600_time_carga=	$row[$i]['system_600_time_carga'];
			$system_600_date_voto=	$row[$i]['system_600_date_voto'];
			$system_600_estado=		$row[$i]['system_600_estado'];
	
			if ($system_600_estado==1)
			{$voto="VOTO";}
			else
			{$voto="";}
			
			$cadena.= $system_600_dni.';';
			$cadena.= utf8_decode($system_600_apellido_nombre).';';
			$cadena.= utf8_decode($system_600_domicilio).';';
			$cadena.= $system_600_circuito.';';		
			$cadena.= utf8_decode($system_600_barrio).';';
			$cadena.= $system_600_mesa.';';
			$cadena.= $system_600_orden.';';
			$cadena.= utf8_decode($nombre_apellido_dirigente).';';
			$cadena.= $voto.';';
			$cadena.= ("\n");
			
			$system_600_dni= "";
			$system_600_apellido_nombre= "";
			$system_600_circuito= "";	
			$system_600_escuela= "";
			$system_600_mesa= "";
			$system_600_orden= "";	
			$system_600_domicilio= "";
		
		}
	} 
	
	
	echo $cadena;	

// DESARROLLADO POR HOSTRED 
// FIRMA DIGITAL f9965928da2bfbd8a2cd2a090c9c7928da515f9e
// MAO-73
?> 
