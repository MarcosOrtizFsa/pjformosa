<?php
class _Abm {

	private $bd;	
	function __construct($base)
	{
		$this -> bd = $base;
	}		
	
	public function consulta_SQL($vars)
	{
		$respuesta = $this -> bd -> EnviarQuery($vars);
		return $respuesta;
	}		
	
	public function voto_seguro($system_607_mesa,$system_607_orden,$system_607_dni,$system_fecha,$hora_public,$sesion_system_03)
	{
		$system_600_date_voto=$system_fecha.' '.$hora_public;
		
		$row = $this -> bd -> EnviarQuery("Select * from system_503_mesas where system_503_mesa = '$system_607_mesa'");
		if ($row == TRUE)
		{
			$system_503_circuito = 	$row[0]['system_503_circuito'];
		}


		$row2 = $this -> bd -> EnviarQuery("Select * from system_600_votos where system_600_dni = '$system_607_dni' ");
		if ($row2 == TRUE)
		{
			$id_system_600 =		$row2[0]['id_system_600'];
			
			if ( $row2[0]['system_600_estado'] == '1' )
			{
			$system_600_estado = '0';
			$system_600_date_voto='';
			$system_607_mesa = '';
			$system_607_orden ='';
			}
			else
			{
			$system_600_estado = '1';
			}
			
			
			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_600_votos SET 
			system_600_orden = 		'$system_607_orden',
			system_600_mesa = 		'$system_607_mesa',
			system_600_date_voto = 	'$system_600_date_voto',
			system_600_estado = 	'$system_600_estado'
			WHERE 
			id_system_600 = 		'$id_system_600'
			");
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [1]';
			}
	
		}
		else
		{
			$system_600_disputa = 2;// es un votante libre . no esta en planilla de dirigentes
			$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_600_votos
			( 	
				rela_system_601,
				system_600_dni,
				system_600_apellido_nombre,
				system_600_barrio,
				system_600_domicilio,
				system_600_orden,
				system_600_mesa,
				system_600_circuito,
				system_600_date_voto,
				system_600_disputa,
				system_600_estado  
			) 
			VALUES 
			(
				'0',
				'$system_607_dni',
				'',
				'',
				'',
				'$system_607_orden',
				'$system_607_mesa',
				'$system_503_circuito',
				'$system_600_date_voto',
				'$system_600_disputa',
				'1'
			)");
			if(!$respuesta!='Fatal')
			{
				return 'Fatal! Hay un error en el query [2]';
			}
			
					
		}		

	
	}



				
}

?>