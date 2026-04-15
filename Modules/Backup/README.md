# Backup

Backup and restore functionality. Create full site backups, schedule automatic backups, and restore from backup files.

## Structure

- Filament admin
- Eloquent models
- HTTP controllers
- Service classes
- Route definitions
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="backup" />
```

### Publish assets

```sh
php artisan module:publish Backup
```

### Configuration

```php
config('modules.backup.name')
```

### Views

```php
view('modules.backup::index')
```

