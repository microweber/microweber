# Admin



## Run module migrations

```sh
php artisan module:migrate Admin
```



## Publish module assets

```sh
php artisan module:publish Admin
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/admin/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/admin/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/admin/img/icon.svg') }}
 ```

### module config values
```php
config('modules.admin.name')
```



### Module views

Extend master layout

```php
@extends('modules.admin::layouts.master')
```

Use Module view

```php
view('modules.admin::index')
```
