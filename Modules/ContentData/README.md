# ContentData

Content data storage for custom key-value metadata attached to content items.

## Structure

- Eloquent models
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="contentdata" />
```

### Run migrations

```sh
php artisan module:migrate ContentData
```

### Publish assets

```sh
php artisan module:publish ContentData
```

### Configuration

```php
config('modules.contentdata.name')
```

