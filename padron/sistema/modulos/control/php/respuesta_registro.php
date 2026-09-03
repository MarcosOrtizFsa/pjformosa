<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
$t->set_file(array(
	'ver'	=> "respuesta_registro.html"
	));

$rela_system_100 = isset($_POST['rela_system_100']) ? $_POST['rela_system_100'] : NULL;
$system_04_dni = isset($_POST['system_04_dni']) ? $_POST['system_04_dni'] : NULL;
$system_04_dni =	formatear_dni($system_04_dni);	

	$row = $mysqli -> consulta_SQL("Select system_04_perfil.*, system_03_usuarios.* from system_04_perfil, system_03_usuarios
					where 
					system_04_perfil.rela_system_03 = system_03_usuarios.id_system_03 
					and 	
					system_04_perfil.system_04_dni = '$system_04_dni'
					");
	if ($row == TRUE)
	{			
		$rela_system_03_cliente = $row[0]['id_system_03'];
		$id_system_04 = 		$row[0]['id_system_04'];
		$system_04_nombre = 	$row[0]['system_04_nombre'];
		$system_04_apellido = 	$row[0]['system_04_apellido'];
		$system_04_email = 		$row[0]['system_04_email'];
		$system_04_dni = 		$row[0]['system_04_dni'];
		$system_04_celular = 	$row[0]['system_04_celular'];
		$system_04_localidad = 	$row[0]['system_04_localidad'];
		$system_04_barrio = 	$row[0]['system_04_barrio'];
		$system_04_direccion = 	$row[0]['system_04_direccion'];
		$t->set_var("msj_resultado",'<center><h3>'.calificacion_cliente(0,$rela_system_03_cliente,$rela_system_100,$mysqli).'</h3></center>');
		$t->set_var("msj_boton",'Actualizar datos');
		$t->set_var("btn_boton",'btn-success');
	}
	else
	{
		$id_system_03 = 		'';
		$id_system_04 = 		'';
		$system_04_nombre = 	'';
		$system_04_apellido = 	'';
		$system_04_email = 		'';
		$system_04_celular = 	'';
		$system_04_localidad = 	'';
		$system_04_barrio = 	'';
		$system_04_direccion = 	'';
		$t->set_var("msj_boton",'Agregar nuevo cliente');
		$t->set_var("btn_boton",'btn-primary');
		$t->set_var("msj_resultado",'<em class="minitex">Registrar al cliente para futuras ofertas o delivery</em>');
	}

		
	$t->set_var("system_04_nombre",		$system_04_nombre);
	$t->set_var("system_04_apellido",	$system_04_apellido);
	$t->set_var("system_04_email",		$system_04_email);
	$t->set_var("system_04_dni",		$system_04_dni);
	$t->set_var("system_04_celular",	$system_04_celular);
	$t->set_var("system_04_localidad",	$system_04_localidad);
	$t->set_var("system_04_barrio",		$system_04_barrio);
	$t->set_var("system_04_direccion",	$system_04_direccion);

	$rela_system_07='6';// cliente reistrado
	
	$url="'modulos/control/php/_interfaz.php'"; // siempre va a abm_interfaz
	$vars="'nombre_funcion=agregar_modificar&";
	$vars.="rela_system_07=$rela_system_07&id_system_03=$id_system_03&";
	$vars.="system_03_cuir=$system_checked&";
	//$vars.="system_04_email='+smp.system_04_email.value+'&";
	$vars.="system_04_dni='+smp.system_04_dni.value+'&";
	$vars.="system_04_celular='+smp.system_04_celular.value+'&";
	$vars.="system_04_apellido='+encodeURIComponent(smp.system_04_apellido.value)+'&";
	$vars.="system_04_nombre='+encodeURIComponent(smp.system_04_nombre.value)+'&";
	$vars.="system_04_localidad='+encodeURIComponent(smp.system_04_localidad.value)+'&";
	$vars.="system_04_barrio='+encodeURIComponent(smp.system_04_barrio.value)+'&";
	$vars.="system_04_direccion='+encodeURIComponent(smp.system_04_direccion.value)";
	
	$url_exito="'modulos/control/php/respuesta_registro.php'";
	$id="'respuesta_registro'";
	$vars_exito="'system_04_dni=$system_04_dni'";	


	$t->set_var("funcion_registrar_cliente","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");		

	
	
						
$t->pparse("OUT", "ver");
?>
