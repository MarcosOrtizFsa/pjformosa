# Módulo de avales

El módulo `sistema/modulos/avales` trabaja exclusivamente con la estructura
normalizada `padron_*`. Las tablas `system_700_avalados` y `system_701_folio`
quedan como respaldo histórico y no reciben nuevas escrituras.

## Flujo operativo

1. Crear una campaña anual. Al activarla, cualquier campaña operativa anterior
   se cierra, pero conserva sus folios y avales.
2. Crear folios numerados con fecha, sede y observaciones.
3. Abrir un folio e incorporar personas mediante DNI.
4. Cerrar el folio cuando su confección termina.
5. Consultar o descargar el folio como CSV cuando sea necesario.

Solo pueden incorporarse personas con una fila activa en `padron_afiliaciones`.
La misma persona no puede aparecer dos veces en una campaña, aunque se intente
agregarla en folios diferentes. Cada folio nuevo admite hasta 15 avales activos.
La carga puede hacerse de a una persona o pegando una lista de DNI; el resumen
indica cuáles fueron omitidos por no estar afiliados, estar repetidos o no tener
lugar disponible.

La carga se realiza dentro de una transacción que bloquea el folio mientras se
controla su ocupación. Esto evita que dos operadores superen simultáneamente el
límite. Los 51 folios históricos de 16 integrantes se muestran como observados,
pero no se modifican automáticamente porque representan documentación física.

## Verificador por DNI

La consulta superior informa si la persona está registrada, si tiene afiliación
activa y cuál fue su último aval. La ausencia electoral se interpreta según el
alcance de la versión activa: con un padrón parcial queda pendiente de verificar;
solo una versión provincial completa permite informar la ausencia.

## Seguridad

- Sesión interna obligatoria.
- Permisos heredados del módulo 110 para agregar, modificar y retirar.
- Token CSRF en todas las operaciones de escritura.
- PDO y parámetros preparados.
- Los avales históricos migrados no pueden retirarse desde la pantalla.
