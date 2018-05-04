<?php

namespace Microweber;

/**
 * Application class.
 *
 * Class that loads other classes
 *
 * @category Application
 * @desc
 *
 * @property \Microweber\Providers\UrlManager                    $url_manager
 * @property \Microweber\Utils\Format                            $format
 * @property \Microweber\Providers\ContentManager                $content_manager
 * @property \Microweber\Providers\CategoryManager               $category_manager
 * @property \Microweber\Providers\MediaManager                  $media_manager
 * @property \Microweber\Providers\ShopManager                   $shop_manager
 * @property \Microweber\Providers\Shop\CartManager              $cart_manager
 * @property \Microweber\Providers\Shop\OrderManager             $order_manager
 * @property \Microweber\Providers\Shop\TaxManager               $tax_manager
 * @property \Microweber\Providers\Shop\CheckoutManager          $checkout_manager
 * @property \Microweber\Providers\Shop\ClientsManager           $clients_manager
 * @property \Microweber\Providers\Shop\InvoicesManager          $invoices_manager
 * @property \Microweber\Providers\OptionManager                 $option_manager
 * @property \Microweber\Providers\CacheManager                  $cache_manager
 * @property \Microweber\Providers\UserManager                   $user_manager
 * @property \Microweber\Providers\Modules                       $modules
 * @property \Microweber\Providers\DatabaseManager               $database_manager
 * @property \Microweber\Providers\NotificationsManager          $notifications_manager
 * @property \Microweber\Providers\LayoutsManager                $layouts_manager
 * @property \Microweber\Providers\LogManager                    $log_manager
 * @property \Microweber\Providers\FieldsManager                 $fields_manager
 * @property \Microweber\Providers\Template                      $template
 * @property \Microweber\Providers\Event                         $event_manager
 * @property \Microweber\Providers\ConfigurationManager          $config_manager
 * @property \Microweber\Providers\TemplateManager               $template_manager
 * @property \Microweber\Providers\Ui                            $ui
 * @property \Microweber\Utils\Captcha                           $captcha
 * @property \Microweber\Providers\FormsManager                  $forms_manager
 * @property \Microweber\Providers\Content\DataFieldsManager     $data_fields_manager
 * @property \Microweber\Providers\Content\TagsManager           $tags_manager
 * @property \Microweber\Providers\Content\AttributesManager     $attributes_manager
 * @property \Microweber\Providers\Helpers\Lang                  $lang_helper

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
        if (self::$instance == null) {
            self::$instance = app();
        }

        return self::$instance;
    }

    public function make($property)
    {
        return app()->make($property);
    }

    public function __get($property)
    {
        return $this->make($property);
    }
}
