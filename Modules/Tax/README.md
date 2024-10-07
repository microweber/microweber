# Tax



## Run module migrations

```sh
php artisan module:migrate Tax
```



## Publish module assets

```sh
php artisan module:publish Tax
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/tax/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/tax/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/tax/img/icon.svg') }}
 ```

### module config values
```php
config('modules.tax.name')
```



### Module views

Extend master layout

```php
@extends('modules.tax::layouts.master')
```

Use Module view

```php
view('modules.tax::index')
```
