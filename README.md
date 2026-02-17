# Sistema de Gestión de Finanzas Personales

Sistema SaaS de gestión de finanzas personales con panel de administración moderno, multi-usuario y multi-tenant. Desarrollado con **Laravel 12** y **FilamentPHP 3**.

---

## Tabla de contenidos

- [Descripción](#descripción)
- [Características](#características)
- [Stack tecnológico](#stack-tecnológico)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Uso](#uso)
- [Credenciales por defecto](#credenciales-por-defecto)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Base de datos](#base-de-datos)
- [Seguridad y permisos](#seguridad-y-permisos)
- [Comandos útiles](#comandos-útiles)
- [Especificaciones](#especificaciones)
- [Licencia](#licencia)

---

## Descripción

Aplicación web que permite a los usuarios registrar **ingresos** y **egresos**, clasificarlos con **etiquetas** (tags) y consultar un **dashboard** con estadísticas y gráficos. Los administradores pueden gestionar usuarios, etiquetas globales y ver todas las transacciones del sistema. El diseño sigue un modelo **multi-tenant**: cada usuario solo accede a sus propios datos, salvo los administradores.

El proyecto fue implementado según las especificaciones del documento **`app_spec.md`** (Tech Spec).

---

## Características

### Gestión de transacciones
- CRUD completo de transacciones (ingresos y egresos).
- Campos: concepto, monto, fecha, tipo y etiquetas (múltiples por transacción).
- Búsqueda por concepto y filtros avanzados:
  - **Por mes**: selector de mes/año (por defecto: mes actual).
  - **Por etiquetas**: una o varias etiquetas a la vez.
  - **Por tipo**: ingreso o egreso.
  - **Por rango de fechas**: desde / hasta.

### Sistema de etiquetas
- **Tags globales**: creadas por administradores y disponibles para todos los usuarios (solo lectura para usuarios regulares).
- **Tags personales**: cada usuario puede crear y gestionar sus propias etiquetas.
- Colores y nombres configurables; creación de tags desde el formulario de transacciones.

### Dashboard
- **Tarjetas de estadísticas**: saldo total, ingresos del mes y gastos del mes.
- **Gráfico**: ingresos vs egresos por mes (año actual).

### Roles y permisos
- **Usuario regular**: gestiona sus transacciones y tags personales; usa tags globales en solo lectura.
- **Administrador**: gestión de usuarios (crear, editar, bloquear), tags globales y visualización de todas las transacciones.

### Otros
- Interfaz responsiva con Filament (Tailwind CSS, Vite).
- Bloqueo de usuarios: los administradores pueden bloquear cuentas; el middleware impide el acceso y cierra sesión si el usuario está bloqueado.
- Código con tipos estrictos en PHP y estándares PSR-12 (Laravel Pint).

---

## Stack tecnológico

| Tecnología        | Uso                          |
|-------------------|------------------------------|
| **PHP** 8.2+      | Backend                      |
| **Laravel** 12    | Framework                    |
| **Filament** 3    | Panel de administración      |
| **SQLite** / MySQL| Base de datos (configurable) |
| **Vite** + Tailwind CSS 4 | Frontend (assets)   |
| **Laravel Pint**  | Estilo de código (PSR-12)    |

---

## Requisitos

- **PHP** ≥ 8.2 (extensiones: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `curl`; para SQLite: `pdo_sqlite`)
- **Composer** 2.x
- **Node.js** y **npm** (para compilar assets con Vite)
- **Base de datos**: SQLite (por defecto) o MySQL / PostgreSQL

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio> finanzas-personales-filament
cd finanzas-personales-filament
```

### 2. Dependencias PHP

```bash
composer install
```

### 3. Variables de entorno

```bash
cp .env.example .env
php artisan key:generate
```

Configura en `.env` al menos:
- `APP_NAME`, `APP_URL` (para producción)
- `DB_CONNECTION`: `sqlite` por defecto; si usas MySQL, define `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

Para **SQLite** (por defecto):

```bash
touch database/database.sqlite
```

### 4. Base de datos

```bash
php artisan migrate --seed
```

### 5. Assets (frontend)

```bash
npm install
npm run build
```

En desarrollo puedes usar `npm run dev` en lugar de `npm run build`.

### 6. Servidor de desarrollo

```bash
php artisan serve
```

La aplicación quedará disponible en `http://localhost:8000`. El panel de administración está en:

**`http://localhost:8000/admin`**

---

## Uso

- **Usuarios regulares**: iniciar sesión → Dashboard con estadísticas → Transacciones (crear, editar, eliminar, filtrar por mes/tags/tipo/fechas) → Etiquetas (solo las propias).
- **Administradores**: además pueden gestionar usuarios (incluido bloqueo), tags globales y ver todas las transacciones.

Al entrar en Transacciones, por defecto se muestran las del **mes actual**; puedes cambiar el mes, añadir filtros por etiquetas o por tipo y rango de fechas.

---

## Credenciales por defecto

Tras ejecutar los seeders, existe un usuario administrador de prueba:

| Rol   | Email           | Contraseña |
|-------|-----------------|------------|
| Admin | `admin@admin.com` | `password` |

**Importante:** cambia esta contraseña en entornos de producción.

---

## Estructura del proyecto

### Modelos (`app/Models`)
- **User**: autenticación, `is_admin`, `blocked_at`; relación `transactions()`.
- **Transaction**: `user_id`, `type`, `amount`, `concept`, `date`; relaciones `user()`, `tags()`; casts para `date` y `amount`.
- **Tag**: `name`, `color`, `user_id` (null = global); relación `transactions()`; scopes `global()` y `forUser($userId)`.

### Recursos Filament (`app/Filament/Resources`)
- **TransactionResource**: CRUD de transacciones; scope multi-tenant en `getEloquentQuery()`; filtros (mes, etiquetas, tipo, rango de fechas); selector de tags con creación al vuelo.
- **TagResource**: gestión de etiquetas; indicador global/personal; solo admin puede crear/editar tags globales.
- **UserResource**: gestión de usuarios (solo admin); creación de usuarios y administradores; estadísticas por usuario.

### Widgets del Dashboard (`app/Filament/Widgets`)
- **FinanceStatsOverview**: tarjetas de saldo total, ingresos del mes y gastos del mes.
- **IncomeExpenseChart**: gráfico de barras ingresos vs egresos por mes.

### Políticas (`app/Policies`)
- **TransactionPolicy**: solo el dueño puede ver/editar/eliminar sus transacciones.
- **TagPolicy**: usuarios normales solo editan sus tags personales; admins editan todas; tags globales en solo lectura para no-admin.
- **UserPolicy**: solo administradores pueden gestionar usuarios.

### Middleware
- **CheckUserBlocked**: cierra sesión y redirige si el usuario tiene `blocked_at` definido.

### Seeders (`database/seeders`)
- **TagSeeder**: 9 tags globales (Salario, Freelance, Inversiones; Vivienda, Comida, Transporte, Servicios, Ocio, Salud).
- **AdminUserSeeder**: usuario `admin@admin.com` con `is_admin = true`.
- **AdminUserDataSeeder**: transacciones de ejemplo para el admin (usa `TransactionFactory`).

### Factories (`database/factories`)
- **UserFactory**: usuario de prueba.
- **TransactionFactory**: transacciones con estados `income()` y `expense()`.

---

## Base de datos

### Tablas principales
- **users**: id, name, email, password, is_admin, blocked_at, timestamps, email_verified_at, remember_token.
- **tags**: id, name, color, user_id (nullable), timestamps.
- **transactions**: id, user_id, type, amount, concept, date, timestamps; índice en (user_id, date).
- **tag_transaction**: transaction_id, tag_id; clave primaria (transaction_id, tag_id).

Sesiones, caché y colas usan **database** por defecto (tablas `sessions`, `cache`, `jobs` según migraciones de Laravel).

---

## Seguridad y permisos

- **Multi-tenancy**: en `TransactionResource::getEloquentQuery()` se aplica `where('user_id', auth()->id())` para usuarios no administradores.
- **Políticas**: todas las acciones sobre transacciones, tags y usuarios pasan por las policies correspondientes.
- **Bloqueo**: el middleware `CheckUserBlocked` impide el acceso a usuarios bloqueados y cierra su sesión.
- **Contraseñas**: hasheadas con bcrypt (configuración por defecto de Laravel).

---

## Comandos útiles

| Acción | Comando |
|--------|--------|
| Servidor de desarrollo | `php artisan serve` |
| Desarrollo (servidor + Vite + cola + logs) | `composer run dev` |
| Migraciones desde cero + seeders | `php artisan migrate:fresh --seed` |
| Solo seeders | `php artisan db:seed` |
| Solo datos del admin | `php artisan db:seed --class=AdminUserDataSeeder` |
| Rutas del panel | `php artisan route:list --path=admin` |
| Verificar estilo (Pint) | `./vendor/bin/pint --test` |
| Corregir estilo | `./vendor/bin/pint` |
| Tests | `php artisan test` |
| Build frontend | `npm run build` |

---

## Especificaciones

El diseño y la implementación siguen el documento **`app_spec.md`** (Tech Spec), que define:

- Esquema de base de datos (users, tags, transactions, tag_transaction).
- Modelos Eloquent y relaciones.
- Recursos Filament (TransactionResource con formulario, tabla y filtros).
- Widgets del dashboard (estadísticas y gráfico ingresos/egresos).
- Políticas de autorización (Transaction, Tag, User).
- Seeders iniciales (tags globales y usuario admin).

La versión actual del proyecto utiliza **Laravel 12** (el spec original menciona Laravel 11) y añade mejoras como filtro por mes (por defecto mes actual), filtro por una o varias etiquetas, bloqueo de usuarios y seeder de datos de ejemplo para el admin.

---

## Licencia

Este proyecto fue desarrollado según las especificaciones del archivo `app_spec.md`. Consulta el repositorio o al autor para más detalles de licencia.
