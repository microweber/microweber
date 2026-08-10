# microweber-packages/notification

Standalone Laravel package for database notifications, the `AppMailChannel`
mail delivery channel, admin notification management, and mail logs. Extracted
from the Microweber CMS `Notification` package so it can be reused in any
Laravel application (with dependent packages such as `mail-sender`).

## Install

```bash
composer require microweber-packages/notification
```

Register the service provider (auto-discovery is supported):

```php
$app->register(\MicroweberPackages\Notification\Providers\NotificationServiceProvider::class);
```

Publish config, views, and migrations:

```bash
php artisan vendor:publish --tag=microweber-notification-config
php artisan vendor:publish --tag=microweber-notification-views
php artisan vendor:publish --tag=microweber-notification-migrations
php artisan migrate
```

## Usage

### Notifications manager

```php
use MicroweberPackages\Notification\Facades\Notifications;
// or
use MicroweberPackages\Notification\Services\NotificationsManager;

NotificationsManager::save([
    'module' => 'shop',
    'rel_type' => 'cart_orders',
    'rel_id' => 1,
    'title' => 'You have a new order',
    'description' => 'A new order was placed',
]);

$count = notifications_manager()->get_unread_count();
// or notification_unread_count();
```

### App mail channel

```php
use MicroweberPackages\Notification\Channels\AppMailChannel;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    public function via($notifiable): array
    {
        return ['database', AppMailChannel::class];
    }
}
```

### Models

```php
use MicroweberPackages\Notification\Models\Notification;
use MicroweberPackages\Notification\Models\NotificationMailLog;

Notification::query()->whereNull('read_at')->count();
NotificationMailLog::create([
    'type' => 'Welcome',
    'notifiable_type' => 'user',
    'notifiable_id' => 1,
]);
```

## Config

See `config/microweber-notification.php`.

| Key | Description |
|-----|-------------|
| `admin_user_model` | Eloquent model for admin notifiables |
| `admin_column` / `admin_value` | Query constraint for admins (`is_admin` = 1 by default) |
| `load_admin_routes` | Register admin HTTP routes |
| `admin_route_prefix` | Prefix (default `admin`, or CMS helper when present) |
| `admin_middleware` | Middleware stack for admin routes |

## Dependent packages

- [microweber-packages/mail-sender](../microweber-mail-sender) — mail transport for `AppMailChannel` and test mail

Optional:

- `tucker-eric/eloquentfilter` — enables `Notification::filter()` for admin listing

## Tests

```bash
composer test
# or from monorepo root:
./vendor/bin/phpunit --testsuite=MicroweberNotification
```

## PHPStan

```bash
composer analyse
# or from monorepo root:
composer analyse -- packages/microweber-notification/src
```
