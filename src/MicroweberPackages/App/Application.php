<?php

namespace MicroweberPackages;

/**
 * Application class.
 *
 * Class that loads other classes
 *
 * @category Application
 * @desc
 *
 * @property \MicroweberPackages\UrlManager                    $url_manager
 * @property \MicroweberPackages\Utils\Format                            $format
 * @property \MicroweberPackages\ContentManager                $content_manager
 * @property \MicroweberPackages\CategoryManager               $category_manager
 * @property \MicroweberPackages\MenuManager                   $menu_manager
 * @property \MicroweberPackages\MediaManager                  $media_manager
 * @property \MicroweberPackages\ShopManager                   $shop_manager
 * @property \MicroweberPackages\CartManager              $cart_manager
 * @property \MicroweberPackages\OrderManager             $order_manager
 * @property \MicroweberPackages\TaxManager               $tax_manager
 * @property \MicroweberPackages\CheckoutManager          $checkout_manager
 * @property \MicroweberPackages\ClientsManager           $clients_manager
 * @property \MicroweberPackages\InvoicesManager          $invoices_manager
 * @property \MicroweberPackages\OptionManager                 $option_manager
 * @property \MicroweberPackages\CacheManager                  $cache_manager
 * @property \MicroweberPackages\UserManager                   $user_manager
 * @property \MicroweberPackages\Modules                       $modules
 * @property \MicroweberPackages\DatabaseManager               $database_manager
 * @property \MicroweberPackages\NotificationsManager          $notifications_manager
 * @property \MicroweberPackages\LayoutsManager                $layouts_manager
 * @property \MicroweberPackages\LogManager                    $log_manager
 * @property \MicroweberPackages\FieldsManager                 $fields_manager
 * @property \MicroweberPackages\Template                      $template
 * @property \MicroweberPackages\Event                         $event_manager
 * @property \MicroweberPackages\ConfigurationManager          $config_manager
 * @property \MicroweberPackages\TemplateManager               $template_manager
 * @property \MicroweberPackages\CaptchaManager               $captcha_manager
 * @property \MicroweberPackages\Ui                            $ui
 * @property \MicroweberPackages\Utils\Http                              $http
 * @property \MicroweberPackages\FormsManager                  $forms_manager
 * @property \MicroweberPackages\DataFieldsManager     $data_fields_manager
 * @property \MicroweberPackages\TagsManager           $tags_manager
 * @property \MicroweberPackages\AttributesManager     $attributes_manager
 * @property \MicroweberPackages\Helpers\Lang                  $lang_helper
 * @property \MicroweberPackages\PermalinkManager              $permalink_manager

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
