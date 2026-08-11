<?php
session_start();
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";

$sesion_system_03 = $_SESSION['sesion_system_03'];
$sesion_system_06 = $_SESSION['sesion_system_06'];
$id_system_01 = isset($_GET['id_system_01']) ? $_GET['id_system_01'] : NULL;
$id_system_11 = isset($_GET['id_system_11']) ? $_GET['id_system_11'] : NULL;
$id_system_10 = isset($_GET['id_system_10']) ? $_GET['id_system_10'] : NULL;
$id_system_15 = isset($_GET['id_system_15']) ? $_GET['id_system_15'] : NULL;
$extraer_info = isset($_GET['extraer_info']) ? $_GET['extraer_info'] : NULL;
$mensage = '';
$cuet="1";



function guardar_file(	$file_name_tmp, 
						$file,
						$destino,
						$sesion_system_03,
						$sesion_system_06,
						$id_system_10,
						$id_system_11,
						$id_system_15,
						$system_09_tipo,
						$system_09_album,
						$system_09_epigrafe,
						$archivo_name,
						$ext,
						$archivo_size,
						$system_08_path,
						$system_08_ancho_max,
						$system_checked,
						$mysqli									
						)
{
	if (move_uploaded_file ($file_name_tmp, $file))
	{ 
		chmod("$file", 0644);
		
		if ($destino !='' )
		{
			$tam=getimagesize("$file"); 
			$n_ancho=$tam[0]; 
			$n_alto=$tam[1]; 
			
			if ( $n_ancho > $system_08_ancho_max )
			{
			$n_ancho=$system_08_ancho_max; 
			$n_alto=($n_ancho*($tam[1]/$tam[0])); 
			}
			
			if ($tam[2] == "1"){
			$img2 = imagecreatefromgif("$file");
			$img1 = imagecreatetruecolor($n_ancho,$n_alto);
			imagecopyresampled($img1,$img2,0,0,0,0,$n_ancho,$n_alto,$tam[0],$tam[1]);
			imagegif($img1,"$destino");
			imagedestroy($img2);
			imagedestroy($img1);
			unlink($file); 
			}
			
			if ($tam[2] == "2"){
			$img2 = imagecreatefromjpeg("$file");
			$img1 = imagecreatetruecolor($n_ancho,$n_alto);
			imagecopyresampled($img1,$img2,0,0,0,0,$n_ancho,$n_alto,$tam[0],$tam[1]);
			imagejpeg($img1,"$destino");
			imagedestroy($img2);
			imagedestroy($img1);
			unlink($file); 
			}
			
			if ($tam[2] == "3"){
			$img2 = imagecreatefrompng("$file");
			$img1 = imagecreatetruecolor($n_ancho,$n_alto);
			imagecopyresampled($img1,$img2,0,0,0,0,$n_ancho,$n_alto,$tam[0],$tam[1]);
			imagepng($img1,"$destino");
			imagedestroy($img2);
			imagedestroy($img1);
			unlink($file);  		
			}
		}

		
		$row = $mysqli -> guardar_archivo_down(
						$sesion_system_03,
						$sesion_system_06,
						$id_system_10,
						$id_system_11,
						$id_system_15,
						$system_09_tipo,
						$system_09_album,
						$system_09_epigrafe,
						$archivo_name,
						$ext,
						$archivo_size,
						$system_08_path,
						$system_checked										
						);
		if ($row == TRUE)
		{
		return 'Fatal! Hay un error en el query guardar_archivo_down[2]';
		}	
	}
	else 
	{
	return 'Fatal! Hay un error en move_uploaded_file [1]';
	}
}


