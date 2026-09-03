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
	
		
	public function nueva_planilla($id_system_601,$system_601_num,$rela_system_03,$system_601_checked,$mysqli)
	{
		if (  $rela_system_03=="" || $system_601_checked=="")
		{
			return "Error: Faltan datos... ";
			exit;
		}
		
		$row = $this -> bd -> EnviarQuery("Select * from system_601_planillas where rela_system_03='$rela_system_03' and system_601_num='$system_601_num' ");
		if ($row == true)
		{	
			return "Error: La planilla $system_601_num ya fu&eacute; creada...";
			exit;
		}
		
		if ($id_system_601 != '')
		{		
			$respuesta = $this -> bd -> EnviarQuery("UPDATE system_601_planillas SET 
			system_601_num='$system_601_num'											
			WHERE 
			id_system_601='$id_system_601'
			");
			if(!$respuesta!='Fatal')
			{
				return 'Fatal! Hay un error en el query [2]';
			}

		}
		else
		{
			$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_601_planillas
			( 	
				rela_system_03,
				system_601_num,
				system_601_estado,
				system_601_checked  
			) 
			VALUES 
			(
				'$rela_system_03',
				'$system_601_num',
				'0',
				'$system_601_checked'
			)");
				
			if(!$respuesta!='Fatal')
			{
			return 'Fatal! Hay un error en el query [1]';
			}
				
		}
				
	}
	
	
	
	public function nueva_lista($lista_dnis,$rela_system_03,$rela_system_601,$mysqli)
	{
	
		if ( $rela_system_03 =="" || $rela_system_601 =="")
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
			$system_600_time_carga = date("Y-m-d H:i:s");
			$cantidad="0";
			$noguardados="0";
			$mal="0";
			$dni="";
			$system_607_mesa =	'';
			$system_607_orden =	'';
			
			$array = explode("@", optener_solo_dni($lista_dnis));
			foreach ($array as $value) 
			{
			
				$data0= trim($value); // dni
	
				if (strlen($data0) >= '7' and strlen($data0) <= '8')
				{		
					$data0 = 	str_pad($data0, 8, "0", STR_PAD_LEFT); // 8 digitos si o si
					
					$row = $this -> bd -> EnviarQuery("Select * from system_607_mesa_orden where system_607_dni = '$data0' ");				
					if($row == true)
					{					
					$system_607_mesa =		$row[0]['system_607_mesa'];
					$system_607_orden =		$row[0]['system_607_orden'];
					}
					
					$row1 = $this -> bd -> EnviarQuery("Select * from system_600_votos where system_600_dni = '$data0' ");				
					if ($row1 == true)
					{
					$system_600_disputa =	"1";
					}
					else
					{
					$system_600_disputa =	"0";
					}
					
					$valu = explode('@', funcion_traer_datos_padron($data0,$mysqli));
					$system_600_apellido_nombre = 		$valu['0'];
					$system_600_circuito = 				$valu['1'];
					$system_600_barrio =				$valu['2'];
					$system_600_domicilio =				$valu['3'];
	
					$respuesta = $this -> bd -> EnviarQuery("INSERT INTO system_600_votos
					( 	
						rela_system_03,
						rela_system_601,
						system_600_dni,
						system_600_apellido_nombre,
						system_600_barrio,
						system_600_domicilio,
						system_600_orden,
						system_600_mesa,
						system_600_circuito,
						system_600_time_carga,
						system_600_disputa  
					) 
					VALUES 
					(
						'$rela_system_03',
						'$rela_system_601',
						'$data0',
						'$system_600_apellido_nombre',
						'$system_600_barrio',
						'$system_600_domicilio',
						'$system_607_orden',
						'$system_607_mesa',
						'$system_600_circuito',
						'$system_600_time_carga',
						'$system_600_disputa'
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
	
	public function remoner_dni($id_system_600,$mysqli)
	{
		$respuesta = $this -> bd -> EnviarQuery("DELETE FROM system_600_votos WHERE id_system_600 = '$id_system_600' ");
		
		if(!$respuesta!='Fatal')
		{
			return 'Fatal! Hay un error en el query [1]';
		}
	}

	public function borrar_planilla($id_system_601,$mysqli)
	{
		$respuesta = $this -> bd -> EnviarQuery("DELETE FROM system_601_planillas WHERE id_system_601 = '$id_system_601' ");
		
		$this -> bd -> EnviarQuery("DELETE FROM system_600_votos WHERE	rela_system_601 = '$id_system_601'");
		
		if(!$respuesta!='Fatal')
		{
			return 'Fatal! Hay un error en el query [1]';
		}

	}
	
	
	public function disputa_ganada($id_system_600,$system_600_disputa,$mysqli)
	{
		if ($system_600_disputa=='1')
		{
			$system_600_disputa='0';
		}
		else
		{
			$system_600_disputa='1';
		}
		
		$respuesta = $this -> bd -> EnviarQuery("UPDATE system_600_votos SET 
			system_600_disputa = '$system_600_disputa'											
			WHERE 
			id_system_600 = '$id_system_600'
			");
			if(!$respuesta!='Fatal')
			{
				return 'Fatal! Hay un error en el query [1]';
			}
	}
		
}


?>




