# Shop



## Run module migrations

```sh
php artisan module:migrate Shop
```



## Publish module assets

```sh
php artisan module:publish Shop
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/shop/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/shop/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/shop/img/icon.svg') }}
 ```

### module config values
```php
config('modules.shop.name')
```



### Module views

Extend master layout

```php
@extends('modules.shop::layouts.master')
```

Use Module view

```php
view('modules.shop::example')
```
