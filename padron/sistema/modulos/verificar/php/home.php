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


$t->set_var("titulo_modulo","Verificaci&oacute;n para avales y fichas 2026");
$variable_buscar = 		isset($_POST['variable_buscar']) ? $_POST['variable_buscar'] : NULL;
$funcion_selector='';



if ( $reset =='go' )
{
$_SESSION['rela_system_2005'] = "";
}


if ( (isset($_POST['rela_system_2005']) ? $_POST['rela_system_2005'] : NULL) != "" )
{
	$_SESSION['rela_system_2005'] = isset($_POST['rela_system_2005']) ? $_POST['rela_system_2005'] : NULL;
	$rela_system_2005 = $_SESSION['rela_system_2005'];
	$funcion_disabled = " DISABLED ";
}
else
{	

	if ( (isset($_SESSION['rela_system_2005']) ? $_SESSION['rela_system_2005'] : NULL) !='' )
	{
		$rela_system_2005 = $_SESSION['rela_system_2005'];
		$funcion_disabled = " DISABLED ";
	}
	else
	{
		$url2="'modulos/verificar/php/home.php'";
		$id2="'content_seccion'";
		$vars2="'id_system_01=$id_system_01&rela_system_2005='";
		$funcion_selector="cargar_post($url2,$id2,$vars2+this.value); ";	
		$funcion_disabled = " ";
		$rela_system_2005 = '';
	}
}

$autor = '';
$autor.= '<select class="form-select form-select-lg" name="" id="" onchange="'.$funcion_selector.'"  '.$funcion_disabled.'>';
$autor.= '		<option >Selecciones Dirigente</option>';

$row = $mysqli -> consulta_SQL("Select * from system_2005_lista_dirigentes ");
if ($row == TRUE)
{
	for ( $i=0; $i < count($row); $i++)
	{
		$id_system_2005 =		$row[$i]['id_system_2005'];
		$system_2005_nombre =	$row[$i]['system_2005_nombre'];
		
		if ($rela_system_2005 == $id_system_2005)
		{
			$autor.="<option value='$id_system_2005' SELECTED >$system_2005_nombre</option>";
		}
		else
		{
			$autor.="<option value='$id_system_2005'>$system_2005_nombre</option>";		
		}
	}
}


$autor.= '		<option value="0">Otros</option>';
$autor.= '</select>';

$url2="'modulos/verificar/php/dirig_abm.php'";
$id2="'cajita'";
$vars2="'id_system_01=$id_system_01'";
$funcion_agre_dirig="cargar_post($url2,$id2,$vars2); ";
		
$autor.= ' <strong type="button" onClick="'.$funcion_agre_dirig.'" >+</strong> ';	
$t->set_var("formulario_autor",$autor);

