<?php

namespace MicroweberPackages\LaravelModules\Contracts;

interface LaravelModulesCacheRepositoryContract
{

    public function set($name, $module);

    public function get($name);

    public function all();

    public function flush();
}
