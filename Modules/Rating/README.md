# Rating



## Run module migrations

```sh
php artisan module:migrate Rating
```



## Publish module assets

```sh
php artisan module:publish Rating
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/rating/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/rating/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/rating/img/icon.svg') }}
 ```

### module config values
```php
config('modules.rating.name')
```



### Module views

Extend master layout

```php
@extends('modules.rating::layouts.master')
```

Use Module view

```php
view('modules.rating::example')
```
