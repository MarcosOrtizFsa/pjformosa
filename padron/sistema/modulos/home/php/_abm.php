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
										$id_system_605,
										$rela_system_602,
										$system_605_mesa,
										$system_605_1ro,
										$system_605_2do,
										$system_605_3ro,
										$system_605_4to,
										$system_605_5to,
										$system_605_6to,
										$system_605_7mo,
										$system_605_8vo
										)
	{		
		
		if ( $rela_system_602 ==""   )
		{
			return "Error: Complete los campos obligatorios... (*) ";
			exit;
		}				
		
		
		if ($id_system_605 != '')
		{										

			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_605_totales SET 
				rela_system_602 = 		'$rela_system_602',		
				system_605_mesa = 		'$system_605_mesa',
				system_605_1ro = 		'$system_605_1ro',
				system_605_2do = 		'$system_605_2do',
				system_605_3ro = 		'$system_605_3ro',
				system_605_4to = 		'$system_605_4to',
				system_605_5to = 		'$system_605_5to',
				system_605_6to = 		'$system_605_6to',
				system_605_7mo = 		'$system_605_7mo',
				system_605_8vo = 		'$system_605_8vo'													
				WHERE 					
				id_system_605 = '$id_system_605'
			");
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [3]';
			}
		
		}
		else
		{	
		
			/*$row = $this -> bd -> EnviarQuery("Select * from system_605_totales where system_04_dni = '$system_04_dni' ");
			if ($row == TRUE)
			{
				return "Error: Ya esta registrado!";
				exit;
			}*/

				$system_605_fecha_hora = date('Y-m-d').' '.date('H:i:s');
				$system_605_estado = 1;
				
				$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_605_totales 
				( 
				id_system_605,
				rela_system_03,
				rela_system_602,
				system_605_mesa,
				system_605_1ro,
				system_605_2do,
				system_605_3ro,
				system_605_4to,
				system_605_5to,
				system_605_6to,
				system_605_7mo,
				system_605_8vo,
				system_605_fecha_hora,
				system_605_estado														
				) 
				VALUES 
				(
				DEFAULT,
				'$sesion_system_03',
				'$rela_system_602',
				'$system_605_mesa',
				'$system_605_1ro',
				'$system_605_2do',
				'$system_605_3ro',
				'$system_605_4to',
				'$system_605_5to',
				'$system_605_6to',
				'$system_605_7mo',
				'$system_605_8vo',
				'$system_605_fecha_hora',
				'$system_605_estado'
				)");
				if(!$respuesta!='Fatal')
				{
					return 'Fatal! Hay un error en el query [1]';
				}				
			
		}
		
	}
	
	
	
	public function agregar_modificar_lema(
									$id_system_602,
									$rela_system_603,
									$rela_system_604,
									$system_602_sublema,	
									$system_602_orden
									)
	{		
		
		if ( $system_602_sublema == ""  or $system_602_orden == "" )
		{
			return "Error: Complete los campos...";
			exit;
		}				
		
		
		if ($id_system_602 != '')
		{										

			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_602_escrutinio SET 
				rela_system_603 = 		'$rela_system_603',		
				rela_system_604 = 		'$rela_system_604',
				system_602_sublema = 	'$system_602_sublema',
				system_602_orden =		'$system_602_orden'													
				WHERE 					
				id_system_602 = 		'$id_system_602'
			");
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [3]';
			}
		
		}
		else
		{	
		
			$row = $this -> bd -> EnviarQuery("Select * from system_602_escrutinio where system_602_sublema = '$system_602_sublema' and system_602_orden = '$system_602_orden' ");
			if ($row == TRUE)
			{
				return "Error: Ya existe este lema/sublema!";
				exit;
			}

				
				$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_602_escrutinio 
				( 
				id_system_602,
				rela_system_603,		
				rela_system_604,
				system_602_sublema,
				system_602_orden														
				) 
				VALUES 
				(
				DEFAULT,
				'$rela_system_603',
				'$rela_system_604',
				'$system_602_sublema',
				'$system_602_orden'	
				)");
				if(!$respuesta!='Fatal')
				{
					return 'Fatal! Hay un error en el query [1]';
				}				
			
		}
		
	}
	
	
	public function totales_am(
								$id_system_606,
								$system_606_mesa,
								$system_606_nulos,
								$system_606_recurridos,
								$system_606_impugnada,
								$system_606_comando,
								$system_606_blanco,
								$system_606_total
								)
	{
			
			$system_606_fecha_hora = date('Y-m-d').' '.date('H:i');
			
			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_606_resumen_total SET 
				system_606_mesa  = 		'$system_606_mesa', 	
				system_606_nulos = 		'$system_606_nulos',  	
				system_606_recurridos = '$system_606_recurridos',  	
				system_606_impugnada = 	'$system_606_impugnada',  	
				system_606_comando = 	'$system_606_comando',  	
				system_606_blanco = 	'$system_606_blanco',  	
				system_606_total = 		'$system_606_total', 	
				system_606_fecha_hora = '$system_606_fecha_hora'  												
				WHERE 					
				id_system_606 = 		'$id_system_606'
			");
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [1]';
			}
	}


	public function configurar_sistema(
					$rela_system_06,
					$system_08_tema,
					$system_08_total_objetivo
					)
	{
	
			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_08_configuracion SET 
				system_08_tema =			'$system_08_tema',
				system_08_total_objetivo =	'$system_08_total_objetivo'													
				WHERE 					
				rela_system_06 = '$rela_system_06'
			");
			if(!$respuesta != 'Fatal')
			{
				return 'Fatal! Hay un error en el query [1]';
			}
			
	}

	
	
	public function eliminar_lema($id_system_602)
	{
		$respuesta = $this -> bd -> EnviarQuery("DELETE FROM system_602_escrutinio 
		WHERE 
		id_system_602 = '$id_system_602'
		");
		if(!$respuesta!='Fatal')
		{
			return 'Fatal! Hay un error en el query [1]';
		}	
	}
	
	
	
	
	
	
}

?>
