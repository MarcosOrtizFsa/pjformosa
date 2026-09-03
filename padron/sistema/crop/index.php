<?php
include "../../lib/mysql_conect.php";
include "../php/constructor_sql.php";
include "../php/abm.php";
include "../php/funciones.php";


$system_2001_ima = 	isset($_GET['system_2001_ima']) ? $_GET['system_2001_ima'] : NULL;
$session = 	isset($_GET['session']) ? $_GET['session'] : NULL;
$ext_file =	''; 
$ext = 		''; 
$tam =		'';
$targ_w = '';
$targ_h = '';
$new_file='';

if ( $session == 'destroy')
{
	$body=" <body onLoad=\"setTimeout(window.close, 1)\">  ";									
}
else
{
	$body=" <body>  ";
}


		
		

		$file =	 	"../../dnis/".$system_2001_ima;
		$ext = 		explode(".",$file);
		$name = 	strtolower($ext[0]);
		$ext = 		end($ext);
		$tam =		getimagesize($file);
		
		//--INDICO A QUE MEDIDA QUIERO GUARDAR
		$n_ancho = 	$tam[0]; 
		$n_alto = 	$tam[1]; 
		$quality = 	100;
	
		// PATH TEMPORAL HASTA FINALIZAR EL CORTE
		$new_file = "../../dnis/$system_2001_ima";
	
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$targ_w = isset($_POST['w']) ? $_POST['w'] : NULL;
			$targ_h = isset($_POST['h']) ? $_POST['h'] : NULL;
			//$targ_w = $targ_h = 300; MODO ANCHO ALTO FIJO A 300PX Y CUADRADO
			
	
			if ($tam[2] == 1){ // GIF
			$origen  = imagecreatefromgif($file);  
			$imagen  = imagecreatetruecolor($targ_w, $targ_h);  
			imagecopyresampled($imagen,$origen,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']);
			imagegif($imagen,"$new_file");		
			}
	
	
			if ($tam[2] == 2){ // JPG
			$origen  = imagecreatefromjpeg($file);  
			$imagen  = imagecreatetruecolor($targ_w, $targ_h);  
			imagecopyresampled($imagen,$origen,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']); 
			imagejpeg($imagen,"$new_file");
			}
			
			if ($tam[2] == 3){ // PNG
			$origen  = imagecreatefrompng($file);  
			$imagen  = imagecreatetruecolor($targ_w, $targ_h);  
			imagecopyresampled($imagen,$origen,0,0,$_POST['x'],$_POST['y'],$targ_w,$targ_h,$_POST['w'],$_POST['h']); 
			imagepng($imagen,"$new_file");
			}
	
		}



?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" /> 
<meta HTTP-EQUIV="Cache-Control" CONTENT ="no-cache">


  <script src="../../scripts/ajax.js"></script>
  <script src="scripts/crop/jquery.min.js"></script>
  <script src="scripts/crop/jquery.Jcrop.js"></script>
  <script type="text/javascript" src="scripts/crop/uploader.js"></script>
  <link rel="stylesheet" href="scripts/crop/main.css" type="text/css" />
  <link rel="stylesheet" href="scripts/crop/demos.css" type="text/css" />
  <link rel="stylesheet" href="scripts/crop/jquery.Jcrop.css" type="text/css" />




<script type="text/javascript">

  $(function(){

    $('#cropbox').Jcrop({
      aspectRatio: 0,
	   //aspectRatio: 1, MODO ANCHO ALTO FIJO
      onSelect: updateCoords
    });

  });

  function updateCoords(c)
  {
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
  };

  function checkCoords()
  {
    if (parseInt($('#w').val())) return true;
    alert('Selecciona el area en la imagen que quieras recortar.');
    return false;
  };

</script>

<title>Herramienta de corte</title>
</head>

<?php echo"$body"; ?>
<table  width="100%" border="0" cellspacing="0" cellpadding="10px">
<tr>
    <td valign="middle" align="center"  style=" color: #FFFFFF;">
 
		
		


<?php if ( $system_2001_ima !=''  )
{

		if ( $_SERVER['REQUEST_METHOD']  == 'POST' )
		{
		?>
		
		<div class="menui">Terminado! Cierre esta ventana...</div>
		<br>
		<img src="<?php echo"$new_file"; ?>"  />
		<div style=""><?php echo"ANCHO: $targ_w px &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ALTO: $targ_h px"; ?></div>


		
		<?php 
		}
		else
		{
		?>
		
		
		<div class="menui"><img src="ico-corte.gif"  class="btn2" border="0" align="middle"> Selecciona, en la imagen, el área que deceas recortar.</div>
		<br>

		<img src="<?php echo"$file"; ?>" id="cropbox" class="sombreado"/>
		<div style=""><?php echo"ANCHO: $n_ancho px &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; ALTO: $n_alto px"; ?></div>
		<!-- This is the form that our event handler fills -->
		<form action="index.php?system_2001_ima=<?php echo"$system_2001_ima"; ?>" method="post" onSubmit="return checkCoords();">
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input type="submit" value="Cortar" class="btn btn-large btn-inverse" />
		</form>
		
		<?php }
		
}
else
{
echo"Cerrando...";
}
	
?>

	 </td>
</tr>
</table>
<iframe name="iframeUpload" style="display:none"></iframe>
</body>

</html>
