<?php
// GENERAMOS UN NUMERO UNICO PARA IDENTIFICAR CUALQUIER DATO DE LA BD
date_default_timezone_set("America/Argentina/Buenos_Aires");
$system_fecha = date('Y-m-d');
$system_ano = date('Y');
$system_hora = date('H:i:s');
$hora_public = date('H:i');
$system_checked = time();		
$system_fecha_qr = date('Ymd');
$modo_login ="sistema";
$id_system_01 = isset($_GET['id_system_01']) ? $_GET['id_system_01'] : NULL;
if (isset($_POST['id_system_01'])!='')
{
$id_system_01 = isset($_POST['id_system_01']) ? $_POST['id_system_01'] : NULL;
}
$id_system_03=isset($_POST['id_system_03']) ? $_POST['id_system_03'] : NULL;
if ( isset($_GET['id_system_03']) !='' )
{
$id_system_03=isset($_GET['id_system_03']) ? $_GET['id_system_03'] : NULL;
}
$mod = isset($_GET['mod']) ? $_GET['mod'] : NULL;
$rec = isset($_GET['rec']) ? $_GET['rec'] : NULL;
$mas = 	isset($_POST['mas']) ? $_POST['mas'] : NULL;
$system_01_modulo = isset($_GET['system_01_modulo']) ? $_GET['system_01_modulo'] : NULL;
$sesion_system_03 = isset($_SESSION['sesion_system_03']) ? $_SESSION['sesion_system_03'] : NULL;
$sesion_system_07= isset($_SESSION['sesion_system_07']) ? $_SESSION['sesion_system_07'] : NULL;
$sesion_system_06= isset($_SESSION['sesion_system_06']) ? $_SESSION['sesion_system_06'] : NULL;
$sesion_system_03_modo = isset($_SESSION['sesion_system_03_modo']) ? $_SESSION['sesion_system_03_modo'] : NULL;
$sesion_perfil = isset($_SESSION['sesion_perfil']) ? $_SESSION['sesion_perfil'] : NULL;
$sesion_vista = isset($_SESSION['sesion_vista']) ? $_SESSION['sesion_vista'] : NULL;
$reset = isset($_POST['reset']) ? $_POST['reset'] : NULL;
if ($reset=='go')
{
$_SESSION['where_seccion']="";
}
$id_system_10 = isset($_GET['id_system_10']) ? $_GET['id_system_10'] : NULL;


$base = new Constructor_SQL(HOST,USU,CLA,BD);
$mysqli = new Abm($base);

$row = $mysqli -> system_08_configuracion();
if ($row == TRUE)
{
	$system_08_titulo_site=			trim($row[0]['system_08_titulo_site']);
	$system_08_dominio = 			trim($row[0]['system_08_dominio']);
	$system_08_descripcion_site = 	$row[0]['system_08_descripcion_site'];
	$system_08_email_alerta = 		$row[0]['system_08_email_alerta'];		
	$system_08_path = 				$row[0]['system_08_path'];
	$system_08_contactos_visibles = $row[0]['system_08_contactos_visibles'];
	$system_08_limit_size_up_archivo = $row[0]['system_08_limit_size_up_archivo'];
	$system_08_ancho_max = 			$row[0]['system_08_ancho_max'];
	$system_08_titular = 			$row[0]['system_08_titular'];
	$system_08_email = 				$row[0]['system_08_email'];	
	$system_08_celular = 			$row[0]['system_08_celular'];
	$system_08_whatsapp = 			$row[0]['system_08_whatsapp'];
	$system_08_telefono = 			$row[0]['system_08_telefono'];
	$system_08_facebook = 			$row[0]['system_08_facebook'];
	$system_08_twitter = 			$row[0]['system_08_twitter'];
	$system_08_youtube = 			$row[0]['system_08_youtube'];
	$system_08_instagram = 			$row[0]['system_08_instagram'];
}


