# Order



## Run module migrations

```sh
php artisan module:migrate Order
```



## Publish module assets

```sh
php artisan module:publish Order
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/order/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/order/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/order/img/icon.svg') }}
 ```

### module config values
```php
config('modules.order.name')
```



### Module views

Extend master layout

```php
@extends('modules.order::layouts.master')
```

Use Module view

```php
view('modules.order::index')
```
