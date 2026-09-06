<?php
declare(strict_types=1);

require_once __DIR__.'/api_clientes_bootstrap.php';
$pdo = api_clientes_pdo();
$usuarioId = api_clientes_usuario();
$csrf = api_clientes_csrf();
$mensaje = null;
$error = null;
$credencialNueva = null;

try {
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['accion'])) {
        api_clientes_validar_csrf();
        $accion = (string) $_POST['accion'];

        if ($accion === 'crear') {
            $nombre = trim((string) ($_POST['nombre'] ?? ''));
            $scopesElegidos = array_values(array_intersect(array_keys(API_CLIENTES_SCOPES), (array) ($_POST['scopes'] ?? [])));
            $limite = filter_var($_POST['limite'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 10000]]);
            $expira = trim((string) ($_POST['expira_en'] ?? ''));
            $ipsTexto = trim((string) ($_POST['ips'] ?? ''));
            if ($nombre === '' || mb_strlen($nombre) > 120) throw new RuntimeException('Indicá un nombre de hasta 120 caracteres.');
            if ($scopesElegidos === []) throw new RuntimeException('Seleccioná al menos un permiso.');
            if ($limite === false) throw new RuntimeException('El límite debe estar entre 0 y 10.000 solicitudes por minuto.');
            if ($expira !== '' && !DateTimeImmutable::createFromFormat('!Y-m-d', $expira)) throw new RuntimeException('La fecha de vencimiento no es válida.');
            $ips = [];
            foreach (preg_split('/[\s,;]+/', $ipsTexto, -1, PREG_SPLIT_NO_EMPTY) ?: [] as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) === false) throw new RuntimeException('La IP '.$ip.' no es válida.');
                $ips[] = $ip;
            }
            $ips = array_values(array_unique($ips));
            $token = api_clientes_token();
            $clientId = api_clientes_uuid();
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('INSERT INTO padron_api_clientes(nombre,client_id,token_hash,scopes,ips_permitidas,limite_por_minuto,expira_en,estado) VALUES(?,?,?,?,?,?,?,1)');
            $stmt->execute([$nombre, $clientId, hash('sha256', $token), implode(',', $scopesElegidos), $ips ? implode(',', $ips) : null, $limite, $expira !== '' ? $expira.' 23:59:59' : null]);
            $clienteId = (int) $pdo->lastInsertId();
            api_clientes_evento($pdo, $clienteId, $usuarioId, 'crear', 'Cliente creado desde el módulo administrativo.');
            $pdo->commit();
            $credencialNueva = ['nombre' => $nombre, 'client_id' => $clientId, 'token' => $token];
            $mensaje = 'Cliente creado. Copiá ahora su token: no podrá recuperarse después.';
        } elseif (in_array($accion, ['activar', 'suspender', 'revocar', 'rotar'], true)) {
            $clienteId = filter_var($_POST['cliente_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($clienteId === false) throw new RuntimeException('El cliente seleccionado no es válido.');
            $stmt = $pdo->prepare('SELECT id,nombre,client_id FROM padron_api_clientes WHERE id=? LIMIT 1');
            $stmt->execute([$clienteId]);
            $cliente = $stmt->fetch();
            if (!$cliente) throw new RuntimeException('El cliente ya no existe.');
            $pdo->beginTransaction();
            if ($accion === 'rotar') {
                $token = api_clientes_token();
                $pdo->prepare('UPDATE padron_api_clientes SET token_hash=?,estado=1,revocado_en=NULL WHERE id=?')->execute([hash('sha256', $token), $clienteId]);
                api_clientes_evento($pdo, $clienteId, $usuarioId, 'rotar', 'El token anterior quedó invalidado.');
                $credencialNueva = ['nombre' => $cliente['nombre'], 'client_id' => $cliente['client_id'], 'token' => $token];
                $mensaje = 'Token rotado. El token anterior dejó de funcionar inmediatamente.';
            } else {
                if ($accion === 'activar') {
                    $estado = $pdo->prepare('SELECT revocado_en FROM padron_api_clientes WHERE id=?');
                    $estado->execute([$clienteId]);
                    if ($estado->fetchColumn()) throw new RuntimeException('Un cliente revocado no puede reactivarse. Rotá su token para emitir una credencial nueva.');
                    $pdo->prepare('UPDATE padron_api_clientes SET estado=1 WHERE id=?')->execute([$clienteId]);
                } elseif ($accion === 'suspender') {
                    $pdo->prepare('UPDATE padron_api_clientes SET estado=0 WHERE id=?')->execute([$clienteId]);
                } else {
                    // Revocar también destruye el hash anterior. Solo una
                    // rotación posterior puede emitir una credencial válida.
                    $pdo->prepare('UPDATE padron_api_clientes SET token_hash=?,estado=0,revocado_en=NOW() WHERE id=?')->execute([hash('sha256', api_clientes_token()), $clienteId]);
                }
                api_clientes_evento($pdo, $clienteId, $usuarioId, $accion, null);
                $mensaje = $accion === 'activar' ? 'Cliente reactivado.' : ($accion === 'suspender' ? 'Cliente suspendido.' : 'Cliente revocado.');
            }
            $pdo->commit();
        }
    }
} catch (Throwable $ex) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $error = $ex instanceof RuntimeException ? $ex->getMessage() : 'No se pudo completar la operación.';
    if (!$ex instanceof RuntimeException) error_log('API CLIENTES: '.$ex->getMessage());
}

