<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'			=> "afiliados.html",
	'un_registro'		=> "un_registro.html",
	'select'		=> "una_opcion.html"
	));
	
$t->set_var("titulo_modulo","");

$PAGINAR="";			
$num_filas="10000";	
if ( $mas=='' or $mas=='0' )
{
$LIMITE =" limit 0,$num_filas ";
}
else
{
$num_filas = $mas + $num_filas;
$LIMITE=" limit 0,$num_filas ";
}



$cuento='0';
$id_system_701 = 		isset($_POST['id_system_701']) ? $_POST['id_system_701'] : NULL;
			
	$row = $mysqli -> consulta_SQL("Select * from system_700_afiliados 
						where system_700_estado = '1'
						 
						ORDER BY id_system_700 desc 
						$LIMITE
						");				
	if($row == true)
	{
		
		for ( $i=0; $i < count($row); $i++)
		{				
			$id_system_700 =			$row[$i]['id_system_700'];
			$rela_system_03 =			$row[$i]['rela_system_03'];
			$system_700_dni = 			$row[$i]['system_700_dni'];
			$system_700_apellido = 		$row[$i]['system_700_apellido'];
			$system_700_nombre = 		$row[$i]['system_700_nombre'];
			$system_700_circuito =		$row[$i]['system_700_circuito'];
			$system_700_domicilio =		$row[$i]['system_700_domicilio'];
			$system_700_estado =		$row[$i]['system_700_estado'];
			$system_700_localidad =		$row[$i]['system_700_localidad'];
			$system_700_dpto =			$row[$i]['system_700_dpto'];	
			

			$t->set_var("system_apellido_nombre",$system_700_apellido.', '.$system_700_nombre);
			$t->set_var("system_700_dni",		$system_700_dni);
			$t->set_var("system_700_domicilio",	$system_700_domicilio);
			$t->set_var("system_700_dpto",		$system_700_dpto);	
			$t->set_var("system_700_localidad",	$system_700_localidad);
			$t->set_var("cuento",				$cuento);
				
			$t->parse("LISTADO","un_registro",true);
			$cuento++;
		}
	} 
	else 						
	{
		
		$t->SET_VAR("LISTADO",'<div class="tabl"><li class="fil-100 file-mov-100">...</li></div>');

	}							

	
	$t->set_var("cuento_total",$cuento);	

		
	$url="'modulos/afiliados/php/home.php'";
	$id="'content_seccion'";
	$vars="'id_system_01=$id_system_01'";
	$t->set_var("funcion_volver","cargar_post($url,$id,$vars)");


	// buscador
	$urlb="'modulos/afiliados/php/afiliados.php'";
	$idb="'content_seccion'";
	$varsb="'id_system_01=$id_system_01&id_system_701=$id_system_701&variable_tipo='+busqueda.variable_tipo.value+'&variable_buscar='+busqueda.variable_buscar.value";
	
	$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
	$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");

	$pagExc1='<a href="modulos/afiliados/php/lista_no_afiliados_csv.php" target="news" ><img src="../image/iconos/page_excel.png" border="0"> lista No afiliados</a>';	
	$t->set_var("EXCELES","$pagExc1");	
				
$t->pparse("OUT", "ver");
?>