# Video



## Run module migrations

```sh
php artisan module:migrate Video
```



## Publish module assets

```sh
php artisan module:publish Video
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/video/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/video/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/video/img/icon.svg') }}
 ```

### module config values
```php
config('modules.video.name')
```



### Module views

Extend master layout

```php
@extends('modules.video::layouts.master')
```

Use Module view

```php
view('modules.video::index')
```
