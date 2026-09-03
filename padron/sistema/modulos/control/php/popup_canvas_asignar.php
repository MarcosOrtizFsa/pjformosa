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
	'ver'			=> "popup_canvas_asignar.html",
	'una_opcion'	=> "una_opcion.html"
	));


	
	$row = $mysqli -> consulta_SQL("Select system_04_perfil.*, system_03_usuarios.*
			from 
			system_04_perfil, system_03_usuarios 
			where 
			system_04_perfil.rela_system_03 = system_03_usuarios.id_system_03 
			and 
			system_03_usuarios.id_system_03 = '$id_system_03' 
			");
	if ($row == TRUE)
	{
		$id_system_03 = 		$row[0]['id_system_03'];
		$rela_system_07 = 		$row[0]['rela_system_07'];
		$system_03_usuario= 	$row[0]['system_03_usuario'];
		$system_03_cuir = 		$row[0]['system_03_cuir'];	
		$system_03_modo = 		$row[0]['system_03_modo'];
		$rela_system_501 = 		$row[0]['rela_system_501'];
		$system_03_mesa = 		$row[0]['system_03_mesa'];
		$id_system_04 = 		$row[0]['id_system_04'];
		$system_04_nombre = 	$row[0]['system_04_nombre'];
		$system_04_apellido = 	$row[0]['system_04_apellido'];
		$system_04_dni = 		$row[0]['system_04_dni'];
		$system_04_celular = 	$row[0]['system_04_celular'];
		$system_04_email = 		$row[0]['system_04_email'];
	}
	
	

	
	if ( $rela_system_07 == '3' )
	{
	
		$t->set_var("title_canvas","Asignar a ".$system_04_nombre." ".$system_04_apellido);
		$t->set_var("system_03_mesa","Mesa asignada N&deg; ".$system_03_mesa."");
		$t->set_var("ver_reset","alt");
		if ( $system_03_mesa == '0' )
		{
		 $t->set_var("ver_reset","hide");
		}
		
		$row2 = $mysqli -> consulta_SQL("SELECT DISTINCT system_503_escuela FROM system_503_mesas  ");
		if($row2 == true ) 	
		{
			for ( $i=0; $i < count($row2); $i++)
			{
				$system_503_escuela = $row2[$i]['system_503_escuela'];
				$t->set_var("NOMBRE",$system_503_escuela);				
				$t->set_var("ID","\"$system_503_escuela\"");		
				$t->parse("MESAS","una_opcion",true);	
			}	
		}
		else
		{
		$t->set_var("MESAS","<option value=\"\"></option>");
		}
			
		$url5="'modulos/control/php/select_3.php'";
		$id5="'ver_mesas'";
		$vars5="'reset=go&id_system_01=$id_system_01&id_system_03=$id_system_03&system_503_escuela='";
		$t->set_var("funcion_escuela","cargar_post($url5,$id5,$vars5+this.value); ");
		
		
		
		
		
				
		// elimiar
		if ( optener_permisos('B',$id_system_01,$sesion_system_03,$mysqli) == '1' and  $candado=='on' )
		{
			$url="'modulos/control/php/_interfaz.php'";
			$vars="'nombre_funcion=reset_asignar_escuela&";
			$vars.="id_system_03=$id_system_03'";
			$url_exito="'modulos/control/php/popup_canvas_asignar.php'";
			$id="'popup_canvas_left'";
			$vars_exito="'id_system_01=$id_system_01&id_system_03=$id_system_03'";
			$atx="''";
			$msg="'Mover esta escuela asignada?'";
			$t->set_var("funcion_mover_escuela","eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);");
		}
		else
		{
			$t->set_var("funcion_mover_escuela","sin_permisos()");
		}
		
	}
	else
	{
		$t->set_var("title_canvas","".$system_04_nombre." ".$system_04_apellido);
		$t->set_var("system_03_mesa","Solo para Tipo Fiscal!");
		
		$t->set_var("MESAS","...");
	}
	
		

$t->pparse("OUT", "ver");
?>
