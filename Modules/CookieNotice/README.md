# Cookie Notice Module for Microweber

A comprehensive cookie consent and notice management module for Microweber CMS, built with Filament v3, Laravel 11, and Livewire v3.

## Features

- Modern, customizable cookie notice popup
- Filament-based admin interface

## Installation

```bash
composer require microweber-modules/cookie-notice
php artisan module:migrate CookieNotice
```

## Configuration

Navigate to Admin Panel > Settings > Cookie Notice to configure:

- Enable/disable cookie notice
- Set cookie policy URL
- Customize appearance (colors, position)

## Usage

The cookie notice will automatically appear to users who haven't set their preferences. Users can:

- Accept all cookies
- Customize cookie preferences
- Access cookie settings via a floating button
- Review cookie policy

## Development

### Build Assets

```bash
cd Modules/CookieNotice
npm install
npm run build
```

### Run Tests

```bash
php artisan test Modules/CookieNotice/Tests
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
