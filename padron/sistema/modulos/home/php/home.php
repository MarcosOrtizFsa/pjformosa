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
	'un_escrutinio'	=> "un_escrutinio.html",
	'select'		=>"una_opcion.html"
	));


function optengo_circuitos_distrito($ver_circuito,$mysqli)
{
	$cadena = '';
	/*$row = $mysqli -> consulta_SQL("SELECT DISTINCT system_600_circuito from system_600_votos WHERE rela_system_601 != '0' order by system_600_circuito asc");
	if ( $row == true )
	{
		for ( $i=0; $i < count($row); $i++)
		{	
			$system_600_circuito =	$row[$i]['system_600_circuito'];
			if ( $ver_circuito == $system_600_circuito)
			{
				$cadena.="<option value='$system_600_circuito' SELECTED >Circuito $system_600_circuito</option>";
			}
			else
			{
				$cadena.="<option value='$system_600_circuito'>Circuito $system_600_circuito</option>";		
			}		
		}	
	}*/
	
	$circuitos = array('1'=>"Circuito 1", '2'=>"Circuito 2", '3'=>"Circuito 3", '4'=>"Circuito 4", '5'=>"Circuito 5", '6'=>"Circuito 6", '7'=>"Circuito 7", '8'=>"Circuito 8", '9'=>"Circuito 9", '10'=>"Circuito 10", '11'=>"Circuito 11" ); 
	while ($select_modo = current($circuitos)) 
	{
		if (key($circuitos) == $ver_circuito) 
		{
			$cadena.='<option value="'.key($circuitos).'" SELECTED >'.$select_modo.'</option>';
		}
		else
		{
			$cadena.='<option value="'.key($circuitos).'" >'.$select_modo.'</option>';
		}
		next($circuitos);
	}
	return $cadena;	
}



$t->set_var("titulo_modulo",$system_08_tema);
$ver_modo =		isset($_POST['ver_modo']) 	? $_POST['ver_modo'] : NULL;
$ver_circuito = trim(isset($_POST['ver_circuito']) 	? $_POST['ver_circuito'] : NULL);
$and = '';
if ($ver_circuito == '0')
{
	$_SESSION['session_circuito'] = "";
	$ver_circuito='';
}

$session_circuito = '';

if ($ver_circuito != '' )
{
	$_SESSION['session_circuito'] = isset($_POST['ver_circuito']) 	? $_POST['ver_circuito'] : NULL;
	$session_circuito = $_SESSION['session_circuito'];
}
else
{	
	if ( (isset($_SESSION['session_circuito']) ? $_SESSION['session_circuito'] : NULL) !='' )
	{
		$session_circuito = $_SESSION['session_circuito'];
	}
	else
	{
		$_SESSION['session_circuito'] = '';
		$session_circuito = '';	
	}
}

if ($session_circuito != '')
{
	$and = " and system_600_circuito = '$session_circuito' ";
}
//echo $session_circuito;

