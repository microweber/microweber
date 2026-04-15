# Updater

System updater. Check for and apply Microweber core and module updates from the repository.

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

