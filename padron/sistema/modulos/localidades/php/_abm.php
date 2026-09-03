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
	
	public function agregar_modificar(	$id_system_502,
										$rela_system_501,
										$system_502_circuito
										)
	{		
		
		if ( $system_502_circuito == "" )
		{
			return "Error: Complete los campos obligatorios... (*) ";
			exit;
		}				
		if ( $rela_system_501 == "" )
		{
			return "Error: Elija localidad... ";
			exit;
		}	
		
		if ($id_system_502 != '')
		{										

			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_502_circuitos SET 			
				rela_system_501  = 		'$rela_system_501',	
				system_502_circuito =	'$system_502_circuito'																	
				WHERE 					
				id_system_502 = '$id_system_502'
			");
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [2]';
			}
		
		}
		else
		{	
		

			$row = $this -> bd -> EnviarQuery("Select * from system_502_circuitos where system_502_localidades = '$system_502_localidades' ");
			if ($row == TRUE)
			{
				return "Error: Ya existe este circuito";
				exit;
			}
				
				$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_502_circuitos 
				( 
				id_system_502,
				rela_system_501,
				system_502_circuito												
				) 
				VALUES 
				(
				DEFAULT,
				'$rela_system_501',
				'$system_502_circuito'
				)");
				
				if(!$respuesta!='Fatal')
				{
					return 'Fatal! Hay un error en el query [1]';
				}				
			
		}
	}





	public function eliminar($id_system_502)
	{
		$respuesta = $this -> bd -> EnviarQuery("DELETE FROM system_502_circuitos 
		WHERE 
		id_system_502='$id_system_502'
		");
		if(!$respuesta!='Fatal')
		{
			return 'Fatal! Hay un error en el query [1]';
		}	
	}
	


	public function agregar_modificar_pueblo(					$id_system_504,
																$system_504_circuito,
																$system_504_pueblo,
																$system_504_mapsgoogle
																)
	{		
		
		if ( $system_504_pueblo =="" )
		{
			return "Error: Complete los campos obligatorios... (*) ";
			exit;
		}				
	
		
		if ($id_system_504 != '')
		{										

			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_504_ubicacion SET 			
				system_504_circuito  = 		'$system_504_circuito',
				system_504_pueblo = 		'$system_504_pueblo',	
				system_504_mapsgoogle =		'$system_504_mapsgoogle'																	
				WHERE 					
				id_system_504 = '$id_system_504'
			");
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [2]';
			}
		
		}
		else
		{	
		

			$row = $this -> bd -> EnviarQuery("Select * from system_504_ubicacion where system_504_circuito = '$system_504_circuito' and system_504_pueblo = '$system_504_pueblo' ");
			if ($row == TRUE)
			{
				return "Error: Ya existe esta localidad...";
				exit;
			}
				
				$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_504_ubicacion 
				( 
				id_system_504,
				system_504_circuito,
				system_504_pueblo,
				system_504_mapsgoogle												
				) 
				VALUES 
				(
				DEFAULT,
				'$system_504_circuito',
				'$system_504_pueblo',
				'$system_504_mapsgoogle'
				)");
				
				if(!$respuesta!='Fatal')
				{
					return 'Fatal! Hay un error en el query [1]';
				}				
			
		}
	}
	
	
}

?>