# Post

Blog post content type. Create and manage blog posts with categories, tags, and publishing controls.

## Structure

- Filament admin
- Eloquent models
- HTTP controllers
- Route definitions
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="post" />
```

### Publish assets

```sh
php artisan module:publish Post
```

### Configuration

```php
config('modules.post.name')
```

### Views

```php
view('modules.post::index')
```

