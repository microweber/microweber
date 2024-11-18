# Menu



## Run module migrations

```sh
php artisan module:migrate Menu
```



## Publish module assets

```sh
php artisan module:publish Menu
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/menu/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/menu/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/menu/img/icon.svg') }}
 ```

### module config values
```php
config('modules.menu.name')
```



### Module views

Extend master layout

```php
@extends('modules.menu::layouts.master')
```

Use Module view

```php
view('modules.menu::index')
```
