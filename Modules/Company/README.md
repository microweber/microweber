# Company



## Run module migrations

```sh
php artisan module:migrate Company
```



## Publish module assets

```sh
php artisan module:publish Company
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/company/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/company/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/company/img/icon.svg') }}
 ```

### module config values
```php
config('modules.company.name')
```



### Module views

Extend master layout

```php
@extends('modules.company::layouts.master')
```

Use Module view

```php
view('modules.company::example')
```
