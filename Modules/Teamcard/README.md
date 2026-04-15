# Teamcard

Team member cards. Display team/staff profiles with photo, name, role, and social links.

## Structure

- Filament admin
- Eloquent models
- Route definitions
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="teamcard" />
```

### Run migrations

```sh
php artisan module:migrate Teamcard
```

### Publish assets

```sh
php artisan module:publish Teamcard
```

### Configuration

```php
config('modules.teamcard.name')
```

### Views

```php
view('modules.teamcard::index')
```

