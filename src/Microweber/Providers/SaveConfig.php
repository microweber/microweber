<?php
namespace Microweber\Providers;

use Illuminate\Config\Repository as BaseConfig;
use Symfony\Component\Finder\Finder;

use \File;

class SaveConfig extends BaseConfig
{
    protected $beforeSave = [];
    protected $changed_keys = array();
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
        $items = (array)$app->make('config');
        $items = end($items);
        parent::__construct($items);
    }

    public function set($key, $val = null)
    {
        $this->changed_keys[$key] = $val;
        return parent::set($key, $val);
    }

    public function get($key, $val = null)
    {
        if (isset($this->changed_keys[$key])) {
            return $this->changed_keys[$key];
        }
        return parent::get($key, $val);
    }

    public function save()
    {
        foreach ($this->items as $collection => $items) {
            $configFiles = Finder::create()->files()->name($collection.'.php')->in($this->app->configPath());
            foreach($configFiles as $configFile)
            {
                //File::put($configFile->getRealpath(), var_export($items, true));
            }
            //dd($items, $env, $group, $namespace);
        }
    }

    protected function callBeforeSave($namespace, $group, $items)
    {
        $callback = $this->beforeSave[$namespace];
        return call_user_func($callback, $this, $group, $items);
    }

    public function beforeSaving($namespace, Closure $callback)
    {
        $this->beforeSave[$namespace] = $callback;
    }

    protected function parseCollection($collection)
    {
        list($namespace, $group) = explode('::', $collection);
        if ($namespace == '*') $namespace = null;
        return [$namespace, $group];
    }

    public function getBeforeSaveCallbacks()
    {
        return $this->beforeSave;
    }
}
