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
	
	public function marcar_voto($sesion_system_03,$system_2004_dni,$system_2004_estado,$system_fecha,$hora_public)
	{
				
		if ($system_2004_estado == '1')
		{
			$system_2004_estado = '0';
			$system_600_date_voto = '0000-00-00 00:00:00';
		}
		else
		{
			$system_2004_estado = '1';
			$system_600_date_voto = $system_fecha.' '.$hora_public;
		}
		
		// INDICO EN TABLA MESA ORDEN EL VOTO EN GENERAL
		$this -> bd -> EnviarQuery("UPDATE system_2004_mesa_orden_general  SET 
			system_2004_estado = 	'$system_2004_estado'
			WHERE 
			system_2004_dni = 		'$system_2004_dni' 
		");

		// BUSCO MESA Y ORDEN EN LA PLANILLA DE VOTOS SEGURO Y MARCO EL VOTO SI EXISTE
		$this -> bd -> EnviarQuery("UPDATE system_600_votos  SET 
			system_600_date_voto = 	'$system_600_date_voto',
			system_600_estado = 	'$system_2004_estado'
			WHERE 
			system_600_dni = 		'$system_2004_dni' 
		");
	

	
	}


	public function cargar_mesas_votantes($system_2002_mesa)
	{
	
		if ( $system_2002_mesa == "" )
		{
			return "Error: No has indicado una mesa... ";
			exit;
		}
		
		$row = $this -> bd -> EnviarQuery("Select * from system_2002_tabla_fiscales  where system_2002_mesa = '$system_2002_mesa' ");
		if ($row == TRUE)
		{
			$system_2002_lectores = $row[0]['system_2002_lectores'];
			$_SESSION['sesion_system_03_mesa'] = $system_2002_mesa;
		}
		
		
			
	}
				
}

?>