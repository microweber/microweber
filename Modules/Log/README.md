# Log



## Run module migrations

```sh
php artisan module:migrate Log
```



## Publish module assets

```sh
php artisan module:publish Log
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/log/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/log/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/log/img/icon.svg') }}
 ```

### module config values
```php
config('modules.log.name')
```



### Module views

Extend master layout

```php
@extends('modules.log::layouts.master')
```

Use Module view

```php
view('modules.log::example')
```
