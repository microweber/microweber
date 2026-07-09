# Microweber App Bootstrap Cache

Versioned bootstrap cache paths for Laravel applications.

## Problem

When you upgrade Laravel or your application, the cached bootstrap files
(`services.php`, `packages.php`, `config.php`, etc.) may become stale and
cause errors. This package solves that by including the Laravel version (and
optionally your application version) in the cache file names.

## Installation

```bash
composer require microweber-packages/app-bootstrap-cache
```

## Usage

Use the `HasVersionedBootstrapCache` trait in your `Application` class:

```php
<?php

namespace App;

use Illuminate\Foundation\Application;
use MicroweberPackages\AppBootstrapCache\HasVersionedBootstrapCache;

class MyApplication extends Application
{
    use HasVersionedBootstrapCache;

    // Optional: define a version constant for your app
    const APP_VERSION = '2.0.0';
}
```

The trait overrides `getCachedServicesPath()`, `getCachedPackagesPath()`,
`getCachedConfigPath()`, `getCachedRoutesPath()`, and `getCachedEventsPath()`
to produce versioned file names like:

```
bootstrap/cache/cache_11_54_0_2_0_0_services.php
bootstrap/cache/cache_11_54_0_2_0_0_config.php
```

## Without an Application Subclass

If you don't have a custom Application class, you can use the
`VersionedBootstrapCacheHelper` to compute versioned paths directly:

```php
use MicroweberPackages\AppBootstrapCache\VersionedBootstrapCacheHelper;

$helper = new VersionedBootstrapCacheHelper('11.54.0', '2.0.0');
$helper->getVersionPrefix();  // "11_54_0_2_0_0"
$helper->getCacheFileName('services');  // "cache_11_54_0_2_0_0_services.php"
```

## License

MIT