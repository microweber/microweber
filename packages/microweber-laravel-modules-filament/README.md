# Laravel Modules Filament

Glue between Microweber's module system and the [`coolsam/modules`](https://github.com/coolsam726/filament-modules)
Filament integration. It adapts the Coolsam Filament-Modules plugin so that each `Modules/<Name>/`
module can expose its own Filament resources/pages (optionally grouped into clusters) through
Microweber's `FilamentRegistry`.

This is a **pure-glue package** — it wires other packages together and exposes no public API of its
own. You enable the behaviour through config.

## What it registers

- **`LaravelModulesFilamentServiceProvider`** — extends `Coolsam\Modules\ModulesServiceProvider`,
  merges `config/filament-modules.php`, and registers `ModulesPluginFilament`.
- **`ModulesPluginFilament`** — when `filament-modules.auto-register-plugins` is enabled, discovers
  each enabled module's `app/Filament/*Plugin.php` and registers it via
  `MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry::registerPlugin()`.

It depends on `microweber-filament-registry`, `microweber-laravel-modules` and `microweber-modules`.

## Installation

```bash
composer require microweber-packages/microweber-laravel-modules-filament
```

Register the provider (Microweber does this from its app bootstrap; standalone apps add it manually):

```php
$app->register(MicroweberPackages\LaravelModulesFilament\LaravelModulesFilamentServiceProvider::class);
```

## Configuration

`config/filament-modules.php` (merged under `filament-modules.*`):

| Key | Default | Purpose |
|-----|---------|---------|
| `auto-register-plugins` | `false` | Auto-discover and register each module's Filament `*Plugin.php`. |
| `auto-register-pages` | `false` | Auto-register module Filament pages in the panel. |
| `clusters.enabled` | `true` | Group each module's resources/pages into a cluster. |
| `clusters.use-top-navigation` | `false` | Show the cluster menu in the top navigation. |

## Usage

Place a Filament plugin in a module (`Modules/Blog/app/Filament/BlogPlugin.php`), enable
`filament-modules.auto-register-plugins`, and it is registered automatically on the admin panel
via the `FilamentRegistry`. No direct calls into this package are required.

## Key classes

- `MicroweberPackages\LaravelModulesFilament\LaravelModulesFilamentServiceProvider` — package provider (extends Coolsam's).
- `MicroweberPackages\LaravelModulesFilament\ModulesPluginFilament` — discovers & registers module Filament plugins.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov

## License

MIT
