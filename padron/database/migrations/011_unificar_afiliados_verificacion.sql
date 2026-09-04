-- Migracion 011: integra las colas del verificador con padron de afiliados.
-- Las tablas system_2003/system_2004 permanecen como respaldo de lectura.

CREATE TABLE IF NOT EXISTS `padron_tramites_afiliacion` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `legacy_id` int unsigned DEFAULT NULL,
    `persona_id` bigint unsigned DEFAULT NULL,
    `dni` char(8) NOT NULL COMMENT 'Permite recibir un tramite aun sin identidad en el padron electoral',
    `anio` smallint unsigned NOT NULL,
    `responsable` varchar(150) DEFAULT NULL,
    `estado` enum('pendiente','completado','cancelado','observado') NOT NULL DEFAULT 'pendiente',
    `fecha` date NOT NULL,
    `observaciones` text DEFAULT NULL,
    `creado_por` int unsigned DEFAULT NULL,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_tramite_legacy` (`legacy_id`),
    UNIQUE KEY `uq_padron_tramite_dni_anio` (`dni`,`anio`),
    KEY `idx_padron_tramite_persona_estado` (`persona_id`,`estado`),
    KEY `idx_padron_tramite_estado_fecha` (`estado`,`fecha`),
    CONSTRAINT `fk_padron_tramite_persona` FOREIGN KEY (`persona_id`) REFERENCES `padron_personas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_candidatos_avales` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `legacy_id` int unsigned DEFAULT NULL,
    `campana_id` bigint unsigned NOT NULL,
    `persona_id` bigint unsigned NOT NULL,
    `responsable` varchar(150) DEFAULT NULL,
    `estado` enum('preseleccionado','asignado','descartado','observado') NOT NULL DEFAULT 'preseleccionado',
    `fecha` date NOT NULL,
    `observaciones` text DEFAULT NULL,
    `creado_por` int unsigned DEFAULT NULL,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_candidato_legacy` (`legacy_id`),
    UNIQUE KEY `uq_padron_candidato_campana_persona` (`campana_id`,`persona_id`),
    KEY `idx_padron_candidato_estado_fecha` (`campana_id`,`estado`,`fecha`),
    CONSTRAINT `fk_padron_candidato_campana` FOREIGN KEY (`campana_id`) REFERENCES `padron_campanas_avales` (`id`),
    CONSTRAINT `fk_padron_candidato_persona` FOREIGN KEY (`persona_id`) REFERENCES `padron_personas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

START TRANSACTION;

INSERT INTO `padron_tramites_afiliacion`
    (`legacy_id`,`persona_id`,`dni`,`anio`,`responsable`,`estado`,`fecha`)
SELECT t.id_system_2003,p.id,LPAD(t.system_2003_dni,8,'0'),YEAR(t.system_2003_fecha),
       NULLIF(TRIM(t.system_2003_dirigente),''),'pendiente',t.system_2003_fecha
FROM system_2003_nuevos_tramites t
LEFT JOIN padron_personas p ON p.dni=LPAD(t.system_2003_dni,8,'0')
ON DUPLICATE KEY UPDATE persona_id=COALESCE(VALUES(persona_id),persona_id),responsable=VALUES(responsable),fecha=VALUES(fecha);

-- Solo se crea la afiliacion pendiente cuando la identidad ya existe. Los DNI
-- fuera del padron quedan en la cola hasta que puedan vincularse con una persona.
INSERT IGNORE INTO `padron_afiliaciones` (`persona_id`,`estado`,`observaciones`,`fuente`)
SELECT DISTINCT persona_id,'pendiente','Trámite migrado desde verificación 2026','system_2003_nuevos_tramites'
FROM padron_tramites_afiliacion WHERE persona_id IS NOT NULL AND estado='pendiente';

INSERT INTO `padron_campanas_avales` (`nombre`,`anio`,`estado`)
VALUES ('Avales y fichas 2026',2026,'activa')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);
SET @campana_verificacion := (SELECT id FROM padron_campanas_avales WHERE nombre='Avales y fichas 2026' AND anio=2026 LIMIT 1);

INSERT INTO `padron_candidatos_avales`
    (`legacy_id`,`campana_id`,`persona_id`,`responsable`,`estado`,`fecha`)
SELECT n.id_system_2004,@campana_verificacion,p.id,NULLIF(TRIM(d.system_2005_nombre),''),
       IF(EXISTS(SELECT 1 FROM padron_avales a WHERE a.campana_id=@campana_verificacion AND a.persona_id=p.id AND a.estado<>'anulado'),'asignado','preseleccionado'),
       n.system_2004_fecha
FROM system_2004_nuevos_avales n
INNER JOIN padron_personas p ON p.dni=LPAD(n.system_2004_dni,8,'0')
LEFT JOIN system_2005_lista_dirigentes d ON d.id_system_2005=n.rela_system_2005
ON DUPLICATE KEY UPDATE responsable=VALUES(responsable),fecha=VALUES(fecha);

COMMIT;

INSERT IGNORE INTO `padron_schema_migrations` (`version`,`descripcion`)
VALUES ('011','Colas de tramites y candidatos integradas al padron de afiliados');
