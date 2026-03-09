---
name: project-context
description: Project-specific context for the personal finance management system (finanzas-personales-filament). Use when working on any feature, bug fix, or modification in this project. Provides domain knowledge about models, authorization, and architecture.
---

# Finanzas Personales - Project Context

## Overview
Personal finance management SaaS built with Laravel 12 + Filament v3.2. Users track income/expenses with tags. Admins manage users only.

## Models & Relationships
- **User**: `HasMany` Transaction. Fields: name, email, password, is_admin (bool), blocked_at (datetime nullable)
- **Transaction**: `BelongsTo` User, `BelongsToMany` Tag. Fields: user_id, type ('income'|'expense'), amount (decimal:2), concept, date. Index on [user_id, date]
- **Tag**: `BelongsTo` User (nullable), `BelongsToMany` Transaction. Fields: name, color, user_id (nullable = global tag)
- Pivot table: `tag_transaction`

## Authorization
- Multi-tenancy: `getEloquentQuery()` filters by `user_id` in Resources
- TransactionPolicy: only owner can CRUD, admin CANNOT access transactions
- Tag scoping: `Tag::forUser($userId)` returns global tags (user_id=null) + user's personal tags
- Admin (`is_admin=true`): manages users only, no transaction access
- Blocked users: `blocked_at != null`

## Filament Structure
- `app/Filament/Resources/TransactionResource.php` - CRUD with month/tags/type/date-range filters
- `app/Filament/Resources/TagResource.php` - Simple ManageTags page
- `app/Filament/Resources/UserResource.php` - Admin only
- `app/Filament/Widgets/FinanceStatsOverview.php` - Balance, monthly income/expense stats
- `app/Filament/Widgets/IncomeExpenseChart.php` - Income vs expense chart

## UI Conventions
- Labels in Spanish, DB keys in English
- `match` expressions for type → labels/colors/icons mapping
- Money: `->money('USD')`, Dates: `d/m/Y`, Default sort: `date desc`

## Deployment
- Docker multi-stage: Node (frontend build) → Composer (deps) → PHP 8.2-fpm + Nginx
- CapRover for deployment (see `captain-definition` and `DEPLOYMENT-CAPROVER.md`)

## Dev Commands
- `composer dev` - Start server + queue + logs + vite concurrently
- `composer test` - Run PHPUnit tests
- `composer setup` - Full project setup
