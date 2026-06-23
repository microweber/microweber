# Microweber Config

A standalone Laravel package that extends the default config repository with:

- **Per-environment config loading** – Loads config files from `config/{env}/` directories, enabling multi-site support (e.g., `config/my-site.com/`)
- **Runtime config saving** – Persist config changes back to PHP files at runtime
- **Relative path conversion** – Automatically converts absolute `storage_path()`, `database_path()`, and `base_path()` references to portable helper calls when saving

## Installation

```bash
composer require microweber-packages/config
```

The package auto-registers its service provider via Laravel's package discovery.

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

When your app runs with `--env=my-site.com`, the files in `config/my-site.com/` override the base config.

### Saving config at runtime

```php
use Illuminate\Support\Facades\Config;

Config::set('microweber.site_name', 'My Site');
Config::save(['microweber']);
```

### Multi-site detection

```php
$config = app('config');
if ($config->isMultisite()) {
    // Environment directory was detected
}
```

## Testing

```bash
cd packages/microweber-config
composer install
vendor/bin/phpunit
```

## License

MIT