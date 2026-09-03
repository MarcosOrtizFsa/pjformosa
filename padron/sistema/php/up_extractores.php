<?php
session_start();
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";

$extraccio_tipo = 	isset($_GET['extraccio_tipo']) ? $_GET['extraccio_tipo'] : NULL;
$paht_dir = 		isset($_POST['paht_dir']) ? $_POST['paht_dir'] : NULL;	
$mensage = 			'';


if ($_FILES)
{
	foreach ($_FILES as $key) //Iteramos el arreglo de archivos
	{			
		$archivo_name = 			$key['name'];
		$archivo_name_original = 	$key['name'];//Obtenemos el nombre original del archivo
		$archivo_type = 			$key['type'];
		$archivo_size = 			$key['size'];			
		$file_name_tmp = 			$key['tmp_name']; //Obtenemos la ruta Original del archivo
		$file_name_error = 			$key['error'];
			
		if ($file_name_error > '0')
		{
			if ($file_name_error=='1')
			{	
				$mensage= 'Error: Supera el peso permitido del servidor.\n';
			}
			else
			if ($file_name_error=='2')
			{
				$mensage= 'Error: Supera el peso programado...\n';
			}
			else
			if ($file_name_error=='3')
			{
				$mensage= 'Error: El archivo no se cargo completamente...\n';
			}
			else
			if ($file_name_error=='4')
			{
				$mensage= 'Error: No hay archivo cargado....\n';
			}
			else
			if ($file_name_error=='7')
			{
				$mensage.= 'Error: Verifique su plan de hosting.\n';
			}
			else
			if ($file_name_error=='8')
			{
				$mensage= 'Error: No se pudo determinar el tipo de archivo.\n';	
			}
			else
			{
				$mensage= 'Error: No pudo subir...\n';	
			}	
		}
		else
		{							
			$ext = 	explode(".",$archivo_name);
			$name = $ext[0];
			$ext = 	strtolower(end($ext));			
			
			if ( $ext == 'csv' and $extraccio_tipo != '' )
			{
				$archivo_name = time().'.'.$ext;
				$file = '../../archivos/'.$archivo_name;				
						
				// guardo el archivo 
				if (move_uploaded_file ($file_name_tmp, $file))
				{ 
					chmod("$file", 0644);
					$mensage = 'Exito! '.$archivo_name;	
				}
				else 
				{
					$mensage = 'Fatal! Hay un error en move_uploaded_file [1]';
				}

			}
			else
			{	
				$mensage = 'Error: Solo archivos de tipo SCV \n';	
			}		
		}											
	}
}
						
echo $extraccio_tipo.''.$mensage;
?>