# Updater

System updater. Check for and apply Microweber core and module updates from the repository.

> **Dusk coverage:** intentionally absent. The Updater module's only externally-observable behaviour is calling the upstream Microweber update repository, which the live test environment cannot reach safely (a successful Dusk run would mutate `composer.json` / `composer.lock` of the host install). The Filament admin pages render the same Livewire-update-table that ships with every Filament resource and is exercised by the Filament-resource smokes; the module-specific upgrade paths are covered by unit tests in `Modules/Updater/Tests/`. See Plan C.3 in `TODO.md`.


## Structure

- Filament admin
- HTTP controllers
- Service classes
- Route definitions
- Blade views

## Usage

### Module tag

```html
<module type="updater" />
```

### Publish assets

```sh
php artisan module:publish Updater
```

### Configuration

```php
config('modules.updater.name')
```

### Views

```php
view('modules.updater::index')
```

