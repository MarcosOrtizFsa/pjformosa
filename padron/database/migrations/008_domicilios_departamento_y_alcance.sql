-- Migracion 008: domicilio electoral unificado y alcance de cada padron.
-- Departamento forma parte del domicilio; no se crea un catalogo territorial.

ALTER TABLE `padron_domicilios`
    ADD COLUMN IF NOT EXISTS `departamento` varchar(120) DEFAULT NULL AFTER `circuito`;

CREATE INDEX IF NOT EXISTS `idx_padron_domicilios_territorio`
    ON `padron_domicilios` (`vigente_hasta`, `departamento`, `localidad`, `circuito`);

ALTER TABLE `padron_importacion_filas`
    ADD COLUMN IF NOT EXISTS `departamento` varchar(120) DEFAULT NULL AFTER `circuito`;

-- Esta copia permite preparar una version sin alterar el domicilio vigente.
ALTER TABLE `padron_version_personas`
    ADD COLUMN IF NOT EXISTS `departamento` varchar(120) DEFAULT NULL AFTER `circuito`;

ALTER TABLE `padron_versiones`
    ADD COLUMN IF NOT EXISTS `alcance` enum('provincial_completo','parcial','prueba')
        NOT NULL DEFAULT 'parcial' AFTER `numero`;

-- Inicializa los domicilios de una version activa anterior a esta migracion.
-- Departamento queda NULL si el archivo historico no lo incluia.
INSERT INTO `padron_domicilios`
    (`persona_id`,`domicilio`,`localidad`,`circuito`,`departamento`,`vigente_desde`,`fuente`)
SELECT vp.persona_id,vp.domicilio,vp.localidad,vp.circuito,vp.departamento,
       COALESCE(DATE(v.activado_en),CURDATE()),CONCAT('version:',v.id)
FROM `padron_version_personas` vp
INNER JOIN `padron_versiones` v ON v.id=vp.version_id AND v.estado='activa'
LEFT JOIN `padron_domicilios` d ON d.persona_id=vp.persona_id AND d.vigente_hasta IS NULL
WHERE d.id IS NULL;

INSERT IGNORE INTO `padron_schema_migrations` (`version`,`descripcion`)
VALUES ('008','Departamento en domicilios y alcance seguro de las versiones');
