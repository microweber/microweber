<?php

declare(strict_types=1);

namespace Arcanedev\Support\Providers\Concerns;

/**
 * Trait     HasFactories
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasFactories
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the migrations path.
     *
     * @return string
     */
    protected function getFactoriesPath(): string
    {
        return $this->getBasePath().DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'factories';
    }

    /**
     * Publish the factories.
     *
     * @param  string|null  $path
     */
    protected function publishFactories(?string $path = null): void
    {
        $this->publishes([
            $this->getFactoriesPath() => $path ?: database_path('factories'),
        ], $this->getPublishedTags('factories'));
    }

    /**
     * Load the factories.
     */
    protected function loadFactories(): void
    {
        $this->loadFactoriesFrom($this->getFactoriesPath());
    }
}
