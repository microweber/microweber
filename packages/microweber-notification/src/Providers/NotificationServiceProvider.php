<?php

declare(strict_types=1);

namespace MicroweberPackages\Notification\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\MailSender\MailSenderServiceProvider;
use MicroweberPackages\MailSender\Services\MailConfigApplier;
use MicroweberPackages\MailSender\Services\MailSenderService;
use MicroweberPackages\Notification\Contracts\NotificationsManagerContract;
use MicroweberPackages\Notification\Http\Controllers\Admin\NotificationController;
use MicroweberPackages\Notification\Services\EmailNotificationsManager;
use MicroweberPackages\Notification\Services\NotificationsManager;

class NotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/microweber-notification.php',
            'microweber-notification'
        );

        // Dependent mail-sender package (optional at runtime if not installed).
        // Only register if not already registered (e.g. by the CMS or discovery).
        if (class_exists(MailSenderServiceProvider::class)
            && ! $this->app->getProvider(MailSenderServiceProvider::class)
        ) {
            $this->app->register(MailSenderServiceProvider::class);
        }

        // Event → listener bindings for notifications.
        if (! $this->app->getProvider(NotificationEventServiceProvider::class)) {
            $this->app->register(NotificationEventServiceProvider::class);
        }

        // One singleton per concrete manager; the contract + string names are
        // aliases so they resolve to the SAME instance (no duplicate closures).
        $this->app->singleton(NotificationsManager::class, function ($app) {
            return new NotificationsManager($app);
        });
        $this->app->alias(NotificationsManager::class, NotificationsManagerContract::class);
        $this->app->alias(NotificationsManager::class, 'notifications_manager');

        $this->app->singleton(EmailNotificationsManager::class, function ($app) {
            return new EmailNotificationsManager($app);
        });
        $this->app->alias(EmailNotificationsManager::class, 'email_notifications_manager');
    }

    public function boot(): void
    {
        $viewNamespaceConfig = config('microweber-notification.view_namespace', 'notification');
        $viewNamespace = is_string($viewNamespaceConfig) && $viewNamespaceConfig !== ''
            ? $viewNamespaceConfig
            : 'notification';
        View::addNamespace($viewNamespace, __DIR__ . '/../../resources/views');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->configureCmsMailSenderIfAvailable();
        $this->registerCmsContentTransformer();
        $this->registerAdminRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/microweber-notification.php' => config_path('microweber-notification.php'),
            ], 'microweber-notification-config');

            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/notification'),
            ], 'microweber-notification-views');

            $this->publishes([
                __DIR__ . '/../../database/migrations' => database_path('migrations'),
            ], 'microweber-notification-migrations');
        }
    }

    /**
     * Restore CMS site-URL rewrite on email bodies (legacy MailSender behaviour).
     */
    private function registerCmsContentTransformer(): void
    {
        if (! class_exists(MailSenderService::class)) {
            return;
        }

        $this->app->resolving(MailSenderService::class, function (MailSenderService $sender): void {
            if (! $this->app->bound('url_manager')) {
                return;
            }

            $sender->setContentTransformer(static function (string $text): string {
                try {
                    $urlManager = app('url_manager');
                    if (is_object($urlManager) && method_exists($urlManager, 'replace_site_url_back')) {
                        $replaced = $urlManager->replace_site_url_back($text);

                        return is_scalar($replaced) ? (string) $replaced : $text;
                    }
                } catch (\Throwable) {
                    return $text;
                }

                return $text;
            });
        });
    }

    /**
     * Map CMS email options into the mail-sender package config when available.
     */
    private function configureCmsMailSenderIfAvailable(): void
    {
        if (! class_exists(MailConfigApplier::class)) {
            return;
        }

        $isInstalled = ! function_exists('mw_is_installed') || mw_is_installed();
        if (! $isInstalled) {
            return;
        }

        if (! class_exists(\MicroweberPackages\Option\Facades\Option::class)) {
            return;
        }

        $smtpHost = \MicroweberPackages\Option\Facades\Option::getValue('smtp_host', 'email');
        $smtpPort = \MicroweberPackages\Option\Facades\Option::getValue('smtp_port', 'email');
        $smtpUsername = \MicroweberPackages\Option\Facades\Option::getValue('smtp_username', 'email');
        $smtpPassword = \MicroweberPackages\Option\Facades\Option::getValue('smtp_password', 'email');
        $smtpAuth = \MicroweberPackages\Option\Facades\Option::getValue('smtp_auth', 'email');

        $emailTransport = \MicroweberPackages\Option\Facades\Option::getValue('email_transport', 'email');
        if (! $emailTransport) {
            $emailTransport = 'php';
        }

        $emailFromName = function_exists('get_email_from_name') ? get_email_from_name() : null;
        if (! $emailFromName) {
            $emailFromName = getenv('USERNAME') ?: null;
        }

        $emailFrom = function_exists('get_email_from') ? get_email_from() : null;
        if (! $emailFrom) {
            $hostname = $this->resolveHostname();
            if ($emailFromName) {
                $emailFrom = str_replace(' ', '-', (string) $emailFromName) . '@' . $hostname;
            } else {
                $emailFrom = 'noreply@' . $hostname;
            }
        }

        /** @var MailConfigApplier $applier */
        $applier = $this->app->make(MailConfigApplier::class);
        $applier->apply([
            'transport' => is_scalar($emailTransport) ? (string) $emailTransport : 'php',
            'from' => [
                'address' => is_scalar($emailFrom) && (string) $emailFrom !== '' ? (string) $emailFrom : null,
                'name' => is_scalar($emailFromName) && (string) $emailFromName !== '' ? (string) $emailFromName : null,
            ],
            'smtp' => [
                'host' => is_scalar($smtpHost) && (string) $smtpHost !== '' ? trim((string) $smtpHost) : null,
                'port' => is_numeric($smtpPort) ? (int) $smtpPort : 587,
                'username' => is_scalar($smtpUsername) && (string) $smtpUsername !== '' ? trim((string) $smtpUsername) : null,
                'password' => is_scalar($smtpPassword) && (string) $smtpPassword !== '' ? trim((string) $smtpPassword) : null,
                'encryption' => is_scalar($smtpAuth) && (string) $smtpAuth !== '' ? trim((string) $smtpAuth) : null,
            ],
            'hostname' => ($h = $this->resolveHostname()) !== '' ? $h : null,
        ]);
    }

    private function resolveHostname(): string
    {
        try {
            if ($this->app->bound('url_manager')) {
                $urlManager = $this->app->make('url_manager');
                if (is_object($urlManager) && method_exists($urlManager, 'hostname')) {
                    $hostname = $urlManager->hostname();

                    return is_scalar($hostname) ? (string) $hostname : '';
                }
            }
        } catch (\Throwable) {
            // fall through
        }

        $appUrl = config('app.url');
        $host = is_string($appUrl) ? parse_url($appUrl, PHP_URL_HOST) : null;

        return is_string($host) ? $host : '';
    }

    private function registerAdminRoutes(): void
    {
        if (! (bool) config('microweber-notification.load_admin_routes', true)) {
            return;
        }

        if ($this->app->routesAreCached()) {
            return;
        }

        $prefix = config('microweber-notification.admin_route_prefix');
        if (! is_string($prefix) || $prefix === '') {
            if (function_exists('mw_admin_prefix_url_legacy')) {
                $prefix = mw_admin_prefix_url_legacy();
            } else {
                $prefix = 'admin';
            }
        }

        /** @var list<string> $middleware */
        $middleware = (array) config('microweber-notification.admin_middleware', ['web']);

        /** @var Router $router */
        $router = $this->app->make('router');

        $prefixString = is_string($prefix) ? $prefix : 'admin';

        $router
            ->middleware($middleware)
            ->prefix($prefixString)
            ->name('admin.')
            ->group(function () use ($router): void {
                $router->post('notification/read', [NotificationController::class, 'read'])
                    ->name('notification.read');
                $router->post('notification/reset', [NotificationController::class, 'reset'])
                    ->name('notification.reset');
                $router->post('notification/delete', [NotificationController::class, 'delete'])
                    ->name('notification.delete');
                $router->post('notification/test_mail', [NotificationController::class, 'testMail'])
                    ->name('notification.test_mail');
                $router->get('notification', [NotificationController::class, 'index'])
                    ->name('notification.index');
            });
    }
}
