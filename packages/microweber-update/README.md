# Microweber Update

Database migration package that upgrades an older Microweber install's schema and data to the current standard.

This package has **no public API, facade, or service class** — it is pure glue. Its only job is to ship a set of Laravel migrations that run automatically on `php artisan migrate`, converting data written by older Microweber versions (≈ v1.x / "213") to the layout the current CMS expects.

## What it does

The service provider extends `MicroweberPackageServiceProvider` and loads the migrations under `src/database/migrations/`:

- **`2024_05_10_000001_migrate_old_version_213`** — a non-transactional data migration (`$withinTransaction = false`) that walks a list of tables and normalises legacy data. Each step is wrapped in its own `try/catch` so a single failure on a fresh install (common on PostgreSQL, where any error aborts the surrounding transaction) does not abort the whole run. Steps include `updateSessionsTable`, `updateCreatedAtFields`, `updateCountiesTable`, `updateTranslationKeysTable`, `updateRelFields`, `updateUsersTable`, `updateMediaTable`, `updateCategoriesTable`, `updateCustomFieldsTable`, `updateContentTable`, `updateCommentsTable`, `updateModulesTable`, `updateCouponsTable`.
- **`2024_05_10_000001_migrate_morph_class_standard`** — rewrites legacy polymorphic `*_type` columns to the standardised morph-class values across `media`, `content_data`, `content_data_variants`, `custom_fields`, `categories`, `comments`, `mail_subscribers`, `rating` (tables are skipped when absent via `Schema::hasTable()`).

## Installation

```bash
composer require microweber-packages/microweber-update
```

Inside the Microweber monorepo the provider is registered by the core
`MicroweberPackages\App\Providers\MicroweberServiceProvider`. In a standalone
Laravel app, register it manually:

```php
// config/app.php (or a bootstrap provider list)
MicroweberPackages\Update\Providers\UpdateMigratorServiceProvider::class,
```

## Usage

There is nothing to call. The migrations run as part of the normal migration lifecycle:

```bash
php artisan migrate
```

`configurePackage()` sets `runsMigrations(true)` and the provider also calls
`loadMigrationsFrom(__DIR__ . '/../database/migrations/')`, so the two migrations
are discovered automatically once the provider is registered.

## Key classes

- `MicroweberPackages\Update\Providers\UpdateMigratorServiceProvider` — registers and loads the migrations.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov

## License

MIT
