-- Migración 001: núcleo normalizado del padrón.
--
-- Este esquema se crea en paralelo a las tablas system_* existentes. No se
-- eliminan ni se copian datos automáticamente: la transición será gradual y
-- verificable, módulo por módulo.

CREATE TABLE IF NOT EXISTS `padron_schema_migrations` (
    `version` varchar(40) NOT NULL,
    `descripcion` varchar(190) NOT NULL,
    `aplicada_en` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_personas` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `dni` char(8) NOT NULL COMMENT 'DNI normalizado a ocho dígitos, sin puntos',
    `apellido` varchar(100) NOT NULL,
    `nombre` varchar(120) NOT NULL,
    `sexo` varchar(10) DEFAULT NULL,
    `clase` smallint unsigned DEFAULT NULL COMMENT 'Año de nacimiento informado por el padrón',
    `tipo_documento` varchar(12) DEFAULT NULL,
    `estado` tinyint unsigned NOT NULL DEFAULT 1,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_personas_dni` (`dni`),
    KEY `idx_padron_personas_apellido_nombre` (`apellido`, `nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_domicilios` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `persona_id` bigint unsigned NOT NULL,
    `domicilio` varchar(190) DEFAULT NULL,
    `localidad` varchar(120) DEFAULT NULL,
    `circuito` varchar(12) DEFAULT NULL,
    `vigente_desde` date DEFAULT NULL,
    `vigente_hasta` date DEFAULT NULL,
    `fuente` varchar(60) DEFAULT NULL,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_padron_domicilios_persona_vigencia` (`persona_id`, `vigente_hasta`),
    CONSTRAINT `fk_padron_domicilios_persona` FOREIGN KEY (`persona_id`) REFERENCES `padron_personas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_elecciones` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `nombre` varchar(160) NOT NULL,
    `fecha` date NOT NULL,
    `tipo` varchar(60) DEFAULT NULL,
    `estado` enum('borrador','validando','activa','archivada') NOT NULL DEFAULT 'borrador',
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_padron_elecciones_estado_fecha` (`estado`, `fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_escuelas` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `codigo` varchar(30) DEFAULT NULL,
    `nombre` varchar(190) NOT NULL,
    `domicilio` varchar(190) DEFAULT NULL,
    `localidad` varchar(120) DEFAULT NULL,
    `estado` tinyint unsigned NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_escuelas_codigo` (`codigo`),
    KEY `idx_padron_escuelas_localidad` (`localidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_asignaciones_electorales` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `eleccion_id` bigint unsigned NOT NULL,
    `persona_id` bigint unsigned NOT NULL,
    `escuela_id` bigint unsigned DEFAULT NULL,
    `mesa` int unsigned DEFAULT NULL,
    `orden` int unsigned DEFAULT NULL,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_asignacion_persona_eleccion` (`eleccion_id`, `persona_id`),
    KEY `idx_padron_asignacion_consulta` (`persona_id`, `eleccion_id`),
    KEY `idx_padron_asignacion_mesa_orden` (`eleccion_id`, `mesa`, `orden`),
    KEY `idx_padron_asignacion_escuela` (`escuela_id`),
    CONSTRAINT `fk_padron_asignacion_eleccion` FOREIGN KEY (`eleccion_id`) REFERENCES `padron_elecciones` (`id`),
    CONSTRAINT `fk_padron_asignacion_persona` FOREIGN KEY (`persona_id`) REFERENCES `padron_personas` (`id`),
    CONSTRAINT `fk_padron_asignacion_escuela` FOREIGN KEY (`escuela_id`) REFERENCES `padron_escuelas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_importaciones` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `eleccion_id` bigint unsigned DEFAULT NULL,
    `archivo_original` varchar(255) NOT NULL,
    `archivo_interno` varchar(255) NOT NULL,
    `hash_sha256` char(64) NOT NULL,
    `estado` enum('subido','validando','validado','importando','completado','fallido','cancelado') NOT NULL DEFAULT 'subido',
    `total_filas` int unsigned NOT NULL DEFAULT 0,
    `filas_validas` int unsigned NOT NULL DEFAULT 0,
    `filas_rechazadas` int unsigned NOT NULL DEFAULT 0,
    `personas_insertadas` int unsigned NOT NULL DEFAULT 0,
    `personas_actualizadas` int unsigned NOT NULL DEFAULT 0,
    `asignaciones_insertadas` int unsigned NOT NULL DEFAULT 0,
    `iniciado_por` int unsigned DEFAULT NULL,
    `mensaje` text DEFAULT NULL,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_importaciones_hash` (`hash_sha256`),
    KEY `idx_padron_importaciones_estado` (`estado`, `creado_en`),
    CONSTRAINT `fk_padron_importacion_eleccion` FOREIGN KEY (`eleccion_id`) REFERENCES `padron_elecciones` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_importacion_filas` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `importacion_id` bigint unsigned NOT NULL,
    `numero_fila` int unsigned NOT NULL,
    `dni` varchar(20) DEFAULT NULL,
    `apellido` varchar(100) DEFAULT NULL,
    `nombre` varchar(120) DEFAULT NULL,
    `sexo` varchar(10) DEFAULT NULL,
    `clase` varchar(10) DEFAULT NULL,
    `domicilio` varchar(190) DEFAULT NULL,
    `localidad` varchar(120) DEFAULT NULL,
    `circuito` varchar(20) DEFAULT NULL,
    `escuela` varchar(190) DEFAULT NULL,
    `mesa` varchar(20) DEFAULT NULL,
    `orden` varchar(20) DEFAULT NULL,
    `es_valida` tinyint unsigned NOT NULL DEFAULT 0,
    `errores` text DEFAULT NULL,
    `nivel_completitud` tinyint unsigned DEFAULT NULL COMMENT '1 completo, 2 parcial, 3 mínimo',
    `advertencias` text DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_importacion_numero_fila` (`importacion_id`, `numero_fila`),
    KEY `idx_padron_importacion_filas_dni` (`importacion_id`, `dni`),
    KEY `idx_padron_importacion_filas_validez` (`importacion_id`, `es_valida`),
    CONSTRAINT `fk_padron_importacion_fila` FOREIGN KEY (`importacion_id`) REFERENCES `padron_importaciones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `padron_schema_migrations` (`version`, `descripcion`)
VALUES ('001', 'Núcleo normalizado de personas, elecciones e importaciones');
