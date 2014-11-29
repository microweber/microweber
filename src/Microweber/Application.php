<?php

namespace Microweber;

    /**
     * Application class
     *
     * Class that loads other classes
     *
     * @package Application
     * @category Application
     * @desc
     */
/**
 * @property \Microweber\Utils\Url $url
 * @property \Microweber\Utils\Format $format
 * @property \Microweber\Providers\ContentManager $content_manager
 * @property \Microweber\Providers\CategoryManager $category_manager
 * @property \Microweber\Providers\MediaManager $media_manager
 * @property \Microweber\Providers\ShopManager $shop_manager
 * @property \Microweber\Providers\OptionManager $option_manager
 * @property \Microweber\Providers\CacheManager $cache_manager
 * @property \Microweber\Providers\UserManager $user_manager
 * @property \Microweber\Providers\Modules $modules
 * @property \Microweber\Providers\DatabaseManager $database_manager
 * @property \Microweber\Providers\NotificationsManager $notifications_manager
 * @property \Microweber\Providers\LayoutsManager $layouts_manager
 * @property \Microweber\Providers\LogManager $log_manager
 * @property \Microweber\Providers\FieldsManager $fields_manager
 * @property \Microweber\Providers\Template $template
 * @property \Microweber\Providers\Event $event_manager
 * @property \Microweber\Providers\ConfigurationManager $config_manager
 */
class Application
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
}