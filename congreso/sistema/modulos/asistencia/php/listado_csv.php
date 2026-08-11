<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);
$name_archivo = isset($_GET['name_archivo']) ? $_GET['name_archivo'] : NULL;

header("Content-type: application/vnd.ms-csv");
header("Content-Disposition: attachment; filename=\"$name_archivo.csv\";");

$where_control=$_SESSION['where_control'];


$total=0;			
$cadena='';
$cadena.='Orden;';
$cadena.='Ord.Secci;';
$cadena.='Nombre congresista;';
$cadena.='DNI;';
$cadena.='Departamento;';
$cadena.='Asistencia;';
$cadena.=("\n");		

$row = $mysqli -> consulta_SQL("Select * from system_100_congresistas $where_control ORDER BY id_system_100 ASC ");
if ($row == TRUE)
{
	for ( $i=0; $i < count($row); $i++)
	{	
		$cadena.=	$row[$i]['system_100_orden'].';';
		$cadena.= 	$row[$i]['system_100_orden_seccion'].';';
		$cadena.= 	$row[$i]['system_100_congresista'].';';
		$cadena.= 	$row[$i]['system_100_dni'].';';
		$cadena.= 	$row[$i]['system_100_departamento'].';';
		
		if ($row[$i]['system_100_estado'] == '1')
		{
		$cadena.= 'Si;';
		}
		else
		{
		$cadena.= '-;';
		}
		$cadena.=("\n");
	}
	
} 

	
	
echo $cadena;			
?>
