-- Migración 004: el circuito describe dónde vive la persona.
-- No pertenece a la escuela ni a la asignación particular de una elección.

ALTER TABLE `padron_asignaciones_electorales`
    DROP COLUMN IF EXISTS `circuito`;

ALTER TABLE `padron_escuelas`
    DROP COLUMN IF EXISTS `circuito`;

INSERT IGNORE INTO `padron_schema_migrations` (`version`, `descripcion`)
VALUES ('004', 'Circuito definido exclusivamente como dato editable del domicilio');
