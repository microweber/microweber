<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Notification\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\MailSender\MailSenderServiceProvider;
use MicroweberPackages\MailSender\Services\MailConfigApplier;
use MicroweberPackages\MailSender\Services\MailSenderService;
use MicroweberPackages\Option\Facades\Option;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Extracted reusable mail-sender package.
        if (class_exists(MailSenderServiceProvider::class)) {
            $this->app->register(MailSenderServiceProvider::class);
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (mw_is_installed()) {
            $this->configureMailSenderFromCmsOptions();
            $this->registerCmsContentTransformer();
        }

        View::addNamespace('notification', dirname(__DIR__) . '/resources/views');

        $this->loadMigrationsFrom(dirname(__DIR__) . '/migrations/');
        $this->loadRoutesFrom(dirname(__DIR__) . '/routes/admin.php');
    }

    /**
     * Restore CMS site-URL rewrite on email bodies (legacy MailSender behaviour).
     */
    private function registerCmsContentTransformer(): void
    {
        if (!class_exists(MailSenderService::class)) {
            return;
        }

        $this->app->resolving(MailSenderService::class, function (MailSenderService $sender): void {
            if (!app()->bound('url_manager')) {
                return;
            }
            $sender->setContentTransformer(static function (string $text): string {
                try {
                    return (string) app()->url_manager->replace_site_url_back($text);
                } catch (\Throwable) {
                    return $text;
                }
            });
        });
    }

    /**
     * Map CMS email options into the mail-sender package config and re-apply
     * once on boot. Replaces the old MailSender::configMailDriver() path.
     */
    private function configureMailSenderFromCmsOptions(): void
    {
        if (!class_exists(MailConfigApplier::class)) {
            return;
        }

        $smtpHost = Option::getValue('smtp_host', 'email');
        $smtpPort = Option::getValue('smtp_port', 'email');
        $smtpUsername = Option::getValue('smtp_username', 'email');
        $smtpPassword = Option::getValue('smtp_password', 'email');
        $smtpAuth = Option::getValue('smtp_auth', 'email');

        $emailTransport = Option::getValue('email_transport', 'email');
        if (!$emailTransport) {
            $emailTransport = 'php';
        }

        // From Name
        $emailFromName = function_exists('get_email_from_name') ? get_email_from_name() : null;
        if (!$emailFromName) {
            $emailFromName = getenv('USERNAME') ?: null;
        }

        // Email From
        $emailFrom = function_exists('get_email_from') ? get_email_from() : null;
        if (!$emailFrom) {
            $hostname = '';
            try {
                if (app()->bound('url_manager')) {
                    $hostname = (string) app()->url_manager->hostname();
                }
            } catch (\Throwable) {
                $hostname = (string) (parse_url((string) config('app.url'), PHP_URL_HOST) ?: '');
            }

            if ($emailFromName) {
                $emailFrom = str_replace(' ', '-', (string) $emailFromName) . '@' . $hostname;
            } else {
                $emailFrom = 'noreply@' . $hostname;
            }
        }

        $hostnameForSubject = '';
        try {
            if (app()->bound('url_manager')) {
                $hostnameForSubject = (string) app()->url_manager->hostname();
            }
        } catch (\Throwable) {
            $hostnameForSubject = (string) (parse_url((string) config('app.url'), PHP_URL_HOST) ?: '');
        }

        /** @var MailConfigApplier $applier */
        $applier = app(MailConfigApplier::class);
        $applier->apply([
            'transport' => (string) $emailTransport,
            'from' => [
                'address' => $emailFrom ?: null,
                'name' => $emailFromName ?: null,
            ],
            'smtp' => [
                'host' => $smtpHost ? trim((string) $smtpHost) : null,
                'port' => $smtpPort ? (int) $smtpPort : 587,
                'username' => $smtpUsername ? trim((string) $smtpUsername) : null,
                'password' => $smtpPassword ? trim((string) $smtpPassword) : null,
                'encryption' => $smtpAuth ? trim((string) $smtpAuth) : null,
            ],
            'hostname' => $hostnameForSubject !== '' ? $hostnameForSubject : null,
        ]);
    }
}
