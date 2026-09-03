# Arquitectura del sistema Padrón

## Objetivo

`padron/` es un subsistema privado con dos responsabilidades:

1. Mantener personas y asignaciones electorales para informar dónde votan.
2. Administrar afiliaciones, avales y documentación partidaria.

El portal público ubicado en la raíz no debe enlazar ni depender de este sistema.

## Transición escalonada

Las tablas `system_*` continúan funcionando como capa heredada. Las tablas
`padron_*` son el nuevo núcleo y no reemplazan datos automáticamente.

El orden de migración será:

1. Importador electoral.
2. Consulta de padrón y API.
3. Afiliaciones y documentos.
4. Avales y campañas.
5. Mesas y escuelas.
6. Retiro controlado de tablas y módulos heredados.

No se elimina una tabla heredada hasta comprobar que su módulo equivalente usa
el nuevo esquema, que los conteos coinciden y que existe una copia de seguridad.

## Separación de datos

- `padron_personas`: DNI, apellido, nombre, sexo y clase; datos estables.
- `padron_domicilios`: domicilio, localidad y circuito con vigencia histórica.
- `padron_elecciones`: identifica cada elección.
- `padron_asignaciones_electorales`: escuela, mesa y orden por persona/elección.
- `padron_importaciones`: estado y métricas de cada archivo recibido.
- `padron_importacion_filas`: área temporal de validación para cargas masivas.
- `padron_afiliaciones`: estado partidario de una persona.
- `padron_campanas_avales` y `padron_avales`: avales separados por campaña.
- `padron_documentos`: metadatos de archivos privados.
- `padron_api_clientes` y `padron_api_registros`: acceso y auditoría de API.

## Carga de hasta 800.000 personas

La importación no debe realizar peticiones de cien filas desde el navegador.
El módulo nuevo deberá:

1. Guardar el archivo con nombre aleatorio y calcular SHA-256.
2. Crear un registro en `padron_importaciones`.
3. Cargar a `padron_importacion_filas` desde un proceso de servidor.
4. Validar formatos, duplicados, escuelas, mesas y órdenes.
5. Mostrar un resumen antes de confirmar.
6. Insertar/actualizar personas y domicilios mediante operaciones por lotes.
7. Crear asignaciones para una elección todavía en estado `validando`.
8. Activar la elección únicamente si los controles finales son correctos.

Una elección anterior se archiva; no se sobrescribe ni se elimina.

## Convenciones

- PHP 8.2, `strict_types` en código nuevo y UTF-8 (`utf8mb4`).
- PDO y consultas preparadas en código nuevo.
- DNI normalizado a ocho dígitos, sin puntos.
- Fechas en base de datos; el año nunca debe quedar fijado dentro del código.
- Cambios estructurales únicamente mediante archivos en `database/migrations/`.
- Las decisiones no evidentes deben quedar comentadas junto al código.
- Datos sensibles y archivos generados nunca se versionan en Git.
