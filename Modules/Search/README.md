# Search Module

A powerful search module for Microweber that allows users to search content across your website.

## Features

- Simple and clean search interface
- Autocomplete search functionality
- Customizable search settings
- Livewire integration for real-time search results
- Responsive design

## Installation

```sh
php artisan module:migrate Search
```

## Publish module assets

```sh
php artisan module:publish Search
```

## Usage

Add the search module to any page or template using the module selector in the Microweber admin panel.

### Module Settings

The search module provides several customization options:

- **Placeholder Text**: Customize the placeholder text in the search field
- **Search in Page**: Select which page to search in (or search in all pages)
- **Enable Autocomplete**: Toggle autocomplete search functionality

### Templates

The module comes with two templates:

1. **Default**: A simple search box with results displayed below
2. **Autocomplete**: A search box with autocomplete functionality that shows results as you type

## Development

### Build Assets

```sh
cd Modules/Search
npm install
npm run dev
```

### Watch for Changes

```sh
npm run watch
```

### Production Build

```sh
npm run prod
```

## Using module assets in your .blade.php file

Using vite assets:
```blade
{{ module_vite('modules/search/dist', 'resources/assets/js/search.js') }}
{{ module_vite('modules/search/dist', 'resources/assets/css/search.css') }}
```

Using static assets:
```blade
{{ asset('modules/search/img/icon.svg') }}
```

## Module config values
```php
config('modules.search.name')
```

## Module views

Extend master layout:
```php
@extends('modules.search::layouts.master')
```

Use Module view:
```php
view('modules.search::example')
```
