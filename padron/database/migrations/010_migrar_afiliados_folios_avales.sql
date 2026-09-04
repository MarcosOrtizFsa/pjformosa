-- Migracion 010: estructura definitiva y migracion controlada del ecosistema PJ.
-- Las tablas system_* se conservan intactas hasta validar los nuevos modulos.

ALTER TABLE `padron_afiliaciones`
    ADD COLUMN IF NOT EXISTS `legacy_id` int unsigned DEFAULT NULL AFTER `id`,
    ADD COLUMN IF NOT EXISTS `fuente` varchar(40) NOT NULL DEFAULT 'sistema' AFTER `observaciones`;
CREATE UNIQUE INDEX IF NOT EXISTS `uq_padron_afiliaciones_legacy`
    ON `padron_afiliaciones` (`legacy_id`);

ALTER TABLE `padron_campanas_avales`
    ADD COLUMN IF NOT EXISTS `fecha_desde` date DEFAULT NULL AFTER `anio`,
    ADD COLUMN IF NOT EXISTS `fecha_hasta` date DEFAULT NULL AFTER `fecha_desde`;
CREATE UNIQUE INDEX IF NOT EXISTS `uq_padron_campanas_nombre_anio`
    ON `padron_campanas_avales` (`nombre`,`anio`);

