<?php

namespace MicroweberPackages\App;

/**
 * Application class.
 *
 * Class that loads other classes
 *
 * @category Application
 * @desc
 *
 * @property \MicroweberPackages\Providers\UrlManager                    $url_manager
 * @property \MicroweberPackages\Utils\Format                            $format
 * @property \MicroweberPackages\Providers\ContentManager                $content_manager
 * @property \MicroweberPackages\Providers\CategoryManager               $category_manager
 * @property \MicroweberPackages\Providers\MenuManager                   $menu_manager
 * @property \MicroweberPackages\Providers\MediaManager                  $media_manager
 * @property \MicroweberPackages\Providers\ShopManager                   $shop_manager
 * @property \MicroweberPackages\Providers\Shop\CartManager              $cart_manager
 * @property \MicroweberPackages\Providers\Shop\OrderManager             $order_manager
 * @property \MicroweberPackages\Providers\Shop\TaxManager               $tax_manager
 * @property \MicroweberPackages\Providers\Shop\CheckoutManager          $checkout_manager
 * @property \MicroweberPackages\Providers\Shop\ClientsManager           $clients_manager
 * @property \MicroweberPackages\Providers\Shop\InvoicesManager          $invoices_manager
 * @property \MicroweberPackages\Providers\OptionManager                 $option_manager
 * @property \MicroweberPackages\Providers\CacheManager                  $cache_manager
 * @property \MicroweberPackages\Providers\UserManager                   $user_manager
 * @property \MicroweberPackages\Providers\Modules                       $modules
 * @property \MicroweberPackages\Providers\DatabaseManager               $database_manager
 * @property \MicroweberPackages\Providers\NotificationsManager          $notifications_manager
 * @property \MicroweberPackages\Providers\LayoutsManager                $layouts_manager
 * @property \MicroweberPackages\Providers\LogManager                    $log_manager
 * @property \MicroweberPackages\Providers\FieldsManager                 $fields_manager
 * @property \MicroweberPackages\Providers\Template                      $template
 * @property \MicroweberPackages\Providers\ConfigurationManager          $config_manager
 * @property \MicroweberPackages\Providers\TemplateManager               $template_manager
 * @property \MicroweberPackages\Providers\CaptchaManager               $captcha_manager
 * @property \MicroweberPackages\Providers\Ui                            $ui
 * @property \MicroweberPackages\Utils\Http                              $http
 * @property \MicroweberPackages\Providers\FormsManager                  $forms_manager
 * @property \MicroweberPackages\Providers\Content\DataFieldsManager     $data_fields_manager
 * @property \MicroweberPackages\Providers\Content\TagsManager           $tags_manager
 * @property \MicroweberPackages\Providers\Content\AttributesManager     $attributes_manager
 * @property \MicroweberPackages\Providers\Helpers\Lang                  $lang_helper

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
