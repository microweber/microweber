# Newsletter



## Run module migrations

```sh
php artisan module:migrate Newsletter
```



## Publish module assets

```sh
php artisan module:publish Newsletter
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/newsletter/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/newsletter/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/newsletter/img/icon.svg') }}
 ```

### module config values
```php
config('modules.newsletter.name')
```



### Module views

Extend master layout

```php
@extends('modules.newsletter::layouts.master')
```

Use Module view

```php
view('modules.newsletter::index')
```
