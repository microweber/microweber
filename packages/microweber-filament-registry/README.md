# Microweber Filament Registry

A standalone Laravel package for dynamically registering Filament panel components (resources, pages, widgets, plugins, clusters). Reusable across any Laravel application with Filament panels.

## Installation

```bash
composer require microweber-packages/filament-registry
```

The service provider auto-registers via Laravel's package discovery.

## Usage

### Register Components

```php
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;

// In a service provider's register() method:
FilamentRegistry::registerResource(UserResource::class, MyPanelProvider::class);
FilamentRegistry::registerPage(SettingsPage::class, MyPanelProvider::class);
FilamentRegistry::registerWidget(StatsWidget::class, MyPanelProvider::class);
FilamentRegistry::registerPlugin(MyPlugin::class, MyPanelProvider::class);
FilamentRegistry::registerCluster(MyCluster::class, MyPanelProvider::class);
```

### Retrieve Components in a Panel Provider

```php
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;

class MyPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->resources(FilamentRegistry::getResources(self::class))
            ->pages(FilamentRegistry::getPages(self::class))
            ->widgets(FilamentRegistry::getWidgets(self::class))
            // ...
    }
}
```

### Multiple Panels

Components are scoped by panel provider class and panel ID:

```php
FilamentRegistry::registerResource(AdminResource::class, AdminPanel::class, 'admin');
FilamentRegistry::registerResource(FrontendResource::class, FrontendPanel::class, 'frontend');
```

### Default Scope

Set a default scope so you don't have to pass it every time:

```php
FilamentRegistry::setDefaultScope(MyPanelProvider::class);
FilamentRegistry::registerResource(UserResource::class); // uses MyPanelProvider scope
```

## Testing

```bash
composer test
```

## License

MIT