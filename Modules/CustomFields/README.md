# CustomFields



## Run module migrations

```sh
php artisan module:migrate CustomFields
```



## Publish module assets

```sh
php artisan module:publish CustomFields
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/customfields/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/customfields/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/customfields/img/icon.svg') }}
 ```

### module config values
```php
config('modules.customfields.name')
```



### Module views

Extend master layout

```php
@extends('modules.customfields::layouts.master')
```

Use Module view

```php
view('modules.customfields::index')
```
