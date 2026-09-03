<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'				=> "ver_ciudadanos.html"
	));

$variable_buscar = isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL;
$system_502_circuito = isset($_POST['system_502_circuito']) ? $_POST['system_502_circuito'] : NULL;
$t->set_var("titulo_modulo","Circuito $system_502_circuito");
if ( $reset =='go' )
{
	$_SESSION['where_control']="";
}
$where = " ";	

if ( $variable_buscar != ""  )
{	
	$variable_buscar=formatear_dni(trim($variable_buscar));
	
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
		$where = "Select * from system_2000_padron_0  where system_2000_dni like '%$variable_buscar%' ";
		}

		 
		$LIMITE = " limit 10 ";    	
    } 
	else 
	{
      $where.= " where system_2000_apellido like '%$variable_buscar%'  ";
    }	
		
	$_SESSION['where_control']=$where;
	
}
else
{	
	if ( (isset($_SESSION['where_control']) ? $_SESSION['where_control'] : NULL) != '' )
	{
		$where_control = $_SESSION['where_control'];
	}
	else
	{
		$_SESSION['where_control'] = '';
	}
}


$where_control = isset($_SESSION['where_control']) ? $_SESSION['where_control'] : NULL;
//echo $where_control;

$resultado='';
if ( $where_control != '' )
{
	$row1 = $mysqli -> consulta_SQL("$where_control ");
	if ($row1 == TRUE)
	{	
		
		$system_2000_dni = $row1[0]['system_2000_dni'];
		$url="'modulos/localidades/php/perfil_am.php'";
		$id="'fila_resultado'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&system_2000_dni=$system_2000_dni'";			
		$funcion_editar_r = "cargar_post($url,$id,$vars)";
			
		$resultado.='<div class="tabl  bg-warning" id="fila_resultado">';
		$resultado.='	<li class="fil-10 file-mov-30 strong" onclick="'.$funcion_editar_r.'">';
		$resultado.='		'.convert_dni($system_2000_dni);
		$resultado.='	</li> ';
		$resultado.='	<li class="fil-30 file-mov-70" onclick="'.$funcion_editar_r.'">';
		$resultado.='		'.$row1[0]['system_2000_apellido'].' '.$row1[0]['system_2000_nombre'];
		$resultado.='	</li> ';
		$resultado.='	<li class="fil-60 file-mov-100 minitex">';
		$resultado.='		'.$row1[0]['system_2000_domicilio'];
		$resultado.='	</li> ';
		$resultado.='</div>';
			
	
					
	}
}
$t->set_var("resultado_busqueda",$resultado);

