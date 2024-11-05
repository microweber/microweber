# SocialLinks



## Run module migrations

```sh
php artisan module:migrate SocialLinks
```



## Publish module assets

```sh
php artisan module:publish SocialLinks
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/sociallinks/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/sociallinks/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/sociallinks/img/icon.svg') }}
 ```

### module config values
```php
config('modules.sociallinks.name')
```



### Module views

Extend master layout

```php
@extends('modules.sociallinks::layouts.master')
```

Use Module view

```php
view('modules.sociallinks::index')
```
