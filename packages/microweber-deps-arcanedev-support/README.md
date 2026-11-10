# Deps Arcanedev Support

Vendored fork of **[arcanedev/support](https://github.com/ARCANEDEV/Support)** — Arcanedev's Laravel package-development toolkit (base service providers, package-aware migrations/seeders/commands, route registrar, validation rule base) — patched for Laravel 13 compatibility. This package `replace`s `arcanedev/support` in Composer so the upstream library resolves to this fork across the Microweber monorepo.

> This is a vendored dependency. It keeps the upstream `Arcanedev\Support\*` namespace and API unchanged — there is **no Microweber-specific public API or facade**. It ships a global `helpers.php` (autoloaded via Composer `files`) and the base classes other packages extend.

Part of the [Microweber](https://microweber.com) CMS.

## Installation

```bash
composer require microweber-packages/microweber-deps-arcanedev-support
```

`"replace": { "arcanedev/support": "*" }` — once installed, anything depending on `arcanedev/support` uses this fork.

## What it provides

- **Base service providers** — `Arcanedev\Support\Providers\ServiceProvider`, `PackageServiceProvider`, `RouteServiceProvider`, `CommandServiceProvider`, `EventServiceProvider`, `AuthorizationServiceProvider`, `ViewComposerServiceProvider`, plus the `Concerns\*` traits (`HasConfig`, `HasViews`, `HasMigrations`, `HasTranslations`, `HasAssets`, `HasFactories`, `InteractsWithApplication`).
- **Database base classes** — `Arcanedev\Support\Database\Migration`, `Seeder`, `PrefixedModel`.
- **Routing** — `Arcanedev\Support\Routing\RouteRegistrar`.
- **Validation / Console** — `Arcanedev\Support\Validation\Rule`, `Arcanedev\Support\Console\Command`.
- **Global helpers** (autoloaded): `laravel_version($version = null)` and `route_is($routes)`.

## Usage

Helpers are available globally once the package is installed:

```php
// Get the running Laravel version, or check a prefix match
$version = laravel_version();          // "13.x.x"
$isEleven = laravel_version('11');     // bool

// Is the current route one of these (supports wildcards)?
if (route_is(['admin.*', 'dashboard'])) {
    // ...
}
```

Extend the base provider the upstream way:

```php
use Arcanedev\Support\Providers\PackageServiceProvider;

class MyPackageServiceProvider extends PackageServiceProvider
{
    // register config/views/migrations via the Concerns\* helpers
}
```

## Key classes

- Providers: `Arcanedev\Support\Providers\PackageServiceProvider` (and siblings)
- Database: `Arcanedev\Support\Database\Migration`, `Seeder`, `PrefixedModel`
- Routing: `Arcanedev\Support\Routing\RouteRegistrar`
- Helpers: `laravel_version()`, `route_is()` (`helpers.php`)

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
