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

use Illuminate\Mail\SendQueuedMailable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Module\Module;
use MicroweberPackages\Module\ModuleManager;
use MicroweberPackages\Notification\Http\Controllers\Admin\NotificationController;
use MicroweberPackages\Notification\Mail\SimpleHtmlEmail;
use MicroweberPackages\Option\Facades\Option;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        if (mw_is_installed()) {
            $this->_configMailSender();
        }

      View::addNamespace('notification', dirname(__DIR__).'/resources/views');

       $this->loadMigrationsFrom(dirname(__DIR__) . '/migrations/');
       $this->loadRoutesFrom(dirname(__DIR__) . '/routes/admin.php');
    }


    private function _configMailSender(){

        // SMTP SETTINGS
        $smtpHost = Option::getValue('smtp_host', 'email');
        $smtpPort = Option::getValue('smtp_port', 'email');
        $smtpUsername = Option::getValue('smtp_username', 'email');
        $smtpPassword = Option::getValue('smtp_password', 'email');
        $smtpAuth = Option::getValue('smtp_auth', 'email');
        $smtpSecure = Option::getValue('smtp_secure', 'email');

        // Type transport
        $emailTransport = Option::getValue('email_transport', 'email');
        if($emailTransport == 'config'){
            // use values from config/mail.php
            return;
        }

        // From Name
        $emailFromName = get_email_from_name();
        if (!$emailFromName) {
            $emailFromName = getenv('USERNAME');
        }

        // Email From
        $emailFrom = get_email_from();
        if (!$emailFrom) {
            $hostname = mw()->url_manager->hostname();
            if ($emailFromName != '') {
                $emailFrom = ($emailFromName) . '@' .$hostname;
            } else {
                $emailFrom = 'noreply@' . $hostname;
            }
            $emailFrom = str_replace(' ', '-', $emailFrom);
        }

        //Set config mails
        Config::set('mail.from.name', $emailFromName);
        Config::set('mail.from.address', $emailFrom);

        // Set mai credentinals
        Config::set('mail.username', $smtpUsername);
        Config::set('mail.password', $smtpPassword);


       // Set mail hots
        Config::set('mail.host', $smtpHost);
        Config::set('mail.port', $smtpPort);
        Config::set('mail.encryption', $smtpAuth);
        Config::set('mail.transport', $emailTransport);
        if ($emailTransport == 'gmail') {
            Config::set('mail.host', 'smtp.gmail.com');
            Config::set('mail.port', 587);
            Config::set('mail.encryption', 'tls');
            Config::set('mail.transport', 'smtp');
        }
        if ($emailTransport == 'cpanel') {
            Config::set('mail.port', 587);
            Config::set('mail.encryption', 'tls');
            Config::set('mail.transport', 'smtp');

        }
        if ($emailTransport == 'plesk') {
            Config::set('mail.port', 25);
            Config::set('mail.encryption', 'tls');
            Config::set('mail.transport', 'smtp');

        }

        if ($emailTransport == 'php') {
            Config::set('mail.transport', 'mail');
        }


    }
}

