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


	public function  agregar_dirigente(	$id_system_2005,
										$system_2005_nombre														
										)
	{
	
		if ($id_system_2005 != '')
		{										
			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_2005_lista_dirigentes SET 
				system_2005_nombre =		'$system_2005_nombre'														
				WHERE 					
				id_system_2005 = '$id_system_2005'
			");
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [2]';
			}
		
		}
		else
		{	
		

			$row = $this -> bd -> EnviarQuery("Select * from system_2005_lista_dirigentes where system_2005_nombre = '$system_2005_nombre' ");
			if ($row == TRUE)
			{
				return "Error: Ya esta cargado!";
				exit;
			}
	
				
				$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_2005_lista_dirigentes  
				( 
				id_system_2005,
				system_2005_nombre														
				) 
				VALUES 
				(
				DEFAULT,
				'$system_2005_nombre'
				)");
				
				if(!$respuesta!='Fatal')
				{
					return 'Fatal! Hay un error en el query [1]';
				}				
			
		}
		
		
		
	}


	public function  nuevos_tramites(		$system_2001_dni,
											$system_fecha
											)
	{		
		
		$system_2003_dirigente = isset($_SESSION['rela_system_2005']) ? $_SESSION['rela_system_2005'] : NULL;	

		if ( $system_2003_dirigente == "" )
		{
			return "Error: Selecciones dirigente ";
			exit;
		}
		
		$row = $this -> bd -> EnviarQuery("Select * from system_2003_nuevos_tramites  where system_2003_dni = '$system_2001_dni' ");
		if ($row == TRUE)
		{	 	 	 	 	 	
			$system_2003_dni = 			$row[0]['system_2003_dni'];
			$system_2003_dirigente = 	$row[0]['system_2003_dirigente'];
			$system_2003_estado = 		$row[0]['system_2003_estado'];
			$system_2003_fecha = 		$row[0]['system_2003_fecha'];
			
			return 'Infor: YA ESTA FICHADO!';
		}
		else
		{
		
			$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_2003_nuevos_tramites  
			( 
				id_system_2003, 	
				system_2003_dni, 	
				system_2003_dirigente, 	
				system_2003_estado, 	
				system_2003_fecha 													
			) 
			VALUES 
			(
				DEFAULT,
				'$system_2001_dni',
				'$system_2003_dirigente',
				'1',
				'$system_fecha'
			)");			
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [1]';
			}
			
		}


	}



public function  nuevos_aval(		$system_2004_dni,
											$system_fecha
											)
	{		
		
		$rela_system_2005 = isset($_SESSION['rela_system_2005']) ? $_SESSION['rela_system_2005'] : NULL;	

		if ( $rela_system_2005 == "" )
		{
			return "Error: Selecciones dirigente ";
			exit;
		}
		
		$row = $this -> bd -> EnviarQuery("Select * from  system_2004_nuevos_avales   where system_2004_dni = '$system_2004_dni' ");
		if ($row == TRUE)
		{	 	 	 	 	 	 
			$system_2004_dni = 			$row[0]['system_2004_dni'];
			$system_2004_ano = 			$row[0]['system_2004_ano'];
			$system_2004_fecha = 		$row[0]['system_2004_fecha'];
			$rela_system_2005 = 		$row[0]['rela_system_2005'];			
			return 'Infor: '.$system_2004_dni.' YA ESTA EN OTRO AVAL '.$system_2004_ano.'!';
		}
		else
		{
		
			$respuesta = $this -> bd -> EnviarQuery("INSERT INTO  system_2004_nuevos_avales   
			( 
				id_system_2004, 	
				system_2004_dni, 	
				system_2004_ano, 	
				system_2004_fecha, 	
				rela_system_2005 													
			) 
			VALUES 
			(
				DEFAULT,
				'$system_2004_dni',
				'2026',
				'$system_fecha',
				'$rela_system_2005'
			)");			
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [1]';
			}
			
		}


	}
	
	
	
		
}

?>