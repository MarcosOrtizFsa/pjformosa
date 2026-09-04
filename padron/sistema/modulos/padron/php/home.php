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

$valorIngresado = trim((string) ($_POST['variable_buscar'] ?? ''));
$dni = $valorIngresado !== '' ? PadronConsulta::normalizarDni($valorIngresado) : null;
$version = PadronConsulta::versionActiva($pdo);
$persona = ($dni !== null && $version !== null) ? PadronConsulta::buscarPorDni($pdo, $dni) : null;
$busquedaRealizada = $valorIngresado !== '';
$idModulo = (int) ($_GET['id_system_01'] ?? $_POST['id_system_01'] ?? 0);

function h(?string $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function dato(?string $valor): string
{
    return trim((string) $valor) !== '' ? h($valor) : '<span class="text-secondary">No informado</span>';
}

$urlConsulta = "'modulos/padron/php/home.php?id_system_01={$idModulo}'";
?>
<style>
    .padron-consulta { --padron-azul:#075a9c; --padron-celeste:#eaf6ff; }
    .padron-hero { background:linear-gradient(120deg,#075a9c,#1593cf); color:#fff; border-radius:0 0 1.25rem 1.25rem; }
    .padron-card { border:0; border-radius:1rem; box-shadow:0 .5rem 1.4rem rgba(20,70,105,.09); }
    .padron-label { color:#668094; font-size:.75rem; text-transform:uppercase; letter-spacing:.04em; margin-bottom:.15rem; }
    .padron-value { font-weight:600; color:#17324a; }
    .nivel-1 { background:#d1e7dd; color:#0f5132; }.nivel-2 { background:#fff3cd; color:#664d03; }.nivel-3 { background:#e2e3e5; color:#41464b; }
</style>

<section class="padron-consulta pb-5">
    <div class="padron-hero px-3 px-lg-5 py-4 mb-4">
        <div class="container-fluid">
            <div class="row align-items-center g-3">
                <div class="col-lg-6">
                    <div class="small text-uppercase opacity-75">Consulta interna</div>
                    <h2 class="h3 mb-1">Padrón electoral</h2>
                    <?php if ($version): ?>
                        <div class="small opacity-75"><?= h($version['eleccion_nombre']) ?> · <?= h(ucfirst($version['tipo'])) ?> #<?= (int) $version['numero'] ?> · <?= number_format((int) $version['total_personas'], 0, ',', '.') ?> personas</div>
                    <?php else: ?>
                        <div class="small opacity-75">No existe una versión activa.</div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6">
                    <form class="input-group input-group-lg" onsubmit="cargar_post(<?= $urlConsulta ?>,'content_seccion','variable_buscar='+encodeURIComponent(this.variable_buscar.value));return false;">
                        <input autofocus class="form-control" name="variable_buscar" inputmode="numeric" autocomplete="off" maxlength="12" placeholder="Ingresá el DNI" value="<?= h($valorIngresado) ?>" aria-label="DNI">
                        <button class="btn btn-light text-primary px-4" type="submit" <?= !$version ? 'disabled' : '' ?>><i class="bi bi-search me-1"></i> Buscar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-3 px-lg-5">
        <?php if (!$version): ?>
            <div class="alert alert-warning padron-card">Primero debés completar y activar una versión desde <strong>Actualizar</strong>.</div>
        <?php elseif (!$busquedaRealizada): ?>
            <div class="card padron-card"><div class="card-body text-center py-5"><i class="bi bi-person-vcard fs-1 text-primary"></i><h3 class="h5 mt-3">Consultá por número de documento</h3><p class="text-secondary mb-0">Podés escribirlo con o sin puntos.</p></div></div>
        <?php elseif ($dni === null): ?>
            <div class="alert alert-danger padron-card">Ingresá un DNI válido de entre 6 y 8 dígitos.</div>
        <?php elseif (!$persona): ?>
            <div class="card padron-card"><div class="card-body text-center py-5"><i class="bi bi-person-x fs-1 text-secondary"></i><h3 class="h5 mt-3">DNI <?= h($dni) ?> no encontrado</h3><p class="text-secondary mb-0">La persona no integra la versión activa del padrón.</p></div></div>
        <?php else: ?>
            <?php $nivel = (int) $persona['nivel_completitud']; ?>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div><div class="text-secondary small">Resultado para DNI <?= h($persona['dni']) ?></div><h3 class="h4 mb-0"><?= h($persona['apellido'].', '.$persona['nombre']) ?></h3></div>
                <span class="badge rounded-pill nivel-<?= $nivel ?> px-3 py-2">Nivel de completitud <?= $nivel ?></span>
            </div>
            <div class="row g-4">
                <div class="col-lg-4"><div class="card padron-card h-100"><div class="card-body p-4"><h4 class="h6 text-primary mb-3"><i class="bi bi-person me-2"></i>Datos personales</h4>
                    <div class="row g-3"><div class="col-6"><div class="padron-label">Tipo</div><div class="padron-value"><?= dato($persona['tipo_documento']) ?></div></div><div class="col-6"><div class="padron-label">DNI</div><div class="padron-value"><?= h($persona['dni']) ?></div></div><div class="col-6"><div class="padron-label">Clase</div><div class="padron-value"><?= dato($persona['clase'] !== null ? (string) $persona['clase'] : null) ?></div></div><div class="col-6"><div class="padron-label">Sexo</div><div class="padron-value"><?= dato($persona['sexo']) ?></div></div></div>
                </div></div></div>
                <div class="col-lg-4"><div class="card padron-card h-100"><div class="card-body p-4"><h4 class="h6 text-primary mb-3"><i class="bi bi-geo-alt me-2"></i>Residencia</h4>
                    <div class="mb-3"><div class="padron-label">Domicilio</div><div class="padron-value"><?= dato($persona['domicilio']) ?></div></div><div class="row g-3"><div class="col-7"><div class="padron-label">Localidad</div><div class="padron-value"><?= dato($persona['localidad']) ?></div></div><div class="col-5"><div class="padron-label">Circuito</div><div class="padron-value"><?= dato($persona['circuito']) ?></div></div></div>
                </div></div></div>
                <div class="col-lg-4"><div class="card padron-card h-100"><div class="card-body p-4"><h4 class="h6 text-primary mb-3"><i class="bi bi-building me-2"></i>Lugar de votación</h4>
                    <div class="mb-3"><div class="padron-label">Escuela</div><div class="padron-value"><?= dato($persona['escuela_nombre']) ?></div></div><div class="row g-3"><div class="col-6"><div class="padron-label">Mesa</div><div class="padron-value fs-5"><?= dato($persona['mesa'] !== null ? (string) $persona['mesa'] : null) ?></div></div><div class="col-6"><div class="padron-label">Orden</div><div class="padron-value fs-5"><?= dato($persona['orden'] !== null ? (string) $persona['orden'] : null) ?></div></div></div>
                </div></div></div>
            </div>
        <?php endif; ?>

        <?php
        // Se mantiene el acceso al importador dentro del módulo, sólo para root/técnicos.
        $modo = (string) ($_SESSION['sesion_system_03_modo'] ?? '');
        $privilegio = (string) ($_SESSION['sesion_system_07'] ?? '');
        if ($privilegio === '1' || in_array($modo, ['0', '1'], true)):
        ?>
            <div class="text-end mt-4"><button class="btn btn-outline-primary" onclick="abrir_popup_g('modulos/padron/php/cargador.php')"><i class="bi bi-cloud-arrow-up me-1"></i> Actualizar padrón</button></div>
        <?php endif; ?>
    </div>
</section>
