-- Migracion 009: catalogo territorial normalizado de Formosa.
-- El circuito es unico y determina localidad y departamento.

CREATE TABLE IF NOT EXISTS `padron_departamentos` (
    `id` tinyint unsigned NOT NULL,
    `nombre` varchar(80) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_departamentos_nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `padron_departamentos` (`id`,`nombre`) VALUES
    (1,'FORMOSA'),(2,'LAISHI'),(3,'PILCOMAYO'),(4,'PIRANE'),(5,'PILAGAS'),
    (6,'PATIÑO'),(7,'BERMEJO'),(8,'MATACOS'),(9,'RAMON LISTA')
ON DUPLICATE KEY UPDATE `nombre`=VALUES(`nombre`);

CREATE TABLE IF NOT EXISTS `padron_territorios` (
    `id` smallint unsigned NOT NULL AUTO_INCREMENT,
    `legacy_id` int unsigned DEFAULT NULL COMMENT 'ID original de system_506_localidad',
    `departamento_id` tinyint unsigned NOT NULL,
    `circuito` varchar(12) NOT NULL,
    `localidad` varchar(120) NOT NULL,
    `activo` tinyint unsigned NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_padron_territorios_circuito` (`circuito`),
    UNIQUE KEY `uq_padron_territorios_legacy` (`legacy_id`),
    KEY `idx_padron_territorios_departamento_localidad` (`departamento_id`,`localidad`),
    CONSTRAINT `fk_padron_territorio_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `padron_departamentos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `padron_territorios` (`legacy_id`,`departamento_id`,`circuito`,`localidad`)
SELECT `id_system_506`,`system_506_dpto`,UPPER(TRIM(`system_506_circuito`)),UPPER(TRIM(`system_506_localidad`))
FROM `system_506_localidad`
ON DUPLICATE KEY UPDATE `legacy_id`=VALUES(`legacy_id`),`departamento_id`=VALUES(`departamento_id`),`localidad`=VALUES(`localidad`),`activo`=1;

-- Tres nombres heredados contienen '?' por una antigua conversion de charset.
UPDATE `padron_territorios` SET `localidad`='VILLAFAÑE' WHERE `legacy_id`=62;
UPDATE `padron_territorios` SET `localidad`='EL BAÑADERO' WHERE `legacy_id`=66;
UPDATE `padron_territorios` SET `localidad`='PILAGÁ TERCERO' WHERE `legacy_id`=79;

ALTER TABLE `padron_domicilios`
    ADD COLUMN IF NOT EXISTS `territorio_id` smallint unsigned DEFAULT NULL AFTER `domicilio`;
ALTER TABLE `padron_version_personas`
    ADD COLUMN IF NOT EXISTS `territorio_id` smallint unsigned DEFAULT NULL AFTER `departamento`;

SET @domicilio_tiene_circuito := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='padron_domicilios' AND column_name='circuito');
SET @sql_migrar_domicilios := IF(@domicilio_tiene_circuito>0,
    'UPDATE padron_domicilios d INNER JOIN padron_territorios t ON UPPER(TRIM(d.circuito)) COLLATE utf8mb4_unicode_ci=t.circuito SET d.territorio_id=t.id WHERE d.territorio_id IS NULL', 'SELECT 1');
PREPARE stmt_migrar_domicilios FROM @sql_migrar_domicilios;
EXECUTE stmt_migrar_domicilios;
DEALLOCATE PREPARE stmt_migrar_domicilios;

UPDATE `padron_version_personas` vp INNER JOIN `padron_territorios` t
    ON UPPER(TRIM(vp.circuito)) COLLATE utf8mb4_unicode_ci=t.circuito
SET vp.territorio_id=t.id WHERE vp.territorio_id IS NULL;

SET @existe_fk_domicilio_territorio := (SELECT COUNT(*) FROM information_schema.table_constraints WHERE constraint_schema=DATABASE() AND table_name='padron_domicilios' AND constraint_name='fk_padron_domicilio_territorio');
SET @sql_fk_domicilio_territorio := IF(@existe_fk_domicilio_territorio=0,
    'ALTER TABLE padron_domicilios ADD KEY idx_padron_domicilios_territorio_id (territorio_id), ADD CONSTRAINT fk_padron_domicilio_territorio FOREIGN KEY (territorio_id) REFERENCES padron_territorios (id)', 'SELECT 1');
PREPARE stmt_fk_domicilio_territorio FROM @sql_fk_domicilio_territorio;
EXECUTE stmt_fk_domicilio_territorio;
DEALLOCATE PREPARE stmt_fk_domicilio_territorio;

SET @existe_fk_version_territorio := (SELECT COUNT(*) FROM information_schema.table_constraints WHERE constraint_schema=DATABASE() AND table_name='padron_version_personas' AND constraint_name='fk_padron_version_territorio');
SET @sql_fk_version_territorio := IF(@existe_fk_version_territorio=0,
    'ALTER TABLE padron_version_personas ADD KEY idx_padron_version_territorio (version_id,territorio_id), ADD CONSTRAINT fk_padron_version_territorio FOREIGN KEY (territorio_id) REFERENCES padron_territorios (id)', 'SELECT 1');
PREPARE stmt_fk_version_territorio FROM @sql_fk_version_territorio;
EXECUTE stmt_fk_version_territorio;
DEALLOCATE PREPARE stmt_fk_version_territorio;

-- La relacion ya esta construida; se quitan las columnas territoriales repetidas.
ALTER TABLE `padron_domicilios`
    DROP INDEX IF EXISTS `idx_padron_domicilios_territorio`,
    DROP COLUMN IF EXISTS `localidad`,
    DROP COLUMN IF EXISTS `circuito`,
    DROP COLUMN IF EXISTS `departamento`;

INSERT IGNORE INTO `padron_schema_migrations` (`version`,`descripcion`)
VALUES ('009','Catalogo de departamentos y territorios relacionado por circuito');
