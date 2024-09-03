<?php

namespace MicroweberPackages\LaravelTemplates\Providers;

use MicroweberPackages\LaravelTemplates\Contracts\TemplatesRepositoryInterface;
use MicroweberPackages\LaravelTemplates\Repositories\LaravelTemplatesFileRepository;
use Nwidart\Modules\Contracts\RepositoryInterface;
use Nwidart\Modules\Providers\BootstrapServiceProvider;

class TemplatesContractsServiceProvider extends BootstrapServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TemplatesRepositoryInterface::class, LaravelTemplatesFileRepository::class);
    }
}
