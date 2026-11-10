# Laravel Helper Functions

A collection of global PHP helper functions used across Microweber — string manipulation, array operations, URL handling, API binding, and Laravel compatibility shims (including back-compat for helpers Laravel removed in newer versions, e.g. `array_get`, `str_slug`, `starts_with`).

## Installation

```bash
composer require microweber-packages/laravel-helper-functions
```

The functions are loaded globally via Composer's `files` autoload (`src/helpers.php`), so they are available everywhere with no import. `LaravelHelperFunctionsServiceProvider` (a Microweber package provider) registers the package itself.

## What's included

`src/helpers.php` pulls in the function groups under `src/functions/`:

- **`array.php`** — `array_get`, `array_set`, `array_has`, `array_only`, `array_except`, `array_flatten`, `array_pluck`, `array_first`, `array_last`, `array_wrap`, `array_dot`, `array_undot_str`, `array_map_recursive`, `array_unique_recursive`, `array_recursive_diff`, `object_get`, `data_set`, `data_fill`, …
- **`string.php`** — `str_slug`, `str_limit`, `str_random`, `str_contains`, `starts_with`, `ends_with`, `str_finish`, `snake_case`, `camel_case`, `studly_case`, `title_case`, `str_replace_first`, `character_limiter`, `br2nl`, `ascii`, …
- **`url.php`** — `site_url`, `site_hostname`, `url_current`, `url_segment`, `is_ajax`, `is_https`.
- **`api.php`** — `api_expose`, `api_expose_admin`, `api_expose_user`, `api_bind`, `api_bind_admin`, `api_bind_user`.
- **`laravel.php`** — `bcrypt`, `dd`, `dot`, `head`, `last`, `class_basename`, `route_exists`, `collection_to_array`, `is_closure`, …
- **`other.php`** — `lipsum`, `is_cli`, `microtime_float`, `sanitize_path`, `php_can_use_func`, `document_ready`, …

## Usage

All functions are global — just call them:

```php
// Arrays (dot-notation safe accessors)
$name  = array_get($config, 'mail.from.name', 'Default');
array_set($config, 'mail.from.address', 'hi@example.com');
$only  = array_only($row, ['id', 'title']);
$flat  = array_flatten($nested);

// Strings
$slug = str_slug('Hello World: My Page');      // "hello-world-my-page"
$slug = str_slug('Hello World', '_');          // "hello_world"
$short = str_limit($longText, 100, '…');
$token = str_random(16);
if (starts_with($path, 'admin/')) { /* … */ }

// URLs / request
$url     = site_url('shop/products');
$host    = site_hostname();
$current = url_current();
$segment = url_segment(1);                      // nth path segment
if (is_ajax()) { /* … */ }

// Placeholder text
$text = lipsum(120);                            // ~120 chars of lorem ipsum

// Safe object accessor
$email = object_get($user, 'profile.email', null);
```

## Key classes

- `MicroweberPackages\LaravelHelperFunctions\Providers\LaravelHelperFunctionsServiceProvider` — package registration.
- `src/helpers.php` — the global `files` autoload entry that includes every function group.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
