# Category



## Run module migrations

```sh
php artisan module:migrate Category
```



## Publish module assets

```sh
php artisan module:publish Category
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/category/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/category/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/category/img/icon.svg') }}
 ```

### module config values
```php
config('modules.category.name')
```



### Module views

Extend master layout

```php
@extends('modules.category::layouts.master')
```

Use Module view

```php
view('modules.category::index')
```
