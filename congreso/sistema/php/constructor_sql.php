<?php
class Constructor_SQL{
	
	
	private $conexion;
	public $error;

	function __construct($host, $usua, $clav, $base) 
	{
		if(!$this->_connect($host, $usua, $clav, $base)) 
		{
			$this->error = $this->conexion->connect_error;
		}
	}

	function __destruct() {
		$this->conexion->close();
	}



	private function _connect($host, $usua, $clav, $base) 
	{
		$this->conexion = new mysqli($host, $usua, $clav, $base);
		$this->conexion -> set_charset("utf8");
		
		if(!$this->conexion->connect_errno) 
		{
			$this->error = $this->conexion->connect_error;
			return false;
		}
	}

	public function EnviarQuery($query) 
	{
		$tipo = strtoupper(substr($query, 0,6));

		switch($tipo) 
		{
			
			
			case 'INSERT':
			$resultado = $this->conexion->query($query);
			if(!$resultado) 
			{
				$this->error = $this->conexion->error;
				return 'Fatal';	// envio un msj de Fatal
			} 
			else 
			{
				$this->conexion->insert_id;
				return false; 
			}	
			break;
				
			case 'UPDATE':
			case 'DELETE':
			$resultado = $this->conexion->query($query);
			if(!$resultado)
			{
				$this->error = $this->conexion->error;
				return 'Fatal';	// envio un msj de Fatal
			} 
			else 
			{
				$this->conexion->affected_rows;
				return false; 
			}
			break;

			case 'SELECT':
			$resultado = $this->conexion->query($query);
			if(!$resultado) 
			{
				$this->error = $this->conexion->error;
				return 'Fatal';	// envio un msj de Fatal
			} 
			else 
			{
				if ( $resultado->num_rows > '0' )
				{
					while($fila = $resultado->fetch_assoc()) 
					{
					$listar_datos[]=$fila;
					}
					
					if (isset($listar_datos)!='')
					{
					return $listar_datos;
					}
				}
				else
				{
				return FALSE;
				}	
			}
			break;
			
			
			default:
			return false;
			break;
			
			
		}

	}

	public function escapar($valor)
	{
		return $this->conexion->real_escape_string((string) $valor);
	}
}
?>
