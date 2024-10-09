# Shipping



## Run module migrations

```sh
php artisan module:migrate Shipping
```



## Publish module assets

```sh
php artisan module:publish Shipping
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/shipping/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/shipping/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/shipping/img/icon.svg') }}
 ```

### module config values
```php
config('modules.shipping.name')
```



### Module views

Extend master layout

```php
@extends('modules.shipping::layouts.master')
```

Use Module view

```php
view('modules.shipping::index')
```
