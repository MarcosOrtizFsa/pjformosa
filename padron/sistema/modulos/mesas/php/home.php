<?php
declare(strict_types=1);

session_start();
require_once dirname(__DIR__, 4).'/lib/mysql_conect.php';
require_once dirname(__DIR__, 4).'/lib/PadronConsulta.php';

if (empty($_SESSION['sesion_system_03']) || empty($_SESSION['sesion_system_07'])) {
    http_response_code(403);
    echo '<div class="alert alert-danger m-4">La sesión venció. Ingresá nuevamente.</div>';
    exit;
}

$pdo = new PDO('mysql:host='.HOST.';dbname='.BD.';charset=utf8mb4', USU, CLA, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
$version = PadronConsulta::versionActiva($pdo);
$idModulo = (int) ($_GET['id_system_01'] ?? $_POST['id_system_01'] ?? 0);
$mesaBuscada = trim((string) ($_POST['mesa'] ?? ''));
$escuelaBuscada = trim((string) ($_POST['escuela'] ?? ''));
$escuelaId = (int) ($_POST['escuela_id'] ?? 0);
$error = null;
$resultadoMesa = [];
$resultadosEscuela = [];
$mesasPorEscuela = [];
$resumen = ['mesas' => 0, 'escuelas' => 0, 'electores' => 0];

if ($version) {
    $versionId = (int) $version['id'];
    $stmt = $pdo->prepare('SELECT COUNT(DISTINCT mesa) AS mesas, COUNT(DISTINCT escuela_id) AS escuelas, COUNT(*) AS electores FROM padron_version_personas WHERE version_id=?');
    $stmt->execute([$versionId]);
    $resumen = $stmt->fetch() ?: $resumen;

    if ($mesaBuscada !== '') {
        if (!ctype_digit($mesaBuscada) || (int) $mesaBuscada <= 0) {
            $error = 'Ingresá un número de mesa válido.';
        } else {
            $stmt = $pdo->prepare('SELECT vp.mesa, COUNT(*) AS electores, MIN(vp.orden) AS orden_desde, MAX(vp.orden) AS orden_hasta,
                    esc.id AS escuela_id, esc.nombre AS escuela_nombre, esc.domicilio AS escuela_domicilio
                FROM padron_version_personas vp
                LEFT JOIN padron_escuelas esc ON esc.id=vp.escuela_id
                WHERE vp.version_id=? AND vp.mesa=?
                GROUP BY vp.mesa,esc.id,esc.nombre,esc.domicilio ORDER BY esc.nombre');
            $stmt->execute([$versionId, (int) $mesaBuscada]);
            $resultadoMesa = $stmt->fetchAll();
        }
    } elseif ($escuelaId > 0 || $escuelaBuscada !== '') {
        $sql = 'SELECT esc.id,esc.nombre,esc.domicilio,COUNT(DISTINCT vp.mesa) AS total_mesas,COUNT(*) AS electores,
                       MIN(vp.mesa) AS mesa_desde,MAX(vp.mesa) AS mesa_hasta
                FROM padron_escuelas esc
                INNER JOIN padron_version_personas vp ON vp.escuela_id=esc.id AND vp.version_id=:version_id ';
        $parametros = ['version_id' => $versionId];
        if ($escuelaId > 0) {
            $sql .= 'WHERE esc.id=:escuela_id ';
            $parametros['escuela_id'] = $escuelaId;
        } else {
            $sql .= 'WHERE esc.nombre LIKE :escuela ';
            $parametros['escuela'] = '%'.$escuelaBuscada.'%';
        }
        $sql .= 'GROUP BY esc.id,esc.nombre,esc.domicilio ORDER BY esc.nombre LIMIT 25';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($parametros);
        $resultadosEscuela = $stmt->fetchAll();

        if ($resultadosEscuela) {
            $ids = array_column($resultadosEscuela, 'id');
            $marcas = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $pdo->prepare("SELECT escuela_id,mesa,COUNT(*) AS electores,MIN(orden) AS orden_desde,MAX(orden) AS orden_hasta
                FROM padron_version_personas WHERE version_id=? AND escuela_id IN ($marcas)
                GROUP BY escuela_id,mesa ORDER BY escuela_id,mesa");
            $stmt->execute(array_merge([$versionId], $ids));
            foreach ($stmt->fetchAll() as $mesa) {
                $mesasPorEscuela[(int) $mesa['escuela_id']][] = $mesa;
            }
        }
    }
}

function hm(?string $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function numero_mesas(int|string|null $valor): string
{
    return number_format((int) $valor, 0, ',', '.');
}

$url = "'modulos/mesas/php/home.php?id_system_01={$idModulo}'";
?>
<style>
    .mesas-modulo { --mesa-azul:#075a9c; color:#17324a; }
    .mesas-hero { background:linear-gradient(120deg,#064f8a,#168fca); color:#fff; border-radius:0 0 1.25rem 1.25rem; }
    .mesas-card { border:0; border-radius:1rem; box-shadow:0 .5rem 1.4rem rgba(20,70,105,.09); }
    .mesa-numero { font-size:2rem; line-height:1; font-weight:800; color:var(--mesa-azul); }
    .mesa-chip { border:1px solid #b9d8eb; background:#f1f9fe; color:#075a9c; border-radius:.75rem; padding:.55rem .7rem; cursor:pointer; text-align:left; }
    .mesa-chip:hover { background:#dff2fd; border-color:#168fca; }
    .dato-secundario { color:#688093; font-size:.85rem; }
</style>

<section class="mesas-modulo pb-5">
    <div class="mesas-hero px-3 px-lg-5 py-4 mb-4"><div class="container-fluid"><div class="row align-items-center g-3">
        <div class="col-lg-5"><div class="small text-uppercase opacity-75">Organización electoral</div><h2 class="h3 mb-1">Mesas y escuelas</h2>
            <?php if ($version): ?><div class="small opacity-75"><?= hm($version['eleccion_nombre']) ?> · <?= hm(ucfirst($version['tipo'])) ?> #<?= (int)$version['numero'] ?></div><?php else: ?><div class="small opacity-75">No existe una versión activa.</div><?php endif; ?>
        </div>
        <div class="col-lg-7"><div class="row g-2"><div class="col-sm-5"><form class="input-group" onsubmit="cargar_post(<?= $url ?>,'content_seccion','mesa='+encodeURIComponent(this.mesa.value));return false;"><input class="form-control" name="mesa" inputmode="numeric" placeholder="Número de mesa" value="<?= hm($mesaBuscada) ?>"><button class="btn btn-light text-primary" <?= !$version?'disabled':'' ?>><i class="bi bi-search"></i></button></form></div>
        <div class="col-sm-7"><form class="input-group" onsubmit="cargar_post(<?= $url ?>,'content_seccion','escuela='+encodeURIComponent(this.escuela.value));return false;"><input class="form-control" name="escuela" placeholder="Nombre de la escuela" value="<?= hm($escuelaBuscada) ?>"><button class="btn btn-light text-primary" <?= !$version?'disabled':'' ?>><i class="bi bi-search"></i></button></form></div></div></div>
    </div></div></div>

    <div class="container-fluid px-3 px-lg-5">
        <?php if (!$version): ?>
            <div class="alert alert-warning mesas-card">Primero debe existir una versión activa del padrón.</div>
        <?php else: ?>
            <div class="row g-3 mb-4"><div class="col-md-4"><div class="card mesas-card h-100"><div class="card-body"><div class="dato-secundario">Mesas</div><div class="h3 mb-0"><?= numero_mesas($resumen['mesas']) ?></div></div></div></div><div class="col-md-4"><div class="card mesas-card h-100"><div class="card-body"><div class="dato-secundario">Escuelas</div><div class="h3 mb-0"><?= numero_mesas($resumen['escuelas']) ?></div></div></div></div><div class="col-md-4"><div class="card mesas-card h-100"><div class="card-body"><div class="dato-secundario">Electores</div><div class="h3 mb-0"><?= numero_mesas($resumen['electores']) ?></div></div></div></div></div>

            <?php if ($error): ?><div class="alert alert-danger mesas-card"><?= hm($error) ?></div><?php endif; ?>

            <?php if ($mesaBuscada !== '' && !$error): ?>
                <?php if (!$resultadoMesa): ?><div class="card mesas-card"><div class="card-body text-center py-5"><i class="bi bi-inbox fs-1 text-secondary"></i><h3 class="h5 mt-3">Mesa no encontrada</h3><p class="text-secondary mb-0">La mesa <?= hm($mesaBuscada) ?> no integra la versión activa.</p></div></div>
                <?php else: ?><h3 class="h5 mb-3">Resultado de la mesa <?= hm($mesaBuscada) ?></h3><div class="row g-3"><?php foreach($resultadoMesa as $mesa): ?><div class="col-lg-6"><article class="card mesas-card h-100"><div class="card-body p-4 d-flex gap-4"><div><div class="dato-secundario">Mesa</div><div class="mesa-numero"><?= (int)$mesa['mesa'] ?></div></div><div><h4 class="h5 mb-1"><?= hm($mesa['escuela_nombre'] ?: 'Escuela no informada') ?></h4><div class="dato-secundario mb-2"><?= hm($mesa['escuela_domicilio']) ?></div><strong><?= numero_mesas($mesa['electores']) ?> electores</strong><div class="dato-secundario">Órdenes <?= (int)$mesa['orden_desde'] ?> a <?= (int)$mesa['orden_hasta'] ?></div></div></div></article></div><?php endforeach; ?></div><?php endif; ?>
            <?php elseif ($escuelaId > 0 || $escuelaBuscada !== ''): ?>
                <?php if (!$resultadosEscuela): ?><div class="card mesas-card"><div class="card-body text-center py-5"><i class="bi bi-building-x fs-1 text-secondary"></i><h3 class="h5 mt-3">Escuela no encontrada</h3><p class="text-secondary mb-0">Probá con una parte más corta del nombre.</p></div></div>
                <?php else: ?><div class="d-flex justify-content-between align-items-center mb-3"><h3 class="h5 mb-0">Escuelas encontradas</h3><span class="text-secondary small">Hasta 25 resultados</span></div><div class="row g-4"><?php foreach($resultadosEscuela as $escuela): $lista=$mesasPorEscuela[(int)$escuela['id']]??[]; ?><div class="col-xl-6"><article class="card mesas-card h-100"><div class="card-body p-4"><div class="d-flex justify-content-between gap-3 mb-3"><div><h4 class="h5 mb-1"><?= hm($escuela['nombre']) ?></h4><div class="dato-secundario"><?= hm($escuela['domicilio']) ?></div></div><div class="text-end"><strong><?= numero_mesas($escuela['total_mesas']) ?></strong><div class="dato-secundario">mesas</div></div></div><div class="d-flex flex-wrap gap-2"><?php foreach($lista as $mesa): ?><button class="mesa-chip" onclick="cargar_post(<?= $url ?>,'content_seccion','mesa=<?= (int)$mesa['mesa'] ?>')"><strong>Mesa <?= (int)$mesa['mesa'] ?></strong><br><span class="small"><?= numero_mesas($mesa['electores']) ?> electores</span></button><?php endforeach; ?></div><div class="dato-secundario mt-3"><?= numero_mesas($escuela['electores']) ?> electores en total</div></div></article></div><?php endforeach; ?></div><?php endif; ?>
            <?php else: ?>
                <div class="card mesas-card"><div class="card-body text-center py-5"><i class="bi bi-search fs-1 text-primary"></i><h3 class="h5 mt-3">Buscá una mesa o una escuela</h3><p class="text-secondary mb-0">El número de mesa devuelve su escuela. El nombre de una escuela devuelve todas sus mesas.</p></div></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
