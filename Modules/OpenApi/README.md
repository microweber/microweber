# OpenApi

OpenAPI/Swagger documentation generator. Auto-generate API docs from route definitions.

## Structure

- Eloquent models
- HTTP controllers
- Route definitions
- Tests

## Usage

### Module tag

```html
<module type="openapi" />
```

### Publish assets

```sh
php artisan module:publish OpenApi
```

### Configuration

```php
config('modules.openapi.name')
```

