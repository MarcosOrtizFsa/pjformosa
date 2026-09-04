<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'			=> "lista_planilla.html",
	'un_afiliado'		=> "un_afiliado.html",
	'select'		=> "una_opcion.html"
	));
	
$t->set_var("titulo_modulo","");
$id_system_701 = 		isset($_POST['id_system_701']) ? $_POST['id_system_701'] : NULL;
$system_701_checked = 	isset($_POST['system_701_checked']) ? $_POST['system_701_checked'] : NULL;			
$variable_tipo = 		isset($_POST['variable_tipo']) ? $_POST['variable_tipo'] : NULL;
$system_701_num = 		isset($_POST['system_701_num']) ? $_POST['system_701_num'] : NULL;
$respuesta = '';
$system_apellido = '';
$system_nombre = '';
$system_circuito = 	'';
$system_sexo =	'';
$system_domicilio =	'';
$system_dpto = 	'';
$system_localidad ='';
$system_apellido_nombre_afiliador='';

if ($system_701_checked != '')
{
	$system_701_checked=$_POST['system_701_checked'];
	$miwhere=" where system_701_checked='$system_701_checked' ";
}
else
{
	$miwhere=" where id_system_701='$id_system_701' ";
}
	
	$row = $mysqli -> consulta_SQL("Select * from system_701_folio $miwhere");				
	if ($row == true)
	{			
		$id_system_701=$row[0]['id_system_701'];
		$rela_system_03=$row[0]['rela_system_03'];
		$system_701_num = $row[0]['system_701_num'];
		$system_701_observaciones = trim($row[0]['system_701_observaciones']);
		$system_apellido_nombre_afiliador=consulto_perfil($rela_system_03,$mysqli);
		$t->set_var("id_system_701",$id_system_701);
		$t->set_var("system_701_observaciones",$system_701_observaciones);
	}
	else
	{
		$id_system_701='';
		$rela_system_03='';
		$t->set_var("system_701_observaciones",'');
	}
	
	$respuesta = 'Folio N&deg; '.$system_701_num.' de '.$system_apellido_nombre_afiliador;
	
	


