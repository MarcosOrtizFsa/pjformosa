<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$t = new _template('../templates');
$t->set_file(array(
	'ver'		=> "home_abm.html",
	'select'	=> "una_opcion.html"
	));



$row = $mysqli -> consulta_SQL("Select system_04_perfil.*, system_03_usuarios.*
		from 
		system_04_perfil, system_03_usuarios 
		where 
		system_04_perfil.rela_system_03=system_03_usuarios.id_system_03 
		and 
		system_03_usuarios.id_system_03='$id_system_03' 
		");
if ($row == TRUE)
{
	$id_system_03 = 		$row[0]['id_system_03'];
	$rela_system_07 = 		$row[0]['rela_system_07'];
	$rela_system_501 = 		$row[0]['rela_system_501'];
	$system_03_usuario= 	$row[0]['system_03_usuario'];
	$system_03_cuir = 		$row[0]['system_03_cuir'];	
	$system_03_modo = 		$row[0]['system_03_modo'];
	$system_03_mesa = 		$row[0]['system_03_mesa'];	
	$id_system_04 = 		$row[0]['id_system_04'];
	$system_04_nombre = 	$row[0]['system_04_nombre'];
	$system_04_apellido = 	$row[0]['system_04_apellido'];
	$system_04_dni = 		$row[0]['system_04_dni'];
	$system_04_celular = 	$row[0]['system_04_celular'];
	$system_04_email = 		$row[0]['system_04_email'];
	
	

	// RESET CLAVE
	$url="'modulos/control/php/reset_clave.php'";
	$id="'content_reset'";
	$vars="'id_system_01=$id_system_01&id_system_03=$id_system_03'";
	$t->set_var("funcion_reset_clave","cargar_post($url,$id,$vars)");		

	$t->set_var("ocultar_ediclave","alt");
	$titulo_modulo="Editar Datos";	
	$boton_modulo="Salvar Datos";
}
else
{
	$id_system_03 = '';
	$rela_system_07 = '';
	$rela_system_501 = '';
	$system_03_usuario= '';
	$system_04_cuil = '';	
	$system_03_modo = '';	
	$id_system_04 = '';
	$system_04_nombre = '';
	$system_04_apellido = '';
	$system_04_dni = '';
	$system_04_celular = '';
	$system_04_email ='';
	$system_03_mesa = '';	
	
	$titulo_modulo="Agregar Usuario";	
	$boton_modulo="Guardar";
	$t->set_var("ocultar_ediclave","hide");
}
	$t->set_var("system_04_apellido","$system_04_apellido");
	$t->set_var("system_04_nombre","$system_04_nombre");
	$t->set_var("system_04_email","$system_04_email");
	$t->set_var("system_04_celular","$system_04_celular");
	$t->set_var("system_04_dni","$system_04_dni");
	$t->set_var("system_03_mesa","$system_03_mesa"); 
	 
	

	if( $system_04_nombre=="" )
	{
	$t->set_var("alert-nombre","   border: 1px solid red;  ");
	}
	
	if( $system_04_apellido=="" )
	{
	$t->set_var("alert-apellido","  border: 1px solid red; ");
	}


	if( $system_04_dni == "" )
	{
	$t->set_var("alert-dni","  border: 1px solid red; ");
	}
	
	if( $system_04_celular=='' )
	{
	$t->set_var("alert-cel","  border: 1px solid red; ");
	}

	if( $system_04_email=='' )
	{
	$t->set_var("alert-email","  ");
	}
	

	if( $system_03_mesa == "0" )
	{
	$t->set_var("alert-mesa","  border: 1px solid red; ");
	}
	

	$url="'modulos/control/php/_interfaz.php'"; // siempre va a abm_interfaz
	$vars="'nombre_funcion=agregar_modificar&";
	$vars.="id_system_01=$id_system_01&";
	$vars.="id_system_03=$id_system_03&";


	if ($sesion_system_03_modo=='0' or $sesion_system_03_modo=='1' or $sesion_system_03_modo=='2')
	{
		$vars.="rela_system_07='+abm.rela_system_07.value+'&";
	}
	else 
	{		
		$rela_system_07="7"; // publico
		$vars.="rela_system_07=$rela_system_07&";
			
	}	
	//$vars.="rela_system_501='+abm.rela_system_501.value+'&";	
	//$vars.="system_03_mesa='+encodeURIComponent(abm.system_03_mesa.value)+'&";	
	$vars.="system_04_nombre='+encodeURIComponent(abm.system_04_nombre.value)+'&";
	$vars.="system_04_apellido='+encodeURIComponent(abm.system_04_apellido.value)+'&";	
	$vars.="system_04_email='+abm.system_04_email.value+'&";
	$vars.="system_04_celular='+encodeURIComponent(abm.system_04_celular.value)+'&";			
	$vars.="system_04_dni='+abm.system_04_dni.value";
 
		
	$url_exito="'modulos/control/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01'";	
	
	if ($sesion_perfil != '')
	{
		$url_exito="'modulos/home/php/home.php'";
		$id="'content_seccion'";
		$vars_exito="''";	
	}


	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");
	


	
	
	$url3="'modulos/control/php/select_2.php'";
	$id3="'content_select_2'";
	$vars3="'system_03_mesa=$system_03_mesa&id_system_03=$id_system_03&rela_system_501='";
	$t->set_var("funcion_selector_circuito","cargar_post($url3,$id3,$vars3+this.value); ");
	

	$t->set_var("BOTON_SALIR",'');
	$cadena="";
	$id_system_03bIS = isset($_GET['id_system_03']) ? $_GET['id_system_03'] : NULL;
	if ( $id_system_03bIS !='' )
	{
		
		$where=" where id_system_07='$rela_system_07' ";
		$titulo_modulo="Editar mi perfil";
		$boton_modulo="Guardar mis datos";
		$t->set_var("disabled","disabled");
		$t->set_var("no_ver","hide");
		$t->set_var("ocultar_cajas", "hide");
						
		if ($sesion_perfil!='')
		{
			$url="'modulos/control/php/_interfaz.php'";
			$vars="'nombre_funcion=saltar_completado'";
			$t->set_var("BOTON_SALIR",'<button type="button" class="btn btn-warning mb-3" onClick="guardar_vars_refresh('.$url.','.$vars.')">En otro momento...</button>');
			$t->set_var("ocultar_ediclave","hide");
			$titulo_modulo=$system_04_nombre.": Completa los campos obligatorios para continuar...";
			$boton_modulo="Completar registro ahora";
			
		}
	
	}
	else
	{
		$t->set_var("disabled","");
		$t->set_var("no_ver","alt");
		// :::::::::::::::::::::::::::::: ESTRUCRURA CON PERMISOS
		if ($sesion_system_07 == '1') // ROOT
		{
		$where=" where id_system_07 IN ('1','2','3','4','5','6','7')";;	
		}
		else
		if ($sesion_system_07 == '2') // COORDINADOR
		{
		$where=" where id_system_07 IN ('2','3','4','5','6','7')";
		}
		else
		if ($sesion_system_07 == '3') // FISCAL
		{
		$where=" where id_system_07 = '3' ";
		}
		else
		if ($sesion_system_07 == '4') // OPERADOR
		{
		$where=" where id_system_07 IN ('4','5','6')";
		}
		else
		if ($sesion_system_07 == '5') // DIRIGENTE
		{
		$where=" where id_system_07 = '5' ";
		}
		else
		if ($sesion_system_07 == '6') // motoquero
		{
		$where=" where id_system_07 = '6' ";
		}
		else // POR DEFECTO PUBLICO OBSERVADOR
		{
		$where=" where id_system_07 = '7' ";
		}
		$cadena.="<option value=''>* Tipo de Registro</option>";
	
	}
	
	$row = $mysqli -> consulta_SQL("Select * from system_07_privilegios $where");
	if ($row == TRUE)
	{
		for ( $i=0; $i < count($row); $i++)
		{
			$id_system_07 =		$row[$i]['id_system_07'];
			$system_07_nombre =	$row[$i]['system_07_nombre'];
			
			if ($rela_system_07==$id_system_07)
			{
				$cadena.="<option value='$id_system_07' SELECTED >$system_07_nombre</option>";
			}
			else
			{
				$cadena.="<option value='$id_system_07'>$system_07_nombre</option>";		
			}
		}
	}
	$t->set_var("PRIVILEGIOS",$cadena);
	


	$url_exito="'modulos/control/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01'";
	$t->set_var("funcion_volver","cargar_post($url_exito,$id,$vars_exito)");
	

$t->set_var("titulo_modulo","$titulo_modulo");	
$t->set_var("boton_modulo","$boton_modulo");
	
$t->pparse("OUT", "ver");
?>
