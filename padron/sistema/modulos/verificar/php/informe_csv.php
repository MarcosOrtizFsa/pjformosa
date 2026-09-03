<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


header('Content-type: application/vnd.ms-csv');
header('Content-Disposition: attachment; filename="lista_verificacion_'.$system_fecha.'.csv";');
$LIMITE = "";
$total=0;			
$cadena='';
$cadena.='	Num;';
$cadena.='	DNI;';
$cadena.='	Dirigente;';
//$cadena.='	Fecha;';
$cadena.=("\n");	

			
$row = $mysqli -> consulta_SQL("Select * from system_2003_nuevos_tramites ");
if ($row == TRUE)
{	
	$nume ='1';
	for ( $i=0; $i < count($row); $i++)
	{
		//$id_system_2003 = 			$row[$i]['id_system_2003'];
		$system_2003_dni = 			$row[$i]['system_2003_dni'];
		$system_2003_dirigente = 	$row[$i]['system_2003_dirigente'];
		//$system_2003_estado = 		$row[$i]['system_2003_estado'];
		//$system_2003_fecha = 		$row[$i]['system_2003_fecha'];

		$cadena.=''.$nume.';';
		$cadena.=''.$system_2003_dni.';';
		$cadena.=''.$system_2003_dirigente.';'; 
		//$cadena.=''.$system_2003_fecha.';';	
		$cadena.=("\n");	
		$nume++;
	}			
}

	

	
	
echo $cadena;			
?>
