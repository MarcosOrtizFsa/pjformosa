# API privada del padron - v1

La API esta pensada para comunicaciones backend a backend entre proyectos
propios. El token nunca debe colocarse en JavaScript, aplicaciones publicas ni
repositorios Git.

## Puesta en marcha

1. Ejecutar las migraciones hasta `012_servicios_api_v1.sql`.
2. Crear un cliente desde la consola del servidor:

```bash
php padron/tools/crear_cliente_api.php "Proyecto consumidor" "sistema:salud,padron:consultar,padron:mesas" 120
```

Los argumentos opcionales son el limite por minuto y una lista de IP separadas
por comas. Cero desactiva el limite. El token se muestra una sola vez.

Para revocar inmediatamente un cliente:

```bash
php padron/tools/revocar_cliente_api.php "CLIENT-ID"
```

3. Guardar `Client-ID` y `Token` como secretos del proyecto consumidor.

## Autenticacion

```http
Authorization: Bearer TOKEN
X-Client-ID: UUID
Accept: application/json
```

`X-Client-ID` es recomendable y `X-API-Key` puede reemplazar a `Authorization`.
No se habilita CORS porque el consumo previsto es desde servidores.

## Servicios

| Metodo y ruta | Scope | Resultado |
| --- | --- | --- |
| `GET /padron/api/v1/salud` | `sistema:salud` | Estado del servicio |
| `GET /padron/api/v1/version` | `padron:consultar` | Version electoral activa |
| `GET /padron/api/v1/personas/{dni}` | `padron:consultar` | Persona, domicilio y lugar de votacion |
| `GET /padron/api/v1/mesas/{numero}` | `padron:mesas` | Escuela y resumen de una mesa |
| `GET /padron/api/v1/escuelas?nombre=texto` | `padron:mesas` | Escuelas coincidentes y sus mesas |
| `GET /padron/api/v1/verificacion/{dni}` | `partido:consultar` | Estado electoral, afiliacion, tramite y ultimo aval |
| `GET /padron/api/v1/avales/{dni}` | `partido:avales` | Historial de avales de una persona |

Los servicios partidarios no devuelven archivos, rutas internas ni imagenes de
documentos. Solo informan su cantidad y tipos en la verificacion.

## Ejemplo PHP consumidor

```php
<?php
$url = 'https://dominio.example/padron/api/v1/personas/12345678';
$curl = curl_init($url);
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
        'Authorization: Bearer '.getenv('PADRON_API_TOKEN'),
        'X-Client-ID: '.getenv('PADRON_API_CLIENT_ID'),
    ],
]);
$cuerpo = curl_exec($curl);
$estado = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
curl_close($curl);
$respuesta = json_decode((string) $cuerpo, true, 512, JSON_THROW_ON_ERROR);
```

## Formato de respuesta

Exito:

```json
{"ok":true,"version":"1.0","request_id":"uuid","data":{}}
```

Error:

```json
{"ok":false,"version":"1.0","request_id":"uuid","error":{"code":"CODIGO","message":"Detalle"}}
```

Codigos HTTP habituales: `200`, `401`, `403`, `404`, `405`, `422`, `429` y
`500`. Cada respuesta queda auditada. El encabezado `Retry-After: 60` acompana
los rechazos por limite de solicitudes.

## API heredada

Los archivos `sistema/php/json_*.php` siguen protegidos por la sesion interna y
no deben utilizarse en integraciones nuevas. Los nuevos consumidores deben usar
exclusivamente `/padron/api/v1`.
