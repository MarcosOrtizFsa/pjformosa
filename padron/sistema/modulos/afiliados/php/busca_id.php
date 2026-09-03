<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'			=> "busca_id.html"
	));


$variable_buscar = 		isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL;
$system_estado = 		isset($_POST['system_estado']) ? $_POST['system_estado'] : NULL;



// buscador
$url3="'modulos/afiliados/php/home.php'";
$id3="'content_seccion'";
$vars3="'id_system_01=$id_system_01&system_id='";
$t->set_var("funcion_buscar_id","cargar_post($url3,$id3,$vars3+this.value); ");	



			
$t->pparse("OUT", "ver");
?>