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



	
	
	
		
	public function  agregar_afiliacion(	$sesion_system_03,	
											$system_2001_dni,
											$system_fecha
											)
	{		
		
				

		
		$row = $this -> bd -> EnviarQuery("Select * from system_2001_extras  where system_2001_dni = '$system_2001_dni' ");
		if ($row == TRUE)
		{
				
			$system_2001_ima_frente = 		$row[0]['system_2001_ima_frente'];
			$system_2001_ima_dorso = 		$row[0]['system_2001_ima_dorso'];
			
			if ( trim($system_2001_ima_frente) != '' and trim($system_2001_ima_dorso) != '' )
			{
			return 'Infor: El dni '.$system_2001_dni.' esta listo...';
			}
			else
			{
			return 'Infor: DNI Incompleto!';
			}

		}
		else
		{
		
			$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_2001_extras  
			( 
				system_2001_dni,
				system_2001_estado 												
			) 
			VALUES 
			(
				'$system_2001_dni',
				'1'
			)");			
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [1]';
			}
			
		}


	}







	public function eliminar($system_2001_dni,$cara)
	{
		
		if ( $cara == '1' )
		{
			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_2001_extras SET 
			system_2001_ima_frente = ''
			WHERE 
			system_2001_dni = '$system_2001_dni'
			");

		}
		
		if ( $cara == '2' )
		{
			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_2001_extras SET 
			system_2001_ima_dorso = ''
			WHERE 
			system_2001_dni = '$system_2001_dni'
			");
		}
		
		
		if(!$respuesta!='Fatal')
		{
			return 'Fatal! Hay un error en el query [1]';
		}	
	}









		
}

?>