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
	
	public function agregar_modificar(	$sesion_system_03,
										$id_system_100,
										$system_100_orden,
										$system_100_orden_seccion,
										$system_100_congresista,
										$system_100_dni,
										$system_100_departamento
										)
	{
		
		
		if ( $system_100_congresista=="" || $system_100_dni=="" )
		{
			return "Error: Complete los campos obligatorios... (*) ";
			exit;
		}		
		
		if ( verifico_dni($system_100_dni)=="" )
		{
			return "Error: Verifique DNI... $system_100_dni ";
			exit;
		}
	

		if ($id_system_100!='')
		{							
				
			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_100_congresistas SET 
				system_100_orden='$system_100_orden',
				system_100_orden_seccion='$system_100_orden_seccion',
				system_100_congresista='$system_100_congresista',
				system_100_dni='$system_100_dni',
				system_100_departamento='$system_100_departamento'														
				WHERE 					
				id_system_100='$id_system_100'
			");
			
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [3]';
			}
		
		}
		else
		{	
		

			$row = $this -> bd -> EnviarQuery("Select * from system_100_congresistas where  system_100_dni='$system_100_dni' ");
			if ($row == TRUE)
			{
				return "Error: Ya esta cargado!";
				exit;
			}
						

				$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_100_congresistas 
				( 
				id_system_100,
				rela_system_03,
				system_100_orden,
				system_100_orden_seccion,
				system_100_congresista,
				system_100_dni,
				system_100_departamento,
				system_100_estado														
				) 
				VALUES 
				(
				DEFAULT,
				'$sesion_system_03',
				'$system_100_orden',
				'$system_100_orden_seccion',
				'$system_100_congresista',
				'$system_100_dni',
				'$system_100_departamento',
				'0'
				)");
				
				if(!$respuesta!='Fatal')
				{
					return 'Fatal! Hay un error en el query [1]';
				}				
			
		}	
	}

	public function eliminar($id_system_100)
	{
		$respuesta = $this -> bd -> EnviarQuery("DELETE FROM system_100_congresistas 
		WHERE 
		id_system_100='$id_system_100'
		");
		if(!$respuesta!='Fatal')
		{
			return 'Fatal! Hay un error en el query [1]';
		}	
	}
	
	public function on_off($id_system_100,$system_100_estado)
	{
		
		if ($system_100_estado=='0')
		{
			$system_100_estado='1';
		}
		else
		{
			$system_100_estado='0';
		}
		
		$respuesta = $this -> bd -> EnviarQuery("UPDATE system_100_congresistas SET 
				system_100_estado='$system_100_estado'				
			WHERE 					
				id_system_100='$id_system_100'
			");
		
		if(!$respuesta!='Fatal')
		{
			return 'Fatal! Hay un error en el query [1]';
		}	
	}


	public function limpiar_asistencias($sesion_system_07)
	{
		
		if ( $sesion_system_07 == 1 )
		{
		
			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_100_congresistas SET 
					system_100_estado = '0'				
				");
			if(!$respuesta!='Fatal')
			{
				return 'Fatal! Hay un error en el query [1]';
			}
		}	
	}
	
				
}

?>