# Microweber Console

Artisan console commands for installing, updating, resetting and maintaining a Microweber CMS instance.

This package is a command library — it ships the command classes but does **not** register them itself. In the Microweber monorepo they are wired up (CLI only) by `MicroweberPackages\App\Providers\AppServiceProvider`, which calls `$this->commands(...)` for each command. In a standalone app, register the ones you need from your own service provider.

## Installation

```bash
composer require microweber-packages/microweber-console
```

## Commands

| Signature | Class | Description |
| --- | --- | --- |
| `microweber:update {--branch=} {--confirm=}` | `UpdateCommand` | Update the Microweber installation |
| `microweber:module {--module=} {--module_action=}` | `ModuleCommand` | Install or uninstall a module |
| `microweber:reset {--only-content=}` | `ResetCommand` | Reset Microweber (optionally content only) |
| `microweber:reload-database` | `ReloadDatabaseCommand` | Reload the Microweber database |
| `microweber:package-install {package}` | `PackageInstallCommand` | Install a package from the Marketplace |
| `microweber:license-add {key}` | `AddLicenseKeyCommand` | Add a license key to Microweber |
| `microweber:vendor-assets-symlink` | `VendorAssetsSymlinkCommand` | Symlink system assets to their vendor locations |
| `make:dusk:color-palette-test {slug}` | `MakeDuskColorPaletteTestCommand` | Scaffold a per-palette Dusk test for a color-pack slug |

## Usage

Once the commands are registered, run them with `php artisan`:

```bash
# Update to the latest version on a given branch, without the confirmation prompt
php artisan microweber:update --branch=master --confirm=yes

# Install a module
php artisan microweber:module --module=shop --module_action=install

# Install a Marketplace package by name
php artisan microweber:package-install my-vendor/my-package

# Add a license key
php artisan microweber:license-add XXXX-XXXX-XXXX-XXXX

# Reset only the content (keep settings/users)
php artisan microweber:reset --only-content=1

# Scaffold a Dusk color-palette test
php artisan make:dusk:color-palette-test blueberry-night
```

## Registering the commands yourself

In a standalone app, register from a service provider's `boot()` (CLI only):

```php
use MicroweberPackages\Console\Commands\UpdateCommand;
use MicroweberPackages\Console\Commands\ModuleCommand;

public function boot(): void
{
    if ($this->app->runningInConsole()) {
        $this->commands([
            UpdateCommand::class,
            ModuleCommand::class,
            // …
        ]);
    }
}
```

## Key classes

All under `MicroweberPackages\Console\Commands\`: `UpdateCommand`, `ModuleCommand`, `ResetCommand`, `ReloadDatabaseCommand`, `PackageInstallCommand`, `AddLicenseKeyCommand`, `VendorAssetsSymlinkCommand`, `MakeDuskColorPaletteTestCommand`.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
