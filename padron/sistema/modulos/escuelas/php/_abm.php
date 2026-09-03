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
	
	public function agregar_modificar_escuela(
												$id_system_505,
												$rela_system_504,
												$system_505_circuito,
												$system_505_escuela,
												$system_505_direccion,
												$system_505_googlemaps
												)
					
	{		
		
		if ( $system_505_circuito == "" and $system_505_escuela == "" )
		{
			return "Error: Complete los campos obligatorios... (*) ";
			exit;
		}				
		
		$row = $this -> bd -> EnviarQuery("Select * from system_502_circuitos where  system_502_circuito = '$system_505_circuito' ");
		if ($row == TRUE)
		{
	
			if ($id_system_505 != '')
			{										
	
				$respuesta = $this -> bd -> EnviarQuery("UPDATE system_505_escuelas SET 			
					rela_system_504  = 		'$rela_system_504',	
					system_505_circuito = 	'$system_505_circuito',
					system_505_escuela =	'$system_505_escuela',
					system_505_direccion = 	'$system_505_direccion',
					system_505_googlemaps = '$system_505_googlemaps'																	
					WHERE 					
					id_system_505 = '$id_system_505'
				");
				if(!$respuesta != 'Fatal')
				{
					return 'Fatal! Hay un error en el query [2]';
				}			
			}
			else
			{	
			
				$row1 = $this -> bd -> EnviarQuery("Select * from system_505_escuelas where system_505_escuela = '$system_505_escuela' ");
				if ($row1 == TRUE)
				{
					return "Error: Ya existe esta escuela!";
					exit;
				}					
				$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_505_escuelas 
				( 
				id_system_505,
				rela_system_504,	
				system_505_circuito,
				system_505_escuela,
				system_505_direccion,
				system_505_googlemaps												
				) 
				VALUES 
				(
				DEFAULT,
				'$rela_system_504',
				'$system_505_circuito',
				'$system_505_escuela',
				'$system_505_direccion',
				'$system_505_googlemaps'
				)");
				
				if(!$respuesta!='Fatal')
				{
					return 'Fatal! Hay un error en el query [1]';
				}				
			}
		}
		else
		{
			return "Error: No existe este Circuito!";
			exit;
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
	


	
	
}

?>