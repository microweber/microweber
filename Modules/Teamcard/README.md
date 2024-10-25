# Teamcard



## Run module migrations

```sh
php artisan module:migrate Teamcard
```



## Publish module assets

```sh
php artisan module:publish Teamcard
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/teamcard/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/teamcard/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/teamcard/img/icon.svg') }}
 ```

### module config values
```php
config('modules.teamcard.name')
```



### Module views

Extend master layout

```php
@extends('modules.teamcard::layouts.master')
```

Use Module view

```php
view('modules.teamcard::index')
```
