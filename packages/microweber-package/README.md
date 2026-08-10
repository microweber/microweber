# microweber-packages/package

Standalone Microweber package loader built on [spatie/laravel-package-tools](https://github.com/spatie/laravel-package-tools).

Use this package as the base for every Microweber library under `packages/*`, and optionally for CMS modules/templates that need Live Edit or Filament registration.

## Features

- **Abstract `MicroweberPackageServiceProvider`** — only extendable; never registered alone
- Spatie lifecycle: `configurePackage()`, `packageRegistered()`, `packageBooted()`
- Optional **`ModulePackage`** fluent API for CMS module integrations
- Soft dependencies: works in a plain Laravel app without Filament or ModuleAdmin
- PHPStan level **max**

## Installation

```bash
composer require microweber-packages/package
```

## Usage in a package

```php
<?php

namespace MicroweberPackages\Example;

use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class ExampleServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('microweber-example')
            ->hasConfigFile('example');
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(ExampleService::class);
    }
}
```

## CMS module registration

```php
public function configureModule(ModulePackage $module): void
{
    $module
        ->type('blog')
        ->hasFilamentPage(BlogSettings::class)
        ->hasLiveEditSettings(BlogSettings::class);
}
```

When FilamentRegistry / ModuleAdmin are not installed, those calls are no-ops.

## Standalone Laravel

1. `composer require microweber-packages/package`
2. Extend `MicroweberPackageServiceProvider` in your package
3. Register your concrete provider in `bootstrap/providers.php` or via package auto-discovery

No CMS services are required.

## Testing

```bash
composer test
composer analyse
```
