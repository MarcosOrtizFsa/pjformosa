# Manual para consumir la API privada de PJ Formosa

Este manual explica cómo conectar un proyecto propio con la API disponible en:

```text
https://pjformosa.com.ar/padron/api/v1
```

La integración debe realizarse desde el backend del proyecto consumidor. El
token no debe enviarse al navegador ni guardarse en JavaScript, HTML o Git.

## 1. Solicitar las credenciales

El administrador de PJ Formosa debe crear un cliente diferente para cada
proyecto y ambiente desde el módulo interno **Clientes API**. Por ejemplo,
producción y desarrollo deben utilizar credenciales distintas.

El proyecto consumidor recibirá dos valores:

```text
Client-ID: UUID DEL CLIENTE
Token: TOKEN SECRETO
```

El token se muestra una sola vez. Si se pierde, se debe revocar el cliente y
crear otro. Nunca se debe solicitar o enviar el token por una URL.

### Permisos disponibles

| Scope | Permite consultar |
| --- | --- |
| `sistema:salud` | Disponibilidad del servicio |
| `padron:consultar` | Versión activa y personas del padrón electoral |
| `padron:mesas` | Mesas y escuelas |
| `partido:consultar` | Verificación electoral, afiliación y último aval |
| `partido:avales` | Historial de avales |

Se deben solicitar solamente los permisos que el proyecto realmente necesite.

## 2. Guardar la configuración como secreto

Crear variables de entorno en el servidor consumidor:

```dotenv
PADRON_API_URL=https://pjformosa.com.ar/padron/api/v1
PADRON_API_CLIENT_ID=reemplazar-por-el-client-id
PADRON_API_TOKEN=reemplazar-por-el-token
```

El archivo `.env` debe estar incluido en `.gitignore`:

```gitignore
.env
.env.local
.env.*.local
```

En hosting compartido, las variables pueden configurarse desde el panel o en un
archivo privado ubicado fuera de `public_html`. No imprimirlas en mensajes de
error ni registrarlas en logs.

## 3. Probar primero el servicio de salud

### PowerShell

```powershell
$env:PADRON_API_URL = "https://pjformosa.com.ar/padron/api/v1"
$env:PADRON_API_CLIENT_ID = "CLIENT-ID"
$env:PADRON_API_TOKEN = "TOKEN"

curl.exe `
  -H "Accept: application/json" `
  -H "Authorization: Bearer $env:PADRON_API_TOKEN" `
  -H "X-Client-ID: $env:PADRON_API_CLIENT_ID" `
  "$env:PADRON_API_URL/salud"
```

### Linux o macOS

```bash
curl \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $PADRON_API_TOKEN" \
  -H "X-Client-ID: $PADRON_API_CLIENT_ID" \
  "$PADRON_API_URL/salud"
```

La respuesta esperada es:

```json
{
  "ok": true,
  "version": "1.0",
  "request_id": "uuid",
  "data": {
    "servicio": "padron-api",
    "estado": "operativo",
    "hora": "2026-09-05T12:00:00-03:00"
  }
}
```

## 4. Crear un cliente PHP reutilizable

Guardar una clase como `src/PjFormosaApi.php` dentro del proyecto consumidor:

```php
<?php
declare(strict_types=1);

final class PjFormosaApi
{
    public function __construct(
        private readonly string $urlBase,
        private readonly string $clientId,
        private readonly string $token,
    ) {
        if ($urlBase === '' || $clientId === '' || $token === '') {
            throw new RuntimeException('Falta configurar la API de PJ Formosa.');
        }
    }

    public function get(string $ruta, array $query = []): array
    {
        $url = rtrim($this->urlBase, '/').'/'.ltrim($ruta, '/');
        if ($query !== []) {
            $url .= '?'.http_build_query($query, '', '&', PHP_QUERY_RFC3986);
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Bearer '.$this->token,
                'X-Client-ID: '.$this->clientId,
            ],
        ]);

        $cuerpo = curl_exec($curl);
        $errorConexion = curl_error($curl);
        $estadoHttp = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        if ($cuerpo === false) {
            throw new RuntimeException('No se pudo conectar con la API: '.$errorConexion);
        }

        try {
            $respuesta = json_decode($cuerpo, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new RuntimeException('La API devolvió una respuesta inválida.');
        }

        if ($estadoHttp < 200 || $estadoHttp >= 300 || !($respuesta['ok'] ?? false)) {
            $codigo = $respuesta['error']['code'] ?? 'HTTP_'.$estadoHttp;
            $mensaje = $respuesta['error']['message'] ?? 'Error al consultar la API.';
            throw new PjFormosaApiException($mensaje, $estadoHttp, (string) $codigo);
        }

        return (array) ($respuesta['data'] ?? []);
    }
}

final class PjFormosaApiException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly int $estadoHttp,
        public readonly string $codigoApi,
    ) {
        parent::__construct($message, $estadoHttp);
    }
}
```

Instanciarlo únicamente en el backend:

```php
$api = new PjFormosaApi(
    (string) getenv('PADRON_API_URL'),
    (string) getenv('PADRON_API_CLIENT_ID'),
    (string) getenv('PADRON_API_TOKEN'),
);
```

## 5. Realizar consultas

