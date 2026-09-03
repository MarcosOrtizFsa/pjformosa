<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);

$id_system_01 = isset($_POST['id_system_01']) ? $_POST['id_system_01'] : NULL;
$id_system_03 = isset($_POST['id_system_03']) ? $_POST['id_system_03'] : NULL;
$id_system_09 = isset($_POST['id_system_09']) ? $_POST['id_system_09'] : NULL;
$rela_system_07 = isset($_POST['rela_system_07']) ? $_POST['rela_system_07'] : NULL;
$rela_system_06 = isset($_POST['rela_system_06']) ? $_POST['rela_system_06'] : NULL;
$system_03_clave = isset($_POST['system_03_clave']) ? $_POST['system_03_clave'] : NULL;
$system_03_clave_copy = isset($_POST['system_03_clave_copy']) ? $_POST['system_03_clave_copy'] : NULL;
$system_03_cuir = isset($_POST['system_03_cuir']) ? $_POST['system_03_cuir'] : NULL;
$system_03_estado = isset($_POST['system_03_estado']) ? $_POST['system_03_estado'] : NULL;
$system_04_nombre = isset($_POST['system_04_nombre']) ? $_POST['system_04_nombre'] : NULL;
$system_04_apellido = isset($_POST['system_04_apellido']) ? $_POST['system_04_apellido'] : NULL;
$system_04_email = isset($_POST['system_04_email']) ? $_POST['system_04_email'] : NULL;
$system_04_celular = isset($_POST['system_04_celular']) ? $_POST['system_04_celular'] : NULL;
$system_04_telefono = isset($_POST['system_04_telefono']) ? $_POST['system_04_telefono'] : NULL;
$system_04_detalles = isset($_POST['system_04_detalles']) ? $_POST['system_04_detalles'] : NULL;
$system_04_cuil = isset($_POST['system_04_cuil']) ? $_POST['system_04_cuil'] : NULL;
$system_04_dni = isset($_POST['system_04_dni']) ? $_POST['system_04_dni'] : NULL;	
$system_04_domicilio = isset($_POST['system_04_domicilio']) ? $_POST['system_04_domicilio'] : NULL;	
$system_04_provincia = isset($_POST['system_04_provincia']) ? $_POST['system_04_provincia'] : NULL;
$system_04_localidad = isset($_POST['system_04_localidad']) ? $_POST['system_04_localidad'] : NULL;
$system_04_barrio = isset($_POST['system_04_barrio']) ? $_POST['system_04_barrio'] : NULL;
$system_03_mesa = isset($_POST['system_03_mesa']) ? $_POST['system_03_mesa'] : NULL;
$rela_system_501 = isset($_POST['rela_system_501']) ? $_POST['rela_system_501'] : NULL;
$system_04_barrio = isset($_POST['system_04_barrio']) ? $_POST['system_04_barrio'] : NULL;
	
$id_system_02 = isset($_POST['id_system_02']) ? $_POST['id_system_02'] : NULL;
$system_02_A  = isset($_POST['system_02_A']) ? $_POST['system_02_A'] : NULL;
$system_02_B  = isset($_POST['system_02_B']) ? $_POST['system_02_B'] : NULL;
$system_02_M  = isset($_POST['system_02_M']) ? $_POST['system_02_M'] : NULL;
$system_02_E  = isset($_POST['system_02_E']) ? $_POST['system_02_E'] : NULL;
$system_02_V  = isset($_POST['system_02_V']) ? $_POST['system_02_V'] : NULL;
$system_02_S = isset($_POST['system_02_S']) ? $_POST['system_02_S'] : NULL;
$system_02_D  = isset($_POST['system_02_D']) ? $_POST['system_02_D'] : NULL;
$system_02_I  = isset($_POST['system_02_I']) ? $_POST['system_02_I'] : NULL;
$system_02_C  = isset($_POST['system_02_C']) ? $_POST['system_02_C'] : NULL;
$nombre_funcion = isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;

$system_04_dni =	formatear_dni($system_04_dni);	
$system_04_cuil = 	formatear_cuit($system_04_cuil);

$system_05_detalles="";	
				
switch ($nombre_funcion)
{
		
	case "agregar_modificar":
		
	if ($system_03_cuir!='')
	{$system_checked = $system_03_cuir;} // REEMPLAZO EL cuir SI EXISTE LA VARIABLE. SINO DEJO QUE LO ASIGNE AUTOMATICAMENTE
		
	$system_05_mensaje = $mysqli -> agregar_modificar(
					$id_system_01,
					$id_system_03,
					$sesion_system_06,	
					$rela_system_07,																
					$system_04_nombre,
					$system_04_apellido,
					trim($system_04_dni),	
					trim($system_04_cuil),
					$system_04_email,
					$system_04_celular,	
					$system_03_mesa,
					$rela_system_501,							
					$system_fecha,
					$system_hora,
					$system_checked,
					$codigo_tabla
					);
	$system_05_detalles="";
	break;
	
	case "eliminar":
	$system_05_mensaje = $mysqli -> eliminar($id_system_03);
	$system_05_detalles="$id_system_03";
	break;	
	
	case "on_off":
	$system_05_mensaje = $mysqli -> on_off($id_system_03,$system_03_estado);
	$system_05_detalles="$id_system_03,$system_03_estado";
	break;

	case "salvar_clave":
	$system_05_mensaje = $mysqli -> salvar_clave($id_system_03,$system_03_clave,$system_03_clave_copy);
	$system_05_detalles="$id_system_03,$system_03_clave,$system_03_clave_copy";
	break;

	case "asignar_modulo":
	$system_05_mensaje = $mysqli -> asignar_modulo($id_system_01,$id_system_03);
	$system_05_detalles="$id_system_01,$id_system_03";
	break;

	case "asignar_escuela":
	$system_05_mensaje = $mysqli -> asignar_escuela($id_system_03,$system_03_mesa);
	$system_05_detalles="$id_system_01,$id_system_03";
	break;

	case "reset_asignar_escuela":
	$system_05_mensaje = $mysqli -> reset_asignar_escuela($id_system_03);
	$system_05_detalles="$id_system_01,$id_system_03";
	break;
			
	case "saltar_completado":
	$_SESSION['sesion_perfil']="";
	break;

	case "salvar_permiso":
	$system_05_mensaje = $mysqli -> salvar_permiso(
		$id_system_02,
		$system_02_A,
		$system_02_B,
		$system_02_M,
		$system_02_E,
		$system_02_V,
		$system_02_S,
		$system_02_D,
		$system_02_I,
		$system_02_C
		);
	$system_05_detalles="$id_system_02,$sesion_system_03";
	break;

	case "remover":
	$system_05_mensaje = $mysqli -> remover($id_system_09);
	$system_05_detalles="$id_system_09,$sesion_system_03";
	break;
					
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;		
						
}
guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
