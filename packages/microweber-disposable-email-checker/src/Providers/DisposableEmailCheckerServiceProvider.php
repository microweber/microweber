<?php

declare(strict_types=1);

namespace MicroweberPackages\DisposableEmailChecker\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\DisposableEmailChecker\Contracts\DisposableEmailCheckerContract;
use MicroweberPackages\DisposableEmailChecker\Http\Middleware\BlockDisposableEmail;
use MicroweberPackages\DisposableEmailChecker\Services\DisposableEmailChecker;
use MicroweberPackages\DisposableEmailChecker\Validators\NotDisposableEmailValidator;

class DisposableEmailCheckerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/disposable-email-checker.php', 'disposable-email-checker');

        $this->app->singleton(DisposableEmailCheckerContract::class, DisposableEmailChecker::class);

        $this->app->alias(DisposableEmailCheckerContract::class, 'disposable_email_checker');
    }

    public function boot(): void
    {
        // Publish config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/disposable-email-checker.php' => $this->app->configPath('disposable-email-checker.php'),
            ], 'disposable-email-checker-config');
        }

        // Register the "not_disposable_email" validation rule
        Validator::extend(
            'not_disposable_email',
            NotDisposableEmailValidator::class . '@validate',
            'You cannot register with an email from a disposable email provider.'
        );

        // Register the middleware alias
        $router = $this->app->make('router');
        $router->aliasMiddleware('block_disposable_email', BlockDisposableEmail::class);
    }
}