$filtroCliente = max(0, (int) ($_POST['filtro_cliente'] ?? 0));
$filtroHttp = trim((string) ($_POST['filtro_http'] ?? ''));
$clientes = $pdo->query("SELECT c.*,
    (SELECT COUNT(*) FROM padron_api_registros r WHERE r.cliente_id=c.id AND r.creado_en>=NOW()-INTERVAL 24 HOUR) solicitudes_24h,
    (SELECT COUNT(*) FROM padron_api_registros r WHERE r.cliente_id=c.id AND r.estado_http>=400 AND r.creado_en>=NOW()-INTERVAL 24 HOUR) errores_24h
    FROM padron_api_clientes c ORDER BY c.estado DESC,c.nombre")->fetchAll();
$condiciones = [];
$parametros = [];
if ($filtroCliente > 0) { $condiciones[] = 'r.cliente_id=?'; $parametros[] = $filtroCliente; }
if (preg_match('/^[1-5][0-9]{2}$/', $filtroHttp)) { $condiciones[] = 'r.estado_http=?'; $parametros[] = (int) $filtroHttp; }
$sqlAuditoria = 'SELECT r.*,c.nombre cliente FROM padron_api_registros r LEFT JOIN padron_api_clientes c ON c.id=r.cliente_id'.($condiciones ? ' WHERE '.implode(' AND ', $condiciones) : '').' ORDER BY r.id DESC LIMIT 100';
$stmt = $pdo->prepare($sqlAuditoria);$stmt->execute($parametros);$registros = $stmt->fetchAll();
$eventos = $pdo->query('SELECT e.*,c.nombre cliente FROM padron_api_eventos e LEFT JOIN padron_api_clientes c ON c.id=e.cliente_id ORDER BY e.id DESC LIMIT 20')->fetchAll();
$totales = $pdo->query("SELECT COUNT(*) total,SUM(estado=1) activos,SUM(estado=0) inactivos FROM padron_api_clientes")->fetch();
$urlHome = "'modulos/api_clientes/php/home.php'";
?>
<style>
.api-admin{--api-azul:#075a9c;color:#17324a}.api-hero{background:linear-gradient(120deg,#064f8a,#168fca);color:#fff;border-radius:0 0 1.25rem 1.25rem}.api-card{border:0;border-radius:1rem;box-shadow:0 .5rem 1.4rem rgba(20,70,105,.09)}.api-code{font-family:ui-monospace,SFMono-Regular,Consolas,monospace;word-break:break-all}.api-dot{width:.7rem;height:.7rem;border-radius:50%;display:inline-block}.api-table{font-size:.9rem}.api-table td{vertical-align:middle}
</style>
<section class="api-admin pb-5">
<div class="api-hero px-3 px-lg-5 py-4 mb-4"><div class="container-fluid"><div class="row align-items-center g-3"><div class="col-lg-7"><div class="small text-uppercase opacity-75">Integraciones privadas</div><h2 class="h3 mb-1">Clientes de la API</h2><p class="mb-0 opacity-75">Emití credenciales, limitá permisos y auditá el consumo de otros proyectos.</p></div><div class="col-lg-5"><div class="row g-2 text-center"><div class="col"><div class="bg-white bg-opacity-10 rounded-3 p-2"><strong class="fs-4"><?= (int)$totales['total'] ?></strong><div class="small">Clientes</div></div></div><div class="col"><div class="bg-white bg-opacity-10 rounded-3 p-2"><strong class="fs-4"><?= (int)$totales['activos'] ?></strong><div class="small">Activos</div></div></div><div class="col"><div class="bg-white bg-opacity-10 rounded-3 p-2"><strong class="fs-4"><?= (int)$totales['inactivos'] ?></strong><div class="small">Inactivos</div></div></div></div></div></div></div></div>
<div class="container-fluid px-lg-5">
<?php if($mensaje):?><div class="alert alert-success api-card"><?=api_clientes_h($mensaje)?></div><?php endif?>
<?php if($error):?><div class="alert alert-danger api-card"><?=api_clientes_h($error)?></div><?php endif?>
<?php if($credencialNueva):?><div class="card api-card border border-warning mb-4"><div class="card-body p-4"><div class="d-flex justify-content-between align-items-start gap-3"><div><span class="badge text-bg-warning mb-2">Visible una sola vez</span><h3 class="h5"><?=api_clientes_h($credencialNueva['nombre'])?></h3></div><button class="btn btn-outline-primary btn-sm" onclick="navigator.clipboard.writeText(document.getElementById('credencialApi').innerText)"><i class="bi bi-copy"></i> Copiar</button></div><div id="credencialApi" class="bg-light rounded p-3 api-code">PADRON_API_CLIENT_ID=<?=api_clientes_h($credencialNueva['client_id'])?><br>PADRON_API_TOKEN=<?=api_clientes_h($credencialNueva['token'])?></div><p class="small text-danger mb-0 mt-2">Guardalo como secreto del proyecto. Al abandonar esta pantalla el token no podrá recuperarse.</p></div></div><?php endif?>
<div class="row g-4"><div class="col-xl-4"><div class="card api-card"><div class="card-body p-4"><h3 class="h5 mb-3">Crear cliente</h3><form onsubmit="cargar_post(<?=$urlHome?>,'content_seccion',new URLSearchParams(new FormData(this)).toString());return false"><input type="hidden" name="accion" value="crear"><input type="hidden" name="csrf" value="<?=api_clientes_h($csrf)?>"><div class="mb-3"><label class="form-label">Proyecto consumidor</label><input class="form-control" name="nombre" maxlength="120" required placeholder="Portal de consultas"></div><fieldset class="mb-3"><legend class="form-label">Permisos</legend><?php foreach(API_CLIENTES_SCOPES as $scope=>$descripcion):?><label class="d-flex gap-2 mb-2"><input class="form-check-input" type="checkbox" name="scopes[]" value="<?=api_clientes_h($scope)?>"><span><code><?=api_clientes_h($scope)?></code><small class="d-block text-secondary"><?=api_clientes_h($descripcion)?></small></span></label><?php endforeach?></fieldset><div class="row g-3 mb-3"><div class="col-6"><label class="form-label">Límite/min.</label><input class="form-control" type="number" name="limite" min="0" max="10000" value="120" required></div><div class="col-6"><label class="form-label">Vence</label><input class="form-control" type="date" name="expira_en"></div></div><div class="mb-3"><label class="form-label">IPs permitidas</label><textarea class="form-control" name="ips" rows="2" placeholder="Una o varias IP; vacío permite todas"></textarea><div class="form-text">Separadas por coma o espacio.</div></div><button class="btn btn-primary w-100"><i class="bi bi-key me-1"></i> Generar credenciales</button></form></div></div></div>
<div class="col-xl-8"><div class="card api-card"><div class="card-body p-4"><div class="d-flex justify-content-between align-items-center mb-3"><h3 class="h5 mb-0">Proyectos autorizados</h3><span class="small text-secondary">El token nunca se muestra nuevamente</span></div><div class="table-responsive"><table class="table api-table align-middle"><thead><tr><th>Cliente</th><th>Permisos</th><th>Uso</th><th>Estado</th><th></th></tr></thead><tbody><?php foreach($clientes as $cliente):?><tr><td><strong><?=api_clientes_h($cliente['nombre'])?></strong><div class="small text-secondary api-code"><?=api_clientes_h($cliente['client_id'])?></div><div class="small text-secondary">Último uso: <?=api_clientes_h($cliente['ultimo_uso_en']?:'Nunca')?></div></td><td><?php foreach(array_filter(explode(',',(string)$cliente['scopes'])) as $scope):?><span class="badge text-bg-light me-1 mb-1"><?=api_clientes_h($scope)?></span><?php endforeach?><div class="small text-secondary"><?= (int)$cliente['limite_por_minuto'] ?> solicitudes/min.</div></td><td><strong><?=number_format((int)$cliente['solicitudes_24h'],0,',','.')?></strong> / 24 h<div class="small <?= (int)$cliente['errores_24h']?'text-danger':'text-secondary'?>"><?= (int)$cliente['errores_24h'] ?> errores</div></td><td><span class="api-dot <?= (int)$cliente['estado']?'bg-success':'bg-secondary'?> me-1"></span><?= (int)$cliente['estado']?'Activo':($cliente['revocado_en']?'Revocado':'Suspendido')?><div class="small text-secondary"><?=api_clientes_h($cliente['expira_en']?'Vence '.$cliente['expira_en']:'Sin vencimiento')?></div></td><td><div class="dropdown"><button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Acciones</button><ul class="dropdown-menu dropdown-menu-end"><?php if((int)$cliente['estado']):?><li><button class="dropdown-item" onclick="apiClienteAccion('suspender',<?=(int)$cliente['id']?>,'¿Suspender este cliente?')">Suspender</button></li><?php else:?><li><button class="dropdown-item" onclick="apiClienteAccion('activar',<?=(int)$cliente['id']?>,'¿Reactivar este cliente?')">Reactivar</button></li><?php endif?><li><button class="dropdown-item" onclick="apiClienteAccion('rotar',<?=(int)$cliente['id']?>,'El token anterior dejará de funcionar. ¿Continuar?')">Rotar token</button></li><li><hr class="dropdown-divider"></li><li><button class="dropdown-item text-danger" onclick="apiClienteAccion('revocar',<?=(int)$cliente['id']?>,'¿Revocar inmediatamente este cliente?')">Revocar</button></li></ul></div></td></tr><?php endforeach?><?php if(!$clientes):?><tr><td colspan="5" class="text-center text-secondary py-5">Todavía no existen clientes.</td></tr><?php endif?></tbody></table></div></div></div></div></div>
<div class="card api-card mt-4"><div class="card-body p-4"><div class="row align-items-end g-2 mb-3"><div class="col"><h3 class="h5 mb-0">Solicitudes recientes</h3></div><div class="col-md-3"><label class="form-label small">Cliente</label><select id="apiFiltroCliente" class="form-select form-select-sm"><option value="0">Todos</option><?php foreach($clientes as $cliente):?><option value="<?=(int)$cliente['id']?>" <?=$filtroCliente===(int)$cliente['id']?'selected':''?>><?=api_clientes_h($cliente['nombre'])?></option><?php endforeach?></select></div><div class="col-md-2"><label class="form-label small">HTTP</label><input id="apiFiltroHttp" class="form-control form-control-sm" inputmode="numeric" maxlength="3" value="<?=api_clientes_h($filtroHttp)?>" placeholder="Todos"></div><div class="col-auto"><button class="btn btn-sm btn-primary" onclick="apiClienteFiltrar()">Filtrar</button></div></div><div class="table-responsive"><table class="table table-sm api-table"><thead><tr><th>Fecha</th><th>Cliente</th><th>Método y ruta</th><th>HTTP</th><th>Duración</th><th>IP</th></tr></thead><tbody><?php foreach($registros as $registro):?><tr><td><?=api_clientes_h($registro['creado_en'])?></td><td><?=api_clientes_h($registro['cliente']?:'No autenticado')?></td><td><span class="badge text-bg-light"><?=api_clientes_h($registro['metodo'])?></span> <span class="api-code"><?=api_clientes_h($registro['ruta'])?></span></td><td><span class="badge <?= (int)$registro['estado_http']<400?'text-bg-success':'text-bg-danger'?>"><?=(int)$registro['estado_http']?></span></td><td><?=(int)$registro['duracion_ms']?> ms</td><td class="api-code"><?=api_clientes_h($registro['ip'])?></td></tr><?php endforeach?><?php if(!$registros):?><tr><td colspan="6" class="text-center text-secondary py-4">No hay solicitudes para este filtro.</td></tr><?php endif?></tbody></table></div></div></div>
<div class="card api-card mt-4"><div class="card-body p-4"><h3 class="h5 mb-3">Eventos administrativos</h3><div class="table-responsive"><table class="table table-sm api-table"><thead><tr><th>Fecha</th><th>Cliente</th><th>Acción</th><th>Usuario</th><th>Detalle</th></tr></thead><tbody><?php foreach($eventos as $evento):?><tr><td><?=api_clientes_h($evento['creado_en'])?></td><td><?=api_clientes_h($evento['cliente']?:'Cliente eliminado')?></td><td><span class="badge text-bg-light"><?=api_clientes_h($evento['accion'])?></span></td><td>#<?=(int)$evento['usuario_id']?></td><td><?=api_clientes_h($evento['detalle'])?></td></tr><?php endforeach?><?php if(!$eventos):?><tr><td colspan="5" class="text-center text-secondary">Todavía no hay eventos.</td></tr><?php endif?></tbody></table></div></div></div>
</div></section>
<script>
function apiClientePost(datos){cargar_post(<?=$urlHome?>,'content_seccion',new URLSearchParams(datos).toString())}
function apiClienteAccion(accion,id,pregunta){if(confirm(pregunta))apiClientePost({accion,cliente_id:String(id),csrf:<?=json_encode($csrf)?>})}
function apiClienteFiltrar(){apiClientePost({filtro_cliente:document.getElementById('apiFiltroCliente').value,filtro_http:document.getElementById('apiFiltroHttp').value})}
</script>
