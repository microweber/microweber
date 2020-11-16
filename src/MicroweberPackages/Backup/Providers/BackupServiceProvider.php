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

namespace MicroweberPackages\Backup\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Backup\BackupManager;

class BackupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Backup\BackupManager    $backup_manager
         */
        $this->app->singleton('backup_manager', function ($app) {
            return new BackupManager();
        });

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}
