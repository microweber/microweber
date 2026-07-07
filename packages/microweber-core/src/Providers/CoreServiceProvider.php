<?php

namespace MicroweberPackages\Core\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Backward-compatibility shim.
 *
 * The real provider lives at {@see \MicroweberPackages\Core\CoreServiceProvider}.
 * This class exists so that existing code that imports
 * `MicroweberPackages\Core\Providers\CoreServiceProvider` still works.
 *
 * It ONLY loads routes (the original behaviour of this class before the
 * migration to explicit package loading).  The parent CoreServiceProvider
 * registered via `bootstrap/providers.php` handles all package loading.
 *
 * @deprecated Use \MicroweberPackages\Core\CoreServiceProvider directly.
 */
class CoreServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        if ($this->app->environment('testing')) {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/tests.php');
        }
    }
}