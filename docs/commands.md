# Module Commands

## Module Creation & Setup

### Create New Module
```bash
php artisan module:make ModuleName
```
Creates a new module with standard directory structure.

### Install Filament Support
```bash
php artisan module:filament:install ModuleName
```
Sets up Filament integration in a module (creates plugin class, resources dir, etc.)

### Publish Module Assets
```bash
php artisan module:publish ModuleName
```
Publishes module assets to public directory.

## Filament-Specific Commands

### Create Filament Resource
```bash
php artisan module:make:filament-resource ResourceName ModuleName
```
Creates a new Filament resource in specified module with:
- Model
- Migration
- Resource class
- Pages (List, Create, Edit)

### Create Filament Page
```bash
php artisan module:make:filament-page PageName ModuleName
```
Creates a new Filament page in specified module.

### Create Filament Widget
```bash
php artisan module:make:filament-widget WidgetName ModuleName
```
Creates a new Filament widget in specified module.

### Create Filament Cluster
```bash
php artisan module:make:filament-cluster ClusterName ModuleName
```
Creates a new Filament cluster in specified module.

### Create Filament Plugin
```bash
php artisan module:make:filament-plugin PluginName ModuleName
```
Creates a new Filament plugin in specified module.

### Create Filament Theme
```bash
php artisan module:make:filament-theme ThemeName ModuleName
```
Creates a new Filament theme in specified module.

## Development Commands

### Run Module Migrations
```bash
php artisan module:migrate ModuleName
```
Runs all migrations for specified module.

### Create Livewire Component
```bash
php artisan module:make-livewire ComponentPath ModuleName
```
Creates Livewire component in module (e.g. `Pages/AboutPage`)

### Generate Module Documentation
```bash
php artisan module:make:docs ModuleName
```
Generates documentation scaffold for module.

## Maintenance Commands

### Enable/Disable Module
```bash
php artisan module:enable ModuleName
php artisan module:disable ModuleName
```
Toggles module activation status.

### List All Modules
```bash
php artisan module:list
```
Displays all modules with their status.




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


