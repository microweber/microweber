# Template

The template engine package for Microweber CMS. It resolves the active template, compiles and serves its stylesheets, renders layouts, manages per-template design settings (colour schemes / style packs), injects `<head>`/footer scripts and styles, and powers the Live Edit template-settings sidebar.

This is a **core, CMS-coupled package** — it depends on many other Microweber packages (core, admin, user, option, view, config, …) and is not meant to run standalone. It is loaded automatically inside a Microweber install.

## Features

- **Template manager** (`TemplateManager`, facade `TemplateManager`, container `template_manager`) — resolve the active template directory/URL/name, read its `config.php`/`composer.json`, compile CSS, render layouts.
- **Meta-tags / script+style manager** (`TemplateMetaTags`, container `template_meta_tags`) — register and emit head/footer scripts, styles and custom tags.
- **Design System** — `design_system`, `color_schemes_registry`, `style_pack_registry` singletons driving colour palettes and style packs.
- **Live Edit sidebar** — Livewire components for editing template settings in the admin.
- **Content JSON** — import/export a template's sections as portable JSON (`ContentJson\*`).
- **Template helper functions** — a large `current_template()` / `template_dir()` / `template_url()` family.

## Installation

```bash
composer require microweber-packages/microweber-template
```

The `TemplateManagerServiceProvider` is auto-discovered. It registers the singletons below, loads the package API routes, and registers the Livewire components `admin-template-update-modal` and `live-edit-template-settings-sidebar`.

Registered singletons:

| Container key | Resolves to |
|---------------|-------------|
| `template_manager` | `MicroweberPackages\Template\TemplateManager` |
| `template_meta_tags` | `...\Repositories\TemplateMetaTagsRepository` |
| `layouts_manager` | layouts manager |
| `color_schemes_registry` | `...\Services\DesignSystem\ColorSchemesRegistry` |
| `style_pack_registry` | `...\Services\DesignSystem\StylePackRegistry` |
| `design_system` | `...\Services\DesignSystem\DesignSystemService` |

## Usage

### Template manager (facade / container)

```php
use MicroweberPackages\Template\Facades\TemplateManager;

// The active template's config (merged config.php + composer.json)
$config = TemplateManager::get_config();          // or app()->template_manager->get_config()

// Paths / names
TemplateManager::dir();          // absolute path to the active template dir
TemplateManager::url();          // public URL of the active template
TemplateManager::name();         // template name
TemplateManager::folder_name();  // on-disk folder name

// Is this a Laravel (Blade) template?
TemplateManager::is_laravel_template();

// Stylesheets
TemplateManager::get_stylesheet($path);
TemplateManager::compile_css($params);

// Render a layout / page
echo TemplateManager::render($params);
TemplateManager::clear_cache();
```

Most of these are also reachable through `app()->template_manager->...`, which is how the rest of the CMS calls them (e.g. `app()->template_manager->get_config()`).

### Head / footer scripts and styles

```php
use MicroweberPackages\Template\Facades\TemplateMetaTags;

TemplateMetaTags::addScript('analytics', 'https://cdn/analytics.js', ['defer' => true], 'head');
TemplateMetaTags::addStyle('theme', template_url() . 'css/custom.css');
TemplateMetaTags::addCustomHeadTag('<meta name="robots" content="index">');

// Emit in the layout
echo TemplateMetaTags::headTags();
echo TemplateMetaTags::styles('head');
echo TemplateMetaTags::scripts('head');
echo TemplateMetaTags::footerTags();
```

### Template helper functions

Autoloaded from `src/helpers/templates.php`:

```php
current_template();          // active template name
template_dir();              // active template dir (optional sub-path arg)
template_url();              // active template URL (optional sub-path arg)
template_name();
template_config();           // active template config array
template_stylesheet_url();
template_framework();        // e.g. 'bootstrap'
template_parent();           // parent template name for child templates
```

### Design system

```php
$palettes = app('color_schemes_registry')->all();     // registered colour schemes
$packs    = app('style_pack_registry')->all();         // registered style packs
app('design_system');                                  // DesignSystemService
```

Built-in palettes and style packs ship as JSON under
`src/resources/assets/shared-design/` (e.g. `color-schemes/default-palettes.json`,
`style-packs/colors/ocean-breeze.json`).

## Key classes

- `MicroweberPackages\Template\Facades\TemplateManager` · `...\TemplateManager`
- `MicroweberPackages\Template\Facades\TemplateMetaTags` · `...\Repositories\TemplateMetaTagsRepository`
- `MicroweberPackages\Template\Managers\TemplateMetaTagManager` · `...\Managers\ScriptStyleManager`
- `MicroweberPackages\Template\Services\DesignSystem\DesignSystemService`
- `MicroweberPackages\Template\Services\DesignSystem\ColorSchemesRegistry` · `...\StylePackRegistry`
- `MicroweberPackages\Template\Services\AssetOptimizationService`
- `MicroweberPackages\Template\ContentJson\ContentJsonInstaller` · `...\ContentJsonExporter` · `...\SectionRenderer`
- `MicroweberPackages\Template\Adapters\MicroweberTemplate` · `...\TemplateCssParser` · `...\TemplateIconFonts`
- `MicroweberPackages\Template\Http\Livewire\Admin\LiveEditTemplateSettingsSidebar` · `...\AdminTemplateUpdateModal`
- `MicroweberPackages\Template\TemplateManagerServiceProvider`

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Nikolay Rachkov
- Alexander Raikov
- Petko Yovchevski

## License

MIT
