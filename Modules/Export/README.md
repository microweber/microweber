# Export



## Run module migrations

```sh
php artisan module:migrate Export
```



## Publish module assets

```sh
php artisan module:publish Export
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/export/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/export/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/export/img/icon.svg') }}
 ```

### module config values
```php
config('modules.export.name')
```



### Module views

Extend master layout

```php
@extends('modules.export::layouts.master')
```

Use Module view

```php
view('modules.export::example')
```
