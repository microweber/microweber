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
        // Aggregating files array from changed keys
        $aggr = array();
        foreach ($this->changed_keys as $key => $value) {
            array_set($aggr, $key, $value);
        }

        // Preparing data
        foreach ($aggr as $file => $items) {
            $path = $this->app->configPath() .'/'. $this->app->environment() .'/';
            if(!file_exists($path))
                File::makeDirectory($path);
            $path.= $file .'.php';

            $code = '<?php return '. var_export($this->items[$file], true) .';';

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