$nuro = '0';
$cadena='';			
$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_0  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$system_2000_dni = $row[$i]['system_2000_dni'];
		$url="'modulos/localidades/php/perfil_am.php'";
		$id="'fila_$system_2000_dni'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&system_2000_dni=$system_2000_dni'";			
		$funcion_editar = "cargar_post($url,$id,$vars)";
			
		$cadena.='<div class="tabl" id="fila_'.$system_2000_dni.'">';
		$cadena.='	<li class="fil-10 file-mov-30 strong" onclick="'.$funcion_editar.'">';
		$cadena.='		'.convert_dni($system_2000_dni);
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-30 file-mov-70" onclick="'.$funcion_editar.'">';
		$cadena.='		'.$row[$i]['system_2000_apellido'].' '.$row[$i]['system_2000_nombre'];
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-60 file-mov-100 minitex">';
		$cadena.='		'.$row[$i]['system_2000_domicilio'];
		$cadena.='	</li> ';
		$cadena.='</div>';
		$nuro++;

	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_1  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$system_2000_dni = $row[$i]['system_2000_dni'];
		$url="'modulos/localidades/php/perfil_am.php'";
		$id="'fila_$system_2000_dni'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&system_2000_dni=$system_2000_dni'";			
		$funcion_editar = "cargar_post($url,$id,$vars)";
			
		$cadena.='<div class="tabl" id="fila_'.$system_2000_dni.'">';
		$cadena.='	<li class="fil-10 file-mov-30 strong" onclick="'.$funcion_editar.'">';
		$cadena.='		'.convert_dni($system_2000_dni);
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-30 file-mov-70" onclick="'.$funcion_editar.'">';
		$cadena.='		'.$row[$i]['system_2000_apellido'].' '.$row[$i]['system_2000_nombre'];
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-60 file-mov-100 minitex">';
		$cadena.='		'.$row[$i]['system_2000_domicilio'];
		$cadena.='	</li> ';
		$cadena.='</div>';
		$nuro++;
	}			
}	

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_2  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$system_2000_dni = $row[$i]['system_2000_dni'];
		$url="'modulos/localidades/php/perfil_am.php'";
		$id="'fila_$system_2000_dni'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&system_2000_dni=$system_2000_dni'";			
		$funcion_editar = "cargar_post($url,$id,$vars)";
			
		$cadena.='<div class="tabl" id="fila_'.$system_2000_dni.'">';
		$cadena.='	<li class="fil-10 file-mov-30 strong" onclick="'.$funcion_editar.'">';
		$cadena.='		'.convert_dni($system_2000_dni);
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-30 file-mov-70" onclick="'.$funcion_editar.'">';
		$cadena.='		'.$row[$i]['system_2000_apellido'].' '.$row[$i]['system_2000_nombre'];
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-60 file-mov-100 minitex">';
		$cadena.='		'.$row[$i]['system_2000_domicilio'];
		$cadena.='	</li> ';
		$cadena.='</div>';
		$nuro++;
	}			
}	

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_3  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$system_2000_dni = $row[$i]['system_2000_dni'];
		$url="'modulos/localidades/php/perfil_am.php'";
		$id="'fila_$system_2000_dni'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&system_2000_dni=$system_2000_dni'";			
		$funcion_editar = "cargar_post($url,$id,$vars)";
			
		$cadena.='<div class="tabl" id="fila_'.$system_2000_dni.'">';
		$cadena.='	<li class="fil-10 file-mov-30 strong" onclick="'.$funcion_editar.'">';
		$cadena.='		'.convert_dni($system_2000_dni);
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-30 file-mov-70" onclick="'.$funcion_editar.'">';
		$cadena.='		'.$row[$i]['system_2000_apellido'].' '.$row[$i]['system_2000_nombre'];
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-60 file-mov-100 minitex">';
		$cadena.='		'.$row[$i]['system_2000_domicilio'];
		$cadena.='	</li> ';
		$cadena.='</div>';
		$nuro++;
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_4  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$system_2000_dni = $row[$i]['system_2000_dni'];
		$url="'modulos/localidades/php/perfil_am.php'";
		$id="'fila_$system_2000_dni'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&system_2000_dni=$system_2000_dni'";			
		$funcion_editar = "cargar_post($url,$id,$vars)";
			
		$cadena.='<div class="tabl" id="fila_'.$system_2000_dni.'">';
		$cadena.='	<li class="fil-10 file-mov-30 strong" onclick="'.$funcion_editar.'">';
		$cadena.='		'.convert_dni($system_2000_dni);
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-30 file-mov-70" onclick="'.$funcion_editar.'">';
		$cadena.='		'.$row[$i]['system_2000_apellido'].' '.$row[$i]['system_2000_nombre'];
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-60 file-mov-100 minitex">';
		$cadena.='		'.$row[$i]['system_2000_domicilio'];
		$cadena.='	</li> ';
		$cadena.='</div>';
		$nuro++;
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_5  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$system_2000_dni = $row[$i]['system_2000_dni'];
		$url="'modulos/localidades/php/perfil_am.php'";
		$id="'fila_$system_2000_dni'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&system_2000_dni=$system_2000_dni'";			
		$funcion_editar = "cargar_post($url,$id,$vars)";
			
		$cadena.='<div class="tabl" id="fila_'.$system_2000_dni.'">';
		$cadena.='	<li class="fil-10 file-mov-30 strong" onclick="'.$funcion_editar.'">';
		$cadena.='		'.convert_dni($system_2000_dni);
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-30 file-mov-70" onclick="'.$funcion_editar.'">';
		$cadena.='		'.$row[$i]['system_2000_apellido'].' '.$row[$i]['system_2000_nombre'];
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-60 file-mov-100 minitex">';
		$cadena.='		'.$row[$i]['system_2000_domicilio'];
		$cadena.='	</li> ';
		$cadena.='</div>';
		$nuro++;
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_6  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$system_2000_dni = $row[$i]['system_2000_dni'];
		$url="'modulos/localidades/php/perfil_am.php'";
		$id="'fila_$system_2000_dni'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&system_2000_dni=$system_2000_dni'";			
		$funcion_editar = "cargar_post($url,$id,$vars)";
			
		$cadena.='<div class="tabl" id="fila_'.$system_2000_dni.'">';
		$cadena.='	<li class="fil-10 file-mov-30 strong" onclick="'.$funcion_editar.'">';
		$cadena.='		'.convert_dni($system_2000_dni);
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-30 file-mov-70" onclick="'.$funcion_editar.'">';
		$cadena.='		'.$row[$i]['system_2000_apellido'].' '.$row[$i]['system_2000_nombre'];
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-60 file-mov-100 minitex">';
		$cadena.='		'.$row[$i]['system_2000_domicilio'];
		$cadena.='	</li> ';
		$cadena.='</div>';
		$nuro++;
	}			
}


