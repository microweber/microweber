# EmailMarketing



## Run module migrations

```sh
php artisan module:migrate EmailMarketing
```



## Publish module assets

```sh
php artisan module:publish EmailMarketing
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/emailmarketing/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/emailmarketing/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/emailmarketing/img/icon.svg') }}
 ```

### module config values
```php
config('modules.emailmarketing.name')
```



### Module views

Extend master layout

```php
@extends('modules.emailmarketing::layouts.master')
```

Use Module view

```php
view('modules.emailmarketing::index')
```
