<?php

namespace Weber;

/**
 * @property \Weber\Adapters $adapters
 * @property \Weber\Url $url
 * @property \Weber\Content $content
 * @property \Weber\Category $category
 * @property \Weber\Media $media
 * @property \Weber\Shop $shop
 * @property \Weber\Option $option
 * @property \Weber\Cache $cache
 * @property \Weber\User $user
 * @property \Weber\Module $module
 * @property \Weber\Providers\Database $database
 * @property \Weber\Notifications $notifications
 * @property \Weber\Layouts $layouts
 * @property \Weber\Log $log
 * @property \Weber\Parser $parser
 * @property \Weber\Format $format
 * @property \Weber\Fields $fields
 * @property \Weber\Http $http
 * @property \Weber\Template $template
 * @property \Weber\Ui $ui
 * @property \Weber\Orm $orm
 * @property \Weber\Event $event
 * @property \Weber\Validator $validator
 */
Abstract class Application
{

    public static $instance;
    public function __construct($params = null)
    {
        $instance = app();
        self::$instance = $instance;
        return self::$instance;

    }

    public static function getInstance($params = null)
    {
        //if (self::$instance == NULL) self::$instance = new Application($params);
        if (self::$instance == NULL) self::$instance = app();
        return self::$instance;
    }

    public function make($class)
    {
        return app()->make($class);
    }

    public function __get($property)
    {
        return $this->make($property);
    }
//
//    function error($e, $f = false, $l = false)
//    {
//        if (!$this->url->is_ajax()) {
//            $err = "Error: " . $e;
//            if ($f != false) {
//                $err = $err . "\nFile: " . $f;
//            }
//            if ($f != false) {
//                $err = $err . "\nLine: " . $l;
//            }
//            throw new \Exception($err);
//        } else {
//            die($e);
//        }
//    }
}