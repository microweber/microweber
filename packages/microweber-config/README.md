# Microweber Config

A standalone Laravel package that swaps the framework's config repository for an extended `ConfigRepository` with:

- **Per-environment config loading** – Loads config files from `config/{env}/` directories, enabling multi-site support (e.g., `config/my-site.com/`)
- **Runtime config saving** – Persist config changes back to PHP files at runtime via `Config::save()`
- **Relative path conversion** – Automatically converts absolute `storage_path()`, `database_path()`, and `base_path()` references to portable helper calls when saving
- **`beforeSaving` hooks** and **multisite detection**

## Installation

```bash
composer require microweber-packages/microweber-config
```

The package auto-registers its service provider via Laravel's package discovery. On register it replaces the `config` container binding with `ConfigRepository` (and clears the `Config` facade's cached instance), so `app('config')`, the `config()` helper and the `Config` facade all resolve to the extended repository.

## Usage

### Per-environment config loading

Create a subdirectory in your `config/` folder named after the environment:

```
config/
├── app.php          # Base config
├── database.php     # Base config
└── my-site.com/     # Environment-specific overrides
    ├── app.php
    └── database.php
```

When your app runs with `--env=my-site.com`, the files in `config/my-site.com/` override the base config (base items are preserved and only overridden where an env-specific file redefines them).

### Saving config at runtime

`save($allowed = [])` writes the given config namespaces back to their PHP files under the config directory, converting absolute paths to portable helper calls:

```php
use Illuminate\Support\Facades\Config;

Config::set('microweber.site_name', 'My Site');
Config::save(['microweber']); // writes config/microweber.php (or config/{env}/microweber.php)
```

### Multi-site detection & hooks

```php
$config = app('config');

if ($config->isMultisite()) {
    // An environment-specific config directory was detected
}

// Inspect which top-level keys changed since load
$config->getChangedKeys();

// Run a callback right before a namespace is saved (e.g. to sanitize values)
$config->beforeSaving('microweber', function (array $values): array {
    return $values;
});

// Clear the static value cache
\MicroweberPackages\Config\ConfigRepository::clearCache();
```

## Key classes

- Repository (bound as `config`): `MicroweberPackages\Config\ConfigRepository`
- Facade: `MicroweberPackages\Config\Facades\ConfigExtended` (accessor `config`)
- Provider: `MicroweberPackages\Config\ConfigServiceProvider`

## Testing

```bash
cd packages/microweber-config
composer install
vendor/bin/phpunit
```

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
