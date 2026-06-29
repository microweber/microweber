<?php

namespace MicroweberPackages\Translation\Providers;

use Illuminate\Support\Facades\Lang;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Repositories\TranslationKeyRepository;
use MicroweberPackages\Translation\Providers\TranslationServiceProvider as PackageTranslationServiceProvider;

/**
 * CMS-specific translation service provider.
 *
 * Extends the standalone package provider and adds CMS-specific bindings
 * (repository manager, legacy routes, etc.)
 */
class CmsTranslationServiceProvider extends PackageTranslationServiceProvider
{
    public function boot()
    {
        // Call parent boot (registers loader, migrations, terminating callback)
        parent::boot();

        // Load CMS-specific routes (package routes/ dir, next to api.php)
        $this->loadRoutesFrom(dirname(__DIR__, 2) . '/routes/web.php');
    }

    public function register()
    {
        parent::register();

        // CMS-specific: register repository manager binding
        if (class_exists(\MicroweberPackages\Repository\RepositoryManager::class)) {
            $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
                $repositoryManager->extend(TranslationKey::class, function () {
                    return new TranslationKeyRepository();
                });
            });

            $this->app->bind('translation_key_repostory', function () {
                return $this->app->repository_manager->driver(TranslationKey::class);
            });
        }
    }

    /**
     * Override: in CMS context, use mw_is_installed() check.
     */
    protected function isDatabaseReady(): bool
    {
        if (function_exists('mw_is_installed')) {
            return mw_is_installed();
        }

        return parent::isDatabaseReady();
    }
}
