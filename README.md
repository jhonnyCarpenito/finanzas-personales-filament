# Sistema de Gestión de Finanzas Personales

Sistema SaaS de gestión de finanzas personales desarrollado con **Laravel 12** y **FilamentPHP 3**.

## 🚀 Características

- ✅ Gestión completa de transacciones (ingresos y egresos)
- ✅ Sistema de etiquetas globales y personales:
  - **Tags Globales**: Creadas por administradores, disponibles para todos
  - **Tags Personales**: Cada usuario puede crear y gestionar sus propias tags
- ✅ Dashboard con estadísticas en tiempo real:
  - Saldo total
  - Ingresos del mes
  - Gastos del mes
  - Gráfico de ingresos vs egresos por mes
- ✅ Sistema de roles y permisos:
  - **Usuario Regular**: Gestiona sus transacciones y tags personales
  - **Administrador**: Acceso completo + gestión de usuarios y tags globales
- ✅ Multi-tenancy: cada usuario solo ve sus propias transacciones
- ✅ Filtros avanzados por tipo y rango de fechas
- ✅ Interfaz moderna y responsiva con Filament

## 📋 Requisitos

- PHP 8.2+
- Composer
- Base de datos SQLite (por defecto) o MySQL

## 🛠️ Instalación

El proyecto ya está instalado y configurado. Para iniciar el servidor de desarrollo:

```bash
php artisan serve
```

Accede a la aplicación en: `http://localhost:8000/admin`

## 👤 Credenciales de Acceso

### Usuario Administrador
- **Email:** admin@admin.com
- **Password:** password

## 🔐 Sistema de Permisos

### Usuario Regular
- ✅ Crear, editar y eliminar sus propias transacciones
- ✅ Crear y editar sus propias tags personales
- ✅ Usar tags globales del sistema (solo lectura)
- ❌ No puede ver transacciones de otros usuarios
- ❌ No puede editar/eliminar tags globales

### Administrador
- ✅ Ver y gestionar todos los usuarios del sistema
- ✅ Crear, editar y eliminar tags globales
- ✅ Ver todas las transacciones del sistema
- ✅ Gestionar tags globales y personales de todos los usuarios
- ✅ Crear usuarios normales y administradores

## 🗂️ Estructura del Proyecto

### Modelos y Relaciones
- **User**: Contiene el campo `is_admin` para identificar administradores
- **Transaction**: Gestiona ingresos y egresos con relación a User y Tags
- **Tag**: Etiquetas para categorizar transacciones

### Recursos Filament
- **TransactionResource**: CRUD completo de transacciones con:
  - Formulario con validación
  - Tabla con búsqueda y filtros
  - Scope de seguridad (multi-tenancy)
  - Selector de tags (globales + personales del usuario)
- **TagResource**: Gestión de etiquetas con:
  - Indicador de tags globales vs personales
  - Toggle para admin: crear tags globales
  - Filtros por tipo de tag (solo admin)
- **UserResource**: Gestión de usuarios (solo admin)
  - Crear usuarios normales y administradores
  - Ver estadísticas de transacciones por usuario
  - Filtros por tipo de usuario

### Widgets del Dashboard
- **FinanceStatsOverview**: Tarjetas con estadísticas clave
- **IncomeExpenseChart**: Gráfico de barras comparativo por mes

### Políticas de Seguridad
- **TransactionPolicy**: Solo el dueño puede editar/eliminar sus transacciones
- **TagPolicy**: 
  - Usuarios normales: pueden editar solo sus tags personales
  - Administradores: pueden editar todas las tags (globales y personales)
  - Tags globales son de solo lectura para usuarios normales
- **UserPolicy**: Solo administradores pueden gestionar usuarios

## 📊 Base de Datos

### Tablas
- `users`: Usuarios del sistema con campo `is_admin`
- `transactions`: Transacciones con tipo, monto, concepto y fecha
- `tags`: Etiquetas con nombre, color y `user_id` (null = global)
- `tag_transaction`: Tabla pivot para relación muchos a muchos

### Seeders Incluidos
- **TagSeeder**: 9 tags globales predefinidas (Salario, Freelance, Vivienda, Comida, etc.)
- **AdminUserSeeder**: Usuario administrador de prueba

## 🎨 Características Técnicas

- ✅ PHP 8.2+ con tipos estrictos (`declare(strict_types=1)`)
- ✅ Código siguiendo estándares PSR-12
- ✅ Políticas de Laravel para autorización
- ✅ Índices de BD para optimizar consultas
- ✅ Validación de formularios
- ✅ Manejo preciso de decimales para montos

## 📝 Comandos Útiles

### Re-ejecutar migraciones y seeders
```bash
php artisan migrate:fresh --seed
```

### Verificar estilo de código
```bash
./vendor/bin/pint --test
```

### Corregir estilo de código
```bash
./vendor/bin/pint
```

### Ver rutas de Filament
```bash
php artisan route:list --path=admin
```

## 🔧 Desarrollo

El proyecto está completamente funcional y listo para usar. Puedes:

### Como Usuario Regular:
1. Iniciar sesión
2. Crear transacciones usando tags globales o propias
3. Crear tus propias tags personales
4. Ver estadísticas de tus finanzas
5. Filtrar y buscar tus transacciones

### Como Administrador:
1. Gestionar usuarios del sistema
2. Crear y editar tags globales
3. Ver todas las transacciones del sistema
4. Gestionar tags de cualquier usuario
5. Crear otros administradores

## 📄 Licencia

Este proyecto fue desarrollado según las especificaciones del archivo `app_spec.md`.
