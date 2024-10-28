# HighlightCode



## Run module migrations

```sh
php artisan module:migrate HighlightCode
```



## Publish module assets

```sh
php artisan module:publish HighlightCode
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/highlightcode/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/highlightcode/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/highlightcode/img/icon.svg') }}
 ```

### module config values
```php
config('modules.highlightcode.name')
```



### Module views

Extend master layout

```php
@extends('modules.highlightcode::layouts.master')
```

Use Module view

```php
view('modules.highlightcode::index')
```