$row = $mysqli -> system_07_privilegios($sesion_system_07);
if ($row == TRUE)
{
	$system_07_admin= 	$row[0]['system_07_admin'];
	$system_07_a= 		$row[0]['system_07_a'];
	$system_07_b= 		$row[0]['system_07_b'];
	$system_07_m= 		$row[0]['system_07_m'];
	$system_07_v= 		$row[0]['system_07_v'];
	$system_07_ad= 		$row[0]['system_07_ad'];
	$system_07_sis= 	$row[0]['system_07_sis'];
	$system_07_r = 		$row[0]['system_07_r'];				

	if ($sesion_system_07=='1')
	{
		$candado="on";
		$root_candado="on";
	}
	else
	if ($sesion_system_07=='2')
	{
		$candado="on";
		$root_candado="off";
	}
	else
	{
		$candado="off";
		$root_candado="off";			
	}		
}

$row = $mysqli -> system_03_usuarios($sesion_system_03);
if ($row == TRUE)
{		
	$system_03_cuir = $row[0]['system_03_cuir'];
}

$row = $mysqli -> system_06_sala($id_system_06);
if ($row == TRUE)
{		
	$system_06_sala=	$row[0]['system_06_sala'];
}

$row = $mysqli -> system_04_perfil($sesion_system_03);
if ($row == TRUE)
{		
	$system_04_name_usuario=substr($row[0]['system_04_nombre'], 0, 1).'. '.$row[0]['system_04_apellido'];	
}

$PAGINAR="";			
$num_filas="10";	
if ( $mas=='' or $mas=='0' )
{
$LIMITE =" limit 0,$num_filas ";
}
else
{
$num_filas = $mas + $num_filas;
$LIMITE=" limit 0,$num_filas ";
}


function funcion_paginar_siguiente($total_filas,$num_filas,$url,$id,$vars)
{		
	$tSQL = ceil($total_filas/$num_filas); 
	if ($tSQL)
	{
		if ($num_filas > $total_filas)
		{
		$PAGINAS=''.$total_filas.' #';	
		}
		else
		{
		$PAGINAS='<a href="javascript:cargar_post('.$url.','.$id.','.$vars.');" title="Desplegar '.$num_filas.' +"><i class="bi bi-sort-down-alt"></i></a>&nbsp;&nbsp; '.$num_filas.' de '.$total_filas;	
		}
	}
	$PAGINAS = isset($PAGINAS) ? $PAGINAS : NULL;
	return "$PAGINAS";
}		
	
	
function color_fila($bgcolor)
{	
	$color_1="#fff";
	$color_2="#f8f9fa";
	if ( $bgcolor!=$color_1 )
	{
	$bgcolor=$color_1;
	}
	else
	{
	$bgcolor=$color_2;
	}
	return $bgcolor;
}

function optengo_imagen($id_system_10,$id_system_11,$system_09_tipo,$modo,$width,$height,$mysqli)
{
	
	if ($id_system_10 == '0'){$id_system_10 = "";}
	if ($id_system_11 == '0'){$id_system_11 = "";}
	
	if ($id_system_10 != '')
	{
	$where ="  where rela_system_10='$id_system_10' and system_09_tipo='$system_09_tipo'  ";
	}
	else
	if ($id_system_11 != '')
	{
	$where ="  where rela_system_11='$id_system_11' and system_09_tipo='$system_09_tipo'  ";
	}
	else
	{
	$where =" where id_system_09='0' ";// invento un id 0 para que me de FALSE
	}
	
	$row = $mysqli -> consulta_SQL("Select * from system_09_archivero $where ");
	if ($row == TRUE)
	{
		$system_09_path =	$row[0]['system_09_path'];
		$system_09_archivo = $row[0]['system_09_archivo'];
	}
	else
	{
		$system_09_path="../image";
		$system_09_archivo="no-imagen.jpg";
	}
		
	if ($modo=='1')
	{
	return '<div style="background: url('.$system_09_path.'/'.$system_09_archivo.') no-repeat 50% 50%; background-size:contain; width: '.$width.'; height: '.$height.';"></div>';	
	}
	else
	{				
	return $system_09_path.'/'.$system_09_archivo;		
	}	
}

