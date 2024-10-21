# Audio



## Run module migrations

```sh
php artisan module:migrate Audio
```



## Publish module assets

```sh
php artisan module:publish Audio
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/audio/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/audio/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/audio/img/icon.svg') }}
 ```

### module config values
```php
config('modules.audio.name')
```



### Module views

Extend master layout

```php
@extends('modules.audio::layouts.master')
```

Use Module view

```php
view('modules.audio::index')
```
