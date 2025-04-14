# Register



## Run module migrations

```sh
php artisan module:migrate Register
```



## Publish module assets

```sh
php artisan module:publish Register
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/register/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/register/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/register/img/icon.svg') }}
 ```

### module config values
```php
config('modules.register.name')
```



### Module views

Extend master layout

```php
@extends('modules.register::layouts.master')
```

Use Module view

```php
view('modules.register::example')
```
