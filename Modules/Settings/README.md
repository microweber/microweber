# Settings



## Run module migrations

```sh
php artisan module:migrate Settings
```



## Publish module assets

```sh
php artisan module:publish Settings
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/settings/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/settings/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/settings/img/icon.svg') }}
 ```

### module config values
```php
config('modules.settings.name')
```



### Module views

Extend master layout

```php
@extends('modules.settings::layouts.master')
```

Use Module view

```php
view('modules.settings::index')
```
