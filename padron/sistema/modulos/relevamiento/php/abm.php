<?php

function voto_seguro($id_system_04,$system_fecha,$hora_public,$sesion_system_03,$mysqli)
{
	$system_600_date_voto=$system_fecha.' '.$hora_public;
	
	
	$sql1=$mysqli->query("Select * from system_04_perfil where id_system_04='$id_system_04' ");				
	if ($row = $sql1 -> fetch_array ())
	{	
		$system_600_apellido_nombre = $row['system_04_apellido'].' '.$row['system_04_nombre'];
		$system_04_dni = $row['system_04_dni'];
		$system_04_orden = $row['system_04_orden'];
		$system_04_mesa = $row['system_04_mesa'];
		$system_04_circuito = $row['system_04_circuito'];		
	}
	
	
	$sql=$mysqli->query("Select * from system_600_votos where system_600_dni='$system_04_dni'");
	if ($row = $sql -> fetch_array ())
	{
		$id_system_600=$row['id_system_600'];
		if ($row['system_600_estado']==1)
		{$system_600_estado='0';}
		else
		{$system_600_estado='1';}
		
		if (!($mysqli->query("UPDATE system_600_votos SET 
		system_600_date_voto='$system_600_date_voto',
		system_600_estado='$system_600_estado'
		WHERE 
		id_system_600='$id_system_600'
		")))
		{
		return "Fatal! Se encontr&oacute; un error...[1]";
		}	
	}
	else
	{
	
				if (!($mysqli->query("INSERT INTO system_600_votos
				( 	
					rela_system_03,
					rela_system_601,
					system_600_dni,
					system_600_apellido_nombre,
					system_600_domicilio,
					system_600_orden,
					system_600_mesa,
					system_600_circuito,
					system_600_escuela,
					rela_system_602,
					system_600_time_carga,
					system_600_date_voto,
					system_600_disputa,
					system_600_estado  
				) 
				VALUES 
				(
					'$sesion_system_03',
					'0',
					'$system_04_dni',
					'$system_600_apellido_nombre',
					'',
					'$system_04_orden',
					'$system_04_mesa',
					'$system_04_circuito',
					'',
					'0',
					'$system_600_date_voto',
					'$system_600_date_voto',
					'2',
					'1'
				)")))
				{
					return "Fatal! Se encontr&oacute; un error...[2]";
				}
				
	}		
}
?>




