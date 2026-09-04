# Importador del padrón

## Archivo aceptado

- CSV codificado en UTF-8.
- Separador: punto y coma (`;`).
- Tamaño máximo de la aplicación: 512 MB.
- La primera fila debe contener exactamente:

`dni;tipo_dni;apellido;nombres;clase;sexo;domicilio;localidad;circuito;escuela;mesa;orden`

El modelo descargable está en
`sistema/modulos/padron/templates/modelo_importacion.csv`.

## Etapas

1. **Subida:** guarda el archivo con un nombre aleatorio fuera del acceso HTTP y crea una elección en borrador.
2. **Validación:** procesa 2.000 filas por petición y guarda los resultados en el área temporal.
3. **Revisión:** muestra cantidades válidas, rechazadas, niveles de completitud y las primeras veinte observaciones.
4. **Aplicación:** procesa 1.000 filas válidas por petición y construye una fotografía completa aislada.
5. **Activación:** el operador confirma qué versión usarán las consultas. La fotografía anterior se elimina.

Las etapas de validación y aplicación guardan su posición después de cada lote.
Si el navegador se cierra o pierde conexión, se retoman desde “Importaciones
recientes” sin comenzar nuevamente.

Cada proceso electoral puede recibir versiones `anual`, `provisorio` y
`definitivo`. Una carga nueva no mezcla sus integrantes con la anterior: una
persona ausente en el CSV nuevo tampoco aparece en el padrón activo.

Al completar la aplicación se eliminan de `padron_importacion_filas` todas las
filas válidas. Al activar se eliminan los temporales y CSV de todas las
importaciones completadas —incluidas cargas anteriores— junto con la fotografía
anterior. Solamente se conserva el resumen numérico en `padron_importaciones`.

## Reglas relevantes

- El DNI se normaliza quitando puntos, espacios y ceros iniciales.
- Solamente DNI válido, apellido y nombres son obligatorios.
- Tipo de documento, clase, sexo, domicilio, localidad, circuito, escuela, mesa y orden pueden estar vacíos.
- Una fila sólo se rechaza si no permite identificar a la persona: DNI inválido, apellido vacío o nombres vacíos.
- Los DNI repetidos se aceptan y se señalan como advertencia; prevalece la última fila del archivo.
- Nivel 1: todos los campos están informados.
- Nivel 2: tiene DNI, apellido y nombres, además de una parte de los campos opcionales.
- Nivel 3: solamente tiene DNI, apellido y nombres.
- Si cambia un domicilio, el anterior se cierra y se conserva como histórico.
- El circuito relaciona el domicilio con `padron_territorios`; de allí se obtienen la localidad y el departamento oficiales sin repetirlos por persona.
- Escuela, mesa y orden pertenecen a la elección; no sobrescriben elecciones anteriores.
- Una elección sólo se publica al presionar “Activar elección”.

## Puesta en producción

Antes de utilizar el módulo debe ejecutarse
`database/migrations/003_importador_padron.sql`. Los límites de PHP se declaran
en `.htaccess` y `.user.ini`; algunos proveedores exigen configurarlos también
desde su panel de hosting.
