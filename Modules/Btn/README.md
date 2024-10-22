# Btn



## Run module migrations

```sh
php artisan module:migrate Btn
```



## Publish module assets

```sh
php artisan module:publish Btn
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/btn/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/btn/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/btn/img/icon.svg') }}
 ```

### module config values
```php
config('modules.btn.name')
```



### Module views

Extend master layout

```php
@extends('modules.btn::layouts.master')
```

Use Module view

```php
view('modules.btn::index')
```
