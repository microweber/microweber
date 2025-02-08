# Ai

## Run module migrations

```sh
php artisan module:migrate Ai
```

## Publish module assets

```sh
php artisan module:publish Ai
```

### module config values

```php
config('modules.ai.name')
```

### Module views

Extend master layout

```php
@extends('modules.ai::layouts.master')
```

Use Module view

```php
view('modules.ai::example')
```
