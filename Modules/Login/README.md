# Login



## Run module migrations

```sh
php artisan module:migrate Login
```



## Publish module assets

```sh
php artisan module:publish Login
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/login/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/login/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/login/img/icon.svg') }}
 ```

### module config values
```php
config('modules.login.name')
```



### Module views

Extend master layout

```php
@extends('modules.login::layouts.master')
```

Use Module view

```php
view('modules.login::example')
```
