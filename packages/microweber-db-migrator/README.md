# Microweber DB Migrator

Standalone Laravel package providing Microweber's resilient database migrator.
Extracted from the CMS install system so it can be reused in any Laravel
application.

## Features

- **MicroweberMigrator** – a resilient migrator that wraps Laravel's `Migrator`,
  auto-creates the `migrations` table, and never throws on duplicate-migration
  errors (so re-running against a partially-migrated database is safe).

The service provider binds it to the container as `mw_migrator`.

> The database installer orchestration lives in
> [`microweber-packages/db-installer`](../microweber-db-installer), and the
> array→table builder lives in `microweber-packages/database`
> (`MicroweberPackages\Database\Utils::build_table`). This package is the
> migrator only.

## Installation

```bash
composer require microweber-packages/db-migrator
```

The package auto-discovers its service provider. If you need to register manually:

```php
// config/app.php
'providers' => [
    MicroweberPackages\DbMigrator\DbMigratorServiceProvider::class,
],
```

## Quick Start

```php
// Resolve the migrator (bound as `mw_migrator`) and run migrations:
app('mw_migrator')->run(app('migrator')->paths());
```

## License

MIT
