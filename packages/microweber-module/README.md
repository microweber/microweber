# Microweber Module

The CMS module engine: loads and renders `<module type="…">` tags, resolves
module locations/templates, exposes the admin module manager UI (Livewire +
Filament), and registers the Live-Edit option field components. This is a core,
CMS-coupled package — the primary public surface is a set of global helpers plus
the `module_manager` / `module_admin_manager` container services.

## Features

- **`ModuleManager`** (`module_manager`) — register, discover, locate, and render modules.
- **`ModuleAdminManager`** (`module_admin_manager`) — register module settings components, admin URLs, and Live-Edit settings URLs.
- **Global helpers** — `module()`, `module_info()`, `is_module()`, `module_url()`, `locate_module()`, …
- **Livewire admin UI** — module list card, uninstall modal, and the `microweber-option::*` Live-Edit option field components (text, toggle, color-picker, media-picker, …).
- **Routes/views/migrations** — API, admin and web routes; the `module` view namespace.
- **Eloquent models** — `Module`, `ModulePreset`.

## Installation

```bash
composer require microweber-packages/microweber-module
```

Register `MicroweberPackages\Module\ModuleServiceProvider` (part of the CMS
provider stack). It singletons `module_manager` and `module_admin_manager`, binds
`module_repository`, loads the API/admin/web routes and migrations, adds the
`module` view namespace, and registers the Livewire components.

## Usage

### Rendering a module

The `module()` helper renders a module to HTML (this is what the `<module …>`
parser ultimately calls):

```php
echo module([
    'type'   => 'shop/products',
    'template' => 'skin-1',
    'limit'  => 12,
]);

// String form is also accepted
echo module('type=posts&limit=5');
```

### The ModuleManager service

```php
$manager = app('module_manager');          // or the deprecated ModuleManager facade

$manager->load('shop/products', ['limit' => 12]); // render with attributes
$manager->exists('shop/products');                 // bool
$manager->is_installed('shop/products');           // bool
$manager->info('shop/products');                   // module config array
$manager->dir('shop/products');                    // filesystem dir
$manager->locate('shop/products');                 // resolved view path
$manager->url('shop/products');                     // public module url
$manager->templates('shop/products');               // available skins/templates
$manager->get(['ui' => true]);                      // list modules by params
```

### Module info / URL helpers

```php
module_info('shop/products');          // config array for the module
is_module('shop/products');            // registered?
is_module_installed('shop/products');  // installed?
module_url('shop/products');           // public URL
module_dir('shop/products');           // source directory
module_admin_url('shop/products');     // resolved admin route for the module
locate_module('shop/products');        // blade view path

// Encode/decode a module name for use in URLs (shop/products <-> shop__products)
module_name_encode('shop/products');
module_name_decode('shop__products');
```

### Admin / Live-Edit settings registration

```php
use MicroweberPackages\Module\Facades\ModuleAdmin; // @deprecated; proxies module_admin_manager

// Register a Livewire settings component for a module type
ModuleAdmin::registerSettingsComponent('shop', 'shop-settings');
ModuleAdmin::getSettingsComponent('shop');

// Live-Edit settings URL
ModuleAdmin::registerLiveEditSettingsUrl('shop', $url);
ModuleAdmin::getLiveEditSettingsUrl('shop');

// Admin URL
ModuleAdmin::registerAdminUrl('shop', $url);
ModuleAdmin::getAdminUrl('shop');
```

### Saved module presets (save as template)

```php
get_saved_modules_as_template($params);
delete_module_as_template($data);
```

## Key classes

- `MicroweberPackages\Module\ModuleManager` — core module engine (`module_manager`).
- `MicroweberPackages\Module\ModuleAdminManager` — admin/settings registry (`module_admin_manager`).
- `MicroweberPackages\Module\Facades\ModuleManager` / `Facades\ModuleAdmin` — facades (both `@deprecated`; prefer the container services / helpers).
- `MicroweberPackages\Module\Concerns\ResolvesModulePaths` — `exists()`/`info()`/`dir()`/`locate()`/`is_installed()`.
- `MicroweberPackages\Module\Repositories\ModuleRepository` — module query repository.
- `MicroweberPackages\Module\Models\Module` / `Models\ModulePreset` — Eloquent models.
- `MicroweberPackages\Module\Http\Livewire\Admin\*` — admin list + `microweber-option::*` field components.
- Global helpers in `src/helpers.php`.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Nikolay Rachkov
- Petko Yovchevski

## License

MIT
