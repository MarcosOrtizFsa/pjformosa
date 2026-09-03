<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'			=> "reporte.html",
	'un_reporte'	=> "un_reporte.html"
	));

$t->set_var("titulo_modulo","Control de progreso");


$id_system_601 = 		isset($_POST['id_system_601']) ? $_POST['id_system_601'] : NULL;
$rela_system_03 = 		isset($_POST['rela_system_03']) ? $_POST['rela_system_03'] : NULL;
$t->set_var("system_apellido_nombre",consulto_nombre_apellido($rela_system_03,$mysqli));
$t->set_var("consulto_contacto_perfil",	consulto_contacto_perfil($rela_system_03,$mysqli));
			
	

	// solo miro los votos sin disputa system_600_disputa = 0				
	$row = $mysqli -> consulta_SQL("Select * from system_600_votos
						where 
						rela_system_601 = '$id_system_601' 
						and
						system_600_disputa = '0'
						
						ORDER BY id_system_600 desc 
						");				
	if($row == true )
	{
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_600 =			$row[$i]['id_system_600'];
			$rela_system_03 =			$row[$i]['rela_system_03'];
			$rela_system_601 =			$row[$i]['rela_system_601'];
			$system_600_dni = 			$row[$i]['system_600_dni'];
			$system_600_apellido_nombre = $row[$i]['system_600_apellido_nombre'];
			$system_600_barrio =		$row[$i]['system_600_barrio'];
			$system_600_domicilio =		$row[$i]['system_600_domicilio'];
			$system_600_orden =			$row[$i]['system_600_orden'];
			$system_600_mesa =			$row[$i]['system_600_mesa'];
			$system_600_circuito =		$row[$i]['system_600_circuito'];
			$system_600_disputa =		$row[$i]['system_600_disputa'];
			$system_600_time_carga =	$row[$i]['system_600_time_carga'];
			$system_600_date_voto =		$row[$i]['system_600_date_voto'];
			$system_600_estado =		$row[$i]['system_600_estado'];	
	
			if ($system_600_estado == '1')
			{
			$t->set_var("system_600_estado",'<button type="button" class="btn btn-success">VOTO</button>');
			}
			else
			{
			$t->set_var("system_600_estado",'<button type="button" class="btn btn-warning">PEND</button>');
			}
			
			
			
			$t->set_var("rela_system_601",$rela_system_601);	
			$t->set_var("system_600_apellido_nombre",$system_600_apellido_nombre);
			$t->set_var("funcion_ver_escuela",funcion_ver_escuela($system_600_mesa,$mysqli));
			$t->set_var("system_600_dni",$system_600_dni);
			$t->set_var("system_600_mesa",$system_600_mesa);
			$t->set_var("system_600_orden","$system_600_orden");
			$t->set_var("system_600_domicilio",$system_600_domicilio);	
			$t->parse("LISTADO","un_reporte",true);
		
		}
	} 
	else 						
	{		
		$t->SET_VAR("LISTADO",'');
	}							

	
	$down = "<a href=\"modulos/checked/php/lista_novoto_txt.php?id_system_601=$id_system_601\" target=\"news\"><img src=\"../image/iconos/page_excel.png\" border=\"0\"></a>&nbsp;&nbsp;";
	$t->set_var("DOWN","$down");
				
	$url="'modulos/checked/php/home.php'";
	$id="'content_seccion'";
	$vars="'id_system_01=$id_system_01'";
	$t->set_var("funcion_volver","cargar_post($url,$id,$vars)");


$t->pparse("OUT", "ver");
?>