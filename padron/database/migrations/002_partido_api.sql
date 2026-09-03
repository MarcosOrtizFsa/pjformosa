-- Migración 002: afiliaciones, avales, documentos y clientes de API.

CREATE TABLE IF NOT EXISTS `padron_afiliaciones` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `persona_id` bigint unsigned NOT NULL,
    `numero_afiliado` varchar(30) DEFAULT NULL,
    `folio` varchar(30) DEFAULT NULL,
    `fecha_afiliacion` date DEFAULT NULL,
    `estado` enum('pendiente','activa','baja','observada') NOT NULL DEFAULT 'pendiente',
    `observaciones` text DEFAULT NULL,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_afiliacion_persona` (`persona_id`),
    KEY `idx_padron_afiliacion_estado` (`estado`),
    CONSTRAINT `fk_padron_afiliacion_persona` FOREIGN KEY (`persona_id`) REFERENCES `padron_personas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_campanas_avales` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `nombre` varchar(160) NOT NULL,
    `anio` smallint unsigned NOT NULL,
    `estado` enum('borrador','activa','cerrada','archivada') NOT NULL DEFAULT 'borrador',
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_padron_campanas_avales_anio_estado` (`anio`, `estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_avales` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `campana_id` bigint unsigned NOT NULL,
    `persona_id` bigint unsigned NOT NULL,
    `folio` varchar(30) DEFAULT NULL,
    `estado` enum('registrado','observado','anulado') NOT NULL DEFAULT 'registrado',
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_aval_persona_campana` (`campana_id`, `persona_id`),
    KEY `idx_padron_avales_persona` (`persona_id`),
    CONSTRAINT `fk_padron_aval_campana` FOREIGN KEY (`campana_id`) REFERENCES `padron_campanas_avales` (`id`),
    CONSTRAINT `fk_padron_aval_persona` FOREIGN KEY (`persona_id`) REFERENCES `padron_personas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_documentos` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `persona_id` bigint unsigned NOT NULL,
    `tipo` enum('dni_frente','dni_dorso','ficha_afiliacion','aval','otro') NOT NULL,
    `nombre_original` varchar(255) NOT NULL,
    `ruta_interna` varchar(255) NOT NULL COMMENT 'Ruta privada; nunca debe exponerse como URL directa',
    `mime_type` varchar(100) NOT NULL,
    `tamano_bytes` bigint unsigned NOT NULL,
    `hash_sha256` char(64) NOT NULL,
    `creado_por` int unsigned DEFAULT NULL,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    `eliminado_en` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_documentos_hash_persona` (`persona_id`, `tipo`, `hash_sha256`),
    KEY `idx_padron_documentos_persona_tipo` (`persona_id`, `tipo`, `eliminado_en`),
    CONSTRAINT `fk_padron_documento_persona` FOREIGN KEY (`persona_id`) REFERENCES `padron_personas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_api_clientes` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `nombre` varchar(120) NOT NULL,
    `client_id` char(36) NOT NULL,
    `token_hash` char(64) NOT NULL COMMENT 'SHA-256 del token; el token original nunca se almacena',
    `scopes` varchar(500) NOT NULL DEFAULT 'padron:consultar',
    `ips_permitidas` text DEFAULT NULL COMMENT 'Lista opcional separada por comas',
    `estado` tinyint unsigned NOT NULL DEFAULT 1,
    `expira_en` datetime DEFAULT NULL,
    `ultimo_uso_en` datetime DEFAULT NULL,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_api_clientes_client_id` (`client_id`),
    UNIQUE KEY `uq_padron_api_clientes_token_hash` (`token_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_api_registros` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `cliente_id` bigint unsigned DEFAULT NULL,
    `request_id` char(36) NOT NULL,
    `metodo` varchar(10) NOT NULL,
    `ruta` varchar(190) NOT NULL,
    `ip` varchar(45) DEFAULT NULL,
    `estado_http` smallint unsigned NOT NULL,
    `duracion_ms` int unsigned NOT NULL DEFAULT 0,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_api_registros_request_id` (`request_id`),
    KEY `idx_padron_api_registros_cliente_fecha` (`cliente_id`, `creado_en`),
    KEY `idx_padron_api_registros_fecha` (`creado_en`),
    CONSTRAINT `fk_padron_api_registro_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `padron_api_clientes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices de transición para acelerar los módulos heredados sin imponer aún
-- unicidad sobre datos que ya contienen duplicados.
ALTER TABLE `system_700_afiliados`
    ADD INDEX IF NOT EXISTS `idx_legacy_afiliados_dni` (`system_700_dni`);

ALTER TABLE `system_700_avalados`
    ADD INDEX IF NOT EXISTS `idx_legacy_avalados_dni` (`system_700_dni`);

INSERT IGNORE INTO `padron_schema_migrations` (`version`, `descripcion`)
VALUES ('002', 'Afiliaciones, avales, documentos y autenticación de API');
