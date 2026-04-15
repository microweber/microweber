# Content

Core content management module. Handles pages, posts, and custom content types with CRUD operations.

## Structure

- Filament admin
- Eloquent models
- HTTP controllers
- Service classes
- Route definitions
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="content" />
```

### Run migrations

```sh
php artisan module:migrate Content
```

### Publish assets

```sh
php artisan module:publish Content
```

### Configuration

```php
config('modules.content.name')
```

### Views

```php
view('modules.content::index')
```

