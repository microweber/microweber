# Laravel Templates

The template engine infrastructure for Microweber CMS. It reuses the
[`nwidart/laravel-modules`](https://github.com/nWidart/laravel-modules) machinery to discover and
manage **templates** under `base_path('Templates')` — the same way `microweber-laravel-modules`
manages `Modules/`. It provides a template repository, file/database activators, generator commands,
and the `template:*` artisan commands.

This is an **infrastructure / glue package**: you consume it by placing templates in `Templates/`
and activating them, not by calling a public API.

## What it provides

- **`LaravelTemplatesServiceProvider`** — extends nwidart's provider; binds the
  `TemplatesRepositoryInterface` (`LaravelTemplatesFileRepository`, aliased `templates`), the template
  activator, the console commands, the dependency cache, and loads the `system_templates` migration.
- **`LaravelTemplate`** — a `LaravelModule` subclass representing a single template.
- **Activators** — `TemplatesFileActivator` (JSON file) and `LaravelTemplatesDatabaseActivator`
  (DB-backed, used for multisite). Default is `file`, switching to `database` under multisite.
- **Generators & commands** — `template:make`, `template:make-provider`, `template:publish`,
  `template:migrate`, plus `TemplateGenerator` and scaffolding stubs.
- **`SystemTemplates`** model over the `system_templates` table.
- **Config** — `config/templates.php` (namespace `Templates`, `paths.modules = base_path('Templates')`).
- **Helpers** — `template_path()` and `template_vite()`.

## Installation

```bash
composer require microweber-packages/microweber-laravel-templates
```

The provider is registered from the Microweber application bootstrap. In a standalone app:

```php
$app->register(MicroweberPackages\LaravelTemplates\LaravelTemplatesServiceProvider::class);
```

## Usage

### Template artisan commands

```bash
php artisan template:make Aurora          # scaffold Templates/Aurora
php artisan template:make-provider Aurora # generate its service provider
php artisan template:publish Aurora       # publish the template's assets
php artisan template:migrate Aurora       # run the template's migrations
```

### Resolve templates at runtime

The repository is bound as `templates` (and as `TemplatesRepositoryInterface`):

```php
use MicroweberPackages\LaravelTemplates\Contracts\TemplatesRepositoryInterface;

$templates = app(TemplatesRepositoryInterface::class);
$all     = $templates->all();
$aurora  = $templates->find('Aurora');
```

### Helpers

```php
// Absolute path inside a template directory
$css = template_path('Aurora', 'resources/assets/css/style.css');

// A Vite instance scoped to the template's build manifest
$vite = template_vite('Aurora', 'resources/assets/js/app.js');
```

## Configuration

`config/templates.php` (merged under the `templates.*` key):

| Key | Default | Purpose |
|-----|---------|---------|
| `namespace` | `Templates` | PHP/Blade namespace for templates. |
| `paths.modules` | `base_path('Templates')` | Where templates are scanned. |
| `paths.assets` | `public_path('templates')` | Published template assets. |
| `activator` | `file` (or `database` under multisite, `TEMPLATES_ACTIVATOR`) | Enable/disable backend. |
| `cache.driver` | `file` (`MODULES_CACHE_DRIVER`) | Template metadata cache. |

## Key classes

- `MicroweberPackages\LaravelTemplates\LaravelTemplatesServiceProvider` — main provider.
- `MicroweberPackages\LaravelTemplates\LaravelTemplate` — single-template model (extends `LaravelModule`).
- `MicroweberPackages\LaravelTemplates\Repositories\LaravelTemplatesFileRepository` — the `templates` repository.
- `MicroweberPackages\LaravelTemplates\Activators\{TemplatesFileActivator, LaravelTemplatesDatabaseActivator}` — activation backends.
- `MicroweberPackages\LaravelTemplates\Generators\TemplateGenerator` — template scaffolding.
- `MicroweberPackages\LaravelTemplates\Commands\Make\{TemplateMakeCommand, TemplateProviderMakeCommand}` · `...\Publish\TemplatePublishCommand` · `...\Migrate\TemplateMigrateCommand` — the `template:*` commands.
- `MicroweberPackages\LaravelTemplates\Models\SystemTemplates` — `system_templates` model.
- `MicroweberPackages\LaravelTemplates\Providers\{TemplatesConsoleServiceProvider, TemplatesContractsServiceProvider, TemplatesBootstrapServiceProvider}` — sub-providers.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov

## License

MIT
