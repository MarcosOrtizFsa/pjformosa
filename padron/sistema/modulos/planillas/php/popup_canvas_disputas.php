<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'			=> "popup_canvas_disputas.html",
	'un_disputa'	=> "un_disputa.html"
	));




$system_600_dni = 		isset($_POST['system_600_dni']) ? $_POST['system_600_dni'] : NULL;
/*$t->set_var("system_apellido_nombre_ciudadano",funcion_traer_datos($system_600_dni,$mysqli));
$t->set_var("system_circuito",funcion_traer_circuito($system_600_dni,$mysqli));*/

$t->set_var("title_canvas",'Disputas DNI: <span class="dni">'.$system_600_dni.'</span>');


	$row = $mysqli -> consulta_SQL("Select * from system_600_votos
						WHERE 
						system_600_dni = '$system_600_dni'
						
						ORDER BY system_600_time_carga desc
						limit 10
						");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_600=				$row[$i]['id_system_600'];
			$rela_system_03=			$row[$i]['rela_system_03'];
			$rela_system_601=			$row[$i]['rela_system_601'];
			$t->set_var("system_600_time_carga",$row[$i]['system_600_time_carga']);
			$system_600_disputa =		$row[$i]['system_600_disputa'];
			
			
				
			// on off
			if ( optener_permisos('E',$id_system_01,$sesion_system_03,$mysqli) == '1'  and $candado=='on' )
			{	
				$url="'modulos/planillas/php/_interfaz.php'";
				$vars="'nombre_funcion=disputa_ganada&id_system_600=$id_system_600&system_600_disputa=$system_600_disputa'";
				$url_exito="'modulos/planillas/php/popup_canvas_disputas.php'";
				$id="'popup_canvas_left'";
				$vars_exito="'id_system_01=$id_system_01&system_600_dni=$system_600_dni'";
				$on_off_disputa = "guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito);";	
			}
			else
			{
				$on_off_disputa = "";
			}
			
			
			if ( optener_permisos('B',$id_system_01,$sesion_system_03,$mysqli) == '1' and $candado=='on' )
			{
				$url="'modulos/planillas/php/_interfaz.php'";
				$vars="'nombre_funcion=remoner_dni&";
				$vars.="id_system_600=$id_system_600'";
				$url_exito="'modulos/planillas/php/popup_canvas_disputas.php'";
				$id="'popup_canvas_left'";
				$vars_exito="'id_system_01=$id_system_01&system_600_dni=$system_600_dni'";
				$atx="''";
				$msg="'Remover este DNI de este dirigente?'";
				$t->set_var("funcion_remover_disputa","eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);");

			}
			else
			{
				$t->set_var("funcion_remover_disputa","sin_permisos()");
			}
		
			if ($system_600_disputa == '0')
			{
				$t->set_var("system_600_disputa",'<i onclick="'.$on_off_disputa.'" class="bi bi-check-circle-fill" style="color:#006600; cursor:pointer;" title="Original"></i>');
			}
			else
			{
				$t->set_var("system_600_disputa",'<i onclick="'.$on_off_disputa.'" class="bi bi-check-circle-fill" style="color:red; cursor:pointer;" title="Repetido"></i>');
			}
			
			$row2 = $mysqli -> consulta_SQL("Select * from system_601_planillas where id_system_601='$rela_system_601'");				
			if ($row2 == true)
			{			
				$id_system_601 =		$row2[0]['id_system_601'];
				$rela_system_03 =		$row2[0]['rela_system_03'];
				$system_601_estado =	$row2[0]['system_601_estado'];
				
				$t->set_var("id_system_601",$id_system_601);
				$t->set_var("system_apellido_nombre_dirigente", consulto_perfil($rela_system_03,$mysqli));

				
			}
	
	
			
		
			$t->parse("LISTADO","un_disputa",true);
		}
	} 
	else 						
	{
		$t->SET_VAR("LISTADO",'');
	}							




$t->pparse("OUT", "ver");
?>
