# Restore

Restore site from backup files. Import and apply previously created backup archives.

## Structure

- Tests

## Usage

### Module tag

```html
<module type="restore" />
```

### Publish assets

```sh
php artisan module:publish Restore
```

### Configuration

```php
config('modules.restore.name')
```

