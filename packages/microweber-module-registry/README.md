# microweber-packages/module-registry

Standalone Laravel package that provides the Microweber **module registry**: register modules, render them, discover blade skins/templates, and integrate with Live Edit settings.

Usable **inside the Microweber CMS** or in any **standalone Laravel application**.

## Installation

```bash
composer require microweber-packages/module-registry
```

The service provider auto-discovers. If you disable discovery, register:

```php
MicroweberPackages\ModuleRegistry\ModuleRegistryServiceProvider::class,
```

## Usage

### Register a module

```php
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use App\Modules\HeroModule;

// In a service provider register():
ModuleRegistry::module(HeroModule::class);
```

### Render a module

```php
$html = ModuleRegistry::render('hero', ['id' => 'module-1']);
```

### List modules / settings

```php
$modules = ModuleRegistry::getModules();
$details = ModuleRegistry::getModulesDetails();
$settings = ModuleRegistry::getSettingsComponents();
$skins = ModuleRegistry::getTemplates('hero');
```

### Define a module

```php
use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;

class HeroModule extends BaseModule
{
    public static string $name = 'Hero';
    public static string $module = 'hero';
    public static string $templatesNamespace = 'modules.hero::templates';
}
```

### Blade skin scanning

```php
use MicroweberPackages\ModuleRegistry\Support\ScanForBladeTemplates;

$scanner = new ScanForBladeTemplates();
$skins = $scanner->scan('modules.hero::templates', 'hero');
```

### CMS compatibility aliases

The container binding `microweber` and the `Microweber` facade alias remain available for backward compatibility:

```php
app()->microweber->hasModule('hero');
\MicroweberPackages\ModuleRegistry\Facades\Microweber::module(HeroModule::class);
```

## Optional CMS helpers

When running outside the CMS, option/template helpers (`get_option`, `template_name`, …) are no-ops or return safe defaults. Register modules and render with explicit params for full standalone use.

## Testing

```bash
composer test
composer analyse
```

## License

MIT
