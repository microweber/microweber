# Billing



## Run module migrations

```sh
php artisan module:migrate Billing
```



## Publish module assets

```sh
php artisan module:publish Billing
```




### Use module assets in your .blade.php file

Using vite assets
```blade
{{ module_vite('modules/billing/dist', 'resources/assets/js/app.js') }}
{{ module_vite('modules/billing/dist', 'resources/assets/sass/app.scss') }}
```


Using static assets
```blade
{{ asset('modules/billing/img/icon.svg') }}
 ```

### module config values
```php
config('modules.billing.name')
```



### Module views

Extend master layout

```php
@extends('modules.billing::layouts.master')
```

Use Module view

```php
view('modules.billing::example')
```
