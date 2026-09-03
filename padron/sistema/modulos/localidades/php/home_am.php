<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$t = new _template('../templates');
$t->set_file(array(
	'ver'				=> "home_am.html"
	));


$id_system_502 = isset($_POST['id_system_502']) ? $_POST['id_system_502'] : NULL;

$row = $mysqli -> consulta_SQL("Select * from system_502_circuitos
								where 
								id_system_502 = '$id_system_502'");
if ($row == TRUE)
{
	$id_system_502 = 		$row[0]['id_system_502'];
	$rela_system_501 = 		$row[0]['rela_system_501'];
	$system_502_circuito = 	$row[0]['system_502_circuito'];
	$system_502_localidades = $row[0]['system_502_localidades'];
		
	$titulo_modulo="Editar Localidad";	
	$boton_modulo="Salvar";
}
else
{
	$id_system_502 = 	'';
	$rela_system_501 = '';
	$system_502_circuito = '';
	$system_502_localidades = '';
	$titulo_modulo="Agregar Localidad";	
	$boton_modulo="Guardar";
}
	
	$t->set_var("id_system_502",		"$id_system_502");
	$t->set_var("rela_system_501",		"$rela_system_501");
	$t->set_var("system_502_circuito",	"$system_502_circuito");
	$t->set_var("system_502_localidades","$system_502_localidades");



	$url="'modulos/localidades/php/_interfaz.php'"; // siempre va a abm_interfaz
	$vars="'nombre_funcion=agregar_modificar&";
	$vars.="id_system_502=$id_system_502&";	
	$vars.="system_502_circuito='+abm.system_502_circuito.value+'&";			
	$vars.="rela_system_501='+abm.rela_system_501.value";	
	$url_exito="'modulos/localidades/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01'";	

	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");
	



	$cadena='';
	$row = $mysqli -> consulta_SQL("Select * from system_501_localidad ");
	if ($row == TRUE)
	{
		for ( $i=0; $i < count($row); $i++)
		{
			$id_system_501 =		$row[$i]['id_system_501'];
			$system_501_departamento =$row[$i]['system_501_departamento'];
			
			if ($rela_system_501 == $id_system_501)
			{
				$cadena.='<option value="'.$id_system_501.'" SELECTED >'.$system_501_departamento.'</option>';
			}
			else
			{
				$cadena.='<option value="'.$id_system_501.'">'.$system_501_departamento.'</option>';		
			}
		}
	}
	$t->set_var("LOCALIDADES",$cadena);
	


$url_exito="'modulos/localidades/php/home.php'";
$id="'content_seccion'";
$vars_exito="'id_system_01=$id_system_01'";
$t->set_var("funcion_volver","cargar_post($url_exito,$id,$vars_exito)");


$t->set_var("titulo_modulo","$titulo_modulo");	
$t->set_var("boton_modulo","$boton_modulo");
	
$t->pparse("OUT", "ver");
?>
