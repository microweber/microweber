# Comments

User comment system for pages and posts. Supports nested replies, moderation, and spam filtering.

## Structure

- Filament admin
- Livewire components
- Eloquent models
- Service classes
- Route definitions
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="comments" />
```

### Run migrations

```sh
php artisan module:migrate Comments
```

### Publish assets

```sh
php artisan module:publish Comments
```

### Configuration

```php
config('modules.comments.name')
```

### Views

```php
view('modules.comments::index')
```

