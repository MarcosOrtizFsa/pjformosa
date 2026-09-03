# Inventario de módulos heredados

## Activos según `system_01_modulos`

- `control`: Usuarios.
- `mesas`: Mesas.
- `padron`: Padrón.
- `afiliados`: Avales.
- `afiliaciones`: Afiliaciones.
- `verificar`: Avales/Fichas 2026.
- `root`: administración técnica.

## Candidatos a retiro o integración

Los siguientes directorios no figuran como módulos activos. No deben borrarse
sin revisar referencias, permisos e historial de uso:

- `cargador`
- `checked`
- `escuelas`
- `fiscales`
- `home`
- `localidades`
- `orden`
- `planillas`
- `relevamiento`

`login` es infraestructura y no es candidato a retiro.

## Criterio para eliminar

Un módulo puede retirarse cuando:

1. No figura activo en `system_01_modulos`.
2. No es destino de rutas, perfiles ni llamadas AJAX.
3. Su información está migrada o confirmada como descartable.
4. Existe backup de código y base.
5. La eliminación fue probada primero fuera de producción.
