<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
//Archivos comunes
$t->set_file(array(
	'ver'		=> "home_abm.html"
	));


$id_system_100 = isset($_POST['id_system_100']) ? $_POST['id_system_100'] : NULL;

$row = $mysqli -> consulta_SQL("Select * from system_100_congresistas
						WHERE 
						id_system_100='$id_system_100' 
						");
if($row == TRUE)
{

	$id_system_100 = 			$row[0]['id_system_100'];
	$rela_system_03 = 			$row[0]['rela_system_03'];
	$system_100_orden = 		$row[0]['system_100_orden'];
	$system_100_orden_seccion = $row[0]['system_100_orden_seccion'];
	$system_100_congresista = 	$row[0]['system_100_congresista'];	
	$system_100_dni = 			$row[0]['system_100_dni'];	
	$system_100_departamento = 	$row[0]['system_100_departamento'];
	$system_100_estado=			$row[0]['system_100_estado'];
		
}
else
{
	$id_system_100 = 			'';
	$rela_system_03 = 			'';
	$system_100_orden = 		'';
	$system_100_orden_seccion = '';
	$system_100_congresista = 	'';	
	$system_100_dni = 			'';	
	$system_100_departamento = 	'';
	$system_100_estado=			'';
	
}


	$t->set_var("system_100_orden","$system_100_orden");
	$t->set_var("system_100_orden_seccion","$system_100_orden_seccion");
	$t->set_var("system_100_congresista","$system_100_congresista");
	$t->set_var("system_100_dni","$system_100_dni");
	$t->set_var("system_100_departamento","$system_100_departamento");
	
	$url="'modulos/asistencia/php/_interfaz.php'";
	$vars="'nombre_funcion=agregar_modificar&";
	$vars.="id_system_100=$id_system_100&";
	$vars.="system_100_orden='+abm.system_100_orden.value+'&";
	$vars.="system_100_orden_seccion='+abm.system_100_orden_seccion.value+'&";	
	$vars.="system_100_dni='+abm.system_100_dni.value+'&";	
	$vars.="system_100_congresista='+encodeURIComponent(abm.system_100_congresista.value)+'&";
	$vars.="system_100_departamento='+encodeURIComponent(abm.system_100_departamento.value)";
	
	
	$url_exito="'modulos/asistencia/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01'";	
	
	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");		
	


$url="'modulos/asistencia/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01'";		
$t->set_var("funcion_cerrar","cargar_post($url,$id,$vars)");				


$t->pparse("OUT", "ver");
?>
