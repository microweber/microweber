# Profile

User profile management with authentication, registration, and profile editing capabilities.

## Structure

- Filament admin
- Eloquent models
- HTTP controllers
- Route definitions
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="profile" />
```

### Run migrations

```sh
php artisan module:migrate Profile
```

### Publish assets

```sh
php artisan module:publish Profile
```

### Configuration

```php
config('modules.profile.name')
```

### Views

```php
view('modules.profile::index')
```

