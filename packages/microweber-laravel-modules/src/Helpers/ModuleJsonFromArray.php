<?php

namespace MicroweberPackages\LaravelModules\Helpers;

use Illuminate\Filesystem\Filesystem;
use Nwidart\Modules\Collection;
use Nwidart\Modules\Exceptions\InvalidJsonException;

/**
 * @phpstan-consistent-constructor
 */
class ModuleJsonFromArray extends \Nwidart\Modules\Json
{
    /**
     * The file path.
     */
    protected string $path;

    /**
     * The laravel filesystem instance.
     */
    protected Filesystem $filesystem;

    /**
     * The attributes collection.
     */
    protected ?\Illuminate\Support\Collection $attributes;

    public function __construct($path, ?Filesystem $filesystem = null, $attributes = [])
    {


        $this->path = (string)$path;
        $this->filesystem = $filesystem ?: new Filesystem();

        if (!empty($attributes)) {

            $this->attributes = Collection::make($attributes);
        } else {
            $this->attributes = Collection::make($this->getAttributes());
        }


    }

    public static function make($path, ?Filesystem $filesystem = null, $attributes = []): static
    {
        return new static($path, $filesystem, $attributes);
    }

    public function getAttributes(): array
    {
        if ($this->attributes) {
            return $this->attributes->toArray();
        }


        if (config('modules.cache.enabled') === false) {
            return $this->decodeContents();
        }

        return app('cache')->store(config('modules.cache.driver'))->remember($this->getPath(), config('modules.cache.lifetime'), function () {
            return $this->decodeContents();
        });
    }
}
