# Microweber System Licenses

Standalone Laravel package for managing software licenses. Provides a `SystemLicensesManager` (reachable through the `SystemLicenses` facade) that handles license CRUD, validation, consumption and caching against the `system_licenses` table.

## Features

- `SystemLicenses` facade over `SystemLicensesManager` — the greppable public API.
- License CRUD: save, delete, list, consume.
- Remote validation via a pluggable `LicenseValidatorInterface` (defaults to `NullLicenseValidator`).
- Cached "active licenses" lookup with `hasLicense()` / `refreshActiveLicenses()`.
- File-based license fallback (`system_licenses.php`) for white-label / offline keys.
- `have_license()` global helper.

## Installation

```bash
composer require microweber-packages/microweber-system-licenses
```

The service provider is auto-discovered. It loads the package migrations and binds `SystemLicensesManager` + a default `LicenseValidatorInterface` (`NullLicenseValidator`) as singletons. Run migrations to create the `system_licenses` table:

```bash
php artisan migrate
```

## Usage

### Via the `SystemLicenses` facade

```php
use MicroweberPackages\SystemLicenses\Facades\SystemLicenses;

// Check for any active license, or a specific module license
$hasAny        = SystemLicenses::hasLicense();
$hasWhiteLabel = SystemLicenses::hasLicense('modules/white_label');

// List licenses
$all    = SystemLicenses::getAllLicenses();
$active = SystemLicenses::getActiveLicenses();

// Save a new license (validates + persists); returns a result array
$result = SystemLicenses::saveLicense(['local_key' => 'YOUR-KEY']);

// Validate all licenses against the remote server (pass params to scope)
$result = SystemLicenses::validateLicenses();

// Consume / delete
SystemLicenses::consumeLicense($id);
SystemLicenses::deleteLicense($id);

// Refresh the cached active-license set
SystemLicenses::refreshActiveLicenses();
```

### Global helper

```php
if (have_license('modules/white_label')) {
    // licensed features
}
```

`have_license()` is autoloaded via `src/helpers.php` and proxies to `SystemLicenses::hasLicense()`.

### File-based licenses

For keys shipped on disk (e.g. white-label builds) the manager reads/writes a `system_licenses.php` file:

```php
SystemLicenses::getFileLicenses();
SystemLicenses::saveFileLicense($code, 'modules/white_label');
SystemLicenses::validateFileLicense($code, 'modules/white_label');
SystemLicenses::getLicenseFilePath();
SystemLicenses::truncateFileLicenses();
```

## Custom validator

The default `NullLicenseValidator` accepts nothing remote. Provide your own by binding the interface **before** the manager is resolved:

```php
use MicroweberPackages\SystemLicenses\Contracts\LicenseValidatorInterface;

$this->app->singleton(LicenseValidatorInterface::class, MyValidator::class);
```

Your validator must implement `LicenseValidatorInterface`; it is injected into `SystemLicensesManager`'s constructor.

## Key classes

- `MicroweberPackages\SystemLicenses\Facades\SystemLicenses`
- `MicroweberPackages\SystemLicenses\SystemLicensesManager`
- `MicroweberPackages\SystemLicenses\Contracts\LicenseValidatorInterface`
- `MicroweberPackages\SystemLicenses\Validators\NullLicenseValidator`
- `MicroweberPackages\SystemLicenses\Models\SystemLicense`
- `MicroweberPackages\SystemLicenses\SystemLicensesServiceProvider`

## Testing

```bash
composer test
```

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
