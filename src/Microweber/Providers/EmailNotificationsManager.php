<?php

namespace Microweber\Providers;


class EmailNotificationsManager {


    /** @var \Microweber\Application */
    public $app;

    function __construct($app = null) {

        if (is_object($app)){
            $this->app = $app;
        } else {
            $this->app = mw();
        }

    }


}