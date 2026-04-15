# HighlightCode

Syntax highlighting for code blocks. Display formatted code snippets with language-specific coloring.

## Structure

- Filament admin
- Route definitions
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="highlight_code" />
```

### Publish assets

```sh
php artisan module:publish HighlightCode
```

### Configuration

```php
config('modules.highlight_code.name')
```

### Views

```php
view('modules.highlight_code::index')
```

