# microweber-packages/mail-sender

Standalone Laravel package for sending transactional email via configurable
transports (SMTP, PHP `mail()`, Gmail, cPanel, Plesk). Extracted from the
Microweber CMS `MailSender` so it can be reused in any Laravel application.

## Install

```bash
composer require microweber-packages/mail-sender
```

Register the service provider (or rely on CoreServiceProvider / auto-discovery):

```php
$app->register(\MicroweberPackages\MailSender\MailSenderServiceProvider::class);
```

Publish config and views:

```bash
php artisan vendor:publish --tag=mail-sender-config
php artisan vendor:publish --tag=mail-sender-views
```

## Usage

Config is applied **once on boot** from `config/mail-sender.php` (or env vars).
Do **not** call a per-instance `configMailDriver()` — that API is removed.

```php
use MicroweberPackages\MailSender\Facades\MailSender;
// or
use MicroweberPackages\MailSender\Services\MailSenderService;

$ok = MailSender::send(
    to: 'user@example.com',
    subject: 'Hello',
    message: '<p>Welcome!</p>',
);

// Fluent setters (compatible with the old CMS API)
$sender = app(MailSenderService::class);
$sender->setEmailTo('user@example.com')
    ->setEmailSubject('Hello')
    ->setEmailMessage('<p>Welcome!</p>')
    ->setEmailFrom('noreply@example.com')
    ->setEmailFromName('Example')
    ->send();
```

## Config

See `config/mail-sender.php`. Key options:

| Key | Description |
|-----|-------------|
| `transport` | `smtp`, `php`, `gmail`, `cpanel`, `plesk`, `config`, `log`, `array` |
| `from.address` / `from.name` | Global From header |
| `smtp.*` | Host, port, username, password, encryption |
| `hostname` | Optional `[hostname]` subject prefix |
| `view` | Blade view for the simple HTML body |

## Filament

```php
$panel->plugin(\MicroweberPackages\MailSender\Filament\MailSenderPlugin::make());
```

## Tests

```bash
composer test
# or from monorepo root:
php artisan test --testsuite=MicroweberMailSender
php artisan dusk --filter=MailSender
```

## PHPStan

```bash
composer analyse -- packages/microweber-mail-sender/src
# package-local:
cd packages/microweber-mail-sender && composer analyse
```
