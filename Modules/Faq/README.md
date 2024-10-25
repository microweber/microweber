# Faq



## Run module migrations

```sh
php artisan module:migrate Faq
```



## Publish module assets

```sh
php artisan module:publish Faq
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/faq/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/faq/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/faq/img/icon.svg') }}
 ```

### module config values
```php
config('modules.faq.name')
```



### Module views

Extend master layout

```php
@extends('modules.faq::layouts.master')
```

Use Module view

```php
view('modules.faq::index')
```
# FAQ Module

This module provides FAQ functionality for the application.

## Installation

1. Copy the module to the `Modules/Faq` directory.
2. Register the service provider in your Laravel application.

## Usage

- Use the `Faq` class to interact with the FAQ module.
- Use the `Microweber\FaqModule` class for Microweber-specific functionality.
# FAQ Module

This module provides FAQ functionality for the application. It includes settings and features to manage frequently asked questions.

## Installation

1. Add the module to your Laravel application.
2. Register the service provider in your `config/app.php`.

## Usage

- Configure the module settings in `FaqModuleSettings`.
- Use `FaqModule` to manage FAQ operations.
# FAQ Module

This module provides FAQ functionality for the application.

## Installation

1. Copy the module to the `Modules/Faq` directory.
2. Register the service provider in `config/app.php`.

## Usage

- Configure the module settings in `FaqModuleSettings.php`.
- Use the module in your application as needed.
# FAQ Module

This module provides FAQ functionality for the application. It includes settings and logic for managing FAQs.

## Installation

1. Add the module to your Laravel application.
2. Register the service provider in your `config/app.php`.

## Usage

- Use the `FaqModuleSettings` for configuration.
- Use the `FaqModule` for handling FAQ logic.
