---
name: laravel-filament
description: Laravel 12 + Filament v3.2 conventions and best practices. Use when working with PHP files, Eloquent models, migrations, Filament Resources, Forms, Tables, Widgets, Policies, or any Laravel-related task in this project.
---

# Laravel 12 + Filament v3.2

## PHP
- PHP 8.2+ with `declare(strict_types=1)` in every file
- PSR-12 standards, format with Laravel Pint
- Use typed properties, match expressions, union types, readonly where appropriate
- Use return type declarations on all methods

## Laravel Conventions
- Use Eloquent ORM and Query Builder, avoid raw SQL
- Use Policies for authorization, never inline auth logic in controllers/views
- Use `config()` helper instead of `env()` directly in code
- Use Form Requests for validation when outside Filament
- Eager load relationships to avoid N+1 (`->with()`, `->load()`)
- Use `php artisan make:model -m` to create model + migration together
- Register commands in `bootstrap/app.php` (no `app/Console/Kernel.php` in Laravel 11+)

## Filament v3.2 Resources
- Override `getEloquentQuery()` for global scoping (multi-tenancy)
- Use `Resource::getUrl('index')` for URL generation
- Use `Forms\Components\Section::make()` to group fields
- Use `->relationship()` on Select for BelongsToMany with `->multiple()` and `->preload()`
- Use `->createOptionForm()` + `->createOptionUsing()` for inline creation
- Use `->form()` on Actions/Filters (v3 syntax)

## Filament Tables
- Use `->badge()` on TextColumn for status indicators
- Use `->color(fn)`, `->icon(fn)`, `->formatStateUsing(fn)` with match expressions
- Use `->money('USD')` for currency, `->date('d/m/Y')` for dates
- Use `SelectFilter` for enums, `Filter` with `->form()` for date ranges

## Filament Widgets
- `StatsOverviewWidget` with `Stat::make()` for metrics
- `ChartWidget` for charts
- Override `canView()` to restrict by user role

## Icons
- Heroicon strings: `'heroicon-o-*'` (outline), `'heroicon-s-*'` (solid), `'heroicon-m-*'` (mini)

## Testing
- PHPUnit for tests
- Use `Livewire::test(class)` for Filament component tests
- Generate smoke tests for every Resource
