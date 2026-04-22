<?php

namespace Modules\WordPressMigration\Providers;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;

class WordPressMigrationServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'WordPressMigration';

    protected string $moduleNameLower = 'wordpressmigration';

    public function register(): void
    {
        $this->registerConfig();
    }
}
