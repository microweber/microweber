# Customer



## Run module migrations

```sh
php artisan module:migrate Customer
```



## Publish module assets

```sh
php artisan module:publish Customer
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/customer/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/customer/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/customer/img/icon.svg') }}
 ```

### module config values
```php
config('modules.customer.name')
```



### Module views

Extend master layout

```php
@extends('modules.customer::layouts.master')
```

Use Module view

```php
view('modules.customer::index')
```
