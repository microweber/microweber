<?php

declare(strict_types=1);

namespace MicroweberPackages\Package;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Abstract base service provider for all Microweber packages.
 *
 * Extends Spatie's {@see PackageServiceProvider} so every package gets a
 * consistent configurePackage() lifecycle (config, views, migrations, routes,
 * assets, …) and can be installed in a standalone Laravel application.
 *
 * This class is abstract and is only meant to be extended by other packages —
 * it is never registered on its own.
 *
 * Lifecycle hooks (preferred over overriding register/boot):
 * - {@see configurePackage()} — required; set package name and resources
 * - {@see configureModule()} — optional; CMS module registry (Live Edit / Filament)
 * - {@see packageRegistered()} — after package configs are registered
 * - {@see packageBooted()} — after package resources are booted
 *
 * If you override register() or boot(), always call the parent method first.
 */
abstract class MicroweberPackageServiceProvider extends PackageServiceProvider
{
    /**
     * Optional CMS module descriptor configured via {@see configureModule()}.
     */
    protected ?ModulePackage $module = null;

    /**
     * When true, {@see configureModule()} must set a non-empty module type.
     * Module / template base providers enable this; regular packages leave it off.
     */
    protected bool $requiresModuleType = false;

    /**
     * Configure the Spatie package (name, config, views, migrations, …).
     *
     * Every concrete package must implement this and call `$package->name(...)`.
     */
    abstract public function configurePackage(Package $package): void;

    /**
     * Optionally configure CMS module integrations for this package.
     *
     * Override in module/template providers. Default is a no-op so regular
     * packages do not need a module type.
     */
    public function configureModule(ModulePackage $module): void
    {
        // no-op for regular packages
    }

    public function newModulePackage(): ModulePackage
    {
        return new ModulePackage();
    }

    /**
     * @return $this
     */
    public function register()
    {
        parent::register();

        $module = $this->newModulePackage();
        $this->configureModule($module);
        $this->module = $module;

        if ($this->requiresModuleType && $module->type === '') {
            throw PackageManagerException::moduleTypeIsRequired();
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function boot()
    {
        parent::boot();

        return $this;
    }

    /**
     * Whether this provider is using the Microweber package loader.
     *
     * Useful for wiring tests across the monorepo.
     */
    public function usesMicroweberPackageLoader(): bool
    {
        return true;
    }

    /**
     * Configured Spatie package instance (available after register()).
     */
    public function getPackage(): Package
    {
        return $this->package;
    }

    /**
     * Configured module package instance (available after register()), if any.
     */
    public function getModulePackage(): ?ModulePackage
    {
        return $this->module;
    }
}
