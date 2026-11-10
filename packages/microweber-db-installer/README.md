# Microweber DB Installer

The Microweber database installer, extracted from the CMS install system. It orchestrates first-install and self-heal of the system database.

`DbInstaller::run()`:

1. ensures the `sessions` and `migrations` tables exist (and drops the legacy `migrations.hash` column if present),
2. runs the custom migrator (`app()->mw_migrator->run(app()->migrator->paths())`, provided by
   [`microweber-packages/microweber-db-migrator`](https://github.com/microweber-packages/microweber-db-migrator)),
3. builds each array-defined system table via the database table builder
   (`MicroweberPackages\Database\Utils::build_table` — the single source of truth
   for the array→table engine, from `microweber-packages/microweber-database`),
4. seeds the system data (calls `->seed()` on every injected schema provider).

## Installation

```bash
composer require microweber-packages/microweber-db-installer
```

The `DbInstallerServiceProvider` binds `DbInstaller` into the container.

## Relationship to other packages

- **db-migrator** — supplies the custom `MicroweberMigrator` bound as `mw_migrator`.
- **database** — supplies `Utils::build_table`, the array→table engine.
- The system schema definitions (`MicroweberPackages\Install\Schema\*`) live in the
  **application**, not this package. They are injected via `setSystemSchemas()`, so
  the installer stays schema-agnostic (it builds/seeds nothing when used standalone
  with no schemas injected).

## Usage

The installer is schema-agnostic: inject the system schema providers, then run. Each
provider is any object exposing `get()` (returns a `['table' => columns, ...]` map for
the table builder) and/or `seed()`.

```php
use MicroweberPackages\DbInstaller\DbInstaller;

$installer = (new DbInstaller())
    ->setSystemSchemas($schemaProviders); // object[] with get() / seed()

$installer->logger = $myLogger; // optional object with ->log($text)
$installer->run();
```

In the Microweber application the binding is wired in `AppServiceProvider`, pulling the
schema list from `MicroweberPackages\Install\InstallSchemas::get()`:

```php
$this->app->bind(\MicroweberPackages\DbInstaller\DbInstaller::class, function () {
    return (new \MicroweberPackages\DbInstaller\DbInstaller())
        ->setSystemSchemas(\MicroweberPackages\Install\InstallSchemas::get());
});

// then, during install:
app(\MicroweberPackages\DbInstaller\DbInstaller::class)->run();
```

## Key classes

- Installer: `MicroweberPackages\DbInstaller\DbInstaller`
- Provider: `MicroweberPackages\DbInstaller\DbInstallerServiceProvider`

Public methods: `setSystemSchemas(array $schemas)`, `run()`, `createSchema()`, `seed()`,
`getSystemSchemas()`, `getVendorSchemas()`, `log($text)`.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
