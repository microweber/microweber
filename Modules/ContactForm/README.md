# ContactForm

Contact form builder with customizable fields, email notifications, and submission management.

## Structure

- Filament admin
- Eloquent models
- HTTP controllers
- Route definitions
- Blade views
- Database migrations

## Usage

### Module tag

```html
<module type="contact_form" />
```

### Run migrations

```sh
php artisan module:migrate ContactForm
```

### Publish assets

```sh
php artisan module:publish ContactForm
```

### Views

```php
view('modules.contact_form::index')
```

