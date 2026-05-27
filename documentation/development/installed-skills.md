# Skills y Reglas Instaladas para IA

Este documento describe todas las skills y reglas instaladas en el proyecto para los asistentes de IA de **Warp** (Oz) y **Cursor IDE**.

---

## Tabla de Contenidos

1. [Resumen General](#resumen-general)
2. [Warp Skills](#warp-skills)
3. [Cursor Rules (Reglas)](#cursor-rules)
4. [Cursor Skills](#cursor-skills)
5. [Cómo Funcionan](#cómo-funcionan)
6. [Mantenimiento](#mantenimiento)

---

## Resumen General

Se instalaron dos tipos de configuraciones:

- **Warp Skills** (`.warp/skills/`): 2 skills personalizadas para el contexto del proyecto.
- **Cursor Rules** (`.cursor/rules/`): 10 reglas del paquete comunitario `pekral/cursor-rules` + 1 regla personalizada del proyecto.
- **Cursor Skills** (`.cursor/skills/`): 11 skills de automatización del paquete `pekral/cursor-rules`.

---

## Warp Skills

Las skills de Warp son descubiertas automáticamente por el agente Oz cuando trabajas dentro del directorio del proyecto. Oz lee el nombre y descripción de cada skill, y la invoca cuando es relevante para tu tarea.

### laravel-filament

- **Ubicación:** `.warp/skills/laravel-filament/SKILL.md`
- **Cuándo se activa:** Al trabajar con archivos PHP, modelos Eloquent, migraciones, Resources de Filament, Policies o cualquier tarea relacionada con Laravel.
- **Qué hace:** Proporciona al agente las convenciones y mejores prácticas específicas del stack:
  - **PHP 8.2+**: `declare(strict_types=1)`, PSR-12, typed properties, match expressions.
  - **Laravel 12**: Eloquent ORM, Policies para autorización, `config()` en vez de `env()`, eager loading.
  - **Filament v3.2**: Sintaxis correcta de Resources, Forms (`->form()` en Actions), Tables (`->badge()`, `->money()`, `->color()`), Widgets (`StatsOverviewWidget`, `ChartWidget`).
  - **Iconos**: Formato de strings Heroicon (`heroicon-o-*`, `heroicon-s-*`, `heroicon-m-*`).
  - **Testing**: PHPUnit con `Livewire::test()` para componentes Filament.

### project-context

- **Ubicación:** `.warp/skills/project-context/SKILL.md`
- **Cuándo se activa:** Al trabajar en cualquier feature, bug fix o modificación del proyecto.
- **Qué hace:** Proporciona conocimiento del dominio específico del proyecto:
  - **Modelos y relaciones**: User → Transaction → Tag, con tabla pivot `tag_transaction`.
  - **Autorización**: Multi-tenancy vía `getEloquentQuery()`, TransactionPolicy, scoping de tags (`forUser()`).
  - **Estructura Filament**: Ubicación exacta de cada Resource y Widget.
  - **Convenciones UI**: Labels en español, claves en inglés, formatos de moneda y fecha.
  - **Deployment**: Docker multi-stage + CapRover.
  - **Comandos de desarrollo**: `composer dev`, `composer test`, `composer setup`.

---

## Cursor Rules

Las reglas de Cursor se aplican automáticamente según el patrón de archivos (globs) o de forma global (`alwaysApply: true`). El agente de Cursor las lee al editar archivos que coincidan con el patrón.

**Origen:** Paquete comunitario [`pekral/cursor-rules`](https://github.com/pekral/cursor-rules) (MIT License, autor: Petr Král).

### php/core.mdc

- **Aplicación:** Siempre activa.
- **Qué hace:** Define el stack tecnológico del proyecto (lee `composer.json`) y establece reglas de comportamiento del agente IA:
  - No inventar cambios ni especular.
  - Preservar código existente.
  - No sugerir cambios cuando no hay modificaciones necesarias.

### php/standards.mdc

- **Aplicación:** Siempre activa.
- **Qué hace:** Estándares unificados de código PHP:
  - **Naming**: PascalCase para clases (siempre `final`), camelCase para métodos/variables, kebab-case para URLs.
  - **Estructura**: Single Responsibility, DTOs en vez de `array<mixed>`, composición sobre herencia.
  - **PHP Style**: Notación corta nullable (`?string`), `void` en return types, match expressions.
  - **Testing**: 100% coverage, patrón arrange-act-assert, data providers.
  - **Bug-Fix Workflow**: Escribir tests ANTES de corregir bugs.
  - **DRY**: Eliminar duplicación, mantener single sources of truth.
  - **API Design**: Estilo fluido tipo SDK.

### laravel/architecture.mdc

- **Aplicación:** Siempre activa.
- **Qué hace:** Convenciones de arquitectura Laravel:
  - **Capas**: Controllers (slim) → Services (lógica) → Repositories (solo lectura) → ModelManagers (escritura).
  - **Controllers**: Solo CRUD, inyección por método, sin `validate()` directo.
  - **Database**: Tipos de datos apropiados, `DECIMAL` para dinero, InnoDB, snake_case.
  - **Migrations**: Solo `up()`, sin `down()`. Actualizar `$fillable` al añadir columnas.
  - **Helpers**: Usar `auth()->id()` en vez de `Auth::id()`, `collect()` en vez de `foreach`.
  - **Queue/Jobs**: Idempotentes, con `$tries`, `$backoff`, `$timeout`.
  - **Middleware**: Una responsabilidad por middleware, sin lógica de negocio.

### laravel/filament.mdc

- **Aplicación:** Solo en archivos `app/Filament/**/*.php`.
- **Qué hace:** Reglas específicas de Filament v3.2 (ajustada desde v4 original):
  - **Resources**: Smoke tests obligatorios, `getEloquentQuery()` para multi-tenancy.
  - **Forms (v3)**: `Section::make()`, `->form()` en Actions, `->createOptionForm()` para creación inline.
  - **Tables (v3)**: `->badge()`, `->money('USD')`, `SelectFilter`, `Filter` con date ranges.
  - **Widgets (v3)**: `StatsOverviewWidget`, `ChartWidget`, `canView()`.
  - **Enums**: Interfaces `HasLabel`, `HasColor`, `HasIcon`.
  - **Icons**: Strings Heroicon (no enum como en v4).

### git/conventions.mdc

- **Aplicación:** Siempre activa.
- **Qué hace:** Convenciones de Git:
  - Formato de commits: `type(scope): description`.
  - Tipos: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`.
  - Commits pequeños y enfocados.
  - Mensajes en inglés.

### git/pr.mdc

- **Aplicación:** Siempre activa.
- **Qué hace:** Convenciones para Pull Requests:
  - Vincular commits a issues de GitHub para cierre automático.
  - Seguir conventional commits.
  - Incluir links a fuentes de análisis en la descripción del PR.
  - **Nunca hacer push a main**.

### sql/optimalize.mdc

- **Aplicación:** Siempre activa.
- **Qué hace:** Optimización de queries SQL:
  - Evitar N+1 con `with()`, `load()` o JOINs.
  - Cláusulas WHERE SARGable (sin funciones en columnas indexadas).
  - Seek pagination en vez de OFFSET.
  - Nunca `SELECT *`.
  - Diseño de índices compuestos (orden izquierda-derecha).
  - Transacciones cortas, sin llamadas externas dentro.
  - Patrones avanzados: CTEs, window functions, CTEs recursivos.

### security/backend.md

- **Aplicación:** Siempre activa.
- **Qué hace:** Reglas de seguridad backend basadas en OWASP Top 10:
  - Injection, autenticación, exposición de datos sensibles.
  - Prepared statements, principio de mínimo privilegio.
  - Sin secretos hardcodeados en código fuente.

### security/frontend.md

- **Aplicación:** Siempre activa.
- **Qué hace:** Reglas de seguridad frontend:
  - XSS, CSRF, CSP headers.
  - Clickjacking, open redirects.
  - Sanitización de inputs dinámicos.

### security/mobile.md

- **Aplicación:** Siempre activa.
- **Qué hace:** Reglas de seguridad móvil (WebView, almacenamiento inseguro). Menos relevante para este proyecto pero incluida por completitud.

### project-context.mdc (personalizada)

- **Aplicación:** Siempre activa.
- **Qué hace:** Contexto específico del proyecto (idéntico al skill de Warp `project-context`):
  - Stack técnico, modelos, relaciones, autorización.
  - Estructura de Filament Resources y Widgets.
  - Convenciones de UI y deployment.

---

## Cursor Skills

Las skills de Cursor son flujos de trabajo automatizados que el agente puede invocar. Se ubican en `.cursor/skills/` y cada una contiene instrucciones paso a paso.

**Origen:** Paquete comunitario [`pekral/cursor-rules`](https://github.com/pekral/cursor-rules) (MIT License, autor: Petr Král).

### Resolución de Issues

#### resolve-github-issue

- **Uso:** Dándole un ID o URL de issue de GitHub.
- **Flujo:**
  1. Obtiene información del issue vía MCP/CLI.
  2. Analiza adjuntos del issue.
  3. Corrige el bug siguiendo las reglas de `class-refactoring`.
  4. Ejecuta code review + security review.
  5. Itera hasta que no haya errores críticos.
  6. Asegura 100% code coverage.
  7. Ejecuta fixers automáticos (Pint, etc.).
  8. Crea un PR según convenciones.
  9. Comenta en el issue los hallazgos críticos/moderados.
  10. Regresa a la rama principal.

#### resolve-jira-issue

- **Uso:** Dándole un ID o URL de issue de JIRA.
- **Flujo:** Similar a `resolve-github-issue`, pero:
  - Usa la herramienta `acli` o MCP de JIRA.
  - Vincula el PR al issue de JIRA.
  - Cambia el estado del issue a "ready for review".

#### resolve-bugsnag-issue

- **Uso:** Dándole un ID o URL de error de Bugsnag.
- **Flujo:** Similar a `resolve-github-issue`, pero obtiene la información del error desde Bugsnag vía MCP.

#### analyze-problem

- **Uso:** Para analizar un problema de un issue tracker sin modificar código.
- **Flujo:**
  1. Analiza el issue y descarga adjuntos.
  2. Genera un análisis técnico detallado de la causa raíz.
  3. Propone soluciones efectivas sin efectos secundarios.
  4. Genera dos salidas: una técnica y otra para no-programadores (product managers).
- **Importante:** No modifica código, solo genera análisis.

### Code Review

#### code-review-github

- **Uso:** Para revisar un PR de GitHub.
- **Flujo:**
  1. Carga todas las reglas del proyecto.
  2. Ejecuta code review + security review.
  3. Cambia a la rama del PR.
  4. Lista solo problemas críticos o moderados.
  5. Publica comentarios en el PR vía CLI de GitHub.
  6. Ejecuta tests y reporta resultados.
- **Importante:** No modifica código, solo genera reportes.

#### code-review-jira

- **Uso:** Para revisar código asociado a un issue de JIRA.
- **Flujo:** Similar a `code-review-github`, pero obtiene contexto desde JIRA.

#### security-review

- **Uso:** Para auditoría de seguridad del código.
- **Flujo:**
  1. Carga todas las reglas de seguridad del proyecto.
  2. Revisa OWASP Top 10 completo.
  3. Verifica: injection, auth, data exposure, CSRF, CORS, CSP, secrets, etc.
  4. Genera reporte estructurado por severidad (Critical / High / Medium / Low).
  5. Incluye ubicación exacta (archivo y línea) y snippets de corrección.
- **Importante:** No modifica código, solo genera el reporte.

### Testing

#### create-test

- **Uso:** Para crear tests de código nuevo o existente.
- **Flujo:**
  1. Localiza tests existentes o crea nuevos siguiendo convenciones.
  2. Usa data providers cuando mejoran legibilidad.
  3. Mock solo para servicios externos o simulación de excepciones.
  4. Asegura 100% coverage para los cambios.
  5. Elimina archivos de coverage generados.
- **Importante:** No modifica código de producción.

#### rewrite-tests-pest

- **Uso:** Para convertir tests PHPUnit existentes a sintaxis PEST.
- **Flujo:**
  1. Identifica tests que no usan sintaxis PEST.
  2. Los reescribe en PEST manteniendo DRY.
  3. Aplica data providers.
  4. Verifica 100% coverage.
  5. Valida que los tests reescritos funcionan correctamente.

### Refactoring

#### class-refactoring

- **Uso:** Para refactorizar clases PHP.
- **Flujo:**
  1. Analiza la clase y completa tareas del TODO.
  2. Preserva funcionalidad (cambia el cómo, no el qué).
  3. Aplica: Single Responsibility, DRY, clean code.
  4. Extrae métodos privados si el body supera ~30 líneas.
  5. Usa DTOs de Spatie en vez de arrays.
  6. Collections sobre `foreach`.
  7. Verifica code coverage después del refactoring.
- **No hace:** Modificar tests existentes (salvo que el refactoring lo requiera).

### Package Review

#### package-review

- **Uso:** Para auditar el `composer.json` del proyecto.
- **Flujo:**
  1. Verifica links en la documentación.
  2. Valida calidad del `composer.json`.
  3. Chequea campos obligatorios: `name`, `description`, `type`, `license`, `authors`, `require`, `autoload`.
  4. Chequea campos opcionales: `keywords`, `homepage`, `support`, `require-dev`, `scripts`.

---

## Cómo Funcionan

### En Warp (Oz)

1. Al iniciar una conversación con Oz, este recibe la lista de skills disponibles (nombre + descripción).
2. Cuando detecta que una skill es relevante para tu tarea, la carga automáticamente.
3. También puedes invocar una skill directamente con `/{nombre-de-la-skill}`.
4. Las skills se descubren por directorio de trabajo: solo se cargan las del proyecto actual.

### En Cursor

1. **Rules** (`.cursor/rules/`): Se aplican automáticamente según los globs definidos en el frontmatter. Las reglas con `alwaysApply: true` se cargan siempre. Las reglas con `globs` se cargan solo al editar archivos que coincidan.
2. **Skills** (`.cursor/skills/`): El agente las invoca cuando son relevantes, o puedes referenciarlas manualmente con `@.cursor/skills/{nombre}/SKILL.md` en el chat.

---

## Mantenimiento

### Actualizar reglas de Cursor (pekral/cursor-rules)

```bash
composer update pekral/cursor-rules
vendor/bin/cursor-rules install --force
```

> **Nota:** `--force` sobrescribe archivos existentes. Sin esta flag, solo se copian archivos nuevos.

### Modificar reglas

- Edita directamente los archivos en `.cursor/rules/` o `.warp/skills/`.
- Las reglas de Filament ya fueron ajustadas de v4 a v3.2 para este proyecto.
- La regla `project-context.mdc` y las skills de Warp deben actualizarse manualmente si cambia la arquitectura del proyecto.