### Persona y lugar de votación

Requiere `padron:consultar`:

```php
$datos = $api->get('/personas/'.rawurlencode($dni));
```

```http
GET /personas/12345678
```

Devuelve datos personales básicos, domicilio electoral, escuela, mesa, orden y
la versión del padrón utilizada.

### Versión activa

Requiere `padron:consultar`:

```php
$version = $api->get('/version');
```

Permite mostrar qué padrón está utilizando el proveedor y si su alcance es
`prueba`, `parcial` o `provincial_completo`.

### Mesa

Requiere `padron:mesas`:

```php
$mesa = $api->get('/mesas/123');
```

Devuelve escuela, cantidad de electores y rango de órdenes.

### Buscar escuelas

Requiere `padron:mesas`. El texto debe tener entre 3 y 100 caracteres:

```php
$escuelas = $api->get('/escuelas', ['nombre' => 'EPEP 58']);
```

Devuelve hasta 25 escuelas coincidentes con sus mesas.

### Verificación partidaria

Requiere `partido:consultar`:

```php
$estado = $api->get('/verificacion/'.rawurlencode($dni));
```

Los valores posibles de `estado_general` son:

- `fuera_del_padron`
- `no_afiliado`
- `afiliacion_en_tramite`
- `afiliado_sin_aval`
- `afiliado_avalado`

La respuesta informa si existen documentos y sus tipos, pero nunca devuelve
imágenes, archivos ni rutas privadas.

### Historial de avales

Requiere `partido:avales`:

```php
$historial = $api->get('/avales/'.rawurlencode($dni));
```

Devuelve campaña, año, folio, fecha, sede, posición y estado.

## 6. Validar el DNI antes de consultar

El consumidor puede aceptar DNI con puntos, pero debe enviar solamente dígitos:

```php
$dni = preg_replace('/\D+/', '', (string) $dniIngresado);
if (!preg_match('/^[0-9]{6,8}$/', $dni)) {
    throw new InvalidArgumentException('El DNI no es válido.');
}
```

No se debe buscar por nombre para intentar reemplazar una consulta por DNI.

## 7. Manejar correctamente los errores

| HTTP | Significado | Acción recomendada |
| --- | --- | --- |
| `401` | Token ausente, inválido o vencido | Revisar secretos o solicitar nuevas credenciales |
| `403` | Scope o IP no autorizados | Solicitar el permiso correcto; no reintentar continuamente |
| `404` | Registro o versión no encontrados | Mostrar un resultado vacío comprensible |
| `405` | Método incorrecto | Utilizar `GET` |
| `422` | DNI, mesa o búsqueda inválidos | Corregir los datos antes de repetir |
| `429` | Límite por minuto superado | Esperar lo indicado por `Retry-After` |
| `500` | Error interno del proveedor | Registrar `request_id` y reintentar más tarde |

Ejemplo:

```php
try {
    $persona = $api->get('/personas/'.$dni);
} catch (PjFormosaApiException $error) {
    if ($error->estadoHttp === 404) {
        $persona = null;
    } else {
        error_log('API PJ Formosa: '.$error->codigoApi);
        throw $error;
    }
}
```

No mostrar al usuario final el token, detalles de conexión ni errores internos.

## 8. Reglas de seguridad

- Consumir siempre por `https://`.
- Realizar las llamadas desde PHP u otro backend, nunca desde JavaScript público.
- Utilizar un cliente diferente por proyecto y ambiente.
- Aplicar el principio de menor permiso al elegir scopes.
- Restringir por IP cuando el servidor consumidor tenga una IP fija.
- No desactivar la validación TLS de cURL.
- No incluir el token en URL, formularios, logs, capturas ni commits.
- Revocar inmediatamente credenciales expuestas o de proyectos retirados.
- Conservar el `request_id` de las respuestas fallidas para auditoría.
- No efectuar reintentos automáticos ante `401`, `403`, `404` o `422`.

## 9. Lista final de comprobación

- [ ] El endpoint `/salud` devuelve HTTP 200.
- [ ] URL, Client-ID y token están configurados como secretos.
- [ ] `.env` está ignorado por Git.
- [ ] El token solo se utiliza en el backend.
- [ ] El cliente tiene únicamente los scopes necesarios.
- [ ] La consulta utiliza HTTPS y valida el certificado.
- [ ] Se controlan timeouts, errores HTTP y JSON inválido.
- [ ] Se respeta `Retry-After` cuando la API devuelve 429.
- [ ] Los logs conservan `request_id`, pero nunca el token.
- [ ] La integración fue probada con un DNI existente y otro inexistente.

## 10. Diagnóstico rápido

1. Probar `/salud` con las mismas credenciales.
2. Confirmar que la URL base no termina duplicando `/v1`.
3. Verificar que el hosting entrega la cabecera `Authorization`.
4. Como alternativa, enviar el token mediante `X-API-Key`.
5. Revisar el scope requerido por el endpoint.
6. Si existe restricción por IP, verificar la IP pública del consumidor.
7. Ante un error del proveedor, informar fecha, endpoint y `request_id`.

Ejemplo alternativo de autenticación:

```http
X-API-Key: TOKEN
X-Client-ID: UUID
Accept: application/json
```
