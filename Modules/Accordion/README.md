# Accordion



## Run module migrations

```sh
php artisan module:migrate Accordion
```



## Publish module assets

```sh
php artisan module:publish Accordion
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/accordion/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/accordion/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/accordion/img/icon.svg') }}
 ```

### module config values
```php
config('modules.accordion.name')
```



### Module views

Extend master layout

```php
@extends('modules.accordion::layouts.master')
```

Use Module view

```php
view('modules.accordion::index')
```
