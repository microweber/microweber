<?php

namespace MicroweberPackages\LaravelModules\Helpers;

use Illuminate\Filesystem\Filesystem;
use Nwidart\Modules\Collection;
use Nwidart\Modules\Exceptions\InvalidJsonException;

class ModuleJsonFromArray extends \Nwidart\Modules\Json
{
    /**
     * The file path.
     *
     * @var string
     */
    protected $path;

    /**
     * The laravel filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The attributes collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $attributes;

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

    public static function make($path, ?Filesystem $filesystem = null, $attributes = [])
    {
        return new static($path, $filesystem, $attributes);
    }

    public function getAttributes()
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
