# Laravel Modules

Microweber's customization of [`nwidart/laravel-modules`](https://github.com/nWidart/laravel-modules).
It powers the `Modules/<Name>/` plugin system: module discovery under `base_path('Modules')`,
file **or** database-backed activation, a module-dependency service, a Filament admin resource, and
an abstract base service provider that every Microweber module extends.

This is an **infrastructure / glue package** — you consume it indirectly by placing modules in
`Modules/` and extending its base provider, rather than by calling an API.

## What it provides

- **`LaravelModulesServiceProvider`** — extends nwidart's provider; binds a custom module repository
  (`LaravelModulesFileRepository`, aliased `modules`), the activator, the console commands, the
  dependency cache, and registers the Filament `ModuleResource` / `ModuleDependencyResource`.
- **`LaravelModule`** — a `Nwidart\Modules\Laravel\Module` subclass (adds the `Macroable` trait and
  Microweber behaviour).
- **Activators** — `LaravelModulesFileActivator` (JSON file) and `LaravelModulesDatabaseActivator`
  (DB-backed, used for multisite). The default is `file`, switching to `database` when multisite is active.
- **`BaseModuleServiceProvider`** — the abstract base each module's provider extends.
- **`ModuleDependencyService`** — parse `module.json`, resolve/validate inter-module dependencies.
- **Console commands** — the full `module:*` family (`make`, `enable`, `disable`, `migrate`, `seed`, …)
  plus module generator stubs.
- **Config** — `config/modules.php`, with `paths.modules` resolved to `base_path('Modules')`.

## Installation

```bash
composer require microweber-packages/microweber-laravel-modules
```

The provider is registered from the Microweber application bootstrap
(`MicroweberPackages\App\Providers\MicroweberServiceProvider`). In a standalone app:

```php
$app->register(MicroweberPackages\LaravelModules\LaravelModulesServiceProvider::class);
```

## Usage

### Generate / manage a module

```bash
php artisan module:make Blog          # scaffold Modules/Blog
php artisan module:enable Blog        # activate (writes to the file or DB activator)
php artisan module:disable Blog
php artisan module:migrate Blog
php artisan module:list
```

### A module service provider extends the base

Each module's provider under `Modules/<Name>/Providers/` extends `BaseModuleServiceProvider`, which
auto-registers the module's config, views (`modules.<name>::`) and translations:

```php
namespace Modules\Blog\Providers;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Package\ModulePackage;

class BlogServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Blog';
    protected string $moduleNameLower = 'blog';

    public function configureModule(ModulePackage $module): void
    {
        $module->type('blog');
    }
}
```

### Resolve modules / dependencies at runtime

```php
use Nwidart\Modules\Facades\Module;

$all     = Module::all();
$enabled = Module::allEnabled();
$blog    = Module::find('Blog');

// Dependency resolution
use MicroweberPackages\LaravelModules\Services\ModuleDependencyService;

$deps = app(ModuleDependencyService::class);
$deps->getModuleDependencies('Blog');     // required modules
$deps->getDependentModules('Blog');       // modules depending on Blog
$deps->canInstallModule('Blog');          // ['can' => bool, 'missing' => [...]]
$deps->canUninstallModule('Blog');
$deps->getDependencyTree('Blog');
```

## Configuration

`config/modules.php` (merged under the `modules.*` key). Notable keys:

| Key | Default | Purpose |
|-----|---------|---------|
| `paths.modules` | `base_path('Modules')` | Where modules are scanned. |
| `paths.assets` | `public_path('modules')` | Published module assets. |
| `activator` | `file` (or `database` under multisite, `MODULES_ACTIVATOR`) | Enable/disable backend. |
| `cache.driver` | `file` (`MODULES_CACHE_DRIVER`) | Module metadata cache. |
| `commands` | `ModulesConsoleServiceProvider::defaultCommands()` | Registered `module:*` commands. |

## Key classes

- `MicroweberPackages\LaravelModules\LaravelModulesServiceProvider` — main provider.
- `MicroweberPackages\LaravelModules\LaravelModule` — module model (extends nwidart `Module`).
- `MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider` — abstract base for module providers.
- `MicroweberPackages\LaravelModules\Providers\ModulesConsoleServiceProvider` — registers `module:*` commands.
- `MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository` — the `modules` repository.
- `MicroweberPackages\LaravelModules\Activators\{LaravelModulesFileActivator, LaravelModulesDatabaseActivator}` — activation backends.
- `MicroweberPackages\LaravelModules\Services\ModuleDependencyService` — dependency parsing/validation.
- `MicroweberPackages\LaravelModules\Models\{SystemModules, SystemModulesSushi, ModuleDependency}` — DB/Sushi models.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
