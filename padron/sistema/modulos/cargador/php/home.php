<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'			=> "home.html",
	'un_cheked'	=> "un_cheked.html",
	'select'		=> "una_opcion.html"
	));
		


$system_503_mesa = isset($_POST['system_503_mesa']) ? $_POST['system_503_mesa'] : NULL;
$id_system_503 = isset($_POST['id_system_503']) ? $_POST['id_system_503'] : NULL;
$reset_mesa = isset($_POST['reset_mesa']) ? $_POST['reset_mesa'] : NULL;
$sesion_system_03_mesa = isset($_SESSION['sesion_system_03_mesa']) ? $_SESSION['sesion_system_03_mesa'] : NULL;
$mesa = "";
$caja_formu = '';
$formu = '	<input type="text" class="form-control" name="" id="" value="'.$sesion_system_03_mesa.'"  placeholder="0000"  maxlength="4" onChange="{funcion_session_mesa}" style="width: 70px; font-size:22px; padding:0px 0px 0px 5px; float:left;" />';
$formu.= '	<h4 class="bi bi-caret-right-square-fill" style=" margin:5px 0px 5px 15px; float:left;"></h4>';
if ( $reset_mesa == 'go' )
{
$_SESSION['sesion_system_03_mesa']="";
$sesion_system_03_mesa ='';
}

// reset
$url6="'modulos/cargador/php/home.php'";
$id6="'content_seccion'";
$vars6="'reset_mesa=go'";
$funcion_reset_mesa = " cargar_post($url6,$id6,$vars6); ";			
		
if ( trim($system_503_mesa) !="" )
{
	$_SESSION['sesion_system_03_mesa'] = $system_503_mesa;
	$mesa = '<h3>'.consulto_escuela($system_503_mesa,$mysqli).' : Mesa '.$system_503_mesa.'</h3>';
	$t->set_var("reset_link",'<h3 onClick="'.$funcion_reset_mesa.'" class="bi bi-arrow-clockwise"  style=" margin:5px 0px 5px 5px; float:right;"></h3>');		
}
else
{	

	if ( $sesion_system_03_mesa !='' )
	{	
		$_SESSION['sesion_system_03_mesa'] = $sesion_system_03_mesa;
		$mesa = '<h3>'.consulto_escuela($sesion_system_03_mesa,$mysqli).' : Mesa '.$sesion_system_03_mesa.'</h3>';
		$t->set_var("reset_link",'<h3 onClick="'.$funcion_reset_mesa.'" class="bi bi-arrow-clockwise"  style=" margin:5px 0px 5px 5px; float:right;"></h3>');			
	}
	else
	{
		$url5="'modulos/cargador/php/home.php'";
		$id5="'content_seccion'";
		$vars5="'system_503_mesa='";
		$funcion_session_mesa = " cargar_post($url5,$id5,$vars5+this.value); ";
	
		$_SESSION['sesion_system_03_mesa']= '';
		$formu = '<input type="text" class="form-control" name="" id="" value=""  placeholder="0000"  maxlength="4" onChange="'.$funcion_session_mesa.'" style="width: 70px; font-size:22px; padding:0px 0px 0px 5px; float:left;" />';
		$formu.= '<h2 class="bi bi-caret-right-square-fill" style=" margin:5px 0px 5px 25px; float:left;"></2>';
		$mesa = '<h3>Indique N&deg; de Mesa : </h3>';
		$caja_formu = $formu.'';
	}
}

$t->set_var("ver_opciones","alt");
if ( $sesion_system_03_mesa != '' and $sesion_system_07 == '3')
{
	$t->set_var("ver_opciones","hide");
}


$t->set_var("sesion_system_03_mesa",$sesion_system_03_mesa);
$t->set_var("titulo_modulo","$mesa");
$t->set_var("caja_formu","$caja_formu");
	
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_503_mesas where system_503_mesa = '$sesion_system_03_mesa' ");
	if($row == true) 	
	{
		
		$id_system_503=			$row[0]['id_system_503'];
		$system_503_mesa=		$row[0]['system_503_mesa'];
		$system_503_escuela=	$row[0]['system_503_escuela'];

			
		$t->set_var("ESCUELAS",'<option value="" SELECTED>'.$system_503_escuela.'</option>');
			
			
			$row = $mysqli -> consulta_SQL("SELECT * FROM system_503_mesas where system_503_escuela = '$system_503_escuela'   order by id_system_503 ASC ");
			if ($row == true)
			{
				for ( $i=0; $i < count($row); $i++)
				{
					$id_system_503=			$row[$i]['id_system_503'];
					$system_503_mesa=		$row[$i]['system_503_mesa'];
					$t->set_var("NOMBRE",	$system_503_mesa);				
					
					if ( $system_503_mesa == $sesion_system_03_mesa )
					{
						$t->set_var("ID","\"$system_503_mesa\" SELECTED ");
					}
					else
					{
						$t->set_var("ID","\"$system_503_mesa\"");		
					}
						
					$t->parse("MESAS","select",true);	
				}
			}

	}






$where=" WHERE system_607_mesa = '$sesion_system_03_mesa' ";	

if ((isset($_POST['system_503_mesa']) ? $_POST['system_503_mesa'] : NULL) != "")
{	
	$system_503_mesa = isset($_POST['system_503_mesa']) ? $_POST['system_503_mesa'] : NULL;
	$where=" WHERE system_607_mesa = '$system_503_mesa'  ";	
	$_SESSION['where_control']=$where;	
}
else
{	
	if ( $where_control!='' )
	{
	$_SESSION['where_control']=$where_control;
	}
	else
	{
	$_SESSION['where_control']=$where;
	}
}
											
$where_control=$_SESSION['where_control'];

//echo $where_control;
// parte del paginador


	$row = $mysqli -> consulta_SQL("Select * from system_607_mesa_orden
						$where_control 
						
						ORDER BY system_607_orden asc
						limit 0,400
						");				
	if($row == true)
	{
		for ( $i=0; $i < count($row); $i++)
		{	
			$id_system_607 =		$row[$i]['id_system_607'];
			$system_607_mesa = 		$row[$i]['system_607_mesa'];
			$system_607_orden =		$row[$i]['system_607_orden'];
			$system_607_dni =		$row[$i]['system_607_dni'];
			
			$row4 = $mysqli -> consulta_SQL("Select * from system_600_votos where system_600_dni = '$system_607_dni' and system_600_estado = '1' ");
			if ($row4 == true)
			{
				$checked_box="checked";	
			}
			else
			{
				$checked_box="";		
			}
			$disabled_box="";
			
			$url="'modulos/cargador/php/_interfaz.php'";
			$vars="'nombre_funcion=voto_seguro&system_607_mesa=$system_607_mesa&system_607_orden=$system_607_orden&system_607_dni=$system_607_dni'";
			$t->set_var("system_600_estado",'<input name="" type="checkbox" value="" onClick="guardar_vars('.$url.','.$vars.');"  style=" width:33px; height:33px;" '.$checked_box.' '.$disabled_box.'/>');	

			
			$t->set_var("system_600_mesa",$system_607_mesa);
			$t->set_var("system_600_orden",$system_607_orden);

				
			$t->parse("LISTADO","un_cheked",true);
		}
	} 
	else 						
	{	
		$t->SET_VAR("LISTADO",'Selecciones escuela y mesa');

	}							
		
		
				

	


	
$t->pparse("OUT", "ver");
?>