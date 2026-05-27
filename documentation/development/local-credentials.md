# Local development credentials

Default accounts created by the database seeders for **local** and **testing** environments.

> **Warning:** These credentials are for development only. Never use them in production. Change passwords after deploying.

## Panel URL

After running `php artisan serve`:

| Environment | URL |
|-------------|-----|
| Filament admin panel | `http://localhost:8000/admin` |

## Seed the database

```bash
php artisan migrate --seed
```

Or, if the schema already exists:

```bash
php artisan db:seed
```

Seeders involved: `AdminUserSeeder`, `TestUserSeeder` (see `database/seeders/DatabaseSeeder.php`).

---

## Administrator

| Field | Value |
|-------|-------|
| Name | Admin |
| Email | `admin@admin.com` |
| Password | `password` |
| `is_admin` | `true` |

**Source:** `database/seeders/AdminUserSeeder.php`

**Access:**

- User management (Filament `UserResource`)
- Global tags
- Does **not** access regular user transactions (project policy: admins manage users, not transactions)

---

## Regular user

| Field | Value |
|-------|-------|
| Name | Usuario de prueba |
| Email | `prueba@ejemplo.com` |
| Password | `password` |
| `is_admin` | `false` |

**Source:** `database/seeders/TestUserSeeder.php` (`TestUserSeeder::TEST_USER_EMAIL`)

**Access:**

- Own transactions, tags, fund origins, and dashboard widgets
- Sample data from `TestUserDataSeeder` and `PersonalTransactionsJsonSeeder` (when applicable)

**Extra seeders (local / testing only):**

- `TestUserCapitalSnapshotsSeeder` — capital snapshots for charts (runs only when `APP_ENV` is `local` or `testing`)

---

## Quick reference

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@admin.com` | `password` |
| Regular user | `prueba@ejemplo.com` | `password` |
