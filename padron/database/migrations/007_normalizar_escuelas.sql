-- Migración 007: la localidad del CSV pertenece al votante, no a la escuela.
-- Se unifican escuelas de igual nombre creadas con localidades de residencia distintas.

UPDATE `padron_version_personas` vp
INNER JOIN `padron_escuelas` e ON e.id=vp.escuela_id
INNER JOIN (
    SELECT UPPER(TRIM(nombre)) AS nombre_normalizado, MIN(id) AS escuela_canonica
    FROM `padron_escuelas` GROUP BY UPPER(TRIM(nombre))
) c ON c.nombre_normalizado=UPPER(TRIM(e.nombre))
SET vp.escuela_id=c.escuela_canonica;

UPDATE `padron_asignaciones_electorales` ae
INNER JOIN `padron_escuelas` e ON e.id=ae.escuela_id
INNER JOIN (
    SELECT UPPER(TRIM(nombre)) AS nombre_normalizado, MIN(id) AS escuela_canonica
    FROM `padron_escuelas` GROUP BY UPPER(TRIM(nombre))
) c ON c.nombre_normalizado=UPPER(TRIM(e.nombre))
SET ae.escuela_id=c.escuela_canonica;

DELETE e FROM `padron_escuelas` e
INNER JOIN (
    SELECT UPPER(TRIM(nombre)) AS nombre_normalizado, MIN(id) AS escuela_canonica
    FROM `padron_escuelas` GROUP BY UPPER(TRIM(nombre))
) c ON c.nombre_normalizado=UPPER(TRIM(e.nombre))
WHERE e.id<>c.escuela_canonica;

-- Primero se liberan las claves anteriores para evitar colisiones transitorias.
UPDATE `padron_escuelas` SET `clave_importacion`=NULL;
UPDATE `padron_escuelas`
SET `clave_importacion`=SHA2(UPPER(TRIM(`nombre`)),256),
    `localidad`=NULL;

INSERT IGNORE INTO `padron_schema_migrations` (`version`,`descripcion`)
VALUES ('007','Escuelas unificadas por nombre sin usar la residencia del votante');
