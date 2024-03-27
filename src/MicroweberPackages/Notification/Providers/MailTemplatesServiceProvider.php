<?php

namespace MicroweberPackages\Notification\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Notification\Mail\MailTemplates;

class MailTemplatesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('mail_templates', function ($app) {
            return new MailTemplates();
        });
    }
}
