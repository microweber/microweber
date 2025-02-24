# Blog



## Run module migrations

```sh
php artisan module:migrate Blog
```



## Publish module assets

```sh
php artisan module:publish Blog
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/blog/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/blog/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/blog/img/icon.svg') }}
 ```

### module config values
```php
config('modules.blog.name')
```



### Module views

Extend master layout

```php
@extends('modules.blog::layouts.master')
```

Use Module view

```php
view('modules.blog::example')
```
