# ContactForm



## Run module migrations

```sh
php artisan module:migrate ContactForm
```



## Publish module assets

```sh
php artisan module:publish ContactForm
```


Using static assets
```blade
{{ asset('modules/contact_form/img/icon.svg') }}
 ```

### module config values
```php
config('modules.contact_form.name')
```



### Module views

Extend master layout

```php
@extends('modules.contact_form::layouts.master')
```

Use Module view

```php
view('modules.contact_form::index')
```
