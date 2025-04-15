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

namespace Modules\Backup\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;

use Illuminate\Contracts\Support\DeferrableProvider;

use Modules\Backup\Filament\Resources\BackupResource;
use Modules\Settings\Filament\Pages\Settings;


class BackupServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Backup';

    protected string $moduleNameLower = 'backup';

    public function boot()
    {
        Config::set('filesystems.disks.backup', [
            'driver' => 'local',
            'root'   => storage_path() . '/backup_content/' . \App::environment() . '/',
            'visibility' => 'private',
        ]);
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

//        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'migrations/');

        $this->mergeConfigFrom(
            __DIR__.'/../config/backup.php', 'backup'
        );

        FilamentRegistry::registerResource(BackupResource::class);
        FilamentRegistry::registerResource(BackupResource::class, Settings::class);
//        FilamentRegistry::registerPage(CreateBackup::class);
    }



}
