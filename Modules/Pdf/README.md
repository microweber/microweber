# Pdf

PDF generation from content. Convert pages, invoices, and reports to downloadable PDF files.

## Structure

- Filament admin
- Blade views
- Tests

## Usage

### Module tag

```html
<module type="pdf" />
```

### Publish assets

```sh
php artisan module:publish Pdf
```

### Configuration

```php
config('modules.pdf.name')
```

### Views

```php
view('modules.pdf::index')
```

