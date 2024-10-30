# FacebookLike



## Run module migrations

```sh
php artisan module:migrate FacebookLike
```



## Publish module assets

```sh
php artisan module:publish FacebookLike
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/facebook_like/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/facebook_like/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/facebook_like/img/icon.svg') }}
 ```

### module config values
```php
config('modules.facebook_like.name')
```



### Module views

Extend master layout

```php
@extends('modules.facebook_like::layouts.master')
```

Use Module view

```php
view('modules.facebook_like::index')
```
