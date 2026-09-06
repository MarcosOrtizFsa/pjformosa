-- Migracion 013: administracion y auditoria de clientes de la API privada.

ALTER TABLE `padron_api_clientes`
    ADD COLUMN IF NOT EXISTS `actualizado_en` datetime NOT NULL DEFAULT current_timestamp()
        ON UPDATE current_timestamp() AFTER `creado_en`,
    ADD COLUMN IF NOT EXISTS `revocado_en` datetime DEFAULT NULL AFTER `actualizado_en`;

CREATE TABLE IF NOT EXISTS `padron_api_eventos` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `cliente_id` bigint unsigned DEFAULT NULL,
    `usuario_id` int unsigned DEFAULT NULL,
    `accion` enum('crear','rotar','activar','suspender','revocar') NOT NULL,
    `detalle` varchar(500) DEFAULT NULL,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_padron_api_eventos_cliente_fecha` (`cliente_id`,`creado_en`),
    CONSTRAINT `fk_padron_api_evento_cliente` FOREIGN KEY (`cliente_id`)
        REFERENCES `padron_api_clientes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- El identificador lo asigna la tabla; la ruta estable evita depender de un ID fijo.
INSERT INTO `system_01_modulos`
    (`system_01_modulo`,`system_01_tipo`,`system_01_path_home`,`system_01_onoff`,`system_01_orden`,`system_01_estado`)
SELECT 'Clientes API','sys','api_clientes','on',x.siguiente_orden,1
FROM (SELECT COALESCE(MAX(`system_01_orden`),0)+1 siguiente_orden FROM `system_01_modulos`) x
WHERE NOT EXISTS (
    SELECT 1 FROM `system_01_modulos` WHERE `system_01_path_home`='api_clientes'
);

INSERT IGNORE INTO `padron_schema_migrations` (`version`,`descripcion`)
VALUES ('013','Modulo administrativo y eventos de clientes API');
