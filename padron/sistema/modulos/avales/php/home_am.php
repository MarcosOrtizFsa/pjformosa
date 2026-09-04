<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'			=> "home_am.html",
	'select'		=> "una_opcion.html"
	));
	
if ($sesion_system_07 == '1' or $sesion_system_07 == '5' or $sesion_system_07 == '6')// es un dirigente 
{
$where="  where id_system_03 = '$sesion_system_03' ";	
}
else
{
$where="  where rela_system_07 IN ('2','3','4','5','6') and system_03_estado = '1'  ";	
}

$id_system_701 = 		isset($_POST['id_system_701']) ? $_POST['id_system_701'] : NULL;
			

	$row = $mysqli -> consulta_SQL("Select * from system_701_folio  where id_system_701 = '$id_system_701' ");				
	if ($row == true)
	{			
		$id_system_701 =	$row[0]['id_system_701'];
		$rela_system_03 =	$row[0]['rela_system_03'];
		$rela_system_703 =	$row[0]['rela_system_703'];
		$system_701_estado =$row[0]['system_701_estado'];
		$system_701_num =	$row[0]['system_701_num'];
	}
	else
	{
		$id_system_701 =	'';
		$rela_system_03 =	'';
		$rela_system_703 =	'';
		$system_701_estado ='';
		$system_701_num =	'';
	}
	
	$t->set_var("system_701_num",$system_701_num);

	$url="'modulos/afiliados/php/_interfaz.php'";
	$vars="'nombre_funcion=nuevo_folio&system_701_checked=$system_checked&";
	$vars.="rela_system_703='+abm.rela_system_703.value+'&";
	$vars.="system_701_num='+abm.system_701_num.value+'&";
	$vars.="rela_system_03='+abm.rela_system_03.value";

	$url_exito="'modulos/afiliados/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01'";	
	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");

	
	$row = $mysqli -> consulta_SQL("Select * from system_03_usuarios $where order by id_system_03 desc ");
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{	
			$id_system_03 =			$row[$i]['id_system_03'];
			//$system_03_usuario =	$row[$i]['system_03_usuario'];
			$t->set_var("NOMBRE",consulto_nombre_apellido($id_system_03,$mysqli));
			$t->set_var("ID","\"$id_system_03\"");		
			$t->parse("DIRIGENTES","select",true);
		}	
	}
	else
	{
	$t->SET_VAR("DIRIGENTES","<option value=''>No hay registros...</option>");
	}
	
	$cadena="";
	$row2 = $mysqli -> consulta_SQL("Select * from system_703_sede order by id_system_703 asc ");
	if ($row2 == TRUE)
	{
		for ( $i=0; $i < count($row2); $i++)
		{
			$id_system_703 =		$row2[$i]['id_system_703'];
			$system_703_procedencia =	$row2[$i]['system_703_procedencia'];
			
			if ($rela_system_703 == $id_system_703)
			{
				$cadena.="<option value='$id_system_703' SELECTED >$system_703_procedencia</option>";
			}
			else
			{
				$cadena.="<option value='$id_system_703'>$system_703_procedencia</option>";		
			}
		}
	}
	$t->set_var("SEDE",$cadena);
	
		
	$url="'modulos/afiliados/php/home.php'";
	$id="'content_seccion'";
	$vars="'id_system_01=$id_system_01'";
	$t->set_var("funcion_volver","cargar_post($url,$id,$vars)");
			
$t->pparse("OUT", "ver");
?>