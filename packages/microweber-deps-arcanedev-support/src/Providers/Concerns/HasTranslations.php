<?php

declare(strict_types=1);

namespace Arcanedev\Support\Providers\Concerns;

/**
 * Trait     HasTranslations
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasTranslations
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the translations' folder name.
     */
    protected function getTranslationsFolderName(): string
    {
        return 'translations';
    }

    /**
     * Get the translations' path.
     */
    protected function getTranslationsPath(): string
    {
        return $this->getBasePath().DIRECTORY_SEPARATOR.$this->getTranslationsFolderName();
    }

    /**
     * Get the destination views path.
     */
    protected function getTranslationsDestinationPath(): string
    {
        return $this->app->langPath(
            'vendor'.DIRECTORY_SEPARATOR.$this->getPackageName()
        );
    }

    /**
     * Publish the translations.
     */
    protected function publishTranslations(?string $path = null): void
    {
        $this->publishes([
            $this->getTranslationsPath() => $path ?: $this->getTranslationsDestinationPath(),
        ], $this->getPublishedTags('translations'));
    }

    /**
     * Load the translations files.
     */
    protected function loadTranslations(): void
    {
        $packagePath = $this->getTranslationsPath();
        $vendorPath = $this->getTranslationsDestinationPath();

        $this->loadTranslationsFrom($packagePath, $this->getPackageName());
        $this->loadJsonTranslationsFrom(file_exists($vendorPath) ? $vendorPath : $packagePath);
    }
}
