# Microweber System Licenses

Standalone Laravel package for managing software licenses. Provides a `SystemLicensesManager` accessible via `app()->system_licenses_manager` that handles license CRUD, validation, and caching.

## Installation

```bash
composer require microweber-packages/system-licenses
```

The package auto-discovers its service provider. Run migrations:

```bash
php artisan migrate
```

## Usage

```php
// Check for any active license
$hasLicense = app()->system_licenses_manager->hasLicense();

// Check for a specific module license
$hasWhiteLabel = app()->system_licenses_manager->hasLicense('modules/white_label');

// Save a new license
$result = app()->system_licenses_manager->saveLicense(['local_key' => 'YOUR-KEY']);

// Validate all licenses against remote server
$result = app()->system_licenses_manager->validateLicenses();

// Delete a license
$result = app()->system_licenses_manager->deleteLicense($id);

// Helper function
if (have_license('modules/white_label')) {
    // Licensed features
}
```

## Custom Validator

Implement `LicenseValidatorInterface` and bind it in your service provider:

```php
$this->app->singleton(LicenseValidatorInterface::class, MyValidator::class);
```

## Testing

```bash
composer test
```