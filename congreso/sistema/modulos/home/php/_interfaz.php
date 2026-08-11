<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$id_system_01=$_POST['id_system_01'];
$nombre_funcion=$_POST['nombre_funcion'];
	
switch ($nombre_funcion)
{
	

	
	default:
	$mensaje="No hay funci&oacute;n...";
	break;	
}
echo "$mensaje";
?>
