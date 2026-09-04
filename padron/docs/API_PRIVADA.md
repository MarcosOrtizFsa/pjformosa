# API privada v1

## Principios

- No utiliza `Access-Control-Allow-Origin: *`.
- Cada proyecto consumidor recibe un token diferente.
- En la base sólo se almacena el SHA-256 del token.
- Los permisos se expresan mediante scopes.
- Cada solicitud queda auditada con cliente, ruta, IP, estado y duración.
- Los errores no exponen SQL, rutas ni credenciales.

## Crear un cliente

Desde una consola del servidor:

```bash
php padron/tools/crear_cliente_api.php "Nombre del proyecto" "padron:consultar,sistema:salud"
```

El token se muestra una sola vez. Debe guardarse en los secretos del proyecto
consumidor, nunca en JavaScript público ni en Git.

## Autenticación

```http
Authorization: Bearer TOKEN
Accept: application/json
```

También se acepta `X-API-Key`. Opcionalmente se puede enviar `X-Client-ID`.

## Endpoints iniciales

```text
GET /padron/api/v1/salud
GET /padron/api/v1/personas/12345678
```

Scopes:

- `sistema:salud`
- `padron:consultar`

La consulta usa exclusivamente las tablas normalizadas. Hasta que se migre o
importe un padrón, responderá que la persona no fue encontrada.

Sólo se consulta `padron_versiones.estado = 'activa'`. Una versión anual,
provisoria o definitiva que todavía se está cargando no modifica las respuestas
de la API.

## APIs heredadas

Los archivos `sistema/php/json_*.php` permanecen temporalmente para no romper
módulos, pero no deben usarse en proyectos nuevos. Serán retirados después de
migrar sus consumidores a `/api/v1`.
