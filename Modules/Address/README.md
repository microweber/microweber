# Address



## Run module migrations

```sh
php artisan module:migrate Address
```



## Publish module assets

```sh
php artisan module:publish Address
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/address/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/address/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/address/img/icon.svg') }}
 ```

### module config values
```php
config('modules.address.name')
```



### Module views

Extend master layout

```php
@extends('modules.address::layouts.master')
```

Use Module view

```php
view('modules.address::example')
```
