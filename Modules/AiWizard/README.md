# AiWizard

AI setup wizard that guides users through configuring AI providers and settings for content generation features.

## Structure

- Filament admin
- Livewire components
- Route definitions
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="aiwizard" />
```

### Publish assets

```sh
php artisan module:publish AiWizard
```

### Configuration

```php
config('modules.aiwizard.name')
```

### Views

```php
view('modules.aiwizard::index')
```

