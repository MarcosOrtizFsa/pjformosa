# Padrón de afiliados y verificación

`padron_pj` concentra la consulta que antes estaba dividida entre padrón de
afiliados y `verificar`. Una búsqueda por DNI informa en una sola respuesta:

- identidad y presencia en el padrón electoral activo;
- alcance completo, parcial o de prueba de esa versión;
- domicilio, localidad, circuito y departamento;
- estado formal de la afiliación;
- trámite de afiliación pendiente y responsable;
- historial de avales y último folio;
- preselección para la campaña de avales activa;
- cantidad y tipos de documentos digitales asociados.

## Estados operativos

- **En padrón electoral:** aparece en la versión activa.
- **Pendiente de verificación:** no aparece, pero la versión es parcial o de prueba.
- **Fuera del padrón vigente:** ausencia confirmada únicamente contra una versión provincial completa.
- **No afiliado:** identidad electoral sin `padron_afiliaciones`.
- **Afiliación pendiente:** existe trámite, pero aún no se confirmó la afiliación.
- **Afiliado activo:** afiliación partidaria vigente, independientemente de su situación electoral derivada.
- **Preseleccionado:** separado para la campaña activa, todavía sin folio.
- **Avalado:** relacionado con un folio formal de la campaña.

## Migración del verificador

La migración 011 conserva `system_2003_nuevos_tramites` en
`padron_tramites_afiliacion` y `system_2004_nuevos_avales` en
`padron_candidatos_avales`. Un trámite puede conservar temporalmente solo el DNI
cuando la persona aún no existe en el padrón; al aparecer en una carga posterior
se podrá relacionar con `padron_personas.id`.

El módulo heredado `verificar` y sus tablas todavía no se eliminan. Se retirarán
después de comprobar el flujo nuevo con los operadores.
