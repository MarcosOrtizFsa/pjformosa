<?php
session_start();
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$where_control = isset($_SESSION['where_control']) ? $_SESSION['where_control'] : NULL;

header('Content-type: application/vnd.ms-csv');
header('Content-Disposition: attachment; filename="lista_registros_'.$system_fecha.'_'.$hora_public.'.csv";');
$LIMITE = "";
$total=0;			
$cadena='';
$cadena.='	ID;';
$cadena.='	NOMRE Y APELLIDO;';
$cadena.='	DNI;';
$cadena.='	CELULAR;';
$cadena.='	TIPO;';
$cadena.='	ESTADO;';
$cadena.='	MODULOS Y PERMISOS;';
$cadena.=("\n");	

			
$row = $mysqli -> consulta_SQL("Select system_04_perfil.*, system_03_usuarios.*, system_07_privilegios.* from system_04_perfil, system_03_usuarios, system_07_privilegios 
								
								$where_control
								and system_07_privilegios.id_system_07 != '1'
								
								ORDER BY system_03_usuarios.id_system_03 DESC 
								$LIMITE
								");
if ($row == TRUE)
{	
	for ( $i=0; $i < count($row); $i++)
	{
		$id_system_03 = 		$row[$i]['id_system_03'];
		$id_system_04 = 		$row[$i]['id_system_04'];
		$id_system_07 = 		$row[$i]['id_system_07'];
		$rela_system_07 = 		$row[$i]['rela_system_07'];
		$system_03_usuario = 	$row[$i]['system_03_usuario'];
		$system_03_clave = 		$row[$i]['system_03_clave'];
		$system_03_cuir = 		$row[$i]['system_03_cuir'];
		$system_03_estado = 	$row[$i]['system_03_estado'];	
		$system_04_celular = 	$row[$i]['system_04_celular'];
		$system_07_nombre = 	utf8_decode($row[$i]['system_07_nombre']);
		$system_04_nombre = 	utf8_decode($row[$i]['system_04_nombre']);
		$system_04_apellido = 	utf8_decode($row[$i]['system_04_apellido']);
		$system_04_email = 		utf8_decode($row[$i]['system_04_email']);
		$system_04_dni = 		convert_dni($row[$i]['system_04_dni']);

	
		if ($system_03_estado == '2')
		{
			$system_03_estado = "Suspendido";		
		}
		else
		if ($system_03_estado == '1')
		{
			$system_03_estado = "Activo";
		}
		else
		{
			$system_03_estado = "Pendiente";	
		}
		
		
		$cadena.=''.$id_system_03.';';
		$cadena.=''.$system_04_nombre.' '.$system_04_apellido.';';
		$cadena.=''.$system_04_dni.';';
		$cadena.=''.$system_04_celular.';'; 
		$cadena.=''.$system_07_nombre.';';	
		$cadena.=''.$system_03_estado.';';
		$cadena.=''.consulto_modulos_asignados($id_system_03,$mysqli).';'; // traigo dos columnas. MODULOS Y PERMISOS
		$cadena.=("\n");	

	}			
}

	

	
	
echo $cadena;			
?>
