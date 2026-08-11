<?php
session_start();
header ("Content-type: image/png"); // el código generado con la función cds(), la guardamos en esta variable…
$scat=$_SESSION['captcha_session'];
$ima = imagecreate(90, 30);
$color_fondo = imagecolorallocate($ima, 153, 153, 153);
$color_texto = imagecolorallocate($ima, 0, 0, 0);
$color_lin = imagecolorallocate($ima, 102, 51, 51);
imageline($ima, 6, 0, 20,30,$color_lin);
imageline($ima, 55, 0, 50,30,$color_lin);
imageline($ima, 22, 0, 80,30,$color_lin);
imagestring($ima, 16, 10, 8,$scat,$color_texto);
imagepng($ima);
?>