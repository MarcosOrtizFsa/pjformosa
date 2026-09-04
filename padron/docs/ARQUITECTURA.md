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

1. Importador electoral (implementado; reemplaza la carga heredada de cien filas).
2. Consulta de padrón y API.
3. Afiliaciones y documentos.
4. Avales y campañas.
5. Mesas y escuelas.
6. Retiro controlado de tablas y módulos heredados.

No se elimina una tabla heredada hasta comprobar que su módulo equivalente usa
el nuevo esquema, que los conteos coinciden y que existe una copia de seguridad.

## Separación de datos

- Datos fijos: `dni`, `tipo_documento`, `apellido`, `nombre`, `clase` y `sexo`.
- Datos editables de residencia: `domicilio` y una referencia territorial que resuelve `localidad`, `circuito` y `departamento`.
- Datos de cada elección: `escuela`, `mesa` y `orden`.

El circuito identifica el lugar de residencia electoral del votante y funciona
como referencia territorial para el partido. Por eso se conserva junto al
domicilio y no se copia en cada asignación electoral.

- `padron_personas`: DNI, apellido, nombre, sexo y clase; datos estables.
- `padron_departamentos` y `padron_territorios`: catálogo fijo de los 9 departamentos y 163 circuitos de Formosa.
- `padron_domicilios`: dirección y referencia al territorio vigente, sin repetir localidad, circuito ni departamento.
- `padron_elecciones`: identifica cada elección.
- `padron_asignaciones_electorales`: exclusivamente escuela, mesa y orden por persona/elección.
- `padron_importaciones`: estado y métricas de cada archivo recibido.
- `padron_importacion_filas`: área temporal de validación para cargas masivas.
- `padron_versiones`: identifica cada fotografía anual, provisoria o definitiva.
- `padron_version_personas`: contiene todas las personas y sus datos dentro de una fotografía.
- `padron_afiliaciones`: estado partidario de una persona.
- `padron_campanas_avales`: separa las presentaciones por año.
- `padron_sedes_avales`: catálogo de procedencias donde se confeccionan los folios.
- `padron_folios_avales`: número, fecha, sede y estado de cada folio.
- `padron_avales`: personas incluidas y posición dentro de cada folio.
- `padron_migracion_incidencias`: inconsistencias heredadas pendientes de revisión.
- `padron_documentos`: metadatos de archivos privados.
- `padron_api_clientes` y `padron_api_registros`: acceso y auditoría de API.

## Carga de hasta 800.000 personas

La importación no debe realizar peticiones de cien filas desde el navegador.
El módulo nuevo sigue este flujo:

1. Guardar el archivo con nombre aleatorio y calcular SHA-256.
2. Crear un registro en `padron_importaciones`.
3. Cargar a `padron_importacion_filas` en lotes reanudables.
4. Validar formatos, duplicados, escuelas, mesas y órdenes.
5. Mostrar un resumen antes de confirmar.
6. Construir una fotografía completa sin alterar la versión activa.
7. Eliminar las filas temporales válidas después de construir la fotografía.
8. Activar la versión nueva mediante confirmación del operador.
9. Eliminar la fotografía anterior, las filas temporales restantes y el CSV procesado.

Formato aceptado: CSV UTF-8 separado por `;`, con las columnas
`dni;tipo_dni;apellido;nombres;clase;sexo;domicilio;localidad;circuito;departamento;escuela;mesa;orden`.

Cada versión informa su alcance (`provincial_completo`, `parcial` o `prueba`).
Una ausencia solo puede afectar el estado electoral de un afiliado cuando la
versión activa abarca el padrón provincial completo.

La aceptación exige únicamente DNI válido, apellido y nombres. Los demás
campos son opcionales y determinan el nivel de completitud, pero nunca dejan a
una persona fuera del padrón.

El resumen de cada carga permanece en `padron_importaciones`, pero sólo se
conserva físicamente la fotografía activa. Así las cargas anual, provisoria y
definitiva no multiplican permanentemente el tamaño del padrón.

## Convenciones

- PHP 8.2, `strict_types` en código nuevo y UTF-8 (`utf8mb4`).
- PDO y consultas preparadas en código nuevo.
- DNI normalizado a ocho dígitos, sin puntos.
- Fechas en base de datos; el año nunca debe quedar fijado dentro del código.
- Cambios estructurales únicamente mediante archivos en `database/migrations/`.
- Las decisiones no evidentes deben quedar comentadas junto al código.
- Datos sensibles y archivos generados nunca se versionan en Git.
