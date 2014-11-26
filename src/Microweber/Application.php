<?php

namespace Microweber;

/**
 * @property \Microweber\Adapters $adapters
 * @property \Microweber\Url $url
 * @property \Microweber\Content $content
 * @property \Microweber\Category $category
 * @property \Microweber\Media $media
 * @property \Microweber\Shop $shop
 * @property \Microweber\Option $option
 * @property \Microweber\Cache $cache
 * @property \Microweber\User $user
 * @property \Microweber\Module $module
 * @property \Microweber\Providers\Database $database
 * @property \Microweber\Notifications $notifications
 * @property \Microweber\Layouts $layouts
 * @property \Microweber\Log $log
 * @property \Microweber\Parser $parser
 * @property \Microweber\Format $format
 * @property \Microweber\Fields $fields
 * @property \Microweber\Http $http
 * @property \Microweber\Template $template
 * @property \Microweber\Ui $ui
 * @property \Microweber\Orm $orm
 * @property \Microweber\Event $event
 * @property \Microweber\Validator $validator
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