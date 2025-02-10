# LayoutContent



## Run module migrations

```sh
php artisan module:migrate LayoutContent
```



## Publish module assets

```sh
php artisan module:publish LayoutContent
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/layout_content/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/layout_content/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/layout_content/img/icon.svg') }}
 ```

### module config values
```php
config('modules.layout_content.name')
```



### Module views

Extend master layout

```php
@extends('modules.layout_content::layouts.master')
```

Use Module view

```php
view('modules.layout_content::example')
```
