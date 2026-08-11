<?php 
session_start();
include "../lib/template.inc";
include "../lib/mysql_conect.php";
include "constructor_sql.php";
include "abm.php";
include "funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'			=> "home.html",
	'un_info'	=> "un_info.html"
	));


$total_filas = '0';
$num_filas = '3';
if ( $mas == '' or $mas == '0')
{
	$mas = $num_filas;
	$LIMITE =" limit 0,$num_filas ";
}
else
{
	$mas = $mas + $num_filas;
	$LIMITE=" limit 0,$mas ";
}

if ( $reset=='go' )
{
	$_SESSION['where_seccion']="";
}


$where=" WHERE system_11_estado='1' AND system_11_stock!='0' ";
$_SESSION['where_seccion']=$where;

$where_seccion=$_SESSION['where_seccion'];



	$t->SET_VAR("LISTADO_CATALOGO","No se encontro resultados...");
	





$t->pparse("OUT", "ver");
?>

