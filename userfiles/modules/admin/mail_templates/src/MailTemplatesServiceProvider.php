<?php

namespace MicroweberPackages\Modules\MailTemplates;

use Illuminate\Support\ServiceProvider;

class MailTemplatesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('mail_templates', function ($app) {
            return new MailTemplates();
        });
    }
}
