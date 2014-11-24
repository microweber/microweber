<?php

namespace Weber\Providers;

use Illuminate\Filesystem\Filesystem;

class FileLoader extends \Illuminate\Config\FileLoader
{
    /**
     * Save the given configuration group.
     *
     * @param  array   $items
     * @param  string  $environment
     * @param  string  $group
     * @param  string  $namespace
     * @return array
     */
    public function save($items, $environment, $group, $namespace = null)
    {
        // First we'll get the root configuration path for the environment which is
        // where all of the configuration files live for that namespace, as well
        // as any environment folders with their specific configuration items.
        $path = $this->getPath($namespace);



        if (is_null($path))
        {
            return;
        }

        // First we'll get the main configuration file for the groups. Once we have
        // that we can check for any environment specific files, which will get
        // merged on top of the main arrays to make the environments cascade.
        $file = (!$environment || ($environment == 'production'))
            ? "{$path}/{$group}.php"
            : "{$path}/{$environment}/{$group}.php";

        $this->files->put($file, '<?php return ' . var_export($items, true) . ';');
    }
}
