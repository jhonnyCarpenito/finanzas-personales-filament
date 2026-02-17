# Despliegue en CapRover — Paso a paso

Esta guía explica cómo subir el proyecto **Finanzas Personales (Laravel + Filament)** a un servidor con CapRover.

---

## Requisitos previos

- Servidor con **CapRover** instalado y accesible.
- Cliente **CapRover** en tu máquina (`npm install -g caprover`) o uso del panel web.
- Este repositorio clonado y con los archivos de despliegue (Dockerfile, docker/, captain-definition, .dockerignore).

---

## Paso 1: Crear la aplicación en CapRover

1. Entra al **panel de CapRover** (por ejemplo `https://captain.tudominio.com`).
2. Inicia sesión.
3. En **Apps**, haz clic en **Create New App**.
4. Nombre de la app (por ejemplo): `finanzas-personales`.
5. Clic en **Create**.

---

## Paso 2: Configurar variables de entorno

En la app recién creada → pestaña **App Configs** → **Environment Variables**, añade al menos:

| Variable      | Valor (ejemplo) | Notas |
|---------------|------------------|--------|
| `APP_NAME`    | `Finanzas Personales` | Nombre de la app. |
| `APP_ENV`     | `production` | Entorno. |
| `APP_DEBUG`   | `false` | Siempre `false` en producción. |
| `APP_KEY`     | `base64:...` | Genera con: `php artisan key:generate --show`. |
| `APP_URL`     | `https://finanzas-personales.tudominio.com` | **Debe ser la URL final** que use la app (con HTTPS). |

### Base de datos

**Opción A — SQLite (simple, un solo contenedor)**

| Variable         | Valor |
|------------------|--------|
| `DB_CONNECTION`  | `sqlite` |
| `DB_DATABASE`    | `/var/www/html/database/database.sqlite` |

Luego configura un **volumen persistente** (Paso 4) para el archivo de la base de datos.

**Opción B — MySQL (recomendado para producción)**

1. En CapRover instala el **one-click app** de MySQL/MariaDB.
2. Anota el host, puerto, base de datos, usuario y contraseña que te asigne.
3. Añade en las variables de entorno:

| Variable         | Valor (ejemplo) |
|------------------|------------------|
| `DB_CONNECTION`  | `mysql` |
| `DB_HOST`        | `srv-captain--mysql` (o el nombre del servicio MySQL en CapRover) |
| `DB_PORT`        | `3306` |
| `DB_DATABASE`    | `finanzas` |
| `DB_USERNAME`    | (usuario que te dio CapRover) |
| `DB_PASSWORD`    | (contraseña que te dio CapRover) |

Sesiones, caché y colas usan `database` por defecto, así que funcionarán con la misma BD.

---

## Paso 3: Conectar el servidor CapRover (solo si despliegas por CLI)

Desde tu máquina:

```bash
caprover capconnect
```

Indica la URL del servidor (ej. `https://captain.tudominio.com`) y la contraseña del panel. Así quedará configurado el deploy por CLI.

---

## Paso 4: Volúmenes persistentes (recomendado)

Para que la base de datos y los logs no se pierdan al reiniciar el contenedor:

1. En la app → **App Configs** → **Volumes**.
2. Añade un volumen para **SQLite** (si usas SQLite):
   - **Container path:** `/var/www/html/database`
   - **Volume name:** (ej. `finanzas-database`)
3. Opcional, para logs y caché:
   - **Container path:** `/var/www/html/storage`
   - **Volume name:** (ej. `finanzas-storage`)

Si usas MySQL, no hace falta volumen para la BD; solo puedes montar `storage` si quieres persistir logs.

---

## Paso 5: Desplegar la aplicación

### Opción A — Desde el panel (Deploy from GitHub/Bitbucket)

1. En la app → **Deployment**.
2. Elige **Deploy from GitHub/Bitbucket** (o similar).
3. Conecta tu cuenta y selecciona el repositorio `finanzas-personales-filament`.
4. Rama: `main` (o la que uses por defecto).
5. CapRover usará el **Dockerfile** de la raíz (definido en `captain-definition`).
6. Pulsa **Deploy**. CapRover construirá la imagen y arrancará el contenedor.

### Opción B — Desde la CLI (`caprover deploy`)

En la raíz del proyecto:

```bash
cd /ruta/a/finanzas-personales-filament
caprover deploy
```

Elige la app (ej. `finanzas-personales`) cuando lo pida. CapRover enviará el contexto de build (respetando `.dockerignore`) y construirá con el Dockerfile.

---

## Paso 6: Dominio y HTTPS

1. En la app → **App Configs** → **Domain**.
2. Asigna el dominio (ej. `finanzas-personales.tudominio.com`).
3. Activa **HTTPS** si CapRover lo ofrece (Let’s Encrypt).
4. **Importante:** el valor de `APP_URL` en las variables de entorno debe coincidir exactamente con esta URL (incluido `https://`).

---

## Paso 7: Comprobar que todo funciona

1. Abre `https://tu-dominio-asignado/admin`.
2. Deberías ver la pantalla de login de Filament.
3. Si usaste los seeders en migraciones, inicia sesión con `admin@admin.com` / `password` (y cambia la contraseña en producción).

Si algo falla, revisa los **logs** de la app en CapRover (Logs) y que todas las variables de entorno estén bien (sobre todo `APP_KEY` y `APP_URL`).

---

## Resumen de archivos de despliegue en el repo

| Archivo / carpeta      | Uso |
|------------------------|-----|
| `Dockerfile`           | Imagen PHP 8.2-FPM + Nginx, build de assets y composer. |
| `docker/nginx/default.conf` | Configuración Nginx para Laravel (root `public/`). |
| `docker/php/php-production.ini` | Ajustes PHP para producción. |
| `docker/entrypoint.sh` | Ejecuta migraciones y arranca PHP-FPM + Nginx. |
| `captain-definition`   | Indica a CapRover que use el Dockerfile. |
| `.dockerignore`       | Excluye archivos innecesarios del contexto de build. |

---

## Solución de problemas

- **Error 500 / blanco:** Revisa que `APP_KEY` esté definida y que la BD sea accesible (variables `DB_*`). Mira los logs en CapRover.
- **Estilos/JS no cargan:** El Dockerfile hace `npm run build`; comprueba que el deploy haya terminado sin errores en la etapa de frontend.
- **Migraciones:** Se ejecutan al arrancar el contenedor (`docker/entrypoint.sh`). Si cambias de SQLite a MySQL, crea la base de datos en MySQL antes del primer deploy.
- **SQLite en volumen:** Si usas SQLite, el volumen debe montar el directorio donde Laravel escribe el archivo (por ejemplo `/var/www/html/database`); así el archivo `.sqlite` persiste entre reinicios.

Con estos pasos deberías tener el proyecto corriendo en CapRover. Si quieres, el siguiente paso puede ser configurar un worker para colas (`queue:work`) como servicio aparte en CapRover.
