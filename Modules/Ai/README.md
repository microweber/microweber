# Ai

AI-powered content generation and assistance. Integrates with AI providers to help users create and edit content automatically.

## Structure

- Filament admin
- Eloquent models
- HTTP controllers
- Service classes
- Route definitions
- Blade views
- Database migrations

## Usage

### Module tag

```html
<module type="ai" />
```

### Run migrations

```sh
php artisan module:migrate Ai
```

### Publish assets

```sh
php artisan module:publish Ai
```

### Configuration

```php
config('modules.ai.name')
```

### Views

```php
view('modules.ai::index')
```

