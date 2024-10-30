# Marquee



## Run module migrations

```sh
php artisan module:migrate Marquee
```



## Publish module assets

```sh
php artisan module:publish Marquee
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/marquee/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/marquee/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/marquee/img/icon.svg') }}
 ```

### module config values
```php
config('modules.marquee.name')
```



### Module views

Extend master layout

```php
@extends('modules.marquee::layouts.master')
```

Use Module view

```php
view('modules.marquee::index')
```
