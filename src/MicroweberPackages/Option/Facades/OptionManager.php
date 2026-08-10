<?php
namespace MicroweberPackages\Option\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * OptionManager facade — greppable public API for option manager.
 *
 * @see \MicroweberPackages\Option\OptionManager
 * @mixin \MicroweberPackages\Option\OptionManager
 */
class OptionManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'option_manager';
    }
}
