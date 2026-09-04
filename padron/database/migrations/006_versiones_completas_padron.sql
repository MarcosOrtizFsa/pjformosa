-- Migración 006: cada carga es una fotografía completa y aislada del padrón.
-- La versión vigente sólo cambia después de terminar y confirmar la importación.

CREATE TABLE IF NOT EXISTS `padron_versiones` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `eleccion_id` bigint unsigned NOT NULL,
    `tipo` enum('anual','provisorio','definitivo') NOT NULL,
    `numero` smallint unsigned NOT NULL DEFAULT 1,
    `estado` enum('preparando','lista','activa','archivada','fallida') NOT NULL DEFAULT 'preparando',
    `total_personas` int unsigned NOT NULL DEFAULT 0,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    `activado_en` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_version_eleccion_tipo_numero` (`eleccion_id`,`tipo`,`numero`),
    KEY `idx_padron_version_estado` (`estado`,`creado_en`),
    CONSTRAINT `fk_padron_version_eleccion` FOREIGN KEY (`eleccion_id`) REFERENCES `padron_elecciones` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_version_personas` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `version_id` bigint unsigned NOT NULL,
    `persona_id` bigint unsigned NOT NULL,
    `domicilio` varchar(190) DEFAULT NULL,
    `localidad` varchar(120) DEFAULT NULL,
    `circuito` varchar(12) DEFAULT NULL,
    `escuela_id` bigint unsigned DEFAULT NULL,
    `mesa` int unsigned DEFAULT NULL,
    `orden` int unsigned DEFAULT NULL,
    `nivel_completitud` tinyint unsigned NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_version_persona` (`version_id`,`persona_id`),
    KEY `idx_padron_version_consulta` (`persona_id`,`version_id`),
    KEY `idx_padron_version_mesa_orden` (`version_id`,`mesa`,`orden`),
    KEY `idx_padron_version_escuela` (`escuela_id`),
    CONSTRAINT `fk_padron_version_personas_version` FOREIGN KEY (`version_id`) REFERENCES `padron_versiones` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_padron_version_personas_persona` FOREIGN KEY (`persona_id`) REFERENCES `padron_personas` (`id`),
    CONSTRAINT `fk_padron_version_personas_escuela` FOREIGN KEY (`escuela_id`) REFERENCES `padron_escuelas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `padron_importaciones`
    ADD COLUMN IF NOT EXISTS `version_id` bigint unsigned DEFAULT NULL AFTER `eleccion_id`,
    ADD COLUMN IF NOT EXISTS `nivel_1` int unsigned NOT NULL DEFAULT 0 AFTER `filas_rechazadas`,
    ADD COLUMN IF NOT EXISTS `nivel_2` int unsigned NOT NULL DEFAULT 0 AFTER `nivel_1`,
    ADD COLUMN IF NOT EXISTS `nivel_3` int unsigned NOT NULL DEFAULT 0 AFTER `nivel_2`;

-- El mismo archivo puede recibirse nuevamente como otro corte. El hash sirve
-- para auditoría y búsqueda, pero ya no debe impedir una nueva fotografía.
SET @existe_hash_unico := (
    SELECT COUNT(*) FROM information_schema.statistics
    WHERE table_schema=DATABASE() AND table_name='padron_importaciones'
      AND index_name='uq_padron_importaciones_hash'
);
SET @sql_hash_unico := IF(@existe_hash_unico>0,
    'ALTER TABLE padron_importaciones DROP INDEX uq_padron_importaciones_hash',
    'SELECT 1');
PREPARE stmt_hash_unico FROM @sql_hash_unico;
EXECUTE stmt_hash_unico;
DEALLOCATE PREPARE stmt_hash_unico;

CREATE INDEX IF NOT EXISTS `idx_padron_importaciones_hash`
    ON `padron_importaciones` (`hash_sha256`);

SET @existe_fk_version := (
    SELECT COUNT(*) FROM information_schema.table_constraints
    WHERE constraint_schema=DATABASE() AND table_name='padron_importaciones'
      AND constraint_name='fk_padron_importacion_version'
);
SET @sql_fk_version := IF(@existe_fk_version=0,
    'ALTER TABLE padron_importaciones ADD CONSTRAINT fk_padron_importacion_version FOREIGN KEY (version_id) REFERENCES padron_versiones (id) ON DELETE SET NULL',
    'SELECT 1');
PREPARE stmt_fk_version FROM @sql_fk_version;
EXECUTE stmt_fk_version;
DEALLOCATE PREPARE stmt_fk_version;

INSERT IGNORE INTO `padron_schema_migrations` (`version`,`descripcion`)
VALUES ('006','Versiones completas con activación atómica y limpieza temporal');
