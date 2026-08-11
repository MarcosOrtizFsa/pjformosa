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
	'ver' => "home.html"
));

const ANO_ESTADISTICAS = 2026;

function tarjeta_estadistica($titulo, $presentes, $total, $destacada = false)
{
	$presentes = (int) $presentes;
	$total = (int) $total;
	$porcentaje = $total > 0 ? (int) round(($presentes * 100) / $total) : 0;
	$porcentaje = max(0, min(100, $porcentaje));
	$titulo_seguro = htmlspecialchars((string) $titulo, ENT_QUOTES, 'UTF-8');
	$clase = $destacada ? ' stats-card--featured' : '';

	$cadena = '<article class="stats-card'.$clase.'">';
	$cadena .= '<div class="stats-card__header">';
	$cadena .= '<div><span class="stats-card__eyebrow">'.($destacada ? 'Resumen general' : 'Departamento').'</span>';
	$cadena .= '<h3>'.$titulo_seguro.'</h3></div>';
	$cadena .= '<strong class="stats-card__percent">'.$porcentaje.'%</strong>';
	$cadena .= '</div>';
	$cadena .= '<div class="progress stats-progress" role="progressbar" aria-label="Asistencia en '.$titulo_seguro.'" aria-valuenow="'.$porcentaje.'" aria-valuemin="0" aria-valuemax="100">';
	$cadena .= '<div class="progress-bar" style="width: '.$porcentaje.'%"></div>';
	$cadena .= '</div>';
	$cadena .= '<div class="stats-card__footer">';
	$cadena .= '<span><strong>'.$presentes.'</strong> presentes</span>';
	$cadena .= '<span>'.$total.' congresistas</span>';
	$cadena .= '</div>';
	$cadena .= '</article>';

	return $cadena;
}

$contenido_estadisticas = '';
$cantidad_departamentos = 0;

if (optener_permisos('V', $id_system_01, $sesion_system_03, $mysqli) == '1')
{
	$consulta = "SELECT
					system_100_departamento AS departamento,
					COUNT(*) AS total,
					SUM(CASE WHEN system_100_estado = 1 THEN 1 ELSE 0 END) AS presentes
				FROM system_100_congresistas
				WHERE system_100_ano = '".ANO_ESTADISTICAS."'
				AND system_100_estado IN (0, 1)
				GROUP BY system_100_departamento
				ORDER BY system_100_departamento ASC";

	$resultados = $mysqli->consulta_SQL($consulta);
	$total_general = 0;
	$presentes_general = 0;
	$tarjetas_departamentos = '';

	if (is_array($resultados))
	{
		foreach ($resultados as $resultado)
		{
			$total = (int) $resultado['total'];
			$presentes = (int) $resultado['presentes'];
			$departamento = trim((string) $resultado['departamento']);
			if ($departamento === '')
			{
				$departamento = 'Sin departamento';
			}

			$total_general += $total;
			$presentes_general += $presentes;
			$cantidad_departamentos++;
			$tarjetas_departamentos .= tarjeta_estadistica($departamento, $presentes, $total);
		}
	}

	$contenido_estadisticas .= '<section class="stats-overview">';
	$contenido_estadisticas .= tarjeta_estadistica('Todos los departamentos', $presentes_general, $total_general, true);
	$contenido_estadisticas .= '</section>';

	if ($cantidad_departamentos > 0)
	{
		$contenido_estadisticas .= '<div class="stats-section-title"><div><span>Detalle territorial</span><h2>Asistencia por departamento</h2></div><small>'.$cantidad_departamentos.' departamentos</small></div>';
		$contenido_estadisticas .= '<section class="stats-grid">'.$tarjetas_departamentos.'</section>';
	}
	else
	{
		$contenido_estadisticas .= '<div class="stats-empty"><i class="bi bi-bar-chart" aria-hidden="true"></i><p>Todavía no hay congresistas cargados para '.ANO_ESTADISTICAS.'.</p></div>';
	}
}
else
{
	$contenido_estadisticas = '<div class="alert alert-warning my-4" role="alert">No tenés permisos para consultar las estadísticas de asistencia.</div>';
}

$t->set_var("contenido_estadisticas", $contenido_estadisticas);
$t->set_var("ano_estadisticas", (string) ANO_ESTADISTICAS);
$t->set_var("hora_actualizacion", date('H:i'));

$url = "'modulos/home/php/home.php'";
$id = "'content_seccion'";
$vars = "'id_system_01=$id_system_01'";
$t->set_var("funcion_actualiar", "cargar_post($url,$id,$vars);");

$t->pparse("OUT", "ver");
?>
