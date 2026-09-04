-- Migración 005: ninguna persona queda fuera por datos complementarios faltantes.
-- Sólo DNI, apellido y nombres determinan si una la fila puede incorporarse.

ALTER TABLE `padron_personas`
    MODIFY `tipo_documento` varchar(12) DEFAULT NULL;

ALTER TABLE `padron_asignaciones_electorales`
    MODIFY `mesa` int unsigned DEFAULT NULL,
    MODIFY `orden` int unsigned DEFAULT NULL;

ALTER TABLE `padron_importacion_filas`
    ADD COLUMN IF NOT EXISTS `nivel_completitud` tinyint unsigned DEFAULT NULL
        COMMENT '1 completo, 2 parcial, 3 mínimo' AFTER `es_valida`,
    ADD COLUMN IF NOT EXISTS `advertencias` text DEFAULT NULL AFTER `errores`;

CREATE INDEX IF NOT EXISTS `idx_padron_importacion_nivel`
    ON `padron_importacion_filas` (`importacion_id`, `nivel_completitud`);

INSERT IGNORE INTO `padron_schema_migrations` (`version`, `descripcion`)
VALUES ('005', 'Aceptación mínima y niveles de completitud de las filas');
