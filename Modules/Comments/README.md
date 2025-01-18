# Comments



## Run module migrations

```sh
php artisan module:migrate Comments
```



## Publish module assets

```sh
php artisan module:publish Comments
```

 

Using static assets
```blade
{{ asset('modules/comments/img/icon.svg') }}
 ```

### module config values
```php
config('modules.comments.name')
```



### Module views

Extend master layout

```php
@extends('modules.comments::layouts.master')
```

Use Module view

```php
view('modules.comments::index')
```
