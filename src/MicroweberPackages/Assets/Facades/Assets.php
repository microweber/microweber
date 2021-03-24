<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/11/2021
 * Time: 3:53 PM
 */

namespace MicroweberPackages\Assets\Facades;


use Illuminate\Support\Facades\Facade;




/**
 * @mixin \MicroweberPackages\Assets\Assets
 */
class Assets extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'assets';
    }
}
