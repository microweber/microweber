# Captcha



## Run module migrations

```sh
php artisan module:migrate Captcha
```



## Publish module assets

```sh
php artisan module:publish Captcha
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/captcha/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/captcha/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/captcha/img/icon.svg') }}
 ```

### module config values
```php
config('modules.captcha.name')
```



### Module views

Extend master layout

```php
@extends('modules.captcha::layouts.master')
```

Use Module view

```php
view('modules.captcha::index')
```
