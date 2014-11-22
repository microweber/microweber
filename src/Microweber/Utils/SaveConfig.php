<?php
namespace Microweber\Utils;

//namespace Tjbp\SaveConfig;

use Illuminate\Config\Repository;
use Illuminate\Config\LoaderInterface;

class SaveConfig extends Repository
{
    /**
     * The before save callbacks for namespaces.
     *
     * @var array
     */
    protected $beforeSave = [];

    /**
     * Create a new configuration repository.
     *
     * @param  \Illuminate\Config\LoaderInterface  $loader
     * @param  string  $environment
     * @return void
     */
    public function __construct(LoaderInterface $loader, $environment)
    {


        $loader = new FileLoader($loader->getFilesystem(), app_path() . '/config');

        parent::__construct($loader, $environment);
    }

    /**
     * Save the configuration.
     *
     * @return void
     */
    public function save()
    {
        $env = $this->environment;

        foreach ($this->items as $collection => $items) {
            list($namespace, $group) = $this->parseCollection($collection);

            if (isset($this->beforeSave[$namespace]))
            {
                $items = $this->callBeforeSave($namespace, $group, $items);
            }

            $this->loader->save($items, $env, $group, $namespace);
        }
    }

    /**
     * Call the before save callback for a namespace.
     *
     * @param  string  $namespace
     * @param  string  $group
     * @param  array   $items
     * @return array
     */
    protected function callBeforeSave($namespace, $group, $items)
    {
        $callback = $this->beforeSave[$namespace];

        return call_user_func($callback, $this, $group, $items);
    }

    /**
     * Register a before save callback for a given namespace.
     *
     * @param  string   $namespace
     * @param  \Closure  $callback
     * @return void
     */
    public function beforeSaving($namespace, Closure $callback)
    {
        $this->beforeSave[$namespace] = $callback;
    }

    /**
     * Parse the collection identifier.
     *
     * @param  string  $collection
     * @return array
     */
    protected function parseCollection($collection)
    {
        list($namespace, $group) = explode('::', $collection);

        if ($namespace == '*') $namespace = null;

        return [$namespace, $group];
    }

    /**
     * Get the before save callback array.
     *
     * @return array
     */
    public function getBeforeSaveCallbacks()
    {
        return $this->beforeSave;
    }
}