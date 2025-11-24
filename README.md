# 2. Microservicio de Post (PRY_POST_MICROSERVICIO)
Estudiantes : 
* Betty Rodriguez
* Victor Villamarin

Microservicio de Posts (Laravel) — gestión CRUD de publicaciones protegida por validación de tokens proporcionada por un microservicio de autenticación externo.



## Requisitos previos

- PHP 8.x compatible con Laravel 12
- Composer
- PostgreSQL
- Extensiones PHP típicas para Laravel (pdo_pgsql, mbstring, openssl, etc.)
- (Opcional) Postman para probar la API

## Configuración rápida

1. Clona o sitúa el código en tu máquina.
2. Copia el archivo de entorno y edítalo:

	 En PowerShell: https://github.com/saoricoder/MicroservicioPos_ExmPractico_1P.git

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
php artisan migrate
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



## Uso / Flujo de la API (Postman Collection)

La colección de Postman (`postman/Posts Microservice Flow.postman_collection.json`) incluye el flujo completo de interacción, abarcando tanto la autenticación externa como el CRUD de posts.

### 1. Autenticación (Servicio Externo)
Estas peticiones se dirigen al microservicio de Auth (`{{auth_url}}`).

- **Login**
  - `POST /api/login`
  - **Body:** `{ "email": "...", "password": "..." }`
  - *Respuesta:* Devuelve el token Bearer y el ID del usuario necesarios para las siguientes peticiones.

### 2. Gestión de Posts (Este Microservicio)
Estas rutas se dirigen al microservicio de Posts (`{{posts_url}}`). Todas requieren el encabezado `Authorization: Bearer <TOKEN>`.

- **Crear Post**
  - `POST /api/posts`
  - **Body:** `{ "title": "...", "content": "..." }`
  - *Nota:* El `user_id` se asigna automáticamente basado en el token del usuario autenticado.

- **Listar Posts**
  - `GET /api/posts`
  - Devuelve la lista de todas las publicaciones.

- **Obtener Post por ID**
  - `GET /api/posts/{id}`
  - Devuelve el detalle de un post específico.

- **Actualizar Post**
  - `PUT /api/posts/{id}`
  - **Body:** `{ "title": "...", "content": "..." }`
  - *Restricción:* Solo el usuario creador del post puede editarlo.

- **Eliminar Post**
  - `DELETE /api/posts/{id}`
  - *Restricción:* Solo el usuario creador del post puede eliminarlo.

### 3. Verificación (Servicio Externo)
- **Validar Token**
  - `GET /api/validate-token`
  - Ruta utilizada internamente por el middleware para confirmar la validez del token.



---




## Consideraciones y notas

- El microservicio de Autenticación NO está en este repositorio; debe estar corriendo en la URL configurada en `AUTH_SERVICE_URL`. Si no está disponible, el middleware devolverá `503 Authentication service unavailable`.
- El middleware valida el token mediante `GET {AUTH_SERVICE_URL}/api/validate-token` y espera `{ "user": { "id": ... } }` como respuesta para un token válido.
- He movido la URL del auth a `config/services.php` y el middleware emplea `config('services.auth.url')`.
- El middleware inyecta `auth_user` y `user_id` en la request; el `PostController` utiliza ese `user_id` para crear/validar propiedad.
- Recomendado: configurar `AUTH_SERVICE_URL` en `.env` y, en entornos de producción, usar HTTPS y tiempo de conexión/reintentos adecuados.


