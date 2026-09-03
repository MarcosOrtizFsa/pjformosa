<?php
session_start();
include "../../../../../lib/template.inc";
include "../../../../../lib/mysql_conect.inc";
include "../../../../php/privilegios.php";
include "../../../../php/funciones.php";


$t = new Template('../templates');

$t->set_file(array(
	'ver'		=> "lista_dni.html"
	));

$system_listado=$_POST['system_listado'];
$nombre_funcion=$_POST['nombre_funcion'];
$t->set_var("system_listado",$system_listado);	
//echo optener_solo_dni($system_listado);


if ( $nombre_funcion=="comparar_lista" )
{

	if ( $system_listado!="" )
	{

		$ok="0";
		$mal="";
		$no="0";

		$array = explode(" ", optener_solo_dni($system_listado));
		foreach ($array as $value) {
		
				$data0= trim($value); // dni

				
				if (strlen($data0) >= '7' and strlen($data0) <= '8')
				{		
					$data0 = formatear_dni($data0);
					// COMPLETO LOS 0 INICIALES A LOS DNI  DE 7 DIGITOS
					$data0 = str_pad($data0, 8, "0", STR_PAD_LEFT);	
					
					$sql1=$mysqli->query("Select * from system_04_perfil where system_04_dni='$data0' ");				
					if ($row = $sql1 -> fetch_array())
					{
					$id_system_04 = $row['id_system_04'];
					$ok++;
					}
					else
					{
					$mal.="$data0<br>";
					$no++;
					}
				}
			
			
		}
		
		
		$t->set_var("exito","DNI Existen: $ok");
		$t->set_var("no_existen","No existen: <strong>$no</strong> <br>$mal ");

	}
	else
	{
		$t->set_var("system_listado","");
		$t->set_var("exito"," &nbsp;");
		$t->set_var("no_existen","Pega la lista de dnis en la caja para comparar...");
	}
}
	
	
	
	

			
		
		



$t->pparse("OUT", "ver");
?>