if ( $variable_buscar != "" and $variable_tipo == '0' and $id_system_701 != '' )
{	
	$variable_buscar = formatear_dni(trim($variable_buscar));
	
	if ( ctype_digit($variable_buscar) == true ) 
	{		
		
		if (strlen($variable_buscar) >= '7' and strlen($variable_buscar) <= '8')
		{
			$row = $mysqli -> consulta_SQL("Select * from system_700_avalados where system_700_dni = '$variable_buscar' ");				
			if($row == true)
			{
			 $respuesta = '<span style="color: red;"><span class="dni">'.$variable_buscar.'</span> Existe en Folio '.$system_701_num.'</span>';
			}
			else
			{
				// system_2000_apellido system_2000_nombre @ system_2000_circuito @ system_2000_sexo @ system_2000_domicilio
				//$dni = 	str_pad($variable_buscar, 8, "0", STR_PAD_LEFT); // 8 digitos si o si
				$valu = explode('@', 	funcion_traer_datos_padron($variable_buscar,$mysqli));
				$system_apellido = 		$valu['0'];
				$system_nombre = 		$valu['1'];
				$system_sexo =			$valu['2'];
				
				//donde_vive=  system_2002_domicilio  localidad_por_circuito	system_2002_circuito 
				$valu1 = explode('@', 	donde_vive($variable_buscar,$mysqli));
				$system_domicilio = 	$valu1['0'];					
				$system_circuito = 		$valu1['2'];
				
				// system_506_dpto   system_506_localidad 
				$valu2 = explode('@', 	funcion_traer_localidad_por_circuito($system_circuito,$mysqli));
				$system_dpto = 			$valu2['0'];
				$system_localidad = 	$valu2['1'];
				$departamento = 		consultar_departamento($system_dpto,$mysqli);
			
								
				$system_700_estado =  			funcion_consulto_extras($variable_buscar,$mysqli);
				
				$mysqli -> consulta_SQL("INSERT INTO system_700_avalados 
				( 	
					id_system_700, 	
					rela_system_03, 	
					rela_system_701, 
					system_700_folio,	
					system_700_dni, 	
					system_700_apellido,
					system_700_nombre,
					system_700_sexo, 	
					system_700_domicilio, 	
					system_700_circuito, 
					system_700_dpto,
					system_700_localidad,
					system_700_estado 	  
				) 
				VALUES 
				(
					DEFAULT,
					'$sesion_system_03',
					'$id_system_701',
					'$system_701_num',
					'$variable_buscar',
					'$system_apellido',
					'$system_nombre',
					'$system_sexo',
					'$system_domicilio',
					'$system_circuito',
					'$departamento',
					'$system_localidad',
					'$system_700_estado'
				)");	
				$respuesta = '<span style="color:#009966;"><span class="dni">'.$variable_buscar.'</span> OK! Folio '.$system_701_num.'</span>';
			}	
		}
		else
		{
			$respuesta = '<span style="color:red;"><span>'.$variable_buscar.'</span> No es un dni...</span>';
		}
			
	} 
		

}

$t->set_var("titulo_modulo",$respuesta);


	$where=" WHERE rela_system_701='$id_system_701' and system_700_estado IN ('0','1') ";			

							
	if ($variable_buscar != "" and $variable_tipo == '1')
	{	
		$variable_buscar=formatear_dni(trim($variable_buscar));
		if (ctype_digit($variable_buscar)) 
		{	
			
			$where.=" and system_700_dni = '$variable_buscar'";
			    	
		} 
		else 
		{
		  //$where.=" and system_04_apellido like '%$variable_buscar%'  ";
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
	// parte del paginador
	$LIMITE = ' limit 100';
	$cuento = 0;

			
	$row = $mysqli -> consulta_SQL("Select * from system_700_avalados 
						$where_control
						
						ORDER BY id_system_700 desc 
						$LIMITE
						");				
	if($row == true)
	{
		
		for ( $i=0; $i < count($row); $i++)
		{				
			$id_system_700 =			$row[$i]['id_system_700'];
			$rela_system_03 =			$row[$i]['rela_system_03'];
			$system_700_dni = 			$row[$i]['system_700_dni'];
			$system_700_apellido = 		$row[$i]['system_700_apellido'];
			$system_700_nombre = 		$row[$i]['system_700_nombre'];
			$system_700_circuito =		$row[$i]['system_700_circuito'];
			$system_700_domicilio =		$row[$i]['system_700_domicilio'];
			$system_700_estado =		$row[$i]['system_700_estado'];
			$system_700_localidad =		$row[$i]['system_700_localidad'];
			$system_700_dpto =			$row[$i]['system_700_dpto'];	
	
			if ($system_700_estado == 1)
			{
			$system_700_estado = "SI";
			}
			else
			{
			$system_700_estado = "NO";
			}
			$t->set_var("system_700_estado","$system_700_estado");
			
				
		
			if ( optener_permisos('B',$id_system_01,$sesion_system_03,$mysqli) == '1' )
			{
				$url="'modulos/afiliados/php/_interfaz.php'";
				$vars="'nombre_funcion=remoner_dni&";
				$vars.="id_system_700=$id_system_700'";
				$url_exito="'modulos/afiliados/php/lista_planilla.php'";
				$id="'content_seccion'";
				$vars_exito="'id_system_01=$id_system_01&id_system_701=$id_system_701'";
				$atx="''";
				$msg="'Remover este DNI de esta lista?'";
				$t->set_var("funcion_remover","eliminar_mostrar($url,$vars,$url_exito,$id,$vars_exito,$msg,$atx);");

			}
			else
			{
				$t->set_var("funcion_remover","sin_permisos()");
			}
						
		
			if ( trim($system_700_apellido) == "")
			{
				$url="'modulos/afiliados/php/nuevo_am.php'";
				$id="'content_$id_system_700'";
				$vars="'id_system_01=$id_system_01&id_system_701=$id_system_701&system_700_dni=$system_700_dni'";
				$funcion_nuevo_padronado = "cargar_post($url,$id,$vars)";
			
				$t->set_var("system_apellido_nombre",'No empadronado... &nbsp;&nbsp;&nbsp;[<strong><a href="javascript:;" onclick="'.$funcion_nuevo_padronado.'" style="color:#009966;">AGREGAR AHORA</a></strong>] ');
				//$t->set_var("system_apellido_nombre",'No existe en el padron...');
			}
			else
			{
				$t->set_var("system_apellido_nombre",$system_700_apellido.', '.$system_700_nombre);
			}
			
			
			

			$t->set_var("id_system_700",$id_system_700);
			$t->set_var("system_700_dni",$system_700_dni);
			$t->set_var("system_700_domicilio",$system_700_domicilio);
			$t->set_var("system_700_dpto",$system_700_dpto);	
			$t->set_var("system_700_localidad",$system_700_localidad);	
			$t->parse("LISTADO","un_afiliado",true);
			$cuento++;
		}
	} 
	else 						
	{
		
		$t->SET_VAR("LISTADO",'<div class="tabl"><li class="fil-100 file-mov-100">No tiene nada cargados a&uacute;n...</li></div>');

	}							

	
	$t->set_var("cuento_total",$cuento);	

		
	$url="'modulos/afiliados/php/home.php'";
	$id="'content_seccion'";
	$vars="'id_system_01=$id_system_01'";
	$t->set_var("funcion_volver","cargar_post($url,$id,$vars)");


	// buscador
	$urlb="'modulos/afiliados/php/lista_planilla.php'";
	$idb="'content_seccion'";
	$varsb="'id_system_01=$id_system_01&id_system_701=$id_system_701&system_701_num=$system_701_num&variable_tipo='+busqueda.variable_tipo.value+'&variable_buscar='+busqueda.variable_buscar.value";
	
	$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
	$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");


	// CARGAR DNIS	
	if ( optener_permisos('A',$id_system_01,$sesion_system_03,$mysqli) == '1' )
	{				
		$url="'modulos/afiliados/php/popup_canvas.php'";
		$id="'popup_canvas'";
		$vars="'id_system_01=$id_system_01&id_system_701=$id_system_701'";
		$t->set_var("funcion_pop_canvas","cargar_post($url,$id,$vars)");		
	}
	else
	{
		$t->set_var("funcion_pop_canvas","sin_permisos()");	
	}
	
	
	$url="'modulos/afiliados/php/_interfaz.php'";
	$vars="'nombre_funcion=salvar_observacion&id_system_701=$id_system_701&system_701_observaciones='";
	$t->set_var("salvar_observacion","guardar_solo($url,$vars+this.value)");
	
				
$t->pparse("OUT", "ver");
?>