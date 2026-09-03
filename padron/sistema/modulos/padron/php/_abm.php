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


	public function limpiar_sufragios()
	{
		$this -> bd -> EnviarQuery("DELETE FROM system_2003_sufragio_0 ");
		$this -> bd -> EnviarQuery("DELETE FROM system_2003_sufragio_1 ");
		$this -> bd -> EnviarQuery("DELETE FROM system_2003_sufragio_2 ");
		$this -> bd -> EnviarQuery("DELETE FROM system_2003_sufragio_3 ");
		$this -> bd -> EnviarQuery("DELETE FROM system_2003_sufragio_4 ");
		$this -> bd -> EnviarQuery("DELETE FROM system_2003_sufragio_5 ");
		$this -> bd -> EnviarQuery("DELETE FROM system_2003_sufragio_6 ");
		$this -> bd -> EnviarQuery("DELETE FROM system_2003_sufragio_7 ");
		$this -> bd -> EnviarQuery("DELETE FROM system_2003_sufragio_8 ");
		$this -> bd -> EnviarQuery("DELETE FROM system_2003_sufragio_9 ");	
	}
	
	public function quitar_extraible($id_system_09)
	{
		$this -> bd -> EnviarQuery("DELETE FROM system_09_archivero where id_system_09 = '$id_system_09' ");
	}
	
}

?>