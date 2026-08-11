-- Índices para el módulo Asistencia.
-- Compatible con MariaDB 10.4 o superior.
-- No elimina datos ni impide repetir un DNI en distintos registros/años.

ALTER TABLE `system_100_congresistas`
    ADD INDEX IF NOT EXISTS `idx_congresistas_ano_estado_id`
        (`system_100_ano`, `system_100_estado`, `id_system_100`),
    ADD INDEX IF NOT EXISTS `idx_congresistas_dni_ano`
        (`system_100_dni`, `system_100_ano`),
    ADD INDEX IF NOT EXISTS `idx_congresistas_ano_estado_departamento`
        (`system_100_ano`, `system_100_estado`, `system_100_departamento`),
    ADD INDEX IF NOT EXISTS `idx_congresistas_departamento_estado`
        (`system_100_departamento`, `system_100_estado`);

-- Verificación: al finalizar deben aparecer PRIMARY y los cuatro índices anteriores.
SHOW INDEX FROM `system_100_congresistas`;
