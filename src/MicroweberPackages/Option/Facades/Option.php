<?php
namespace MicroweberPackages\Option\Facades;

use Illuminate\Support\Facades\Facade;

class Option extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'option';
    }
}