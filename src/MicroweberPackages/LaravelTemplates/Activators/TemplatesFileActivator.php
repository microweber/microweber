<?php

namespace MicroweberPackages\LaravelTemplates\Activators;

use Illuminate\Config\Repository as Config;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use MicroweberPackages\LaravelModules\Activators\LaravelModulesFileActivator;
use MicroweberPackages\LaravelTemplates\Contracts\TemplateActivatorInterface;

class TemplatesFileActivator extends LaravelModulesFileActivator implements TemplateActivatorInterface
{
    public $configPrefix = 'templates';
}
