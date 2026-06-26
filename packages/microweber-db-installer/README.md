# Microweber DB Installer

The Microweber database installer, extracted from the CMS install system.

`DbInstaller` orchestrates first-install and self-heal of the system database:

1. ensures the `sessions` and `migrations` tables exist,
2. runs the custom migrator (`app()->mw_migrator`, provided by
   [`microweber-packages/db-migrator`](../microweber-db-migrator)),
3. builds each array-defined system table via the database table builder
   (`MicroweberPackages\Database\Utils::build_table` — the single source of
   truth for the array→table engine, from `microweber-packages/database`),
4. seeds the system data.

## Relationship to other packages

- **db-migrator** — supplies the custom `MicroweberMigrator` (`mw_migrator`).
- **database** — supplies `Utils::build_table`, the array→table engine.
- The system schema definitions (`MicroweberPackages\Install\Schema\*`) live in
  the application and are consumed by the installer; they are intentionally
  **not** moved into a package (they are app-specific). The installer pulls the
  list from `MicroweberPackages\Install\InstallSchemas::get()`, so this package
  stays schema-agnostic (it returns no schemas when used standalone).

## Usage

```php
$installer = new \MicroweberPackages\DbInstaller\DbInstaller();
$installer->run();
```

The legacy `\MicroweberPackages\Install\DbInstaller` is kept as a `@deprecated`
back-compat subclass of this class.
