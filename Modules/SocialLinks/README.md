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
{{ module_vite('modules/social_links/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/social_links/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/social_links/img/icon.svg') }}
 ```

### module config values
```php
config('modules.social_links.name')
```



### Module views

Extend master layout

```php
@extends('modules.social_links::layouts.master')
```

Use Module view

```php
view('modules.social_links::index')
```
