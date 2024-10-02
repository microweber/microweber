<?php

namespace MicroweberPackages\LaravelModules\Helpers;

use MicroweberPackages\LaravelModules\LaravelModule;
use MicroweberPackages\LaravelTemplates\LaravelTemplate;
use MicroweberPackages\Utils\System\ClassLoader;
use Nwidart\Modules\Json;

class SplClassLoader
{
    public $namespaces = [];
    public $notFoundClasses = [];
    public $foundClasses = [];


    public static function addNamespace($namespace, $path)
    {

        $loader = new self();
        $loader->namespaces[$namespace][] = $path;
        spl_autoload_register([$loader, 'autoloadClass']);
    }
    public function autoloadClass($class): bool
    {


        if (empty($this->namespaces) || isset($this->notFoundClasses[$class])) {
            return false;
        }

        if (isset($this->foundClasses[$class])) {
            require_once($this->foundClasses[$class]);
            return true;
        }

        $segments = explode('\\', $class);
        $originalClass = $class;

        while (!empty($segments)) {
            $namespace = implode('\\', $segments);
            if (isset($this->namespaces[$namespace])) {
                foreach ($this->namespaces[$namespace] as $path) {
                    $relativeClass = str_replace($namespace . '\\', '', $originalClass);
                    $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass);
                    $file = rtrim($path, '\\/') . DIRECTORY_SEPARATOR . rtrim($classFile, '\\/') . '.php';
                    if (is_file($file)) {
                        $this->foundClasses[$class] = $file;
                        require_once($file);
                        return true;
                    }
                }
            }
            array_pop($segments);
        }

        $this->notFoundClasses[$class] = true;
        return false;
    }
}
