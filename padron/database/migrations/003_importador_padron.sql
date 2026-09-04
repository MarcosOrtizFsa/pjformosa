-- Migración 003: estado reanudable del importador electoral.
-- Las posiciones permiten procesar archivos grandes mediante peticiones cortas.

ALTER TABLE `padron_importaciones`
    ADD COLUMN IF NOT EXISTS `delimitador` char(1) NOT NULL DEFAULT ';' AFTER `hash_sha256`,
    ADD COLUMN IF NOT EXISTS `posicion_bytes` bigint unsigned NOT NULL DEFAULT 0 AFTER `delimitador`,
    ADD COLUMN IF NOT EXISTS `siguiente_fila` int unsigned NOT NULL DEFAULT 2 AFTER `posicion_bytes`,
    ADD COLUMN IF NOT EXISTS `ultima_fila_id` bigint unsigned NOT NULL DEFAULT 0 AFTER `siguiente_fila`,
    ADD COLUMN IF NOT EXISTS `filas_importadas` int unsigned NOT NULL DEFAULT 0 AFTER `filas_rechazadas`,
    ADD COLUMN IF NOT EXISTS `finalizado_en` datetime DEFAULT NULL AFTER `actualizado_en`;

ALTER TABLE `padron_importacion_filas`
    ADD COLUMN IF NOT EXISTS `tipo_documento` varchar(12) DEFAULT NULL AFTER `dni`;

ALTER TABLE `padron_escuelas`
    ADD COLUMN IF NOT EXISTS `clave_importacion` char(64) DEFAULT NULL AFTER `codigo`;

-- El código oficial puede faltar; esta clave identifica establemente una escuela
-- por nombre y localidad durante las importaciones.
SET @existe_indice_escuela := (
    SELECT COUNT(*) FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'padron_escuelas'
      AND index_name = 'uq_padron_escuelas_clave_importacion'
);
SET @sql_indice_escuela := IF(
    @existe_indice_escuela = 0,
    'ALTER TABLE padron_escuelas ADD UNIQUE KEY uq_padron_escuelas_clave_importacion (clave_importacion)',
    'SELECT 1'
);
PREPARE stmt_indice_escuela FROM @sql_indice_escuela;
EXECUTE stmt_indice_escuela;
DEALLOCATE PREPARE stmt_indice_escuela;

INSERT IGNORE INTO `padron_schema_migrations` (`version`, `descripcion`)
VALUES ('003', 'Estado reanudable y campos auxiliares del importador electoral');
