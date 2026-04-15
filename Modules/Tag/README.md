# Tag

Content tagging system. Add and manage tags for categorizing and filtering content.

## Structure

- Filament admin
- Eloquent models
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="tag" />
```

### Run migrations

```sh
php artisan module:migrate Tag
```

### Publish assets

```sh
php artisan module:publish Tag
```

### Configuration

```php
config('modules.tag.name')
```

### Views

```php
view('modules.tag::index')
```

