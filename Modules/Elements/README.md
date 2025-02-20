# Elements

## Publish module assets

```sh
php artisan module:publish Elements
```

### Use module assets in your .blade.php file

Using vite assets

```blade
{{ module_vite('modules/elements/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/elements/dist', 'resources/assets/sass/app.scss') }}
```

Using static assets

```blade
{{ asset('modules/elements/img/icon.svg') }}
 ```

### module config values

```php
config('modules.elements.name')
```

### Module views

Extend master layout

```php
@extends('modules.elements::layouts.master')
```

Use Module view

```php
view('modules.elements::example')
```