CREATE TABLE IF NOT EXISTS `padron_sedes_avales` (
    `id` smallint unsigned NOT NULL AUTO_INCREMENT,
    `legacy_id` int unsigned DEFAULT NULL,
    `nombre` varchar(120) NOT NULL,
    `activo` tinyint unsigned NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_sedes_legacy` (`legacy_id`),
    UNIQUE KEY `uq_padron_sedes_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `padron_folios_avales` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `legacy_id` int unsigned DEFAULT NULL,
    `campana_id` bigint unsigned NOT NULL,
    `sede_id` smallint unsigned DEFAULT NULL,
    `numero` int unsigned NOT NULL,
    `fecha` date NOT NULL,
    `observaciones` text DEFAULT NULL,
    `estado` enum('borrador','abierto','cerrado','observado','anulado') NOT NULL DEFAULT 'borrador',
    `creado_por` int unsigned DEFAULT NULL,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    `actualizado_en` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_folios_legacy` (`legacy_id`),
    UNIQUE KEY `uq_padron_folios_campana_numero` (`campana_id`,`numero`),
    UNIQUE KEY `uq_padron_folios_id_campana` (`id`,`campana_id`),
    KEY `idx_padron_folios_sede_fecha` (`sede_id`,`fecha`),
    CONSTRAINT `fk_padron_folio_campana` FOREIGN KEY (`campana_id`) REFERENCES `padron_campanas_avales` (`id`),
    CONSTRAINT `fk_padron_folio_sede` FOREIGN KEY (`sede_id`) REFERENCES `padron_sedes_avales` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `padron_avales`
    ADD COLUMN IF NOT EXISTS `folio_id` bigint unsigned DEFAULT NULL AFTER `id`,
    ADD COLUMN IF NOT EXISTS `legacy_id` int unsigned DEFAULT NULL AFTER `persona_id`,
    ADD COLUMN IF NOT EXISTS `posicion` tinyint unsigned DEFAULT NULL AFTER `legacy_id`,
    ADD COLUMN IF NOT EXISTS `observaciones` text DEFAULT NULL AFTER `estado`;
CREATE UNIQUE INDEX IF NOT EXISTS `uq_padron_avales_legacy`
    ON `padron_avales` (`legacy_id`);
CREATE UNIQUE INDEX IF NOT EXISTS `uq_padron_avales_folio_posicion`
    ON `padron_avales` (`folio_id`,`posicion`);

SET @existe_fk_aval_folio := (
    SELECT COUNT(*) FROM information_schema.table_constraints
    WHERE constraint_schema=DATABASE() AND table_name='padron_avales'
      AND constraint_name='fk_padron_aval_folio'
);
SET @sql_fk_aval_folio := IF(@existe_fk_aval_folio=0,
    'ALTER TABLE padron_avales ADD CONSTRAINT fk_padron_aval_folio FOREIGN KEY (folio_id,campana_id) REFERENCES padron_folios_avales (id,campana_id)',
    'SELECT 1');
PREPARE stmt_fk_aval_folio FROM @sql_fk_aval_folio;
EXECUTE stmt_fk_aval_folio;
DEALLOCATE PREPARE stmt_fk_aval_folio;

CREATE TABLE IF NOT EXISTS `padron_migracion_incidencias` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `migracion` varchar(20) NOT NULL,
    `tabla_origen` varchar(80) NOT NULL,
    `registro_origen_id` int unsigned NOT NULL,
    `codigo` varchar(60) NOT NULL,
    `detalle` varchar(500) NOT NULL,
    `resuelta_en` datetime DEFAULT NULL,
    `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_incidencia_origen` (`migracion`,`tabla_origen`,`registro_origen_id`,`codigo`),
    KEY `idx_padron_incidencias_pendientes` (`codigo`,`resuelta_en`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

START TRANSACTION;

-- Se prioriza padron_personas cuando el DNI ya proviene del padron electoral.
-- Para personas nuevas se toma la primera ficha heredada de cada DNI.
INSERT IGNORE INTO `padron_personas`
    (`dni`,`apellido`,`nombre`,`sexo`,`estado`)
SELECT LPAD(a.system_700_dni,8,'0'),TRIM(a.system_700_apellido),TRIM(a.system_700_nombre),
       NULLIF(TRIM(a.system_700_sexo),''),1
FROM `system_700_afiliados` a
INNER JOIN (
    SELECT system_700_dni,MIN(id_system_700) id_canonico
    FROM system_700_afiliados GROUP BY system_700_dni
) c ON c.id_canonico=a.id_system_700;

-- Algunos avales historicos no tienen una ficha en system_700_afiliados.
INSERT IGNORE INTO `padron_personas`
    (`dni`,`apellido`,`nombre`,`sexo`,`estado`)
SELECT LPAD(a.system_700_dni,8,'0'),TRIM(a.system_700_apellido),TRIM(a.system_700_nombre),
       NULLIF(TRIM(a.system_700_sexo),''),1
FROM `system_700_avalados` a;

INSERT INTO `padron_afiliaciones`
    (`legacy_id`,`persona_id`,`estado`,`observaciones`,`fuente`)
SELECT a.id_system_700,p.id,'activa',NULL,'system_700_afiliados'
FROM `system_700_afiliados` a
INNER JOIN (
    SELECT system_700_dni,MIN(id_system_700) id_canonico
    FROM system_700_afiliados GROUP BY system_700_dni
) c ON c.id_canonico=a.id_system_700
INNER JOIN `padron_personas` p ON p.dni=LPAD(a.system_700_dni,8,'0')
ON DUPLICATE KEY UPDATE `estado`='activa',`fuente`='system_700_afiliados';

-- Los duplicados no se pierden: se consolidan y quedan informados para auditoria.
INSERT IGNORE INTO `padron_migracion_incidencias`
    (`migracion`,`tabla_origen`,`registro_origen_id`,`codigo`,`detalle`)
SELECT '010','system_700_afiliados',a.id_system_700,'AFILIADO_DNI_DUPLICADO',
       CONCAT('DNI ',a.system_700_dni,' consolidado con el registro ',c.id_canonico)
FROM `system_700_afiliados` a
INNER JOIN (
    SELECT system_700_dni,MIN(id_system_700) id_canonico
    FROM system_700_afiliados GROUP BY system_700_dni HAVING COUNT(*)>1
) c ON c.system_700_dni=a.system_700_dni
WHERE a.id_system_700<>c.id_canonico;

INSERT INTO `padron_sedes_avales` (`legacy_id`,`nombre`)
SELECT id_system_703,TRIM(system_703_procedencia) FROM system_703_sede
ON DUPLICATE KEY UPDATE `nombre`=VALUES(`nombre`),`activo`=1;

INSERT INTO `padron_campanas_avales`
    (`nombre`,`anio`,`fecha_desde`,`fecha_hasta`,`estado`)
SELECT 'Avales históricos 2024',2024,
       MIN(DATE(FROM_UNIXTIME(system_701_checked))),
       MAX(DATE(FROM_UNIXTIME(system_701_checked))),'cerrada'
FROM `system_701_folio`
ON DUPLICATE KEY UPDATE `fecha_desde`=VALUES(`fecha_desde`),`fecha_hasta`=VALUES(`fecha_hasta`);

SET @campana_legacy_2024 := (
    SELECT id FROM padron_campanas_avales WHERE nombre='Avales históricos 2024' AND anio=2024 LIMIT 1
);

INSERT INTO `padron_folios_avales`
    (`legacy_id`,`campana_id`,`sede_id`,`numero`,`fecha`,`observaciones`,`estado`,`creado_por`,`creado_en`)
SELECT f.id_system_701,@campana_legacy_2024,s.id,f.system_701_num,
       DATE(FROM_UNIXTIME(f.system_701_checked)),NULLIF(TRIM(f.system_701_observaciones),''),
       IF(COUNT(a.id_system_700)>15,'observado','cerrado'),NULLIF(f.rela_system_03,0),
       FROM_UNIXTIME(f.system_701_checked)
FROM `system_701_folio` f
LEFT JOIN `system_700_avalados` a ON a.rela_system_701=f.id_system_701
LEFT JOIN `padron_sedes_avales` s ON s.legacy_id=f.rela_system_703
GROUP BY f.id_system_701,s.id
ON DUPLICATE KEY UPDATE `sede_id`=VALUES(`sede_id`),`numero`=VALUES(`numero`),
    `fecha`=VALUES(`fecha`),`observaciones`=VALUES(`observaciones`),`estado`=VALUES(`estado`);

INSERT IGNORE INTO `padron_migracion_incidencias`
    (`migracion`,`tabla_origen`,`registro_origen_id`,`codigo`,`detalle`)
SELECT '010','system_701_folio',f.id_system_701,'FOLIO_EXCEDE_LIMITE',
       CONCAT('Folio ',f.system_701_num,' contiene ',COUNT(a.id_system_700),' avales; limite actual: 15')
FROM `system_701_folio` f
INNER JOIN `system_700_avalados` a ON a.rela_system_701=f.id_system_701
GROUP BY f.id_system_701 HAVING COUNT(a.id_system_700)>15;

-- ROW_NUMBER conserva el orden fisico heredado dentro de cada folio.
INSERT INTO `padron_avales`
    (`folio_id`,`campana_id`,`persona_id`,`legacy_id`,`posicion`,`estado`,`observaciones`,`creado_en`)
SELECT f.id,@campana_legacy_2024,p.id,x.id_system_700,x.posicion,'registrado',NULL,f.creado_en
FROM (
    SELECT a.*,ROW_NUMBER() OVER (PARTITION BY a.rela_system_701 ORDER BY a.id_system_700) posicion
    FROM system_700_avalados a
) x
INNER JOIN `padron_folios_avales` f ON f.legacy_id=x.rela_system_701
INNER JOIN `padron_personas` p ON p.dni=LPAD(x.system_700_dni,8,'0')
ON DUPLICATE KEY UPDATE `folio_id`=VALUES(`folio_id`),`campana_id`=VALUES(`campana_id`),
    `persona_id`=VALUES(`persona_id`),`posicion`=VALUES(`posicion`);

INSERT IGNORE INTO `padron_migracion_incidencias`
    (`migracion`,`tabla_origen`,`registro_origen_id`,`codigo`,`detalle`)
SELECT '010','system_700_avalados',a.id_system_700,'AVAL_SIN_AFILIACION_LEGACY',
       CONCAT('DNI ',a.system_700_dni,' no aparece en system_700_afiliados')
FROM `system_700_avalados` a
LEFT JOIN `system_700_afiliados` af ON af.system_700_dni=a.system_700_dni
WHERE af.id_system_700 IS NULL;

COMMIT;

-- Todo aval pertenece obligatoriamente a un folio. El numero se conserva una
-- sola vez en padron_folios_avales y no como texto repetido por persona.
ALTER TABLE `padron_avales`
    MODIFY COLUMN `folio_id` bigint unsigned NOT NULL,
    DROP COLUMN IF EXISTS `folio`;

INSERT IGNORE INTO `padron_schema_migrations` (`version`,`descripcion`)
VALUES ('010','Migracion normalizada de afiliados, folios y avales historicos');
