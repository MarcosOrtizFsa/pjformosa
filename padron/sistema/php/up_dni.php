<?php
session_start();
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";


$system_2001_dni = 		isset($_GET['system_2001_dni']) ? $_GET['system_2001_dni'] : NULL;
$mensage = '';
$cuet="1";

if ( $_FILES )
{
	foreach ( $_FILES as $key ) //Iteramos el arreglo de archivos
	{	
		$archivo_name = 			$key['name'];
		$archivo_name_original = 	$key['name'];		//Obtenemos el nombre original del archivo
		$archivo_type = 			$key['type'];
		$archivo_size = 			$key['size'];			
		$file_name_tmp = 			$key['tmp_name']; 	//Obtenemos la ruta Original del archivo
		$file_name_error = 			$key['error'];
		
		/*
		UPLOAD_ERROR_OK, 			valor 0, significa que no se produjo ningún error.
		UPLOAD_ERR_INI_SIZE, 		valor 1, significa que el tamaño del archivo cargado supera el valor máximo especificado en el archivo php.ini con la directiva upload_max_filesize.
		UPLOAD_ERR_FORM_SIZE, 		valor 2, significa que el tamaño del archivo cargado supera el valor máximo especificado en el formulario HTML en el elemento MAX_FILE_SIZE.
		UPLOAD_ERR_PARTIAL, 		valor 3, significa que el archivo se cargó solo parcialmente.
		UPLOAD_ERR_NO_FILE, 		valor 4, significa que no se cargó ningún archivo.
		UPLOAD_ERR_NO_TMP_DIR, 		valor 6, significa que no se especificó ningún directorio temporal en php.ini.
		UPLOAD_ERR_CANT_WRITE, 		valor 7, significa que falló la escritura del archivo en el disco.
		UPLOAD_ERR_EXTENSION, 		valor 8, significa que una extensión PHP detuvo el proceso de carga del archivo.
		*/	
			
		if ($file_name_error != '0')
		{
			if ($file_name_error == '1')
			{	
				$mensage= 'Error: '.$cuet.') El archivo supera el peso ('.$file_name_error.') permitido del servidor.';
			}
			else
			if ($file_name_error == '2')
			{
				$mensage= 'Error: '.$cuet.')  El archivo supera el peso programado.';
			}
			else
			if ($file_name_error == '3')
			{
				$mensage= 'Error: '.$cuet.') El archivo no se cargo completamente...';
			}
			else
			if ($file_name_error == '4')
			{
				$mensage= 'Error: '.$cuet.') Ningun archivo fue subido.';
			}
			else
			if ($file_name_error == '7')
			{
				$mensage.= 'Error: '.$cuet.') Verifique la capasidad de su plan de hosting.';
			}
			else
			if ($file_name_error == '8')
			{
				$mensage= 'Error: '.$cuet.') No se pudo determinar el tipo de archivo.';	
			}
			else
			{
				$mensage= 'Error: '.$cuet.') '.$archivo_name_original.' desconocido...';	
			}	
		}
		else
		{
			if (!(strpos($archivo_type, "php") || strpos($archivo_type, "inc") || strpos($archivo_type, "js") || strpos($archivo_type, "html") ))
			{
		
				
				
					
					$ext = explode(".",$archivo_name);
					$name= $ext[0];
					$ext= end($ext);
					
					
						
					$row = $mysqli -> consulta_SQL("Select * from system_2001_extras  where system_2001_dni = '$system_2001_dni' ");
					if ($row == TRUE)
					{
							
						$system_2001_ima_frente = 		$row[0]['system_2001_ima_frente'];
						$system_2001_ima_dorso = 		$row[0]['system_2001_ima_dorso'];
						
						if ( trim($system_2001_ima_frente) == '' )
						{	
							$msj= 'Frente cargado!';
							$acti= '1';
							$archivo_name = $system_2001_dni.'-1-'.time().'.'.$ext;
						}
						else
						if ( trim($system_2001_ima_dorso) == '' )
						{
							$msj= 'Dorso cargado!';
							$acti= '2';
							$archivo_name = $system_2001_dni.'-2-'.time().'.'.$ext;
						}
						else
						{
							$mensage= 'Error: Ya estan cargadas las dos caras del dni';
							exit;
						}
			
					}
					
					$file = '../../dnis/'.$archivo_name;	
						
					if ( move_uploaded_file($file_name_tmp, $file) )
					{ 
						chmod("$file", 0644);
						
						if ( $acti == '1' )
						{
							$mysqli -> consulta_SQL("UPDATE system_2001_extras SET system_2001_ima_frente = '$archivo_name' WHERE system_2001_dni = '$system_2001_dni' ");
						}
						if ( $acti == '2' )
						{
							$mysqli -> consulta_SQL("UPDATE system_2001_extras SET system_2001_ima_dorso = '$archivo_name' WHERE system_2001_dni = '$system_2001_dni' ");
						}						
						
					}
					else 
					{
						$msj= 'Fatal! Hay un error en move_uploaded_file [1]';
					}

					$mensage= $msj;
					

				
				
			}
			else // NO SE ENCONTRO EL TIPO PERMITIDO
			{	
				$mensage= 'Error: '.$cuet.') '.$archivo_name_original.' No se reconoce como archivo para este sistema!\n';			
			}
			
		}
											
	$cuet++;
	}
}
							
echo $mensage;
?>