<?php
declare(strict_types=1);

/**
 * Consulta única del padrón vigente.
 *
 * Tanto la pantalla administrativa como la API usan esta clase para evitar
 * diferencias en los datos que recibe cada consumidor.
 */
final class PadronConsulta
{
    public static function normalizarDni(string $valor): ?string
    {
        $dni = ltrim(preg_replace('/\D+/', '', $valor) ?? '', '0');
        if (!preg_match('/^[0-9]{6,8}$/', $dni)) {
            return null;
        }
        return str_pad($dni, 8, '0', STR_PAD_LEFT);
    }

    public static function buscarPorDni(PDO $pdo, string $dni): ?array
    {
        $consulta = $pdo->prepare(
            "SELECT
                p.id, p.dni, p.tipo_documento, p.apellido, p.nombre, p.sexo, p.clase,
                vp.domicilio, COALESCE(t.localidad,vp.localidad) AS localidad,
                COALESCE(t.circuito,vp.circuito) AS circuito,
                COALESCE(dep.nombre,vp.departamento) AS departamento, vp.mesa, vp.orden,
                vp.nivel_completitud,
                e.id AS eleccion_id, e.nombre AS eleccion_nombre, e.fecha AS eleccion_fecha,
                v.id AS version_id, v.tipo AS version_tipo, v.numero AS version_numero, v.alcance AS version_alcance,
                esc.id AS escuela_id, esc.nombre AS escuela_nombre,
                esc.domicilio AS escuela_domic, esc.localidad AS escuela_localidad
             FROM padron_personas p
             INNER JOIN padron_version_personas vp ON vp.persona_id = p.id
             INNER JOIN padron_versiones v ON v.id = vp.version_id AND v.estado = 'activa'
             INNER JOIN padron_elecciones e ON e.id = v.eleccion_id
             LEFT JOIN padron_escuelas esc ON esc.id = vp.escuela_id
             LEFT JOIN padron_territorios t ON t.id = vp.territorio_id
             LEFT JOIN padron_departamentos dep ON dep.id = t.departamento_id
             WHERE p.dni = :dni AND p.estado = 1
             LIMIT 1"
        );
        $consulta->execute(['dni' => $dni]);
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }

    public static function versionActiva(PDO $pdo): ?array
    {
        $consulta = $pdo->query(
            "SELECT v.id, v.tipo, v.numero, v.alcance, v.total_personas, v.activado_en,
                    e.nombre AS eleccion_nombre, e.fecha AS eleccion_fecha
             FROM padron_versiones v
             INNER JOIN padron_elecciones e ON e.id=v.eleccion_id
             WHERE v.estado='activa'
             ORDER BY v.activado_en DESC, v.id DESC LIMIT 1"
        );
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);
        return $fila ?: null;
    }
}
