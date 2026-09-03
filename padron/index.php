<?php
declare(strict_types=1);

/* Entrada estable del subsistema privado. No depende del dominio ni de rutas
 * locales, por lo que funciona igual en desarrollo y producción. */
header('Location: sistema/', true, 302);
exit;
