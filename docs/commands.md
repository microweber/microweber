# Commands


# Make Module


```sh
php artisan module:make MyModule
```

This command will create a new module in the `Modules` directory of your application. 

# Publish Module assets

```sh
php artisan module:publish MyModule
```

## Make Module Livewire component

```sh
php artisan module:make-livewire Pages/AboutPage MyModule
```

This command will create a new Livewire component in the `Modules/MyModule/Http/Livewire/Pages` directory of your application.


## Make Module Filament setup

```sh
php artisan module:filament:install MyModule
```

## Make Module Filament Page

```sh
php artisan module:make:filament-page MyModuleSettings MyModule
```

## Publish module assets

```sh
php artisan module:publish MyModule
```

# Run module migrations
```sh
php artisan module:migrate MyModule
```




...

# Make a Template

```sh
php artisan template:make MyTemplate
```

This command will create a new template in the `Templates` directory of your application.  

 
# Publish template assets

```sh
php artisan template:publish MyTemplate
```
# Run template migrations
```sh
php artisan template:migrate MyTemplate
```


## Template Head

In your template's blade layout you should print the head tags and footer with `meta_tags_head()` function and `meta_tags_footer()` function.
```php
{!! meta_tags_head() !!}
{!! meta_tags_footer() !!}

```

Here is example of a template's blade layout `master.blade.php`.

```php

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

 
    {!! meta_tags_head() !!}
    
    {{-- Vite CSS --}}
    {{-- {{ template_vite('templates/mytemplate/dist', 'resources/assets/sass/app.scss') }} --}}

 


</head>

<body>
    @yield('content')

    {{-- Vite JS --}}
    {{-- {{ template_vite('templates/mytemplate/dist', 'resources/assets/js/app.js') }} --}}

    {!! meta_tags_footer() !!}
</body>

```



To add a script to the head  and footer tags you can use the functions `meta_tags_head_add()` and `meta_tags_footer_add()`.

```php
meta_tags_head_add($src);
meta_tags_footer_add($src);
```



## Publish template assets

```sh
php artisan template:publish MyTemplate
```


