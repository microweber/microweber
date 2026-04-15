# Menu

Navigation menu builder. Create and manage site menus with drag-and-drop ordering and nesting.

## Structure

- Filament admin
- Livewire components
- Eloquent models
- Route definitions
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="menu" />
```

### Run migrations

```sh
php artisan module:migrate Menu
```

### Publish assets

```sh
php artisan module:publish Menu
```

### Configuration

```php
config('modules.menu.name')
```

### Views

```php
view('modules.menu::index')
```

