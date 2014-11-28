<?php

namespace Microweber\Providers;

use Microweber\Utils\Adapters\Cache\LaravelCache;

use Content;
use Menu;

/**
 * Content class is used to get and save content in the database.
 *
 * @package Content
 * @category Content
 * @desc  These functions will allow you to get and save content in the database.
 *
 */
class MenuManager
{



    public $app = null;

    /**
     *  Boolean that indicates the usage of cache while making queries
     *
     * @var $no_cache
     */
    public $no_cache = false;


    function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
    }





}

