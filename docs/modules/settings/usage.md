# Settings Module — Usage

Day-to-day patterns for reading + writing options and customizing the admin UI.

## Reading an option

```php
// Most common: simple read with a string default
$title = get_option('website_title', 'website') ?: 'My Site';

// With a fallback default inline
$currency = get_option('currency_symbol', 'website') ?: '$';

// Per-module option
$apiKey = get_option('api_key', 'analytics', false, false, 'GoogleAnalytics');
```

Signature:

```php
get_option(
    string $key,
    string|false $option_group = false,
    bool $return_full = false,         // true returns the full Option row, not just option_value
    string|false $orderby = false,
    string|false $module = false       // restrict lookup to a module scope
): string|false|array
```

The lookup chain: most-specific (key + group + module) → less-specific (key + group) → least (key only).

## Writing an option

```php
save_option('website_title', 'My Awesome Site', 'website');
save_option('maintenance_mode', '1', 'website');
save_option('api_key', $secret, 'analytics', null, 'GoogleAnalytics');
```

Signature:

```php
save_option(
    string $optionKey,
    string|false $value = false,
    string|false $group = false,
    string|null $lang = false  // per-locale override
): bool
```

Returns true on insert/update success.

## Bulk read

```php
// All options in the 'email' group
$emailConfig = get_options(['option_group' => 'email']);

// All options for a specific module
$gaConfig = get_options(['module' => 'GoogleAnalytics']);

// Filter by multiple criteria
$config = get_options([
    'option_group' => 'shop',
    'lang' => 'en',
]);
```

Returns a collection of Option rows (`option_key => option_value` flat array if `return_full=false`).

## Per-locale options

Some options vary per locale (e.g. site title in English vs Spanish). Pass `$lang`:

```php
save_option('website_title', 'My Awesome Site', 'website', 'en');
save_option('website_title', 'Mi Sitio Increíble', 'website', 'es');

// Reads pick up the current locale automatically
app()->setLocale('es');
echo get_option('website_title', 'website');  // "Mi Sitio Increíble"

app()->setLocale('en');
echo get_option('website_title', 'website');  // "My Awesome Site"
```

The lookup chain prefers the active locale, then falls back to the `null`-locale row, then to false.

## Programmatic admin actions

Triggering the same save flow the Filament admin does:

```php
// Equivalent to filling the Email form and clicking Save
save_option('email_smtp_server', 'smtp.mailgun.org', 'email');
save_option('email_smtp_port', '587', 'email');
save_option('email_smtp_user', 'postmaster@yourdomain', 'email');
save_option('email_smtp_password', $secret, 'email');
save_option('email_from', 'noreply@yourdomain.com', 'email');
```

Test the SMTP config end-to-end via:

```bash
php artisan tinker
> \Mail::raw('Test', fn ($m) => $m->to('me@example.com')->subject('SMTP test'));
```

## Maintenance mode

```php
// Enable
save_option('maintenance_mode', '1', 'website');
save_option('maintenance_message', 'Back in 30 minutes', 'website');

// Allow specific IPs through (whitelist)
save_option('maintenance_allowed_ips', '203.0.113.5,198.51.100.7', 'website');

// Disable
save_option('maintenance_mode', '0', 'website');
```

The `MaintenanceMiddleware` (in the Settings module or App's HTTP kernel) reads these and gates non-allowed-IP requests with a 503.

## Encrypted / sensitive options

Microweber doesn't encrypt option_value at rest by default. For SMTP passwords, API keys, etc., one of:

1. Store via Laravel's encrypted cast (subclass Option model with `protected $casts = ['option_value' => 'encrypted']`)
2. Reference an `.env` variable from the option value (e.g. `option_value = 'env:SMTP_PASSWORD'`) and resolve at read time
3. Use Laravel's config + `.env` directly for the most sensitive secrets — bypass the Option store entirely

Option 3 is the standard for production secrets. The Option store is for site-owner-editable config, not for credentials that should never appear in the admin UI.

## Caching

`get_option()` results are cached per-request via the `OptionRepository`. Subsequent reads of the same key + group inside the same request return the cached value without hitting the DB.

`save_option()` flushes the matching cache entry automatically. For manual flushes (e.g. after a direct SQL update):

```php
\Cache::tags(['options', 'settings'])->flush();
```

## Filament admin pages

Each section is a separate Filament Page under `Modules\Settings\Filament\Pages\`. To customize:

```php
// Extend the existing email page
namespace App\Filament\Pages;

class AdminEmailPage extends \Modules\Settings\Filament\Pages\AdminEmailPage
{
    protected function getFormFields(): array
    {
        return array_merge(parent::getFormFields(), [
            \Filament\Forms\Components\Toggle::make('email_log_outbound')
                ->label('Log all outbound mail'),
        ]);
    }
}
```

Register the subclass in your AppPanelProvider in place of the original.

## REST API for reads

Read-only endpoints at `/api/settings` let frontend code (e.g. a JS widget on the public site) read non-sensitive options without authentication. Sensitive groups (`email`, `system`) require Sanctum admin scope.

See [`api.md`](./api.md) for the full endpoint list.

## Common option keys (informational, not exhaustive)

| Group | Key | Typical value |
|---|---|---|
| `website` | `website_title` | Site name |
| `website` | `website_description` | Tagline |
| `website` | `current_template` | e.g. `'Big2'` or `'Bootstrap'` |
| `website` | `language` | e.g. `'en'` |
| `website` | `timezone` | e.g. `'UTC'` or `'America/New_York'` |
| `website` | `maintenance_mode` | `'0'` or `'1'` |
| `email` | `email_smtp_server` | Hostname |
| `email` | `email_smtp_port` | Port (typically `'587'` or `'465'`) |
| `email` | `email_from` | Default `From:` address |
| `shop` | `currency` | ISO code, e.g. `'USD'` |
| `shop` | `currency_symbol` | `'$'`, `'€'`, etc. |
| `media` | `default_image_path` | Fallback image URL |
| `media` | `max_upload_mb` | Per-file upload limit |
| `media` | `allowed_extensions` | Comma-separated whitelist |
