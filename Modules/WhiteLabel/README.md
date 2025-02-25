# WhiteLabel



## Run module migrations

```sh
php artisan module:migrate WhiteLabel
```



## Publish module assets

```sh
php artisan module:publish WhiteLabel
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/white_label/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/white_label/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/white_label/img/icon.svg') }}
 ```

### module config values
```php
config('modules.white_label.name')
```



### Module views

Extend master layout

```php
@extends('modules.white_label::layouts.master')
```

Use Module view

```php
view('modules.white_label::example')
```
