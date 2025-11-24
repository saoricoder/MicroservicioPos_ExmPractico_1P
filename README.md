PRY_POST_MICROSERVICIO

Microservicio de Posts (Laravel) — gestión CRUD de publicaciones protegida por validación de tokens proporcionada por un microservicio de autenticación externo.

## Resumen

Este repositorio contiene el microservicio de Posts. Requisitos principales:

- Laravel app (código ya incluido)
- Base de datos PostgreSQL (configurable vía `.env`)
- Un microservicio de Autenticación externo que expone `/api/validate-token` y devuelve JSON con `{ "user": { "id": ..., ... } }` para tokens válidos.

El middleware `app/Http/Middleware/CheckAuthToken.php` valida el Bearer token llamando al servicio de autenticación (configurable con `AUTH_SERVICE_URL`) y inyecta en la request el objeto `auth_user` y `user_id` (por lo tanto, el cliente NO debe enviar `user_id` en las peticiones).

## Requisitos previos

- PHP 8.x compatible con Laravel 12
- Composer
- PostgreSQL
- Extensiones PHP típicas para Laravel (pdo_pgsql, mbstring, openssl, etc.)
- (Opcional) Postman para probar la API

## Configuración rápida

1. Clona o sitúa el código en tu máquina.
2. Copia el archivo de entorno y edítalo:

	 En PowerShell:

```powershell
Copy-Item .env.example .env
```

3. Ajusta estas variables en ` .env ` (ejemplo):

```
APP_NAME=PRY_POST_MICROSERVICIO
APP_ENV=local
APP_KEY=
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tu_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# URL base del microservicio de autenticación (usada por el middleware)
AUTH_SERVICE_URL=http://localhost:8001
```

4. Instala dependencias PHP:

```powershell
composer install
```

5. Genera la key de la aplicación:

```powershell
php artisan key:generate
```

6. Ejecuta migraciones:

```powershell
```

Si necesitas volver a crear la base de datos en limpio durante desarrollo:

```powershell
php artisan migrate:fresh --seed
```

7. Levanta el servidor de desarrollo:

```powershell
php artisan serve --port=8000
```

## Uso / Rutas principales

Las rutas del microservicio están en `routes/api.php` y requieren autorización mediante el middleware `auth.micro` (alias para `CheckAuthToken`):

- GET `/api/posts` — Listar posts
- POST `/api/posts` — Crear post (body JSON: `title`, `content`) — `user_id` se asigna desde el token
- GET `/api/posts/{post}` — Ver post por ID
- PUT `/api/posts/{post}` — Actualizar post (body JSON: `title`, `content`) — solo el propietario
- DELETE `/api/posts/{post}` — Eliminar post — solo el propietario

Todas las peticiones deben llevar cabecera `Authorization: Bearer <TOKEN>`.

## Postman

He incluido una colección para Postman en la carpeta `postman`: `postman/PRY_POST_MICROSERVICIO.postman_collection.json`.

Importa la colección en Postman y configura las variables de colección/entorno:

- `baseUrl` — por defecto `http://localhost:8000`
- `token` — pega un Bearer token válido que devuelva el microservicio de Auth
- `postId` — usar para operaciones que requieren ID

Ejemplo: crear un post — En Postman, `POST {{baseUrl}}/api/posts` con body JSON:

```json
{
	"title": "Mi título",
	"content": "Contenido de ejemplo"
}
```

No enviar `user_id` en el body.

## Consideraciones y notas

- El microservicio de Autenticación NO está en este repositorio; debe estar corriendo en la URL configurada en `AUTH_SERVICE_URL`. Si no está disponible, el middleware devolverá `503 Authentication service unavailable`.
- El middleware valida el token mediante `GET {AUTH_SERVICE_URL}/api/validate-token` y espera `{ "user": { "id": ... } }` como respuesta para un token válido.
- He movido la URL del auth a `config/services.php` y el middleware emplea `config('services.auth.url')`.
- El middleware inyecta `auth_user` y `user_id` en la request; el `PostController` utiliza ese `user_id` para crear/validar propiedad.
- Recomendado: configurar `AUTH_SERVICE_URL` en `.env` y, en entornos de producción, usar HTTPS y tiempo de conexión/reintentos adecuados.

## Tests

No hay tests integrados específicos para el flujo de autenticación en este repositorio. Recomendación:

- Añadir tests que fingen (`Http::fake()`) las respuestas del auth service para validar el middleware.

## Problemas conocidos

- El microservicio depende de un servicio de autenticación externo para validar tokens. Para desarrollo puedes crear un mock simple que devuelva `{ "user": { "id": 1 } }` ante cualquier token.

## Soporte / Siguientes pasos sugeridos

- Añadir logging y métricas para trazabilidad
- Agregar tests automatizados para middleware y controladores
- Mejorar tolerancia: `Http::retry()` y cache de validación (si el auth lo permite)

---

Si quieres, puedo:
- Añadir un mock de Auth dentro de este repositorio para pruebas locales, o
- Crear tests automatizados que fingen el servicio de Auth.
Indica cuál prefieres y lo preparo.
