<?php
include "../../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";

$respuesta = $mysqli -> reporte_error($_POST['error'],$_POST['url_error'],$_POST['url_error'],$system_08_titulo_site,$system_08_email_alerta);
echo $respuesta;	
	
?>
