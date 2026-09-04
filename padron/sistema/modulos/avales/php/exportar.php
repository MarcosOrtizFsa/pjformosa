<?php
declare(strict_types=1);
require_once __DIR__.'/avales_bootstrap.php';
$pdo=avales_pdo();avales_usuario();
if(!avales_permiso($pdo,'D')&&!avales_permiso($pdo,'V')){http_response_code(403);exit('No tenés permiso para descargar folios.');}
$folioId=(int)($_GET['folio_id']??0);
$stmt=$pdo->prepare("SELECT f.numero,f.fecha,c.nombre campana,s.nombre sede FROM padron_folios_avales f INNER JOIN padron_campanas_avales c ON c.id=f.campana_id LEFT JOIN padron_sedes_avales s ON s.id=f.sede_id WHERE f.id=?");
$stmt->execute([$folioId]);$folio=$stmt->fetch();if(!$folio){http_response_code(404);exit('Folio inexistente.');}
$stmt=$pdo->prepare("SELECT a.posicion,p.dni,p.apellido,p.nombre,p.sexo,d.domicilio,t.circuito,t.localidad,dep.nombre departamento,a.estado FROM padron_avales a INNER JOIN padron_personas p ON p.id=a.persona_id LEFT JOIN padron_domicilios d ON d.persona_id=p.id AND d.vigente_hasta IS NULL LEFT JOIN padron_territorios t ON t.id=d.territorio_id LEFT JOIN padron_departamentos dep ON dep.id=t.departamento_id WHERE a.folio_id=? ORDER BY a.posicion,a.id");
$stmt->execute([$folioId]);
header('Content-Type: text/csv; charset=UTF-8');header('Content-Disposition: attachment; filename="folio-'.(int)$folio['numero'].'.csv"');header('Cache-Control: no-store');
$salida=fopen('php://output','wb');fwrite($salida,"\xEF\xBB\xBF");
fputcsv($salida,['Folio','Fecha','Campaña','Sede','Posición','DNI','Apellido','Nombres','Sexo','Domicilio','Circuito','Localidad','Departamento','Estado'],';');
while($fila=$stmt->fetch())fputcsv($salida,[$folio['numero'],$folio['fecha'],$folio['campana'],$folio['sede'],$fila['posicion'],ltrim($fila['dni'],'0'),$fila['apellido'],$fila['nombre'],$fila['sexo'],$fila['domicilio'],$fila['circuito'],$fila['localidad'],$fila['departamento'],$fila['estado']],';');
fclose($salida);
