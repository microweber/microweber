# microweber-packages/microweber-config-merge

Package-priority config merging for Laravel service providers.

## Problem

Laravel's built-in `ServiceProvider::mergeConfigFrom()` gives the **application** config priority over package defaults. This means a package cannot ship opinionated defaults that override what the app already has.

This package inverts that behaviour: the **package/module** config values take priority, letting CMS modules and packages ship defaults that "win" unless the system explicitly overrides them at a deeper level.

## Installation

```bash
composer require microweber-packages/microweber-config-merge
```

## Usage

```php
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\ConfigMerge\MergesConfigFromPackage;

class MyServiceProvider extends ServiceProvider
{
    use MergesConfigFromPackage;

    public function register()
    {
        // Package config values override application defaults
        $this->mergeConfigFrom(__DIR__.'/../config/my-package.php', 'my-package');
    }
}
```

## How it works

- **Scalar values**: Package value wins over the application value.
- **Associative arrays**: Recursively merged; package values win at each level.
- **Numeric arrays** and special keys (`middleware`, `register`): Concatenated via `array_merge` to preserve list-append semantics.
- **Cached configuration**: Skipped entirely (respects `CachesConfiguration`).
- **Missing file**: A missing `$path` is a no-op (graceful degradation), not a fatal `require` error.

The trait overrides Laravel's `mergeConfigFrom()`, so existing service-provider code needs no other changes — just `use MergesConfigFromPackage;`.

## Key classes

- Trait: `MicroweberPackages\ConfigMerge\MergesConfigFromPackage`

This package is a single reusable trait — it ships no service provider, facade or config, and registers nothing on its own; a consuming service provider opts in by using the trait.

## License

MIT

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
