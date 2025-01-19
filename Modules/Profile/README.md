# Profile



## Run module migrations

```sh
php artisan module:migrate Profile
```



## Publish module assets

```sh
php artisan module:publish Profile
```





Using static assets
```blade
{{ asset('modules/profile/img/icon.svg') }}
 ```

### module config values
```php
config('modules.profile.name')
```



### Module views

Extend master layout

```php
@extends('modules.profile::layouts.master')
```

Use Module view

```php
view('modules.profile::example')
```
