# Sistema de Gestión de Finanzas Personales

Sistema SaaS de gestión de finanzas personales desarrollado con **Laravel 12** y **FilamentPHP 3**.

## 🚀 Características

- ✅ Gestión completa de transacciones (ingresos y egresos)
- ✅ Sistema de etiquetas para categorizar transacciones
- ✅ Dashboard con estadísticas en tiempo real:
  - Saldo total
  - Ingresos del mes
  - Gastos del mes
  - Gráfico de ingresos vs egresos por mes
- ✅ Multi-tenancy: cada usuario solo ve sus propias transacciones
- ✅ Rol de administrador con acceso completo
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
- **TagResource**: Gestión simple de etiquetas

### Widgets del Dashboard
- **FinanceStatsOverview**: Tarjetas con estadísticas clave
- **IncomeExpenseChart**: Gráfico de barras comparativo por mes

### Políticas de Seguridad
- **TransactionPolicy**: Solo el dueño puede editar/eliminar sus transacciones
- **UserPolicy**: Solo administradores pueden gestionar usuarios

## 📊 Base de Datos

### Tablas
- `users`: Usuarios del sistema con campo `is_admin`
- `transactions`: Transacciones con tipo, monto, concepto y fecha
- `tags`: Etiquetas con nombre y color
- `tag_transaction`: Tabla pivot para relación muchos a muchos

### Seeders Incluidos
- **TagSeeder**: Tags predefinidos (Salario, Freelance, Vivienda, Comida, etc.)
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

1. Iniciar sesión como administrador
2. Crear nuevas transacciones
3. Agregar etiquetas personalizadas
4. Ver estadísticas en el dashboard
5. Filtrar y buscar transacciones

## 📄 Licencia

Este proyecto fue desarrollado según las especificaciones del archivo `app_spec.md`.
