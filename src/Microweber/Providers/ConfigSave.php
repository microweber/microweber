<?php
namespace Microweber\Providers;

use Illuminate\Config\Repository;
use Symfony\Component\Finder\Finder;
use \File;

class ConfigSave extends Repository
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
        $this->init();
    }

    private function init()
    {
        $this->items = array();
        foreach (Finder::create()->files()->name('*.php')->in($this->app->configPath()) as $file) {
            $path = $file->getRelativePath();
            if ($path && $path != $this->app->environment())
                continue;
            $key = basename($file->getRealPath(), '.php');
            $this->set($key, require $file->getRealPath());
        }
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
        // Aggregating files array from changed keys
        $aggr = array();
        foreach ($this->changed_keys as $key => $value) {
            array_set($aggr, $key, $value);
        }

        $allow_in_cli = array('database', 'microweber');
        // Preparing data
        foreach ($aggr as $file => $items) {
            $path = $this->app->configPath() . '/' . $this->app->environment() . '/';

            if ($this->app->runningInConsole()) {
                if (!in_array($file, $allow_in_cli)) {
                    continue;
                }
            }

            if (!file_exists($path)) {
                File::makeDirectory($path);
            }


            $path .= $file . '.php';

            $code = '<?php return ' . var_export($this->items[$file], true) . ';';

            // Storing data
            File::put($path, $code);
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