//echo $autor;



				
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
			 	
					$row2 =  $mysqli -> consulta_SQL("Select * from system_2004_nuevos_avales  where system_2004_dni = '$variable_buscar' ");
					if ($row2 == TRUE)
					{	 	 	 	 	 	
						
						$respuesta = '<div class="responder" style=" background: #FFCCCC ; color: RED; text-align:center;">';
						$respuesta.= '<h1>DNI: <span class="dni">'.$row2[0]['system_2004_dni'].'</span></h1>';
						$respuesta.= '<br><br>';
						$respuesta.= '<div ><h3>YA ESTA EN AVALES 2026!</h3></div>'.$row2[0]['system_2004_fecha'].'';
						$respuesta.= '<br>';
						$respuesta.= '<h1>DESCARTAR!</h1>';
						$respuesta.= '<br></div>';
				
					}
					else
					{
						$url="'modulos/verificar/php/_interfaz.php'";
						$vars="'nombre_funcion=nuevos_aval&";
						$vars.="system_2004_dni=$variable_buscar'";
					
						$url_exito="'modulos/verificar/php/home.php'";
						$id="'content_seccion'";
						$vars_exito="'id_system_01=$id_system_01'";
						$funcion_agregar_aval = " guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito); ";
										
						$respuesta = '<div class="responder" style=" background:#99FFCC ; color: #000; text-align:center;">';
						$respuesta.= '<h1>DNI: <span class="dni">'.$row[0]['system_700_dni'].'</span></h1>';
						$respuesta.= '<div >'.$row[0]['system_700_apellido'].', '.$row[0]['system_700_nombre'].'</div>';
						$respuesta.= '<div >Domicilio: '.$row[0]['system_700_domicilio'].'</div>';
						$respuesta.= '<div >'.$row[0]['system_700_localidad'].' - '.$row[0]['system_700_dpto'].'</div>';
						$respuesta.= '<br><br>';
						$respuesta.= '<div ><h3>Es afiliado al PARTIDO JUSTICIALISTA!</h3></div>';
						$respuesta.= '<button type="button" onclick="'.$funcion_agregar_aval.'" class="btn btn-success btn-lg w-100 mb-3">Hacer AVAL 2026</button>';
						$respuesta.= '<br><br>';
						$respuesta.= '</div>';	
					}
					
				
			}
			else
			{
				
		
					$row2 =  $mysqli -> consulta_SQL("Select * from system_2003_nuevos_tramites  where system_2003_dni = '$variable_buscar' ");
					if ($row2 == TRUE)
					{	 	 	 	 	 	
						
						$respuesta = '<div class="responder" style=" background:#FFFF99 ; color: #000; text-align:center;">';
						$respuesta.= '<h1>DNI: <span class="dni">'.$row2[0]['system_2003_dni'].'</span></h1>';
						$respuesta.= '<br><br>';
						$respuesta.= '<div ><h3>YA ESTA FICHADO/A EN 2026!</h3></div>'.$row2[0]['system_2003_fecha'].' ('.$row2[0]['system_2003_dirigente'].')';
						$respuesta.= '<br>';
						$respuesta.= '<h1>Guardar para AVAL FUTURO!</h1>';
						$respuesta.= '<br></div>';
				
					}
					else
					{
					
							
							$digit_dni = substr("$variable_buscar", -1);
							
							if ($digit_dni == 1)
							{
							$where = "Select * from system_2000_padron_1  where system_2000_dni like '%$variable_buscar%' ";
							}
							else
							if ($digit_dni == 2)
							{
							$where = "Select * from system_2000_padron_2  where system_2000_dni like '%$variable_buscar%' ";
							}
							else
							if ($digit_dni == 3)
							{
							$where ="Select * from system_2000_padron_3  where system_2000_dni like '%$variable_buscar%' ";
							}
							else
							if ($digit_dni == 4)
							{
							$where = "Select * from system_2000_padron_4  where system_2000_dni like '%$variable_buscar%' ";
							}
							else
							if ($digit_dni == 5)
							{
							$where = "Select * from system_2000_padron_5  where system_2000_dni like '%$variable_buscar%' ";
							}
							else
							if ($digit_dni == 6)
							{
							$where = "Select * from system_2000_padron_6  where system_2000_dni like '%$variable_buscar%' ";
							}
							else
							if ($digit_dni == 7)
							{
							$where = "Select * from system_2000_padron_7  where system_2000_dni like '%$variable_buscar%' ";
							}
							else
							if ($digit_dni == 8)
							{
							$where = "Select * from system_2000_padron_8  where system_2000_dni like '%$variable_buscar%' ";
							}
							else
							if ($digit_dni == 9)
							{
							$where = "Select * from system_2000_padron_9  where system_2000_dni like '%$variable_buscar%' ";
							}
							else
							{
							$where = "Select * from system_2000_padron_0  where system_2000_dni = '$variable_buscar' ";
							}
							
							$row = $mysqli -> consulta_SQL("$where ");				
							if($row == true)
							{					
								$system_2000_dni = 		$row[0]['system_2000_dni'];
								$system_2000_crto = 	$row[0]['system_2000_crto'];
								$system_2000_domicilio = $row[0]['system_2000_domicilio'];
								$system_2000_apellido_nombre = 	$row[0]['system_2000_apellido_nombre'];
								
								$url="'modulos/verificar/php/_interfaz.php'";
								$vars="'nombre_funcion=nuevos_tramites&";
								$vars.="system_2001_dni=$variable_buscar'";
							
								$url_exito="'modulos/verificar/php/home.php'";
								$id="'content_seccion'";
								$vars_exito="'id_system_01=$id_system_01'";
								$funcion_agregar_afiliacion = " guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito); ";
								
								$respuesta = '<div class="responder" style="color:#006600; text-align:center;">';
								$respuesta.= '<h1>DNI: <span class="dni">'.$system_2000_dni.'</span></h1>';
								$respuesta.= '<div >'.$system_2000_apellido_nombre.'</div>';
								$respuesta.= '<div >Domicilio: '.$system_2000_domicilio.'</div>';
								$respuesta.= '<div >'.localidad_por_circuito($system_2000_crto,$mysqli).'</div>';
								$respuesta.= '<br><br>';
								$respuesta.= '<div ><h1>NO ES AFILIADO/A!</h1></div>';
								$respuesta.= '<button type="button" onclick="'.$funcion_agregar_afiliacion.'" class="btn btn-success btn-lg w-100 mb-3">Hacer nueva ficha 2026</button>';
								$respuesta.= '<br><br>';
								$respuesta.= '</div>';
							
							}
							else
							{
								$respuesta = '<div class="responder" style="color: RED; text-align:center;">';
								$respuesta.= '<h1>DNI: <span class="dni">'.$variable_buscar.'</span></h1>';
								$respuesta.= '<div >...</div>';
								$respuesta.= '<div >...</div>';
								$respuesta.= '<div >...</div>';
								$respuesta.= '<br><br>';
								$respuesta.= '<div ><h3>NO SE ENCONTRO EN EL PADRON 2025...</h3></div>';
								$respuesta.= '';
								$respuesta.= '<br><br>';
								$respuesta.= '</div>';
							}
	
						
						
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
	$respuesta = '<div class="responder align-center"><h2>Verificaci&oacute;n para avales y fichas 2026</h2></div>';
}

									
$t->set_var("RESULTADO",$respuesta);



// VERIFICAR DNI
$urlb="'modulos/verificar/php/home.php'";
$idb="'content_seccion'";
$varsb="'id_system_01=$id_system_01&variable_buscar='+busqueda.variable_buscar.value";
$t->set_var("funcion_busqueda","cargar_post($urlb,$idb,$varsb)");
$t->set_var("funcion_busqueda_enter","pinchar_enter(event,$urlb,$idb,$varsb)");


$total_total=' ';
$informe='';
if ( $sesion_system_07=='1' )
{	 	 	 	 	 	
	
	$tto = $mysqli -> consulta_SQL("Select COUNT(*) as total_total from system_2003_nuevos_tramites  ");
	if ($tto == TRUE)
	{		
		$total_total = $tto[0]['total_total'];
	}


	$urlb="'modulos/verificar/php/informe.php'";
	$idb="'content_seccion'";
	$varsb="'id_system_01=$id_system_01'";
	$informe = "cargar_post($urlb,$idb,$varsb);";

}
$t->set_var("informe",$informe);
$t->set_var("total_total",$total_total);

$t->pparse("OUT", "ver");
?>