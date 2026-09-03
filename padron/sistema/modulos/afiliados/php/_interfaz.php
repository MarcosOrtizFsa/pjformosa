<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";
include "_abm.php";
$mysqli = new _Abm($base);


$lista_dnis = 			isset($_POST['lista_dnis']) ? $_POST['lista_dnis'] : NULL;
$id_system_700 = 		isset($_POST['id_system_700']) ? $_POST['id_system_700'] : NULL;
$rela_system_03 = 		isset($_POST['rela_system_03']) ? $_POST['rela_system_03'] : NULL;
$system_701_num = 		isset($_POST['system_701_num']) ? $_POST['system_701_num'] : NULL;
$id_system_701 = 		isset($_POST['id_system_701']) ? $_POST['id_system_701'] : NULL;
$rela_system_701 = 		isset($_POST['rela_system_701']) ? $_POST['rela_system_701'] : NULL;
$system_701_checked = 	isset($_POST['system_701_checked']) ? $_POST['system_701_checked'] : NULL;
$rela_system_703 = 	isset($_POST['rela_system_703']) ? $_POST['rela_system_703'] : NULL;
$system_700_dni  = 			isset($_POST['system_700_dni']) ? $_POST['system_700_dni'] : NULL;		
$system_700_apellido  = 		isset($_POST['system_700_apellido']) ? $_POST['system_700_apellido'] : NULL;		
$system_700_nombre = 			isset($_POST['system_700_nombre']) ? $_POST['system_700_nombre'] : NULL;		
$system_700_sexo  = 			isset($_POST['system_700_sexo']) ? $_POST['system_700_sexo'] : NULL;		
$system_700_domicilio  = 		isset($_POST['system_700_domicilio']) ? $_POST['system_700_domicilio'] : NULL;	
$system_700_circuito = 			isset($_POST['system_700_circuito']) ? $_POST['system_700_circuito'] : NULL;		
$system_700_dpto 	 = 			isset($_POST['system_700_dpto']) ? $_POST['system_700_dpto'] : NULL;	
$system_700_localidad  = 		isset($_POST['system_700_localidad']) ? $_POST['system_700_localidad'] : NULL;		
$system_700_estado = 			isset($_POST['system_700_estado']) ? $_POST['system_700_estado'] : NULL;	
$system_701_observaciones = 			isset($_POST['system_701_observaciones']) ? $_POST['system_701_observaciones'] : NULL;
	
$nombre_funcion = 		isset($_POST['nombre_funcion']) ? $_POST['nombre_funcion'] : NULL;

$system_05_detalles="";	
				
switch ($nombre_funcion)
{
	case "votar":		
	$system_05_mensaje = $mysqli -> votar($id_system_600,$mysqli);	
	$system_05_detalles="U:$sesion_system_03, id:$id_system_600";	
	break;		
	
	case "nueva_lista":		
	$system_05_mensaje = $mysqli -> nueva_lista($lista_dnis,$rela_system_03,$rela_system_701,$mysqli);		
	$system_05_detalles="U:$sesion_system_03, id:$rela_system_03, $rela_system_701 ";	
	break;	
	
	case "nuevo_folio":		
	$system_05_mensaje = $mysqli -> nuevo_folio($id_system_701,$system_701_num,$rela_system_703,$rela_system_03,$system_701_checked,$mysqli);		
	$system_05_detalles="U:$sesion_system_03, id:$rela_system_03, $rela_system_701, $system_701_checked ";
	break;	
	
	case "remoner_dni":		
	$system_05_mensaje = $mysqli -> remoner_dni($id_system_700,$mysqli);
	$system_05_detalles="U:$sesion_system_03, id:$id_system_700 ";
	break;	

	case "borrar_folio":		
	$system_05_mensaje = $mysqli -> borrar_folio($id_system_701,$mysqli);
	$system_05_detalles="U:$sesion_system_03, id:$id_system_701 ";		
	break;	

	case "agregar_nuevo_afiliado":		
	$system_05_mensaje = $mysqli -> agregar_nuevo_afiliado(	
															$id_system_700, 	
															$rela_system_03, 	
															$rela_system_701, 	
															formatear_dni($system_700_dni), 	
															strtoupper($system_700_apellido), 	
															strtoupper($system_700_nombre), 	
															strtoupper($system_700_sexo), 	
															$system_700_domicilio, 	
															strtoupper($system_700_circuito), 	
															strtoupper($system_700_dpto), 	
															strtoupper($system_700_localidad), 	
															$system_700_estado,
															$mysqli
															);
	$system_05_detalles="U:$sesion_system_03, id:$id_system_701 ";		
	break;	

	case "salvar_observacion":		
	$system_05_mensaje = $mysqli -> salvar_observacion($id_system_701,$system_701_observaciones,$mysqli);
	$system_05_detalles="";		
	break;	
				
	default:
	$system_05_mensaje="No hay funci&oacute;n...";
	break;		
						
}
guardar_logistica($sesion_system_03,$sesion_system_07,$sesion_system_06,$id_system_01,$nombre_funcion,$system_05_detalles,$system_05_mensaje,$mysqli);

echo "$system_05_mensaje";
?>
