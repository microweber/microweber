# Cloudflare



## Run module migrations

```sh
php artisan module:migrate Cloudflare
```



## Publish module assets

```sh
php artisan module:publish Cloudflare
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/cloudflare/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/cloudflare/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/cloudflare/img/icon.svg') }}
 ```

### module config values
```php
config('modules.cloudflare.name')
```



### Module views

Extend master layout

```php
@extends('modules.cloudflare::layouts.master')
```

Use Module view

```php
view('modules.cloudflare::index')
```
