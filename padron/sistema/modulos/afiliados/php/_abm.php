<?php
class _Abm 
{
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
	
		
	public function nuevo_folio($id_system_701,$system_701_num,$rela_system_703,$rela_system_03,$system_701_checked,$mysqli)
	{
		if (  $rela_system_03=="" || $system_701_checked=="" )
		{
			return "Error: Faltan datos... ";
			exit;
		}
		
		if (  $system_701_num == "" )
		{
			return "Error: Faltan numero de Folio! ";
			exit;
		}
		
		if (  $rela_system_703 == "" )
		{
			return "Error: Elije filial! ";
			exit;
		}
		
		$row = $this -> bd -> EnviarQuery("Select * from system_701_folio  where rela_system_03='$rela_system_03' and system_701_num='$system_701_num' ");
		if ($row == true)
		{	
			return "Error: El folio $system_701_num ya esta creado!";
			exit;
		}
		
		if ($id_system_701 != '')
		{		
			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_701_folio  SET 
			rela_system_703 = '$rela_system_703',
			system_701_num='$system_701_num'											
			WHERE 
			id_system_701='$id_system_701'
			");
			if(!$respuesta!='Fatal')
			{
				return 'Fatal! Hay un error en el query [2]';
			}

		}
		else
		{
			$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_701_folio 
			( 	
				rela_system_03,
				rela_system_703,
				system_701_num,
				system_701_estado,
				system_701_checked  
			) 
			VALUES 
			(
				'$rela_system_03',
				'$rela_system_703',
				'$system_701_num',
				'0',
				'$system_701_checked'
			)");
				
			if(!$respuesta!='Fatal')
			{
			return 'Fatal! Hay un error en el query [1]';
			}
				
		}
				
	}
	
	
	
	public function nueva_lista($lista_dnis,$rela_system_03,$rela_system_701,$mysqli)
	{
	
		if ( $rela_system_03 =="" || $rela_system_701 =="")
		{
			return "Error: Faltan datos... ";
			exit;
		}
		
		if ( $lista_dnis =="" )
		{
			return "Error: No hay lista de dnis... ";
			exit;
		}
					
				
		if ( $lista_dnis !="" )
		{
			
			$cantidad="0";
			$noguardados="0";
			$mal="0";
			$dni="";

			
			$array = explode("@", optener_solo_dni($lista_dnis));
			foreach ($array as $value) 
			{
			
				$data0= trim($value); // dni
	
				if (strlen($data0) >= '7' and strlen($data0) <= '8')
				{		
					$data0 = 	str_pad($data0, 8, "0", STR_PAD_LEFT); // 8 digitos si o si
			
					$valu = explode('@', funcion_traer_datos_padron($data0,$mysqli));
					$system_apellido_nombre = 		$valu['0'];
					$system_circuito = 				$valu['1'];
					$system_barrio =				$valu['2'];
					$system_domicilio =				$valu['3'];
	
					$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_700_afiliados 
					( 	
						id_system_700, 	
						rela_system_03, 	
						rela_system_701, 	
						system_700_dni, 	
						system_700_apellido_nombre, 	
						system_700_domicilio, 	
						system_700_circuito, 	
						system_700_estado 	  
					) 
					VALUES 
					(
						DEFAULT,
						'$rela_system_03',
						'$rela_system_701',
						'$data0',
						'$system_apellido_nombre',
						'$system_domicilio',
						'$system_circuito',
						'1'
					)");	
					if($respuesta!='Fatal')
					{
					$cantidad++;
					}
	
					$dni.=$data0."<br>";
					
				}
				else
				{
				$mal++;
				}
				
				
			}
			return "PoInf: $cantidad Dni cargados. Errores: $mal  ";
		}
	
	}
	
	public function remoner_dni($id_system_700,$mysqli)
	{
		$respuesta = $this -> bd -> EnviarQuery("DELETE FROM system_700_afiliados WHERE id_system_700 = '$id_system_700' ");
		
		if(!$respuesta!='Fatal')
		{
			return 'Fatal! Hay un error en el query [1]';
		}
	}

	public function borrar_folio($id_system_701,$mysqli)
	{
		$respuesta = $this -> bd -> EnviarQuery("DELETE FROM  system_701_folio  WHERE id_system_701 = '$id_system_701' ");
		
		$this -> bd -> EnviarQuery("DELETE FROM system_700_afiliados WHERE	rela_system_701 = '$id_system_701'");
		
		if(!$respuesta!='Fatal')
		{
			return 'Fatal! Hay un error en el query [1]';
		}

	}
	
	

	
	
	
	public function agregar_nuevo_afiliado(	
															$id_system_700, 	
															$rela_system_03, 	
															$rela_system_701, 	
															$system_700_dni, 	
															$system_700_apellido, 	
															$system_700_nombre, 	
															$system_700_sexo, 	
															$system_700_domicilio, 	
															$system_700_circuito, 	
															$system_700_dpto, 	
															$system_700_localidad, 	
															$system_700_estado,
															$mysqli
															)
	{
		
		if (  $system_700_apellido=="" || $system_700_nombre=="")
		{
			return "Error: Faltan datos... ";
			exit;
		}
		
		$respuesta = $this -> bd -> EnviarQuery("UPDATE system_700_afiliados SET 
			system_700_apellido  = 	'$system_700_apellido',	
			system_700_nombre  = 	'$system_700_nombre',	
			system_700_sexo = 		'$system_700_sexo', 	
			system_700_domicilio = 	'$system_700_domicilio', 	
			system_700_circuito = 	'$system_700_circuito', 	
			system_700_dpto = 		'$system_700_dpto', 	
			system_700_localidad  = '$system_700_localidad',	
			system_700_estado = 	'$system_700_estado'								
			WHERE 
			id_system_700 = '$id_system_700'
			");
			if(!$respuesta!='Fatal')
			{
				return 'Fatal! Hay un error en el query [1]';
			}
	}	
	
	


	public function salvar_observacion($id_system_701,$system_701_observaciones)
	{
		
			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_701_folio SET 	
			system_701_observaciones = 	'$system_701_observaciones'								
			WHERE 
			id_system_701 = '$id_system_701'
			");
			if(!$respuesta!='Fatal')
			{
				return 'Fatal! Hay un error en el query [1]';
			}

	}
	
	
	
			
}


?>




