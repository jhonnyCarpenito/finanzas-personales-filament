---
name: laravel-tests
description: Runs and interprets the Laravel phpunit test suite for this project (php artisan test), summarizes failures, and suggests fixes. Use whenever the user asks to run tests, check coverage, or verify that recent changes did not break existing behavior.
---

# Laravel Tests Skill

## Context

This project is a Laravel 12 + Filament 3 application.  
Testing is done via `php artisan test` (phpunit), with:

- Unit tests in `tests/Unit`
- Feature / integration tests in `tests/Feature`
- Source under test in `app/`

The phpunit bootstrap and configuration live in `phpunit.xml`.

## When to Use This Skill

Use this skill when:

- The user asks to **run tests**, **check that everything is OK**, or **verify coverage**.
- The user has made changes touching policies, Filament resources, widgets, seeders, or providers.
- Before or after a significant refactor or deployment change (e.g., Docker/CaPRover adjustments).

## How to Run Tests

### Full Test Suite

From the project root (`/home/jhonny/Proyectos/laravel/finanzas-personales-filament`):

```bash
php artisan test
```

Behavior:

- Runs all Unit and Feature tests defined in `phpunit.xml`.
- Uses in-memory SQLite (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`) as configured.

### Filtered Runs

When debugging a specific area, run only the relevant tests:

```bash
# Single test class
php artisan test --filter=TransactionPolicyTest

# Namespace or partial name
php artisan test --filter=Filament\\\\TransactionResourceTest
```

## How to Interpret Results

1. **All tests passing**  
   - Report to the user: tests passed, with counts and duration.  
   - Call out any **skipped** tests and briefly explain why they are skipped.

2. **Failures**  
   For each failed test:

   - Capture:
     - Test class and method
     - Exception type and message
     - Relevant stack frame(s) in `app/` or `database/` (ignore deep vendor traces unless needed)
   - Summarize in plain language:
     - **What** went wrong (e.g., forbidden access, validation error, DB constraint).
     - **Where** (file + line, e.g. `TransactionPolicy::view`).
     - **Why** (mismatch between expected behavior and actual).
   - Propose **specific next actions**, such as:
     - Adjust a policy
     - Update a Filament Resource query
     - Fix a seeder or factory
     - Update the test expectation if the business rule changed intentionally

## Coverage Strategy for This Project

When asked about “cobertura total” (overall coverage), focus on these areas:

- **Policies**: `UserPolicy`, `TagPolicy`, `TransactionPolicy`
- **Filament Resources**: `TransactionResource`, `UserResource`, `TagResource`
- **Widgets**: `IncomeExpenseChart`, `FinanceStatsOverview`
- **Seeders**: admin user, test user (`prueba@ejemplo.com`), and their data
- **Providers/Infra**: `AppServiceProvider` (HTTPS forcing), `AuthServiceProvider`

When adding new functionality in these areas:

1. Create a **Feature test** in `tests/Feature/...` that:
   - Sets up the minimal data required (using factories/seeders).
   - Calls into the behavior via **policies**, **resources**, or **HTTP routes** (`php artisan route:list` to find names).
   - Asserts both **positive** (allowed paths) and **negative** (forbidden/hidden) cases.
2. Run `php artisan test` and iterate until green.

## Examples of Use

### Example 1 – Run full suite and summarize

1. Execute:

   ```bash
   php artisan test
   ```

2. If everything passes:
   - Reply with: total tests, assertions, duration.
   - Highlight key areas covered (e.g., “policies, seeders, Filament widgets, HTTPS provider”).

3. If there are failures:
   - For each failure, include a short bullet like:
     - **TransactionPolicyTest::test_admin_cannot_view_any_transactions** – expected `false`, got `true`; admin can still see transactions.  
       - Likely fix: adjust `TransactionPolicy::viewAny` or update the test if the rule changed.

### Example 2 – Focused check after policy change

1. After modifying `TransactionPolicy` or `TransactionResource`:

   ```bash
   php artisan test --filter='TransactionPolicyTest|TransactionResourceTest|TransactionsEndToEndTest'
   ```

2. Report only on these related tests, to give fast feedback on the security/isolation rules.

