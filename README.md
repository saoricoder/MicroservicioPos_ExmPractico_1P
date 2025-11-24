# 2\. Microservicio de Post (PRY\_POST\_MICROSERVICIO)

> **Microservicio de Posts (Laravel)** — Gestión CRUD de publicaciones protegida por validación de tokens proporcionada por un microservicio de autenticación externo.

### 🎓 Estudiantes

  * **Betty Rodriguez**
  * **Victor Villamarin**

-----

## 🛠️ Requisitos Previos

Asegúrate de tener instalado lo siguiente antes de comenzar:

  - **PHP 8.x** (Compatible con Laravel 12)
  - **Composer**
  - **PostgreSQL**
  - **Extensiones PHP:** `pdo_pgsql`.
  - **Postman**  para pruebas de API.

-----

## 🚀 Configuración Rápida

Sigue estos pasos en orden para levantar el proyecto:

### 1\. Clonar el Repositorio

Sitúate en tu carpeta de proyectos y ejecuta:

```bash
git clone https://github.com/saoricoder/MicroservicioPos_ExmPractico_1P.git
cd MicroservicioPos_ExmPractico_1P
```

### 2\. Configurar el Entorno (.env)

Copia el archivo de ejemplo. En **PowerShell**:

```powershell
Copy-Item .env.example .env
```

Edita el archivo `.env` y ajusta las siguientes variables clave (Base de datos y URL del servicio de Auth):

```ini
APP_NAME=PRY_POST_MICROSERVICIO
APP_ENV=local
APP_KEY=
APP_URL=http://localhost:8000

# Configuración de Base de Datos
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_post
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

# URL del Microservicio de Autenticación (Externo)
AUTH_SERVICE_URL=http://localhost:8001
```

### 3\. Instalar Dependencias y Generar Key

Ejecuta los siguientes comandos para instalar las librerías de Laravel y generar la llave de encriptación:

```powershell
composer install
php artisan key:generate
```

### 4\. Base de Datos

Ejecuta las migraciones para crear las tablas necesarias:

```powershell
php artisan migrate
```

> **Nota:** Si necesitas reiniciar la base de datos con datos de prueba (seeders), usa:
>
> ```powershell
> php artisan migrate:fresh --seed
> ```

### 5\. Levantar el Servidor

Inicia el servidor local en el puerto 8000:

```powershell
php artisan serve --port=8000
```

-----

## 🔌 Uso y Flujo de la API

El sistema depende de un flujo de autenticación externo. A continuación se describe el ciclo de vida de las peticiones.

### 1️⃣ Autenticación (Servicio Externo)

*Estas peticiones van dirigidas al microservicio de Auth (ej. puerto 8001).*

| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| `POST` | `/api/login` | **Body:** `{ "email": "...", "password": "..." }`<br>Devuelve el `token` necesario. |

### 2️⃣ Gestión de Posts (Este Microservicio)

*Todas las peticiones requieren Header:* `Authorization: Bearer <TOKEN>`

| Método | Endpoint | Descripción | Restricciones |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/posts` | Listar todos los posts | - |
| `POST` | `/api/posts` | Crear un nuevo post | `user_id` se asigna desde el token |
| `GET` | `/api/posts/{id}` | Ver detalle de un post | - |
| `PUT` | `/api/posts/{id}` | Actualizar un post | Solo el propietario |
| `DELETE` | `/api/posts/{id}` | Eliminar un post | Solo el propietario |

*(Body para POST/PUT: `{ "title": "...", "content": "..." }`)*

### 3️⃣ Verificación (Interna)

| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| `GET` | `/api/validate-token` | Usada internamente por el middleware para validar el token contra el servicio de Auth. |

-----

## 🧪 Postman

Se incluye una colección lista para usar en la carpeta del proyecto:
📂 `postman/PRY_POST_MICROSERVICIO.postman_collection.json`

**Configuración en Postman:**

1.  Importa la colección.
2.  Configura las variables de entorno/colección:
      - `baseUrl`: `http://localhost:8000`
      - `auth_url`: `http://localhost:8001` (Donde corra tu servicio de auth)
      - `token`: Pega aquí el token obtenido del login.

-----

## ⚠️ Consideraciones Importantes

1.  **Dependencia Externa:** Este microservicio **NO** gestiona usuarios ni tokens. Depende de que `AUTH_SERVICE_URL` esté configurado y el servicio de autenticación esté corriendo.
2.  **Middleware:** El middleware `CheckAuthToken` intercepta las peticiones, valida el token con el servicio externo e inyecta el `user_id` y `auth_user` en la request.
3.  **Error 503:** Si el servicio de autenticación está apagado, la API responderá con `503 Authentication service unavailable`.