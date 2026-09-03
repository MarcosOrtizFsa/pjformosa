<?php

$path = 		"../../dnis/";
$archivo = 	isset($_POST['elemento']) ? $_POST['elemento'] : NULL;

//header('Content-type: image/jpeg');

	
	$file = 	$path.''.$archivo;
	$ext_file =	explode("[.]",$file); 
	$ext = 		strtolower(isset($ext_file[2])); 
	$tam =		getimagesize($file);
	
	//--INDICO A QUE MEDIDA QUIERO GUARDAR
	$n_ancho = 	$tam[0]; 
	$n_alto = 	$tam[1]; 
	$quality = 	100;
	$grados = 	270;

	// PATH TEMPORAL HASTA FINALIZAR EL CORTE
	$new_file = $path.''.$archivo;

	if ($tam[2] == 2)
	{ // JPG
		$origen = imagecreatefromjpeg($file);
		$imagen  = imagecreatetruecolor($n_ancho, $n_alto);  
		imagecopyresampled($imagen,$origen,0,0,0,0,$n_ancho,$n_alto,$n_ancho,$n_alto); 
		$imagen = imagerotate($imagen, $grados, 0);
		imagejpeg($imagen,"$new_file");
		//imagejpeg($imagen);		
		// Liberar la memoria
		imagedestroy($origen);
		imagedestroy($imagen);		
	}
	
	

?>
