<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'		=> "home.html",
	'un_padron'			=> "un_padron.html",
	'select'		=> "una_opcion.html"
	));



$variable_buscar = 	isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL;
$reset = 	isset($_POST['reset']) ? $_POST['reset'] : NULL;
$mesa = 		'';
$orden = 		'';
$tipo_dni = 	'';
$clase = 		'';
if ( $reset =='go' )
{
	$_SESSION['where_control']="";
}

if ( $variable_buscar =='' )
{
	$_SESSION['where_control']="";
}

$where="";	
$LIMITE = " limit 0 ";
if ( $variable_buscar !="" )
{	
	$variable_buscar=formatear_dni($variable_buscar);
	
	if (ctype_digit($variable_buscar)) 
	{	
		$digit_dni = substr("$variable_buscar", -1);
		
		if ($digit_dni == 1)
		{
		$where = "Select * from system_2000_padron_1  where system_2000_dni like '%$variable_buscar%' ";
		}
		else
		if ($digit_dni == 2)
		{
		$where = "Select * from system_2000_padron_2  where system_2000_dni like '%$variable_buscar%' ";
		}
		else
		if ($digit_dni == 3)
		{
		$where ="Select * from system_2000_padron_3  where system_2000_dni like '%$variable_buscar%' ";
		}
		else
		if ($digit_dni == 4)
		{
		$where = "Select * from system_2000_padron_4  where system_2000_dni like '%$variable_buscar%' ";
		}
		else
		if ($digit_dni == 5)
		{
		$where = "Select * from system_2000_padron_5  where system_2000_dni like '%$variable_buscar%' ";
		}
		else
		if ($digit_dni == 6)
		{
		$where = "Select * from system_2000_padron_6  where system_2000_dni like '%$variable_buscar%' ";
		}
		else
		if ($digit_dni == 7)
		{
		$where = "Select * from system_2000_padron_7  where system_2000_dni like '%$variable_buscar%' ";
		}
		else
		if ($digit_dni == 8)
		{
		$where = "Select * from system_2000_padron_8  where system_2000_dni like '%$variable_buscar%' ";
		}
		else
		if ($digit_dni == 9)
		{
		$where = "Select * from system_2000_padron_9  where system_2000_dni like '%$variable_buscar%' ";
		}
		else
		{
		$where = "Select * from system_2000_padron_0  where system_2000_dni = '$variable_buscar' ";
		}
	
    } 
	else 
	{
      $where.= "";
    }	
		
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


	
	$cadena = '';
	$row = $mysqli -> consulta_SQL("$where_control ");				
	if($row == true)
	{					
		$system_2000_dni = 		$row[0]['system_2000_dni'];
		$system_2000_crto = 	$row[0]['system_2000_crto'];
		$system_2000_domicilio = $row[0]['system_2000_domicilio'];
		$system_2000_crto = 	$row[0]['system_2000_crto'];
		$system_2000_mesa = 	$row[0]['system_2000_mesa'];
		$system_2000_orden = 	$row[0]['system_2000_orden'];
		
		  	 	
		
		$dat = explode('@',mesa_escuela($system_2000_mesa,$mysqli));
		$system_504_escuela = 		$dat[1];
		$system_504_dpto = 			$dat[2];
		$system_504_localidad = 	$dat[3];
		

		
		$titulo_modulo = 'DNI: <span class="dni">'.$system_2000_dni.'</strong>';
	
		$cadena.= '<div class="tabl">';
		$cadena.= '	<li class="fil-30 file-mov-100">';
		$cadena.= '		<h5><span class="dni" >'.$system_2000_dni.'</span></h5>';
		$cadena.= '		'.$row[0]['system_2000_apellido_nombre'];
		$cadena.= '	</li>';		
		$cadena.= '	<li class="fil-30 file-mov-100">';
		$cadena.= '		'.$row[0]['system_2000_domicilio'];
		$cadena.= '		<div class="minitex">'.localidad_por_circuito($system_2000_crto,$mysqli).' - Crto.: '.$system_2000_crto.'</div>';
		$cadena.= '	</li>';
		$cadena.= '	<li class="fil-40 file-mov-100">';
		$cadena.= '		Mesa: '.$system_2000_mesa.' - Orden: '.$system_2000_orden.' - Esc: '.$system_504_escuela.'';
		$cadena.= '		<div class="minitex">'.$system_504_localidad.'</div>';
		$cadena.= '	</li>';	
		$cadena.= '</div>';	
	} 
	else 						
	{
		$titulo_modulo = "Consultar Padr&oacute;n General";	
		if ( $variable_buscar !="" )
		{
			$cadena.= '<div class="tabl">';
			$cadena.= '	<li class="fil-100 file-mov-100 align-center">';
			$cadena.= '		<br><br><br>';
			$cadena.= '		<h5>No se encontro resultados...</h5>';
			$cadena.= '		<br><br><br>';
			$cadena.= '	</li>';		
			$cadena.= '</div>';		
		
		}
		else
		{
			$cadena.= '<div class="tabl">';
			$cadena.= '	<li class="fil-100 file-mov-100 align-center">';
			$cadena.= '		<br><br><br>';
			$cadena.= '		<h5>Haga una b&uacute;squeda con el DNI</h5>';
			$cadena.= '		<br><br><br>';
			$cadena.= '	</li>';		
			$cadena.= '</div>';		
		}

	
	}	
	
	$t->set_var("PERFIL_PADRON",$cadena);	
	$t->set_var("titulo_modulo",$titulo_modulo);

	

	// buscador
	$urlb="'modulos/padron/php/home.php'";
	$idb="'content_seccion'";
	$varsb="'reset=go&variable_buscar='+busqueda.variable_buscar.value";
	$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
	$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");


	if (  optener_permisos('A',$id_system_01,$sesion_system_03,$mysqli) == '1' and  $root_candado == 'on' )
	{

		
		
		
		$url="'modulos/padron/php/cargador.php'";	
		$comando_camara = "abrir_popup($url)";
		$opciones_menu = '<button type="button" class="btn btn-success btn-sm" style="font-size:12px;  margin:0px; padding:4px 10px 4px 10px;" onclick="'.$comando_camara.'">ACTUALIZAR</button>';
		$t->set_var("opciones_menu",$opciones_menu);

						
	}
	else
	{
		$t->set_var("funcion_cargar_archivo","");
		$t->set_var("totales","");
	}
	





$t->pparse("OUT", "ver");
?>