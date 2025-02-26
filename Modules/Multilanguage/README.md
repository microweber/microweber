# Multilanguage



## Run module migrations

```sh
php artisan module:migrate Multilanguage
```



## Publish module assets

```sh
php artisan module:publish Multilanguage
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/multilanguage/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/multilanguage/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/multilanguage/img/icon.svg') }}
 ```

### module config values
```php
config('modules.multilanguage.name')
```



### Module views

Extend master layout

```php
@extends('modules.multilanguage::layouts.master')
```

Use Module view

```php
view('modules.multilanguage::example')
```
