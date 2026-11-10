# Microweber App

The central application kernel for Microweber. This package provides the custom Laravel `Application` boot class, registers the entire Microweber package graph, wires the module-tag `Parser`, and ships the global helper functions (`mw()`, `app()`, cache/url/api helpers, …) that the rest of the CMS is built on. It is the glue that turns a Laravel skeleton into a Microweber site.

## What it provides

- **`LaravelApplication`** — the bootstrapped app (`extends Illuminate\Foundation\Application`). Carries `APP_VERSION`, seeds Laravel's default facade aliases (Laravel 11 ships none and MW relies on them in ~600 files), checks/creates missing config files, and uses **versioned bootstrap cache** via `HasVersionedBootstrapCache` (from `microweber-app-bootstrap-cache`).
- **`Application`** — a lightweight accessor over the container: `app()->url`, `app()->template_manager`, `app()->option_manager`, `app()->user_manager`, `app()->format`, `app()->event_manager`, … resolve managers through `__get()`/`make()`.
- **`MicroweberServiceProvider`** — registers dozens of sub-package providers (url, security, user, option, install, admin, template, livewire, module, meta-tags, pagination, …) in the correct order.
- **`Parser`** (`src/Utils/`) — the `<module type="…" template="skin-N" …/>` template/module parser (`->process($layout, $options)`).
- **Managers** — `CacheManager`, `ConfigurationManager`, `Ui`, `PermalinkManager`, `UpdateManager`, `MicroweberLicenseValidator`.
- **HTTP/Console kernels, routes** (`routes/web.php`, `routes/api.php`), the exception `Handler`, and `RequestRoute`.
- **Global helper functions** autoloaded via Composer `files`: `src/functions/api.php`, `src/functions/laravel.php`, `src/functions/common.php` (and more pulled in at runtime: `paths.php`, `events.php`, `mail.php`, `string.php`, `url.php`, `lang.php`, …).

## Installation

```bash
composer require microweber-packages/microweber-app
```

## Usage

### Bootstrapping

The app is booted from `bootstrap/app.php` using this package's `LaravelApplication` in place of Laravel's default:

```php
// bootstrap/app.php
return \MicroweberPackages\App\LaravelApplication::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // …
    ->create();
```

`MicroweberServiceProvider` is then registered as an application provider and brings up the rest of the package graph.

### Global helpers

Because the helpers are loaded through Composer's `files` autoload, they are available everywhere:

```php
$templateManager = mw('template_manager');   // mw($class) === app($class)
$container       = app();                     // Microweber's app() shim
$option          = app()->option_manager;     // resolve a manager via magic accessor

// api / cache helpers
cache_get('key');
cache_save($value, 'key', $tags);
cache_clear();
api_url('module/action');
```

### Module-tag parsing

```php
use MicroweberPackages\App\Utils\Parser;

$parser = new Parser();
$html   = $parser->process($layout, $options);
```

> Note: the parser rewrites **every** `<module …>` occurrence in the string it is given (including inside string literals). Emit module tags from Blade; never feed module markup through the parser as a raw string.

## Key classes

- `MicroweberPackages\App\LaravelApplication` — bootstrapped Laravel app (versioned cache, facade aliases).
- `MicroweberPackages\App\Application` — container accessor for managers.
- `MicroweberPackages\App\Providers\MicroweberServiceProvider` — registers the package graph.
- `MicroweberPackages\App\Utils\Parser` — module/template tag parser.
- `MicroweberPackages\App\Managers\{CacheManager, ConfigurationManager, Ui, PermalinkManager, UpdateManager}` — core managers.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Petko Yovchevski
- Alexander Raikov

## License

MIT
