# Microweber Option

Key/value settings store for Laravel & Microweber. Options are grouped
(`option_group`), optionally scoped to a `module`, translatable per language, and
cached. This is the package behind the global `get_option()` / `save_option()`
helpers used all over the CMS.

## Features

- **Grouped key/value options** persisted in the `options` table (migration included).
- **Global helpers:** `get_option()`, `save_option()`, `delete_option()`, `get_options()`.
- **Module-scoped options:** `get_module_option()`, `save_module_option()`, `module_option()`.
- **`OptionManager` service** (singleton `option_manager`) with caching and group helpers.
- **`Option` / `ModuleOption` Eloquent models** and an `OptionRepository`.
- **Lifecycle events** (`OptionWasCreated`, `OptionWasUpdated`, `OptionWasDeleted`, …).
- **Multilanguage** integration via the `TranslateOption` translate provider.
- **Save API endpoint** + `OptionCommand` artisan command.

## Installation

```bash
composer require microweber-packages/microweber-option
```

Register `MicroweberPackages\Option\Providers\OptionServiceProvider` (it is part of
the CMS provider stack). It binds `option_manager`, `option`, `global_options` and
`option_repository`, loads the migrations and the save-option API route, and
registers the `Option` facade alias.

## Usage

### Global helpers (the common path)

```php
// Read a value from a group
$title = get_option('website_title', 'website');

// Read the full DB row (not just the value)
$row = get_option('website_title', 'website', true);

// Save a value: save_option($key, $value, $group)
save_option('website_title', 'My Site', 'website');

// Save for a specific language
save_option('website_title', 'Моят сайт', 'website', 'bg_BG');

// Save by array
save_option([
    'option_key'   => 'website_title',
    'option_value' => 'My Site',
    'option_group' => 'website',
]);

// Delete
delete_option('website_title', 'website');

// Fetch many rows by query params
$rows = get_options(['option_group' => 'website']);
```

### Module-scoped options

```php
// get_module_option($key, $group, $returnFull = false, $module = false)
$layout = get_module_option('layout', 'shop');

// module_option($group, $key, $default) — returns $default when null
$perPage = module_option('shop', 'items_per_page', 12);

// save_module_option($key, $value, $group, $module, $lang = null)
save_module_option('layout', 'grid', 'shop');
```

### The OptionManager service / facade

```php
use MicroweberPackages\Option\Facades\OptionManager;

// get($optionKey, $optionGroup, $returnFull, $orderBy, $module)
$value = OptionManager::get('website_title', 'website');

// save(array $data)
OptionManager::save([
    'option_key'   => 'items_per_page',
    'option_value' => 15,
    'option_group' => 'website',
]);

OptionManager::delete('website_title', 'website');     // delete($key, $group, $moduleId)
OptionManager::get_groups();                            // list option groups
OptionManager::get_items_per_page('website');           // cached per-group page size
OptionManager::override('website', 'maintenance', 1);   // runtime-only override

// Or resolve from the container
$manager = app('option_manager');
```

### The Option model

The `Option` facade proxies the Eloquent model:

```php
use MicroweberPackages\Option\Models\Option;

Option::where('option_group', 'website')->get();
```

### Events

Hook into option changes:

```php
use MicroweberPackages\Option\Events\OptionWasUpdated;

Event::listen(OptionWasUpdated::class, function (OptionWasUpdated $event) {
    // $event carries the affected option
});
```

Available: `OptionIsCreating`, `OptionWasCreated`, `OptionIsUpdating`,
`OptionWasUpdated`, `OptionWasDeleted`, `OptionWasRetrieved`.

## Key classes

- `MicroweberPackages\Option\OptionManager` — main service (bound as `option_manager`).
- `MicroweberPackages\Option\Facades\OptionManager` — facade for the manager.
- `MicroweberPackages\Option\Facades\Option` — facade for the `Option` model.
- `MicroweberPackages\Option\Models\Option` / `Models\ModuleOption` — Eloquent models.
- `MicroweberPackages\Option\Repositories\OptionRepository` — query repository (`option_repository`).
- `MicroweberPackages\Option\GlobalOptions` — eager-loaded option cache (`global_options`).
- `MicroweberPackages\Option\TranslateTables\TranslateOption` — multilanguage provider.
- Global helpers in `src/helpers/options.php`.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Petko Yovchevski

## License

MIT
