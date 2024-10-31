# Embed



## Run module migrations

```sh
php artisan module:migrate Embed
```



## Publish module assets

```sh
php artisan module:publish Embed
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/embed/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/embed/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/embed/img/icon.svg') }}
 ```

### module config values
```php
config('modules.embed.name')
```



### Module views

Extend master layout

```php
@extends('modules.embed::layouts.master')
```

Use Module view

```php
view('modules.embed::index')
```
