<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";


$t = new _template('../templates');
$t->set_file(array(
	'ver'		=> "home.html",
	'un_padron'			=> "un_padron.html"
	));


$t->set_var("titulo_modulo","Consultar padr&oacute;n de afiliados al Partido Justicialista");
$variable_buscar = 		isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL;

if ( $reset =='go' )
{
$_SESSION['where_control']="";
}
					
if ($variable_buscar != "")
{		
	$variable_buscar=formatear_dni(trim($variable_buscar));
	if ( ctype_digit($variable_buscar) == true ) 
	{		
		
		if (strlen($variable_buscar) >= '7' and strlen($variable_buscar) <= '8')
		{
			$row = $mysqli -> consulta_SQL("Select * from system_700_afiliados where system_700_dni = '$variable_buscar' ");				
			if($row == true)
			{
			 	//$respuesta = '<div class="responder" style="color: #336600;"><span class="dni">'.$variable_buscar.'</span> en FOLIO '.$row[0]['system_700_folio'].'</div>';
	
				$respuesta = '<div class="responder" style="color: #0033CC; text-align:center;">';
				$respuesta.= '<h1>DNI: <span class="dni">'.$row[0]['system_700_dni'].'</span></h1>';
				$respuesta.= '<div >'.$row[0]['system_700_apellido'].', '.$row[0]['system_700_nombre'].'</div>';
				$respuesta.= '<div >Domicilio: '.$row[0]['system_700_domicilio'].'</div>';
				$respuesta.= '<div >'.$row[0]['system_700_localidad'].' - '.$row[0]['system_700_dpto'].'</div>';
				$respuesta.= '<br><br>';
				$respuesta.= '<div >Es afiliado al <br>PARTIDO JUSTICIALISTA</div>';
				$respuesta.= '<br><br>';
				//$respuesta.= '<button type="button" class="btn btn-primary btn-lg w-100">Solicitar afiliaci&oacute;n</button>';
				$respuesta.= '</div>';
			}
			else
			{
				// system_2000_apellido system_2000_nombre @ system_2000_circuito @ system_2000_sexo @ system_2000_domicilio
				//$dni = 	str_pad($variable_buscar, 8, "0", STR_PAD_LEFT); // 8 digitos si o si
				$valu = explode('@', 			funcion_traer_datos_padron($variable_buscar,$mysqli));
				$system_apellido_nombre = 		$valu['0'];
				$system_circuito = 				$valu['1'];
				//$system_sexo =					$valu['2'];

				if ( $system_apellido_nombre != '' )
				{
					
					$url="'modulos/afiliaciones/php/_interfaz.php'";
					$vars="'nombre_funcion=iniciar_tramite&";
					$vars.="system_2001_dni=$variable_buscar'";
				
					$url_exito="'modulos/afiliaciones/php/afiliar.php'";
					$id="'content_seccion'";
					$vars_exito="'id_system_01=$id_system_01&system_2001_dni=$variable_buscar'";
					$funcion_agregar_afiliacion = " guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito); ";
					
					$respuesta = '<div class="responder" style="color: #000; text-align:center;" >';
					$respuesta.= '<h1>DNI: <span class="dni">'.$variable_buscar.'</span></h1>';
					$respuesta.= '<div >'.$system_apellido_nombre.'</div>';
					$respuesta.= '<br><br>';
					$respuesta.= '<div ><strong>NO ES AFILIADO/A...</strong></div><br><br>';
					$respuesta.= '<button type="button" onclick="'.$funcion_agregar_afiliacion.'" class="btn btn-primary btn-lg w-100 mb-3">Iniciar tr&aacute;mite</button>';
					$respuesta.= '</div>';
					
				}
				else
				{
					
					$url="'modulos/afiliaciones/php/_interfaz.php'";
					$vars="'nombre_funcion=iniciar_tramite&";
					$vars.="system_2001_dni=$variable_buscar'";
				
					$url_exito="'modulos/afiliaciones/php/afiliar.php'";
					$id="'content_seccion'";
					$vars_exito="'id_system_01=$id_system_01&system_2001_dni=$variable_buscar'";
					$funcion_agregar_afiliacion = " guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito); ";
					
					$respuesta = '<div class="responder" style="color: #000; text-align:center;" >';
					$respuesta.= '<h1>DNI: <span class="dni">'.$variable_buscar.'</span></h1>';
					$respuesta.= '<div >'.$system_apellido_nombre.'</div>';
					$respuesta.= '<br><br>';
					$respuesta.= '<div ><strong>NO ES AFILIADO/A...</strong></div><br><br>';
					//$respuesta.= '<button type="button" onclick="'.$funcion_agregar_afiliacion.'" class="btn btn-primary btn-lg w-100 mb-3">Iniciar tr&aacute;mite</button>';
					$respuesta.= '</div>';

				}

			
			}	
		}
		else
		{
			$respuesta = '<div class="responder" style="color:red;"><h2>No parece ser un DNI...</h2></div>';
		}
			
	} 
}
else
{	
	$respuesta = '<div class="responder align-center"><h2>Haz una consulta con DNI</h2></div>';
}

									
$t->set_var("RESULTADO",$respuesta);



// VERIFICAR DNI
$urlb="'modulos/afiliaciones/php/home.php'";
$idb="'content_seccion'";
$varsb="'id_system_01=$id_system_01&variable_buscar='+busqueda.variable_buscar.value";
$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");


$url2="'modulos/afiliados/php/lista_pj.php'";
$id2="'content_seccion'";
$vars2="'id_system_01=$id_system_01'";
$t->set_var("funcion_lista","cargar_post($url2,$id2,$vars2); ");	




$t->pparse("OUT", "ver");
?>