function consulto_perfil($rela_system_03,$mysqli)
{			
	$row = $mysqli -> consulta_SQL("SELECT system_03_usuarios.*, system_04_perfil.* FROM system_03_usuarios, system_04_perfil 
	where 
	system_04_perfil.rela_system_03=system_03_usuarios.id_system_03 
	and 
	system_03_usuarios.id_system_03='$rela_system_03'
	");
	if ($row == TRUE)
	{
		return substr($row[0]['system_04_nombre'], 0, 1).'. '.$row[0]['system_04_apellido'];	 
	}
}	

function consulto_comision($id_system_404,$mysqli)
{
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_404_comisiones where id_system_404='$id_system_404'");
	if ($row == TRUE)
	{ 
		return		$row[0]['system_404_comision'];	
	} 	
}

function consulto_expediente($id_system_450,$mysqli)
{					
	$cadena="";
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_450_mesa_entrada where id_system_450='$id_system_450' ");
	if ($row == TRUE)
	{ 
		$cadena.= $row[0]['id_system_450'].'@';	
		$cadena.= $row[0]['rela_system_400'].'@'; 	
		$cadena.= consulto_comision($row[0]['rela_system_404'],$mysqli).'@'; 		
		$cadena.= $row[0]['system_450_fecha_entrada'].'@';
		$cadena.= $row[0]['system_450_fecha_entrada_camara'].'@'; 		
		$cadena.= $row[0]['system_450_fecha_consideracion'].'@'; 		
		$cadena.= $row[0]['system_450_fecha_promulgado'].'@'; 
		$cadena.= $row[0]['system_450_expediente'].'@';
		$cadena.= $row[0]['system_450_archivo'].'@'; 
		$cadena.= $row[0]['system_450_expedientes_adjuntos'].'@';		
		$cadena.= $row[0]['system_450_observaciones'].'@';	
		$cadena.= $row[0]['system_450_estado'].'@';		
		$cadena.= $row[0]['system_450_checked'];	 
	}
	
	return trim($cadena);
}

function numero_descargas($rela_system_11,$mysqli)
{
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_09_archivero WHERE rela_system_11='$rela_system_11' ");
	if ($row == TRUE)
	{
		return $row[0]['system_09_descargado'];
	}		
}

function traigo_archivo($id_system_09,$mysqli)
{
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_09_archivero WHERE id_system_09='$id_system_09' ");
	if ($row == TRUE)
	{
		return $row[0]['system_09_path'].'/'.$row[0]['system_09_archivo'];
	}		
}

function traigo_dialogo($sesion_system_03,$system_11_checked,$url_exito,$id,$vars_exito,$mysqli)
{
	
	$cadena="";
	$row = $mysqli -> consulta_SQL("SELECT * FROM system_11b_dialogos 
	where 
	system_11b_checked='$system_11_checked'
	and 
	system_11b_estado = '1'
	
	ORDER BY id_system_11b ASC 
	");
	if ($row == TRUE)
	{	
		for ( $i=0; $i < count($row); $i++)
		{				
			$id_system_11b = 			$row[$i]['id_system_11b'];	
			$rela_system_03 = 			$row[$i]['rela_system_03']; 	
			$system_11b_fecha = 		formatear_fecha($row[$i]['system_11b_fecha']); 
			$system_11b_hora = 			hora_min($row[$i]['system_11b_hora']); 
			$system_11b_dialogo = 		nl2br($row[$i]['system_11b_dialogo']); 

			
			if ( $rela_system_03 == $sesion_system_03 )
			{	
				$url="'modulos/foro/php/_interfaz.php'";
				$vars="'nombre_funcion=eliminar_dialogo&";
				$vars.="id_system_11b=$id_system_11b'";
				//$url_exito,$id,$vars_exito...
				$atx="''";
				$msg="'Borrar mensaje?'";
				$funcion_borrar = "eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);";	
				
				$ico_dialog = '<i class="bi bi-chat-right-text-fill"></i>';
				$ico_borrar = '<a href="javascript:;" onClick="'.$funcion_borrar.'"><i class="bi bi-trash-fill"></i></a>';

			}
			else
			{
				$ico_dialog = '<i class="bi bi-chat-left-text"></i>';
				$ico_borrar = '';

			}
			
			
			$cadena.= '<div class="tabl">';
			$cadena.= '	<li class="fil-100 file-mov-100 minitex">';
			$cadena.= $ico_dialog.'<strong>'.consulto_perfil($rela_system_03,$mysqli).':</strong> '.$system_11b_dialogo;
			$cadena.= '	</li>';
			$cadena.= '	<li class="fil-75 file-mov-50 minitex">';
			$cadena.= 	$system_11b_fecha.' - '.$system_11b_hora.' hs.';
			$cadena.= '	</li>'; 
			$cadena.= '	<li class="fil-25 file-mov-50 minitex align-right">';
			$cadena.= '		'.$ico_borrar;
			$cadena.= '	</li>';
			$cadena.= '</div>';

		}		
	}
	return $cadena;		
}


$codigo_tabla='';
function crear_capcha()
{			
// Generamos codigos para los campos que lo requieran
$codigo_tabla='';
$max_chars = round(rand(5,5));  // tendrá entre 7 y 9 caracteres
$chars = array();
	for ($i='a'; $i<'z'; $i++) $chars[] = $i;  // creamos vector de letras
	$chars[] = 'z';
	for ($i=0; $i<$max_chars; $i++)
	{
	  $letra = round(rand(0, 1));  // primero escogemos entre letra y número
	  if ($letra) // es letra
			$codigo_tabla .= $chars[round(rand(0, count($chars)-1))];
	  else 
			$codigo_tabla .= round(rand(0, 9));
	}

return $codigo_tabla;
}	


function formatear_fecha($fecha)
{	
	if ($fecha!='')
	{
		$v_fecha = explode('-',$fecha);
		$dia=$v_fecha[0];
		if (strlen($dia)==1)
			$dia="0".$dia;
	
		$mes=$v_fecha[1];
		if (strlen($mes)==1)
			$mes="0".$mes;
	
		$ano=trim($v_fecha[2]);
		
		$ret_fecha=$ano."-".$mes."-".$dia;
	}
	else
	{
	$ret_fecha="";
	}
	return $ret_fecha;
}
function formatear_hora($hora)
{
	$h = explode(':',$hora);
	$_hora=$h[0];

	$_min=$h[1];
	
	$ret_hora=$_hora .":". $_min;
	return $ret_hora;
}
function quito_0($vars)
{
	if ($vars=='0000-00-00'){$vars="";}
	if ($vars=='00-00-0000'){$vars="";}
	if ($vars=='--')		{$vars="";}
	if ($vars=='00:00:00')	{$vars="";}
	if ($vars=='00:00')		{$vars="";}
	if ($vars==':')			{$vars="";}
	if ($vars=='0.0')		{$vars="";}
	if ($vars=='0.00')		{$vars="";}
	if ($vars=='00')		{$vars="";}
	if ($vars=="0")			{$vars="";}
	return $vars;
}

function porcentual($monto,$porciento)
{
	$monto=round(($monto/100) * $porciento, 2);
	return $monto;	
}

function formatear_moneda($mony)
{
	$value = explode(',',$mony);
	$ent=$value[0];
	if (strlen($value[1])=='2')
	{
	$ent=str_replace(".","",$ent); //quito puntos al entero
	$mony=$ent.'.'.$value[1]; // entero punto decimal
	}
	else
	{
		$value2 = explode('.',$ent);
		$ent2=$value2[0];
		if (strlen($value2[1])=='2')
		{
		$ent==str_replace(".","",$ent2); //quito puntos al entero
		$mony=$ent.'.'.$value2[1]; // entero punto decimal
		}
		else
		{
		$mony=str_replace(".","",$ent); //quito puntos al entero
		}
	}
	return $mony;
}

function format_money($n) 
{       
	$ajuste = explode('.',$n);
	$unidad=isset($ajuste[0]);
	$centavo=isset($ajuste[1]);
	if (strlen($centavo)==1)
	{$centavo=$centavo."0";}
	if ($centavo=='')
	{$centavo="00";}
	$mony= $unidad.'.'.$centavo;
	if ($mony=='.00'){$mony="";}
	return $mony;	
}

function hora_min($hora)
{
	$h = explode(':',$hora);
	$_hora=$h[0];

	$_min=$h[1];
	
	$ret_hora=$_hora .":". $_min;
	return $ret_hora;
}

function numero_mesa($num)
{
	if (strlen($num)==1)
	{$num="0".$num;} 
	return $num;
}

function numero_recivo($num)
{
	// AGREGO 0 HASTA COMPLETAR 4 DIGITOS
	if (strlen($num)==1)
	{$num="000".$num;}
	if (strlen($num)==2)
	{$num="00".$num;}
	if (strlen($num)==3)
	{$num="0".$num;} 
	return $num;
}

function formatear_dni($dni)
{
	$dni=str_replace(".","",$dni); 
	return $dni;
}

function formatear_cuit($cuit)
{
	$cuit=str_replace(".","",$cuit); 
	$cuit=str_replace("-","",$cuit); 
	return $cuit;
}
function verifico_cuit($cuit)
{
	$cuit=str_replace(".","",$cuit); 
	$cuit=str_replace("-","",$cuit);
	if (strlen($cuit)=='11') {
	$cuit=preg_replace('/([0-9]{2})+([0-9]{8})+([0-9]{1})/', "\\1-\\2-\\3", $cuit);
	$row = explode('-',$cuit);
	if ($row[0] < 10){$cuit="";}
	}else{
	$cuit="";
	}
	return $cuit;
}
function verifico_dni($dni)
{
	$dni=str_replace(".","",$dni); 
	if (strlen($dni)!='8'){
	$dni="";
	}
	return $dni;
}
function convert_dni($dni)
{
	$dni = preg_replace('/([0-9]{1,2})+([0-9]{3})+([0-9]{3})/', "\\1.\\2.\\3", $dni); 
	return trim($dni);
}
function convert_cuit($cuit)
{
	$cuit=str_replace(".","",$cuit); 
	$cuit=str_replace("-","",$cuit);
	$cuit = preg_replace('/([0-9]{2})+([0-9]{8})+([0-9]{1})/', "\\1-\\2-\\3", $cuit); 
	$cuit = trim($cuit);	 
	return $cuit;
}
function convert_cuil_a_dni($cuit)
{
	$cuit=str_replace(".","",$cuit); 
	$cuit=str_replace("-","",$cuit);
	$cuit = preg_replace('/([0-9]{2})+([0-9]{8})+([0-9]{1})/', "\\2", $cuit); 
	$cuit = trim($cuit);	 
	return $cuit;
}


function urls_amigables($url_amigo) {
	// Tranformamos todo a minusculas
	$url_amigo = strtolower($url_amigo);
	//Rememplazamos caracteres especiales latinos
	$finx = array('&','ñ','Ñ','Á','É','Í','Ó','Ú','á','é','í','ó','ú');
	$repx = array('-','n','N','A','E','I','O','U','a','e','i','o','u');
	$url_amigo = str_replace($finx, $repx, utf8_decode($url_amigo));	
	
	// Añaadimos los guiones
	$findv = array(' ', '&', '\r\n', '\n', '+');
	$url_amigo = str_replace ($findv, '-', $url_amigo);
	// Eliminamos y Reemplazamos demás caracteres especiales
	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl = array('', '-', '');
	$url_amigo = preg_replace ($find, $repl, $url_amigo);
	return $url_amigo;
}

function prefijo_whatsapp($phone) 
{
	// AGREGO EL PREFIJO 9 AL NUMERO = +54 9 0000 000000
	// quito espacios si tubiera 
	$phone = str_replace(' ','',$phone);
	// Indico el 9 de la caracteristica arg manualmente si no lo ponen
	$phone = preg_replace('/^(\+54)+([0-9]{4})+([0-9]{6})/','\\1 9 \\2 \\3',$phone);
	// quito los espacios en blanco
	$phone = str_replace(' ','',$phone);
	return $phone;
}

function formatear_tel_cel($num)
{
	$num=str_replace("+","",$num); 
	$num=str_replace("-","",$num); 
	$num=str_replace("(","",$num);
	$num=str_replace(")","",$num);
	$num=str_replace(" ","",$num);
	return $num;
}

function fechaleible($FechaLeible) 
{
	$system_fechale=str_replace("-",".",$FechaLeible);
	$my_fecha=strtotime($system_fechale);
	$system_fecha_dia=date('l', $my_fecha);
	$system_fecha_nro=date('j', $my_fecha);
	$system_fecha_mes=date('F', $my_fecha);
	$system_fecha_ano=date('Y', $my_fecha);
	$system_fecha_dia=str_replace("Monday","Lunes",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Tuesday","Martes",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Wednesday","Mi&eacute;rcoles",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Thursday","Jueves",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Friday","Viernes",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Saturday","S&aacute;bado",$system_fecha_dia); 
	$system_fecha_dia=str_replace("Sunday","Domingo",$system_fecha_dia); 
	$system_fecha_mes=str_replace("January","Enero",$system_fecha_mes); 
	$system_fecha_mes=str_replace("February","Febrero",$system_fecha_mes); 
	$system_fecha_mes=str_replace("March","Marzo",$system_fecha_mes); 
	$system_fecha_mes=str_replace("April","Abril",$system_fecha_mes); 
	$system_fecha_mes=str_replace("May","Mayo",$system_fecha_mes); 
	$system_fecha_mes=str_replace("June","Junio",$system_fecha_mes); 
	$system_fecha_mes=str_replace("July","Julio",$system_fecha_mes); 
	$system_fecha_mes=str_replace("August","Agosto",$system_fecha_mes); 
	$system_fecha_mes=str_replace("September","Setiembre",$system_fecha_mes); 
	$system_fecha_mes=str_replace("October","Octubre",$system_fecha_mes); 
	$system_fecha_mes=str_replace("November","Noviembre",$system_fecha_mes); 
	$system_fecha_mes=str_replace("December","Diciembre",$system_fecha_mes); 
	
	
	if ( $FechaLeible == date('Y-m-d') )
	{
	$FechaLeible="Hoy";	
	}
	else
	{
	$FechaLeible=$system_fecha_dia." ".$system_fecha_nro;	
	}
	
	return $FechaLeible;
}	

function funcion_cargar_elemento($url_exito,$id,$vars,$msj,$tipo_multiple)
{	
	if ($msj=='')
	{
	$msj="Examinar...";
	}
		
	return '<input type="file" '.$tipo_multiple.' id="archivos" class="form-control" onChange="down_multiple_archivos(\''.$url_exito.'\',\''.$id.'\',\''.$vars.'\');"  style=" height:30px; width: 100px; float:left;"><div id="estado_de_carga" style="padding-top:10px; font-size:11px;">&nbsp;&nbsp;'.$msj.'</div>';	
}

function importe_mas_iva($importe,$iva)
{
	$ivares= ($importe / 100) * $iva;
	return round($importe - $ivares, 2);	
}

function recortar_texto($texto,$limite)
{			
	if ( strlen($texto) > $limite )
	{
		$texto = sprintf("%.".$limite."s\n",$texto)." ...+ ";
	}			
	return $texto;
}

function dangerous_texts($vars)
{	
	$vars = preg_replace('/(\$)+([0-9\.\,]+)/','\\1 \\2 ',$vars);
	/*$vars = preg_replace('/>/','&#8250;',$vars);
	$vars = preg_replace('/</','&#8249;',$vars);*/
	$vars = str_replace("'","\"",$vars);
	/*$vars = str_replace("’","\"",$vars);
	$vars = str_replace("´","\"",$vars); 
	$vars = str_replace('“',"\"",$vars);
	$vars = str_replace("”","\"",$vars);*/
	
	return $vars;
}

class DocxConversion
{
       private $filename;

       public function __construct($filePath) 
	   {
           $this->filename = $filePath;
       }

        private function read_txt() 
	   {
           $fileHandle = fopen($this->filename, "r");
           $line= @fread($fileHandle, filesize($this->filename));   
           $lines= explode(chr(0x0D),$line);
           $outtext= "";
           foreach($lines as $thisline)
             {
               $pos = strpos($thisline, chr(0x00));
               if (($pos !== FALSE)||(strlen($thisline)==0))
                 {
				 $outtext.="\n";
                 } else {
                   $outtext.=$thisline."\n";
                 }
             }
             $outtext=utf8_encode($outtext);
            //$outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
           return $outtext;
       }
	   
	   private function read_doc() 
	   {
           $fileHandle = fopen($this->filename, "r");
           $line= @fread($fileHandle, filesize($this->filename));   
           $lines= explode(chr(0x0D),$line);
           $outtext= "";
           foreach($lines as $thisline)
             {
               $pos = strpos($thisline, chr(0x00));
               if (($pos !== FALSE)||(strlen($thisline)==0))
                 {
				 $outtext.="\n";
                 } else {
                   $outtext.=$thisline."\n";
                 }
             }
             $outtext=utf8_encode($outtext);
            //$outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
           return $outtext;
       }

       private function read_docx()
	   {

           $striped_content = '';
           $content = '';

           $zip = zip_open($this->filename);

           if (!$zip || is_numeric($zip)) return false;

           while ($zip_entry = zip_read($zip)) {

               if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

               if (zip_entry_name($zip_entry) != "word/document.xml") continue;

               $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

               zip_entry_close($zip_entry);
           }// end while

           zip_close($zip);

           $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
           $content = str_replace('</w:r></w:p>', "\r\n", $content);
           $striped_content = strip_tags($content);

           return $striped_content;
       }

    /************************excel sheet************************************/

function xlsx_to_text($input_file)
{
       $xml_filename = "xl/sharedStrings.xml"; //content file name
       $zip_handle = new ZipArchive;
       $output_text = "";
       if(true === $zip_handle->open($input_file)){
           if(($xml_index = $zip_handle->locateName($xml_filename)) !== false){
               $xml_datas = $zip_handle->getFromIndex($xml_index);
               $xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
               $output_text = strip_tags($xml_handle->saveXML());
           }else{
               $output_text .="";
           }
           $zip_handle->close();
       }else{
       $output_text .="";
       }
       return $output_text;
}

/*************************power point files*****************************/
function pptx_to_text($input_file)
{
       $zip_handle = new ZipArchive;
       $output_text = "";
       if(true === $zip_handle->open($input_file)){
           $slide_number = 1; //loop through slide files
           while(($xml_index = $zip_handle->locateName("ppt/slides/slide".$slide_number.".xml")) !== false){
               $xml_datas = $zip_handle->getFromIndex($xml_index);
               $xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
               $output_text .= strip_tags($xml_handle->saveXML());
               $slide_number++;
           }
           if($slide_number == 1){
               $output_text .="";
           }
           $zip_handle->close();
       }else{
       $output_text .="";
       }
       return $output_text;
}


       public function convertToText() {

           if(isset($this->filename) && !file_exists($this->filename)) {
               return "No existe el archivo...";
           }

           $fileArray = pathinfo($this->filename);
           $file_ext  = $fileArray['extension'];
           if($file_ext == "rtf" || $file_ext == "doc" || $file_ext == "docx" || $file_ext == "xlsx" || $file_ext == "pptx" || $file_ext == "txt")
           {
               if($file_ext == "doc") {
                   return $this->read_doc();
               } 
			   else
			   if($file_ext == "rtf") {
                   return $this->read_docx();
               } 
			   else
			   if($file_ext == "docx") {
                   return $this->read_docx();
               } 
			   else
			   if($file_ext == "xlsx") {
                   return $this->xlsx_to_text();
               }
			   else
			   if($file_ext == "pptx") {
                   return $this->pptx_to_text();
               }
			   else
			   if($file_ext == "txt") {
                   return $this->read_txt();
               }
           } 
		   else 
		   {
               return "No se pudo leer el contenido...";
           }
       }

}

?>