function extraer_info(		$file_name_tmp, 
							$file,
							$destino,
							$sesion_system_03,
							$sesion_system_06,
							$id_system_10,
							$id_system_11,
							$id_system_15,
							$system_09_tipo,
							$system_09_album,
							$system_09_epigrafe,
							$archivo_name,
							$ext,
							$archivo_size,
							$system_08_path,
							$system_08_ancho_max,
							$system_checked,
							$mysqli										
							)
{
	if (move_uploaded_file ($file_name_tmp, $file))
	{ 
		chmod("$file", 0644);
		
				$mysqli -> guardar_archivo_down(
						$sesion_system_03,
						$sesion_system_06,
						$id_system_10,
						$id_system_11,
						$id_system_15,
						$system_09_tipo,
						$system_09_album,
						$system_09_epigrafe,
						$archivo_name,
						$ext,
						$archivo_size,
						$system_08_path,
						$system_checked										
						);
						
				if ( $ext=="csv" )
				{
					$cadena_txt = fopen("$file", "r") or exit('Fatal! Hay un error en FOPEN [1]');
					while(!feof($cadena_txt))
					{	
						$array = explode("\n", fgets($cadena_txt));
						foreach ($array as $value) 
						{
							$row = explode(";", $value);			
							if ( ctype_digit($row[0]) == true and count($row) == '5') // si o si debe tener un id. 0=agregar nuevo producto
							{				
								$orden = 			trim($row[0]);
								$orde_seccion=		trim($row[1]);
								$congresista=		utf8_encode(trim($row[2]));								
								$dni=				trim($row[3]);
								$departamento=		utf8_encode(trim($row[4]));

								
								
												
								if ( $orden !='' || $orde_seccion !='' || $congresista !='' || $dni !=''  )
								{
									$mysqli -> guardar_dato_extraido(
									$sesion_system_03,
									$orden,
									$orde_seccion,
									strtoupper($congresista),
									$dni,
									strtoupper($departamento)
									);
								}
								
							}	
							$system_checked++;
							$id_system_11 = 				'';
							$system_11_codigo_barra	=		'';
							$system_11_producto	=			'';
							$system_11_precio_costo	=		'';
							$system_11_precio_mayorista	=	'';
							$system_11_precio_minorista	=	'';
							$system_11_unidades	=			'';
							$system_11_stock	=			'';	
							$system_11_estado_iva = 		'';
						}				
					}
					fclose($cadena_txt);
					
				}	
	}
	else 
	{
	return 'Fatal! Hay un error en move_uploaded_file [1]';
	}
}


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
			$mensage= 'Error: '.$cuet.') El archivo supera el peso permitido del servidor.\n';
			}
			else
			if ($file_name_error=='2')
			{
			$mensage= 'Error: '.$cuet.')  El archivo supera el peso programado.\n';
			}
			else
			if ($file_name_error=='3')
			{
			$mensage= 'Error: '.$cuet.') El archivo no se cargo completamente...\n';
			}
			else
			if ($file_name_error=='4')
			{
			$mensage= 'Error: '.$cuet.') Ningun archivo fue subido.\n';
			}
			else
			if ($file_name_error=='7')
			{
			$mensage.= 'Error: '.$cuet.') Verifique la capasidad de su plan de hosting.\n';
			}
			else
			if ($file_name_error=='8')
			{
			$mensage= 'Error: '.$cuet.') No se pudo determinar el tipo de archivo.\n';	
			}
			else
			{
			$mensage= 'Error: '.$cuet.') '.$archivo_name_original.' no pudo subir...\n';	
			}	
		}
		else
		{
			if (!(strpos($archivo_type, "php") || strpos($archivo_type, "inc") || strpos($archivo_type, "js") || strpos($archivo_type, "html") ))
			{
		
				if ( $archivo_size > $system_08_limit_size_up_archivo ) 
				{ 	
				$mensage= 'Error: '.$cuet.') '.$archivo_size.' supera el peso permitido ('.$system_08_limit_size_up_archivo.')\n';	
				}
				else
				{
					
					$ext = explode(".",$archivo_name);
					$name= $ext[0];
					$ext= end($ext);
					
					
					if ( $ext=='csv' and $extraer_info=='ok')
					{
						$system_09_album="Temporal";
						$system_09_epigrafe=$archivo_name;
						$archivo_name=time().'.'.$ext;
						$file = '../../archivos/'.$archivo_name;
						$destino = '';
						$system_09_tipo="3";// 1=imagen 2=archivo, 3=temporal
						$system_08_path= $system_08_path.'/archivos';
						
						$mensage= extraer_info(		$file_name_tmp, 
													$file,
													$destino,
													$sesion_system_03,
													$sesion_system_06,
													$id_system_10,
													$id_system_11,
													$id_system_15,
													$system_09_tipo,
													$system_09_album,
													$system_09_epigrafe,
													$archivo_name,
													$ext,
													$archivo_size,
													$system_08_path,
													$system_08_ancho_max,
													$system_checked,
													$mysqli										
													);
					}
					else
					if ( $ext=='txt' or $ext=='csv' or $ext=='xls' or $ext=='xlsx' or $ext=='zip' or $ext=='rar' or $ext=='pdf')
					{
						$system_09_album="Archivos";
						$system_09_epigrafe=$archivo_name;
						$archivo_name=time().'_'.urls_amigables($name).'.'.$ext;
						$file = '../../archivos/'.$archivo_name;
						$destino = '';
						$system_09_tipo="2";// 1=imagen 2=archivo
						$system_08_path= $system_08_path.'/archivos';
						
						$mensage= guardar_file(		$file_name_tmp, 
													$file,
													$destino,
													$sesion_system_03,
													$sesion_system_06,
													$id_system_10,
													$id_system_11,
													$id_system_15,
													$system_09_tipo,
													$system_09_album,
													$system_09_epigrafe,
													$archivo_name,
													$ext,
													$archivo_size,
													$system_08_path,
													$system_08_ancho_max,
													$system_checked,
													$mysqli										
													);

							
						
					}
					else
					if ( $ext=='png' or $ext=='jpg' or $ext=='jpeg' or $ext=='gif')
					{
						
						
						
						

						if ( $id_system_10 !='' )
						{
						$system_09_album="Noticia";
						$system_09_epigrafe=$archivo_name;
						$archivo_name=date('Ymd_his').'.'.$ext;
						$file = '../../tmp/'.$archivo_name;
						$destino = '../../imagenes/'.$archivo_name;
						$system_09_tipo="1";// 1=imagen 2=archivo
						$system_08_path= $system_08_path.'/imagenes';
						}

						if ( $id_system_15 !='' )
						{
						$system_09_album="Banners";
						$system_09_epigrafe=$archivo_name;
						$archivo_name=time().'_'.urls_amigables($name).'.'.$ext;
						$file = '../../tmp/'.$archivo_name;
						$destino = '../../banners/'.$archivo_name;
						$system_09_tipo="3";// 1=imagen 2=archivo, 3=banner
						$system_08_path= $system_08_path.'/banners';
						}
						
						if ( $id_system_11 !='' )
						{
						$system_09_album="Catalogo";
						$system_09_epigrafe=$archivo_name;
						$archivo_name=time().'_'.urls_amigables($name).'.'.$ext;
						$file = '../../tmp/'.$archivo_name;
						$destino = '../../imagenes/'.$archivo_name;
						$system_09_tipo="1";// 1=imagen 2=archivo
						$system_08_path= $system_08_path.'/imagenes';
						}
						
						
							
						$mensage= guardar_file(		$file_name_tmp, 
													$file,
													$destino,
													$sesion_system_03,
													$sesion_system_06,
													$id_system_10,
													$id_system_11,
													$id_system_15,
													$system_09_tipo,
													$system_09_album,
													$system_09_epigrafe,
													$archivo_name,
													$ext,
													$archivo_size,
													$system_08_path,
													$system_08_ancho_max,
													$system_checked,
													$mysqli										
													);

					}
					else
					{	
						$mensage= 'Error: '.$cuet.') '.$archivo_name_original.' No parece ser un archivo permitido aqui...\n';	
					}

				}
				
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