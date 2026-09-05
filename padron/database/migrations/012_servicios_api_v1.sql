-- Migracion 012: limites y soporte operativo para proveedores API privados.

ALTER TABLE `padron_api_clientes`
    ADD COLUMN IF NOT EXISTS `limite_por_minuto` smallint unsigned NOT NULL DEFAULT 120
        AFTER `ips_permitidas`;

CREATE INDEX IF NOT EXISTS `idx_padron_api_registros_limite`
    ON `padron_api_registros` (`cliente_id`,`creado_en`);

INSERT IGNORE INTO `padron_schema_migrations` (`version`,`descripcion`)
VALUES ('012','Servicios privados API v1 y limite por cliente');
