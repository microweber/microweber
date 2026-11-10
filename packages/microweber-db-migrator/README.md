# Microweber DB Migrator

Standalone Laravel package providing Microweber's resilient database migrator.
Extracted from the CMS install system so it can be reused in any Laravel
application.

## Features

- **MicroweberMigrator** – a resilient migrator that wraps Laravel's `Migrator`,
  auto-creates the `migrations` table before every `run()`/`runUp()`, and never
  throws on a failing migration — it logs the error (and `Log::error`s it) and
  continues, so re-running against a partially-migrated database is safe.

`DbMigratorServiceProvider` singleton-binds it to the container as `mw_migrator`.

> The database installer orchestration lives in
> [`microweber-packages/microweber-db-installer`](https://github.com/microweber-packages/microweber-db-installer), and the
> array→table builder lives in `microweber-packages/microweber-database`
> (`MicroweberPackages\Database\Utils::build_table`). This package is the
> migrator only.

## Installation

```bash
composer require microweber-packages/microweber-db-migrator
```

`DbMigratorServiceProvider` is registered through Microweber's package loader. To
wire it up manually in a plain Laravel app:

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

// Optional: attach a logger object exposing ->log($text)
app('mw_migrator')->logger = $myLogger;
```

## Key classes

- Migrator: `MicroweberPackages\DbMigrator\MicroweberMigrator` (extends `Illuminate\Database\Migrations\Migrator`)
- Provider: `MicroweberPackages\DbMigrator\DbMigratorServiceProvider`

Container binding: `mw_migrator`.

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
