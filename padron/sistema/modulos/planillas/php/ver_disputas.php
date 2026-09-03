<?php
session_start();
include "../../../../../lib/template.inc";
include "../../../../../lib/mysql_conect.inc";
include "../../../../php/privilegios.php";
include "../../../../php/funciones.php";


$t = new Template('../templates');
$t->set_file(array(
	'ver'			=> "ver_disputas.html",
	'un_disputa'	=> "un_disputa.html"
	));
	
$t->set_var("titulo_modulo","");
$system_600_dni=$_POST['system_600_dni'];
$t->set_var("system_apellido_nombre_ciudadano",funcion_traer_datos($system_600_dni,$mysqli));
$t->set_var("system_circuito",funcion_traer_circuito($system_600_dni,$mysqli));
$t->set_var("system_600_dni",$system_600_dni);

	$sql=$mysqli->query("Select * from system_600_votos
						WHERE 
						system_600_dni='$system_600_dni'
						
						ORDER BY system_600_time_carga desc
						limit 10
						");				
	$tr = $sql -> num_rows;
	if($tr != '0')
	{
		while ($row = $sql -> fetch_array ())
		{			
			$id_system_600=$row['id_system_600'];
			$rela_system_03=$row['rela_system_03'];
			$rela_system_601=$row['rela_system_601'];
			$t->set_var("system_600_time_carga",$row['system_600_time_carga']);
	
			$sql2=$mysqli->query("Select * from system_601_planillas where id_system_601='$rela_system_601'");				
			if ($row = $sql2 -> fetch_array ())
			{			
				$id_system_601=$row['id_system_601'];
				$rela_system_03=$row['rela_system_03'];
				$system_601_estado=$row['system_601_estado'];
				$t->set_var("id_system_601",$id_system_601);
				$t->set_var("system_apellido_nombre_dirigente",funcion_nombre_apellido($rela_system_03,$mysqli));
				
				$sql3=$mysqli->query("Select * from system_03_usuarios where id_system_03='$rela_system_03'");				
				if ($row = $sql3 -> fetch_array ())
				{
				$rela_system_502=$row['rela_system_502'];
				}
				
				$sql4=$mysqli->query("Select * from system_502_municipios where id_system_502='$rela_system_502'");				
				if ($row = $sql4 -> fetch_array ())
				{
				$t->set_var("system_502_circuito",$row['system_502_circuito']);
				$system_502_localidades=$row['system_502_localidades'];
				}
				
			}
	
	
			if ( $system_07_b == "1" || $candado=='on' )
			{
				$url="'templates/modulos/vseguro/php/abm_interfaz.php'";
				$vars="'nombre_funcion=remoner_dni&";
				$vars.="id_system_600=$id_system_600'";
				$url_exito="'templates/modulos/vseguro/php/ver_disputas.php'";
				$id="'popup'";
				$vars_exito="'system_600_dni=$system_600_dni'";
				$atx="''";
				$msg="'Remover este DNI de este dirigente?'";
				$t->set_var("funcion_remover_disputa","eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);");

			}
			
			// color de fila			
			if ( $color=='1' )
			{
			$color="2";
			$t->set_var("bgcolor",$bgcolor2);
			}
			else
			{
			$color="1";	
			$t->set_var("bgcolor",$bgcolor1);
			}
			
				
			$t->parse("LISTADO","un_disputa",true);
		
		}
	} 
	else 						
	{
		
		$t->SET_VAR("LISTADO",'<tr class="listado"><td colspan="5" align="center">No hay conflictos encontrados...</td></tr>');

	}							




		
$t->pparse("OUT", "ver");
?>