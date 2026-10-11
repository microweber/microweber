<?php

namespace MicroweberPackages\FormBuilder\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\FormBuilder\FieldTypeRegistry;
use MicroweberPackages\FormBuilder\FormBuilder;
use MicroweberPackages\FormBuilder\Resolvers\FilamentFieldResolver;
use MicroweberPackages\FormBuilder\Resolvers\MwCustomFieldResolver;

class FormBuilderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register the FieldTypeRegistry as a singleton
        $this->app->singleton(FieldTypeRegistry::class, function () {
            return new FieldTypeRegistry();
        });

        // Register the FormBuilder class
        $this->app->singleton(FormBuilder::class, function () {
            return new FormBuilder();
        });
    }

    public function boot(): void
    {
        $registry = $this->app->make(FieldTypeRegistry::class);

        // Register default Filament field types
        FilamentFieldResolver::register($registry);

        // Register Microweber custom field types
        MwCustomFieldResolver::register($registry);
    }
}
