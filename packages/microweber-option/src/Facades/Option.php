<?php
namespace MicroweberPackages\Option\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \MicroweberPackages\Option\Models\Option
 */
class Option extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'option';
    }
}