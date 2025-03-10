# Spacer



## Run module migrations

```sh
php artisan module:migrate Spacer
```



## Publish module assets

```sh
php artisan module:publish Spacer
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/spacer/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/spacer/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/spacer/img/icon.svg') }}
 ```

### module config values
```php
config('modules.spacer.name')
```



### Module views

Extend master layout

```php
@extends('modules.spacer::layouts.master')
```

Use Module view

```php
view('modules.spacer::example')
```