$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_7  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$system_2000_dni = $row[$i]['system_2000_dni'];
		$url="'modulos/localidades/php/perfil_am.php'";
		$id="'fila_$system_2000_dni'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&system_2000_dni=$system_2000_dni'";			
		$funcion_editar = "cargar_post($url,$id,$vars)";
			
		$cadena.='<div class="tabl" id="fila_'.$system_2000_dni.'">';
		$cadena.='	<li class="fil-10 file-mov-30 strong" onclick="'.$funcion_editar.'">';
		$cadena.='		'.convert_dni($system_2000_dni);
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-30 file-mov-70" onclick="'.$funcion_editar.'">';
		$cadena.='		'.$row[$i]['system_2000_apellido'].' '.$row[$i]['system_2000_nombre'];
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-60 file-mov-100 minitex">';
		$cadena.='		'.$row[$i]['system_2000_domicilio'];
		$cadena.='	</li> ';
		$cadena.='</div>';
		$nuro++;
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_8  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$system_2000_dni = $row[$i]['system_2000_dni'];
		$url="'modulos/localidades/php/perfil_am.php'";
		$id="'fila_$system_2000_dni'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&system_2000_dni=$system_2000_dni'";			
		$funcion_editar = "cargar_post($url,$id,$vars)";
			
		$cadena.='<div class="tabl" id="fila_'.$system_2000_dni.'">';
		$cadena.='	<li class="fil-10 file-mov-30 strong" onclick="'.$funcion_editar.'">';
		$cadena.='		'.convert_dni($system_2000_dni);
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-30 file-mov-70" onclick="'.$funcion_editar.'">';
		$cadena.='		'.$row[$i]['system_2000_apellido'].' '.$row[$i]['system_2000_nombre'];
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-60 file-mov-100 minitex">';
		$cadena.='		'.$row[$i]['system_2000_domicilio'];
		$cadena.='	</li> ';
		$cadena.='</div>';
		$nuro++;
	}			
}

$row = $mysqli -> consulta_SQL("Select * from system_2000_padron_9  where system_2000_circuito  = '$system_502_circuito' ");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$system_2000_dni = $row[$i]['system_2000_dni'];
		$url="'modulos/localidades/php/perfil_am.php'";
		$id="'fila_$system_2000_dni'";
		$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&system_2000_dni=$system_2000_dni'";			
		$funcion_editar = "cargar_post($url,$id,$vars)";
			
		$cadena.='<div class="tabl" id="fila_'.$system_2000_dni.'">';
		$cadena.='	<li class="fil-10 file-mov-30 strong" onclick="'.$funcion_editar.'">';
		$cadena.='		'.convert_dni($system_2000_dni);
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-30 file-mov-70" onclick="'.$funcion_editar.'">';
		$cadena.='		'.$row[$i]['system_2000_apellido'].' '.$row[$i]['system_2000_nombre'];
		$cadena.='	</li> ';
		$cadena.='	<li class="fil-60 file-mov-100 minitex">';
		$cadena.='		'.$row[$i]['system_2000_domicilio'];
		$cadena.='	</li> ';
		$cadena.='</div>';
		$nuro++;
	}			
}


$t->set_var("LISTADO",$cadena);
$t->set_var("cuento",$nuro);



	// buscador
	$url="'modulos/localidades/php/ver_ciudadanos.php'";
	$id="'content_seccion'";
	$vars="'id_system_01=$id_system_01&system_502_circuito=$system_502_circuito&reset=go&";		
	$vars.="variable_buscar='+busqueda.variable_buscar.value";
	$t->set_var("funcion_busqueda","cargar_post($url,$id,$vars)");
	
	
	
$pagExc='<a href="modulos/padron/php/padron_x_circuito_csv.php?system_502_circuito='.$system_502_circuito.'" target="news"  ><h2 class="bi bi-file-earmark-spreadsheet"></h2></a> ';	
$t->set_var("EXCEL",$pagExc);
		
$url_exito="'modulos/localidades/php/home.php'";
$id="'content_seccion'";
$vars_exito="'id_system_01=$id_system_01'";
$t->set_var("funcion_volver","cargar_post($url_exito,$id,$vars_exito)");
	
$t->pparse("OUT", "ver");
?>
