# MailTemplate

Email template editor. Customize notification emails for orders, registration, password resets, and more.

## Structure

- Filament admin
- Eloquent models
- Service classes
- Blade views
- Database migrations
- Tests

## Usage

### Module tag

```html
<module type="mail_template" />
```

### Run migrations

```sh
php artisan module:migrate MailTemplate
```

### Publish assets

```sh
php artisan module:publish MailTemplate
```

### Configuration

```php
config('modules.mail_template.name')
```

### Views

```php
view('modules.mail_template::index')
```