$id_system_503="0";
$LIMITE = "limit 10 ";
$ver_circuito='';	
	
	//$total_padron = 479880; // total padron
	$total_padron = $system_08_total_objetivo;
	// VOTOS TOTALES
	$row = $mysqli -> consulta_SQL("Select COUNT(*) as pendientes from system_600_votos 
	where 
	rela_system_03 != '0' 
	and 
	system_600_estado IN ('0','1')	
	and 
	system_600_disputa = '0' 
	$and 													
	");
	if ($row == TRUE)
	{		
		$total_voto_pendiente = $row[0]['pendientes'];
	}
	
	$row2 = $mysqli -> consulta_SQL("Select COUNT(*) as seguros from system_600_votos 
	where 
	rela_system_03 != '0' 
	and 
	system_600_estado = '1'	
	and 
	system_600_disputa = '0' 
	$and 														
	");
	if ($row2 == TRUE)
	{		
		$total_voto_seguro = $row2[0]['seguros'];
	}
	
	$row3 = $mysqli -> consulta_SQL("Select COUNT(*) as poblacion from system_600_votos 
	where 
	system_600_estado = '1'	
	and 
	system_600_disputa != '1' 											
	");
	if ($row3 == TRUE)
	{		
		$total_voto_poblacion = $row3[0]['poblacion'];
	}

	$barrita = porcentual($total_voto_poblacion,$total_padron);
	$cadena='<div style="width:100%; float:none; display: table;">';
	$cadena.='	<div style="width:75%; float:left; display: table; text-align:left;">';
	$cadena.='		<h4>General</h4>';
	$cadena.='	</div>';
	$cadena.='	<div style="width:25%; float:left; display: table; text-align:right;">';
	$cadena.='		<h4>'.$total_voto_poblacion.' de '.$total_padron.'</h4>';
	$cadena.='	</div>';	
	$cadena.='	<div style="width:100%; float:none; display: table;">';
	$cadena.='		<div style="background: #AED7FF; width:100%; height: 30px; border:0px solid #999;" align="left">';
	$cadena.='			<div style="background:#3366ff; width:'.$barrita.'%; height: 30px; padding:4px; text-align:right; font-size:13px; color:#fff;">'.$barrita.'%&nbsp;</div>';
	$cadena.='		</div>';
	$cadena.='	</div>';
	$cadena.='</div>';
	
	$t->set_var("PROGRESO_GENERAL",$cadena);

	$barrita3 = porcentual($total_voto_seguro,$total_padron);
	$cadena='<div style="width:100%; float:none; display: table;">';
	$cadena.='	<div style="width:75%; float:left; display: table; text-align:left;">';
	$cadena.='		<h4>Seguros de general</h4>';
	$cadena.='	</div>';
	$cadena.='	<div style="width:25%; float:left; display: table; text-align:right;">';
	$cadena.='		<h4>'.$total_voto_seguro.' de '.$total_padron.'</h4>';
	$cadena.='	</div>';	
	$cadena.='	<div style="width:100%; float:none; display: table;">';
	$cadena.='		<div style="background: #AED7FF; width:100%; height: 30px; border:0px solid #999;" align="left">';
	$cadena.='			<div style="background:#3366ff; width:'.$barrita3.'%; height: 30px; padding:4px; text-align:right; font-size:13px; color:#fff;">'.$barrita3.'%&nbsp;</div>';
	$cadena.='		</div>';
	$cadena.='	</div>';
	$cadena.='</div>';
	
	$t->set_var("PROGRESO_SEGUROS_GENERAL",$cadena);
	
	
		// por circuito
	$url3="'modulos/home/php/home.php'";
	$id3="'content_seccion'";
	$vars3="'id_system_01=$id_system_01&ver_circuito='";
	$funcion_selector_circuito = "cargar_post($url3,$id3,$vars3+this.value); ";	
	
	
	$barrita2 = porcentual($total_voto_seguro,$total_voto_pendiente);
	$cadena='<div style="width:100%; float:none; display: table;">';
	$cadena.='	<div style="width:75%; float:left; display: table; text-align:left;">';
	$cadena.='		<h4 style="float: left;">Votos seguros: </h4>';
	
	$cadena.='		<select class="" style="width:150px; margin-left:10px;  font-size:20px; float: left;" onChange="'.$funcion_selector_circuito.'">';
	$cadena.='		<option value="0">Todos</option>';	
	$cadena.='		'.optengo_circuitos_distrito($session_circuito,$mysqli);				
	$cadena.='		</select>';
	
	
	$cadena.='	</div>';
	$cadena.='	<div style="width:25%; float:left; display: table; text-align:right;">';
	$cadena.='		<h4>'.$total_voto_seguro.' de '.$total_voto_pendiente.'</h4>';
	$cadena.='	</div>';	
	$cadena.='	<div style="width:100%; float:none; display: table;">';
	$cadena.='		<div style="background: #AED7FF; width:100%; height: 30px; border:0px solid #999;" align="left">';
	$cadena.='			<div style="background:#3366ff; width:'.$barrita2.'%; height: 30px; padding:4px; text-align:right; font-size:13px; color:#fff;">'.$barrita2.'%&nbsp;</div>';
	$cadena.='		</div>';
	$cadena.='	</div>';
	$cadena.='</div>';
	
	$t->set_var("PROGRESO_SEGUROS",$cadena);
	

	
	
	
	
	
	
	
	
	
	
	/*$cadena = '';
	$row = $mysqli -> consulta_SQL("Select * from system_602_escrutinio order by id_system_602 asc ");				
	if ($row == true)
	{	
		for ( $i=0; $i < count($row); $i++)
		{		
			$id_system_602 = 			$row[$i]['id_system_602'];
			$system_602_sublema=		$row[$i]['system_602_sublema'];
			$system_602_orden=			$row[$i]['system_602_orden'];	
	
			$row4 = $mysqli -> consulta_SQL("Select * from system_605_totales where system_605_mesa='0' and rela_system_602 = '$id_system_602'");				
			if ($row4 == true)
			{			
				$id_system_605 = 	$row4[0]['id_system_605'];
				$system_605_1ro =	$row4[0]['system_605_1ro'];
				$system_605_2do =	$row4[0]['system_605_2do'];
				$system_605_3ro =	$row4[0]['system_605_3ro'];
				$system_605_4to =	$row4[0]['system_605_4to'];
				$system_605_5to =	$row4[0]['system_605_5to'];
				$system_605_6to =	$row4[0]['system_605_6to'];
				$system_605_7mo =	$row4[0]['system_605_7mo'];
				$system_605_8vo =	$row4[0]['system_605_8vo'];
			
			}
			else
			{
				$system_605_1ro =	'0';
				$system_605_2do =	'0';
				$system_605_3ro =	'0';
				$system_605_4to =	'0';
				$system_605_5to =	'0';
				$system_605_6to =	'0';
				$system_605_7mo =	'0';
				$system_605_8vo =	'0';
			}
			
			$t->set_var("id_system_602",$id_system_602);
			$t->set_var("system_602_sublema",$system_602_sublema);
			$t->set_var("system_602_orden",$system_602_orden);
			$t->set_var("system_605_1ro","$system_605_1ro");
			$t->set_var("system_605_2do","$system_605_2do");
			$t->set_var("system_605_3ro","$system_605_3ro");
			$t->set_var("system_605_4to","$system_605_4to");
			$t->set_var("system_605_5to","$system_605_5to");
			$t->set_var("system_605_6to","$system_605_6to");
			$t->set_var("system_605_7mo","$system_605_7mo");
			$t->set_var("system_605_8vo","$system_605_8vo");

			
			if (  optener_permisos('M',$id_system_01,$sesion_system_03,$mysqli) == '1' )
			{	
				$url="'modulos/home/php/home_am.php'";
				$id="'content_$id_system_602'";
				$vars="'id_system_01=$id_system_01&id_system_602=$id_system_602&id_system_503=0'";	
				$t->set_var("funcion_editar_numeros","cargar_post($url,$id,$vars)");	
			}
			else
			{
				$t->set_var("funcion_editar_numeros","acceso_denegado()");
			}
			
			if ( $candado == 'on' ) // solo coordinador o root
			{					
				$url="'modulos/home/php/lema_am.php'";
				$id="'content_$id_system_602'";
				$vars="'id_system_01=$id_system_01&id_system_602=$id_system_602&id_system_503=0'";	
				$t->set_var("funcion_editar_sublema","cargar_post($url,$id,$vars)");	
					
			}
			else
			{
				$t->set_var("funcion_editar_sublema","acceso_denegado()");
			}


			$t->parse("ESCRUTINIO","un_escrutinio",true);
		}
	}
	



	$row = $mysqli -> consulta_SQL("Select * from system_606_resumen_total where system_606_mesa = '0'");				
	if ($row == true )
	{			
		$id_system_606 = 		$row[0]['id_system_606'];
		$system_606_mesa=		$row[0]['system_606_mesa'];
		$system_606_nulos=		$row[0]['system_606_nulos'];
		$system_606_recurridos=	$row[0]['system_606_recurridos'];
		$system_606_impugnada=	$row[0]['system_606_impugnada'];
		$system_606_comando=	$row[0]['system_606_comando'];
		$system_606_blanco=		$row[0]['system_606_blanco'];
		$system_606_total = 	$row[0]['system_606_total'];
	}
	else
	{
		$id_system_606=			'';
		$system_606_nulos=		'0';
		$system_606_recurridos=	'0';
		$system_606_impugnada=	'0';
		$system_606_comando=	'0';
		$system_606_blanco=		'0';
		$system_606_total = 	'0';
	}

	$url4="'modulos/home/php/totales_am.php'";
	$id4="'content_totales'";
	$vars4="'id_system_01=$id_system_01&id_system_606=$id_system_606&system_606_mesa=0'";	
	$t->set_var("funcion_editar_totales","cargar_post($url4,$id4,$vars4)");	

		
	$t->set_var("system_606_nulos",$system_606_nulos);
	$t->set_var("system_606_recurridos",$system_606_recurridos);
	$t->set_var("system_606_impugnada",$system_606_impugnada);
	$t->set_var("system_606_comando","$system_606_comando");
	$t->set_var("system_606_blanco","$system_606_blanco");
	$t->set_var("system_606_total","$system_606_total");*/
	

	if (  optener_permisos('A',$id_system_01,$sesion_system_03,$mysqli) == '1' )
	{
		$url="'modulos/home/php/lema_am.php'";
		$id="'content_'";
		$vars="'id_system_01=$id_system_01'";		
		$t->set_var("funcion_agregar","cargar_post($url,$id,$vars)");
	}
	else
	{
		$t->set_var("funcion_agregar","sin_permisos()");
	}
	
	if (  optener_permisos('A',$id_system_01,$sesion_system_03,$mysqli) == '1' and $root_candado == "on" )
	{
		$url="'modulos/home/php/confi.php'";
		$id="'configuracion'";
		$vars="'id_system_01=$id_system_01'";		
		$t->set_var("funcion_fonfi","cargar_post($url,$id,$vars)");
		$t->set_var("ver_confi","alt");
	}
	else
	{
		$t->set_var("funcion_fonfi","sin_permisos()");
		$t->set_var("ver_confi","hide");
	}

$url="'modulos/home/php/home.php'";
$id="'content_seccion'";
$vars="'id_system_01=$id_system_01'";
$t->set_var("funcion_refres","cargar_post($url,$id,$vars); ");



$url_api="'https://equipoebersolis.com.ar/sistema/php/json_api.php'";
$id="'content_api'";
$vars="'system_post=2'";
$t->set_var("funcion_api","api_json($url_api,$id,$vars); ");



$t->pparse("OUT", "ver");
?>
