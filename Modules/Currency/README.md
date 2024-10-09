# Currency



## Run module migrations

```sh
php artisan module:migrate Currency
```



## Publish module assets

```sh
php artisan module:publish Currency
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/currency/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/currency/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/currency/img/icon.svg') }}
 ```

### module config values
```php
config('modules.currency.name')
```



### Module views

Extend master layout

```php
@extends('modules.currency::layouts.master')
```

Use Module view

```php
view('modules.currency::index')
```
