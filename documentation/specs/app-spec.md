---
titulo: "Sistema de Gestión de Finanzas Personales - Tech Spec"
autor: "Jhonny Carpenito (Google AI Assistant)"
version: "2.1.0-filament-optimized"
stack: "Laravel 11, FilamentPHP 3, MySQL"
fecha: "2026-01-20"
---

# Contexto y Rol
Actúa como un Desarrollador Senior de Laravel y FilamentPHP. Tu objetivo es generar el código para un **Sistema de Gestión de Finanzas Personales** tipo SaaS simple.

**Reglas Generales de Código:**
1.  **PHP Version:** 8.2+ (Uso estricto de tipos `declare(strict_types=1);`).
2.  **Laravel:** Versión 11.x.
3.  **Filament:** Versión 3.x (Panel Builder).
4.  **Estilo:** Sigue los estándares PSR-12.
5.  **Seguridad:** Nunca pongas lógica de autorización compleja en los controladores/vistas, usa **Policies** de Laravel.

---

# 1. Especificaciones de Base de Datos (Migrations)

Genera las migraciones respetando estrictamente estos tipos de datos y relaciones.

## A. Tabla: `users`
* Extiende la migración por defecto de Laravel.
* Añadir columna: `$table->boolean('is_admin')->default(false);`

## B. Tabla: `tags`
* `id`: Primary Key.
* `name`: String, Unique.
* `color`: String, Nullable (Almacenará códigos hex o nombres de colores de Filament como 'success', 'danger').
* `timestamps()`

## C. Tabla: `transactions`
* `id`: Primary Key.
* `user_id`: Foreign Key (`constrained('users')->cascadeOnDelete()`).
* `type`: Enum o String (`'income'`, `'expense'`). **Importante:** Usar claves en inglés para la BD.
* `amount`: `$table->decimal('amount', 10, 2);` (Manejo preciso de dinero).
* `concept`: String (max 255).
* `date`: Date.
* `timestamps()`
* **Index:** Crear índice compuesto en `[user_id, date]` para optimizar reportes.

## D. Tabla Pivot: `tag_transaction`
* `transaction_id`: Foreign Key (`constrained()->cascadeOnDelete()`).
* `tag_id`: Foreign Key (`constrained()->cascadeOnDelete()`).
* Primary Key compuesta `['transaction_id', 'tag_id']`.

---

# 2. Modelos y Relaciones (Eloquent)

* **User:** `HasMany` Transaction.
* **Tag:** `BelongsToMany` Transaction.
* **Transaction:**
    * `BelongsTo` User.
    * `BelongsToMany` Tag.
    * **Casts:**
        * `date` => `date`
        * `amount` => `decimal:2`
        * `type` => `string` (o Enum de PHP si se prefiere).

---

# 3. Filament Resources (UI & CRUD)

## 3.1. TransactionResource
**Objetivo:** Gestión completa de ingresos y gastos.

### A. Eloquent Query Scope (Seguridad Multi-tenancy simple)
* Sobrescribir el método `getEloquentQuery()` en el Resource.
* Lógica: Si `!auth()->user()->is_admin`, aplicar `where('user_id', auth()->id())`.

### B. Form Schema (`form()`)
Usar componentes `Filament\Forms\Components`:
1.  **Section:** "Detalles de la Transacción".
    * `TextInput::make('concept')`: Required, MaxLength(255).
    * `TextInput::make('amount')`: Numeric, Required, Min(0.01), Prefix('$').
    * `DatePicker::make('date')`: Required, Default(now), MaxDate(now).
    * `Select::make('type')`:
        * Options: `['income' => 'Ingreso', 'expense' => 'Egreso']`.
        * Required.
        * Native: false.
    * `Select::make('tags')`:
        * Relationship: `tags`, `name`.
        * Multiple: True.
        * Preload: True.
        * CreateOptionForm: Permitir crear tags al vuelo (Name, Color).

### C. Table Schema (`table()`)
Usar columnas `Filament\Tables\Columns`:
1.  `TextColumn::make('date')`: Sortable, Date Format('d/m/Y').
2.  `TextColumn::make('concept')`: Searchable, Limit(50).
3.  `TextColumn::make('tags.name')`: Badge, Separator(',').
4.  `TextColumn::make('type')`:
    * Badge.
    * Colors: `['success' => 'income', 'danger' => 'expense']`.
    * FormatStateUsing: Translate labels ('Ingreso', 'Egreso').
    * Icons: `['heroicon-m-arrow-trending-up' => 'income', 'heroicon-m-arrow-trending-down' => 'expense']`.
5.  `TextColumn::make('amount')`: Money('USD'), Sortable, Alignment(Right).

### D. Filters
* `SelectFilter::make('type')`: Opciones Ingreso/Egreso.
* `Filter::make('date')`: Usar `Filter::make('created_at')` o custom filter con `Forms\Components\DatePicker` para rango (From/To).

---

# 4. Dashboard Widgets (Reportes)

Generar widgets que se registren en el `Dashboard` principal.

## A. `StatsOverviewWidget`
Debe calcular métricas basadas en el usuario autenticado (usar Scope):
1.  **Saldo Total:** (Sum of Income) - (Sum of Expense).
2.  **Ingresos del Mes:** Sum `amount` where `type='income'` & `month = current`.
3.  **Gastos del Mes:** Sum `amount` where `type='expense'` & `month = current`.

## B. `IncomeExpenseChart` (ChartWidget)
* **Tipo:** Line Chart o Bar Chart.
* **Datos:** Usar `Trend` (paquete `flowframe/laravel-trend`) o agregación manual DB raw.
    * Agrupado por `date` (perMonth) del año actual.
* **Series:** Dos datasets (Ingresos vs Egresos) con colores distintos.

---

# 5. Seguridad y Roles (Policies)

Generar `TransactionPolicy`:
* `viewAny`: `true` (El scope global filtra los datos, la policy autoriza la acción).
* `view`, `update`, `delete`: `return $record->user_id === $user->id;` (Solo el dueño puede editar).
* `create`: `true`.

Generar `UserPolicy` (Para acceso al panel de Admin):
* Métodos: `viewAny`, `create`, `update`, `delete`.
* Lógica: Solo accesible si `$user->is_admin === true`.

---

# 6. Seeders Iniciales

Crear `DatabaseSeeder` que llame a:
1.  **`TagSeeder`**: Insertar array predefinido de tags:
    * Ingresos: Salario, Freelance, Inversiones.
    * Egresos: Vivienda, Comida, Transporte, Servicios, Ocio, Salud.
2.  **`AdminUserSeeder`**: Crear usuario admin de prueba (`admin@admin.com` / `password`) con `is_admin = true`.
