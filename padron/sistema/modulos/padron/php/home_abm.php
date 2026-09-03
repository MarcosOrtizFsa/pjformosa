<?php
session_start();
include "../../../../lib/template.inc";
include "../../../../lib/mysql_conect.php";
include "../../../php/constructor_sql.php";
include "../../../php/abm.php";
include "../../../php/funciones.php";

$t = new _template('../templates');
//Archivos comunes
$t->set_file(array(
	'ver'		=> "home_abm.html",
	'select'	=> "una_opcion.html"
	));

$system_2000_dni = 	isset($_POST['system_2000_dni']) ? $_POST['system_2000_dni'] : NULL;
$system_2000_dni =	formatear_dni($system_2000_dni);		
$digit_dni = substr("$system_2000_dni", -1);
		
	if ($digit_dni == 1)
	{
		$where = "Select * from system_2000_padron_1  where system_2000_dni = '$system_2000_dni' ";
	}
	else
	if ($digit_dni == 2)
	{
		$where = "Select * from system_2000_padron_2  where system_2000_dni = '$system_2000_dni' ";
	}
	else
	if ($digit_dni == 3)
	{
		$where ="Select * from system_2000_padron_3  where system_2000_dni = '$system_2000_dni' ";
	}
	else
	if ($digit_dni == 4)
	{
		$where = "Select * from system_2000_padron_4  where system_2000_dni = '$system_2000_dni' ";
	}
	else
	if ($digit_dni == 5)
	{
		$where = "Select * from system_2000_padron_5  where system_2000_dni = '$system_2000_dni' ";
	}
	else
	if ($digit_dni == 6)
	{
		$where = "Select * from system_2000_padron_6  where system_2000_dni = '$system_2000_dni' ";
	}
	else
	if ($digit_dni == 7)
	{
		$where = "Select * from system_2000_padron_7  where system_2000_dni = '$system_2000_dni' ";
	}
	else
	if ($digit_dni == 8)
	{
		$where = "Select * from system_2000_padron_8  where system_2000_dni = '$system_2000_dni' ";
	}
	else
	if ($digit_dni == 9)
	{
		$where = "Select * from system_2000_padron_9  where system_2000_dni = '$system_2000_dni' ";
	}
	else
	{
		$where = "Select * from system_2000_padron_0  where system_2000_dni = '$system_2000_dni' ";
	}
		
			
	$row = $mysqli -> consulta_SQL("$where");				
	if ($row == true)
	{			
		$system_2000_dni = 		$row[0]['system_2000_dni'];
		$rela_system_504 = 		$row[0]['rela_system_504'];
		$system_2000_apellido=	$row[0]['system_2000_apellido'];
		$system_2000_nombre=	$row[0]['system_2000_nombre'];
		$system_2000_sexo=		$row[0]['system_2000_sexo'];
		$system_2000_tipo_dni =	$row[0]['system_2000_tipo_dni'];
		$system_2000_circuito =	$row[0]['system_2000_circuito'];
		$system_2000_clase =	$row[0]['system_2000_clase'];	
		$system_2000_domicilio =$row[0]['system_2000_domicilio'];
		$t->set_var("disabled",	"disabled");
		$tipo_am=1;	
	}
	else
	{
		$tipo_am=0;
		$rela_system_504 = 		'';
		$system_2000_apellido=	'';
		$system_2000_nombre=	'';
		$system_2000_sexo=		'';
		$system_2000_tipo_dni ='';
		$system_2000_circuito =	'';
		$system_2000_clase =	'';	
		$system_2000_domicilio ='';
		$t->set_var("disabled",	"");
	}
 
	$t->set_var("system_2000_nombre",	"$system_2000_nombre");
	$t->set_var("system_2000_apellido",	"$system_2000_apellido");
	$t->set_var("system_2000_dni",		"$system_2000_dni");
	$t->set_var("system_2000_clase",	"$system_2000_clase");
	$t->set_var("system_2000_domicilio","$system_2000_domicilio");
	$t->set_var("system_2000_tipo_dni",	"$system_2000_tipo_dni");
	$t->set_var("system_localidad",pueblo_por_id(0,$rela_system_504,$mysqli));
	$t->set_var("system_2000_circuito",	"$system_2000_circuito");		
	
	$url="'modulos/padron/php/_interfaz.php'";
	$vars="'nombre_funcion=agregar_modificar&tipo_am=$tipo_am&";
	$vars.="system_2000_dni=$system_2000_dni&";
	$vars.="rela_system_504='+abm2.rela_system_504.value+'&";
	$vars.="system_2000_sexo='+abm2.system_2000_sexo.value+'&";
	$vars.="system_2000_circuito='+abm2.system_2000_circuito.value+'&";
	$vars.="system_2000_apellido='+encodeURIComponent(abm2.system_2000_apellido.value)+'&";
	$vars.="system_2000_nombre='+encodeURIComponent(abm2.system_2000_nombre.value)+'&";
	$vars.="system_2000_domicilio='+encodeURIComponent(abm2.system_2000_domicilio.value)";

	$url_exito="'modulos/padron/php/home.php'";
	$id="'content_seccion'";
	$vars_exito="'id_system_01=$id_system_01&variable_buscar=$system_2000_dni&reset=go&'";
	$t->set_var("funcion_guardar","guardar_mostrar($url,$vars,$url_exito,$id,$vars_exito)");

	
	

	$cadena='';
	$row = $mysqli -> consulta_SQL("Select * from system_504_ubicacion  where  system_504_circuito = '$system_2000_circuito'  order by system_504_pueblo ASC ");				
	if($row == true)
	{
		$cadena.="<option value=''>Seleccione localidad</option>";	
		for ( $i=0; $i < count($row); $i++)
		{			
			$id_system_504 = 			$row[$i]['id_system_504'];
			$system_504_pueblo = 		$row[$i]['system_504_pueblo'];
			
			if ($id_system_504 == $rela_system_504)
			{
				$cadena.="<option value='$id_system_504' SELECTED >$system_504_pueblo</option>";
			}
			else
			{
				$cadena.="<option value='$id_system_504'>$system_504_pueblo</option>";		
			}
		}
		$url_exito="'modulos/padron/php/home_abm.php'";
		$id_exito="'content_$system_2000_dni'";
		$vars_exito="'id_system_01=$id_system_01&system_2000_dni=$system_2000_dni&reset=go&'";
	
		$cadena.='<option onmouseup="nuevo_circuito_js(\''.$system_2000_circuito.'\','.$url_exito.','.$id_exito.','.$vars_exito.');"  value="+" style="background:#CCC;"> + Agregar Localidad o Barrio</option>';	
	} 
	else 						
	{
		$cadena.="<option value=''>".$system_2000_circuito." no es un circuito...</option>";	
	}	
	
	$t->set_var("LISTADO_PUEBLOS",$cadena);
	
	
	$sexx = array('M'=>"M", 'F'=>"F"); 
	while ($select_s = current($sexx)) 
	{
		if ( key($sexx) == $system_2000_sexo ) 
		{
			$t->set_var("ID",key($sexx)." SELECTED ");	
		}
		else
		{
			$t->set_var("ID",key($sexx));	
		}
		$t->set_var("NOMBRE",$select_s);	
		$t->parse("SEXO","select",true);
		next($sexx);
	}
	
		
	$url2="'modulos/padron/php/home.php'";
	$id2="'content_seccion'";
	$vars2="''";
	$t->set_var("funcion_volver","cargar_post($url2,$id2,$vars2)");	

		
$t->pparse("OUT", "ver");
?>
