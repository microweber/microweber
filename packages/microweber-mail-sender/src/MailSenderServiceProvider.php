<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\MailSender\Contracts\MailSenderContract;
use MicroweberPackages\MailSender\Services\MailConfigApplier;
use MicroweberPackages\MailSender\Services\MailSenderService;

class MailSenderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/mail-sender.php', 'mail-sender');

        $this->app->singleton(MailConfigApplier::class, static fn () => new MailConfigApplier());

        // Transient: callers may use fluent setters; shared state would be unsafe.
        $this->app->bind(MailSenderService::class, static fn () => new MailSenderService());
        $this->app->bind(MailSenderContract::class, MailSenderService::class);

        // One canonical string name for the package.
    }

    public function boot(): void
    {
        // Apply mail config once on boot — replaces the old per-instance configMailDriver().
        $this->app->make(MailConfigApplier::class)->apply();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mail-sender');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/mail-sender.php' => config_path('mail-sender.php'),
            ], 'mail-sender-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/mail-sender'),
            ], 'mail-sender-views');
        }
    }
}
