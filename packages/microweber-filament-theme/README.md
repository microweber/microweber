# Microweber CMS - Filament admin panel theme

[![Latest Version on Packagist](https://img.shields.io/packagist/v/microweber-packages/microweber-filament-theme.svg?style=flat-square)](https://packagist.org/packages/microweber-packages/microweber-filament-theme)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/microweber-packages/microweber-filament-theme/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/microweber-packages/microweber-filament-theme/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/microweber-packages/microweber-filament-theme/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/microweber-packages/microweber-filament-theme/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/microweber-packages/microweber-filament-theme.svg?style=flat-square)](https://packagist.org/packages/microweber-packages/microweber-filament-theme)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require microweber-packages/microweber-filament-theme
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="microweber-filament-theme-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="microweber-filament-theme-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="microweber-filament-theme-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$microweberFilamentTheme = new MicroweberPackages\MicroweberFilamentTheme();
echo $microweberFilamentTheme->echoPhrase('Hello, MicroweberPackages!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Bozhidar](https://github.com/bobimicroweber)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
