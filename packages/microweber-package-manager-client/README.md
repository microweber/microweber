# microweber-packages/package-manager-client

Standalone Composer package manager client for **Microweber CMS** and any **Laravel** application.

## Features

- Search packages from one or more Satis / Composer `packages.json` endpoints
- Install and update packages (download zip → extract → place in install dir)
- **Install-dir detection** for:
  - `microweber-module` → `Modules/{TargetDir}/`
  - `microweber-template` → `Templates/{TargetDir}/`
  - generic **nwidart** Laravel modules (`laravel-module` / `nwidart-module` / `module.json`) → `Modules/{StudlyName}/`
- License headers for private marketplace servers
- Ed25519 package signature verification (optional supply-chain check)
- Works without Microweber helpers — fully configurable paths

## Install

```bash
composer require microweber-packages/package-manager-client
```

Path repository (monorepo):

```json
{
  "type": "path",
  "url": "packages/microweber-package-manager-client",
  "options": { "symlink": true }
}
```

## Standalone Laravel usage

```php
use MicroweberPackages\PackageManagerClient\PackageManagerClientService;

$client = app(PackageManagerClient::class);

// Point at your Satis / packages.json URL
$client->setPackageServers(['http://127.0.0.1:9999/packages.json']);

// Search all packages
$all = $client->search();

// Search one package
$pkg = $client->search([
    'require_name' => 'microweber-modules/sample-hello',
    'require_version' => '1.0.0',
]);

// Install (two-step confirm flow, same as Microweber marketplace)
$step1 = $client->requestInstall([
    'require_name' => 'microweber-modules/sample-hello',
    'require_version' => 'latest',
]);

if (isset($step1['form_data_module_params']['confirm_key'])) {
    $step2 = $client->requestInstall($step1['form_data_module_params']);
    // $step2['success'] on success
}
```

### Config

Publish:

```bash
php artisan vendor:publish --tag=package-manager-client-config
```

Key options (`config/package-manager-client.php`):

| Key | Default | Purpose |
|-----|---------|---------|
| `package_servers` | modules.microweberapi.com | Satis endpoints |
| `modules_path` | `Modules` | Module install base |
| `templates_path` | `Templates` | Template install base |
| `download_path` | `storage/cache/composer-download` | Temp download dir |

## Install-dir detection

```php
use MicroweberPackages\PackageManagerClient\InstallDirDetector;

$detector = app(InstallDirDetector::class);
$target = $detector->detect([
    'name' => 'acme/blog',
    'type' => 'laravel-module',
    'extra' => ['laravel-module' => ['name' => 'Blog']],
]);

// $target->type === 'laravel-module'
// $target->directory === 'Blog'
// $target->absolutePath === base_path('Modules/Blog')
```

## Microweber CMS

The CMS marketplace, installer, and update manager resolve
`PackageManagerClient` (and legacy class aliases) via the service container.
Register the provider if auto-discovery is disabled:

```php
MicroweberPackages\PackageManagerClient\PackageManagerClientServiceProvider::class,
```

## Tests

```bash
cd packages/microweber-package-manager-client
composer test
# or from monorepo root:
./vendor/bin/phpunit packages/microweber-package-manager-client/tests
```

Satis-style fixtures live under `tests/Fixtures/sample-packages/` and are
served by an in-process temp HTTP server during install tests.

## PHPStan

```bash
composer analyse -- packages/microweber-package-manager-client/src
# or package-local:
cd packages/microweber-package-manager-client && composer analyse
```

Level **9** (max).
