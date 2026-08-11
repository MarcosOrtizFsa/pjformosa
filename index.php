<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Noticias, actividades y novedades del Partido Justicialista de Formosa.">
    <meta name="theme-color" content="#075ca8">
    <title>PJ Formosa | Noticias y novedades</title>
    <link rel="icon" href="favicon.ico">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/portal.css?v=1">
</head>
<body>
    <a class="skip-link" href="#noticias">Saltar a las noticias</a>

    <header class="site-header sticky-top">
        <nav class="navbar navbar-expand" aria-label="Navegación principal">
            <div class="container portal-container">
                <a class="navbar-brand d-flex align-items-center gap-3" href="#inicio" aria-label="PJ Formosa, inicio">
                    <span class="brand-mark" aria-hidden="true">PJ</span>
                    <span>
                        <strong>PJ Formosa</strong>
                        <small>Información y actualidad</small>
                    </span>
                </a>
                <a class="nav-news" href="#noticias">Noticias</a>
            </div>
        </nav>
    </header>

    <main id="inicio">
        <section class="hero" aria-labelledby="hero-title">
            <img src="image/bg.jpg" alt="Gildo Insfrán y Eber Solís" class="hero-image">
            <div class="hero-shade" aria-hidden="true"></div>
            <div class="container portal-container hero-content">
                <div class="hero-copy">
                    <span class="hero-kicker">Partido Justicialista · Formosa</span>
                    <h1 id="hero-title">La actualidad de nuestra provincia, en un solo lugar.</h1>
                    <p>Noticias, encuentros y acciones que construyen una Formosa unida.</p>
                    <a class="btn hero-button" href="#noticias">Ver novedades</a>
                </div>
            </div>
        </section>

        <section id="noticias" class="news-section">
            <div class="container portal-container">
                <div class="section-heading">
                    <div>
                        <span class="section-kicker">Actualidad</span>
                        <h2>Noticias y novedades</h2>
                    </div>
                    <span class="live-label"><i aria-hidden="true"></i> Información actualizada</span>
                </div>

                <div id="news-status" class="news-status" role="status" aria-live="polite">
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    Cargando noticias…
                </div>
                <div id="news-feed" class="news-feed" aria-live="polite"></div>

                <div class="load-more-wrap">
                    <button id="load-more" class="btn load-more" type="button" hidden>
                        Cargar más noticias
                    </button>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container portal-container d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <strong>PJ Formosa</strong>
            <span>Información y actualidad de nuestra provincia</span>
        </div>
    </footer>

    <noscript>
        <div class="container py-4 text-center">Necesitás habilitar JavaScript para consultar las noticias.</div>
    </noscript>
    <script src="scripts/portal.js?v=1" defer></script>
</body>
</html>
