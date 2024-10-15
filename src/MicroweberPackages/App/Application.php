<?php

namespace MicroweberPackages\App;

use MicroweberPackages\App\Managers\CacheManager;
use MicroweberPackages\App\Managers\ConfigurationManager;
use MicroweberPackages\App\Managers\Helpers\Lang;
use MicroweberPackages\App\Managers\LogManager;
use MicroweberPackages\App\Managers\NotificationsManager;
use MicroweberPackages\App\Managers\PermalinkManager;
use MicroweberPackages\App\Managers\Ui;
use MicroweberPackages\Cart\CartManager;
use MicroweberPackages\Cart\Repositories\CartRepository;
use MicroweberPackages\Category\CategoryManager;
use MicroweberPackages\Category\Repositories\CategoryRepository;
use MicroweberPackages\Checkout\CheckoutManager;
use MicroweberPackages\Content\AttributesManager;
use MicroweberPackages\Content\ContentManager;
use MicroweberPackages\Content\DataFieldsManager;
use MicroweberPackages\Content\Repositories\ContentRepository;
use MicroweberPackages\CustomField\FieldsManager;
use MicroweberPackages\CustomField\Repositories\CustomFieldRepository;
use MicroweberPackages\Database\DatabaseManager;
use MicroweberPackages\Event\Event;
use MicroweberPackages\Helper\Format;
use MicroweberPackages\Helper\HTMLClean;
use MicroweberPackages\Helper\UrlManager;
use MicroweberPackages\Helper\XSSSecurity;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;
use MicroweberPackages\LaravelTemplates\Repositories\LaravelTemplatesFileRepository;
use MicroweberPackages\Media\MediaManager;
use MicroweberPackages\Media\Repositories\MediaRepository;
use MicroweberPackages\Menu\MenuManager;
use MicroweberPackages\Menu\Repositories\MenuRepository;
use MicroweberPackages\Microweber\Microweber;
use MicroweberPackages\Module\ModuleManager;
use MicroweberPackages\Module\Repositories\ModuleRepository;
use MicroweberPackages\Multilanguage\Repositories\MultilanguageRepository;
use MicroweberPackages\Multilanguage\TranslateManager;
use MicroweberPackages\Offer\Repositories\OfferRepository;
use MicroweberPackages\Option\OptionManager;
use MicroweberPackages\Option\Repositories\OptionRepository;
use MicroweberPackages\Order\OrderManager;
use MicroweberPackages\Order\Repositories\OrderRepository;
use MicroweberPackages\Payment\PaymentManager;
use MicroweberPackages\Repository\RepositoryManager;
use MicroweberPackages\Shop\ShopManager;
use MicroweberPackages\Template\LayoutsManager;
use MicroweberPackages\Template\Template;
use MicroweberPackages\Template\TemplateManager;
use MicroweberPackages\Translation\Translator;
use MicroweberPackages\User\UserManager;
use MicroweberPackages\Utils\Captcha\CaptchaManager;
use MicroweberPackages\Utils\Http\Http;
use Modules\Payment\PaymentMethodManager;
use Modules\Shipping\ShippingManager;
use Modules\Shipping\ShippingMethodManager;


/**
 * Application class.
 *
 * Class that loads other classes
 *
 * @category Application
 * @desc
 *
 * @property UrlManager                    $url_manager
 * @property HTMLClean                            $html_clean
 * @property XSSSecurity                            $xss_security
 * @property Format                            $format
 * @property ContentManager                $content_manager
 * @property RepositoryManager                $repository_manager
 * @property ContentRepository                $content_repository
 * @property CategoryManager               $category_manager
 * @property CategoryRepository              $category_repository
 * @property MenuManager                   $menu_manager
 * @property MenuRepository              $menu_repository
 * @property MediaManager                  $media_manager
 * @property MediaRepository                  $media_repository
 * @property ShopManager                   $shop_manager
 * @property CartManager              $cart_manager
 * @property CartRepository         $cart_repository
 * @property OrderManager             $order_manager
 * @property OrderRepository    $order_repository
 * @property CustomFieldRepository $custom_field_repository
 * @property OfferRepository             $offer_repository
 * @property \Modules\Tax\TaxManager               $tax_manager
 * @property CheckoutManager          $checkout_manager
 * @property ShippingManager          $shipping_manager
 * @property PaymentManager          $payment_manager
 * @property OptionManager                 $option_manager
 * @property OptionRepository                 $option_repository
 * @property CacheManager                  $cache_manager
 * @property UserManager                   $user_manager
 * @property DatabaseManager              $database_manager
 * @property NotificationsManager          $notifications_manager
 * @property LayoutsManager                $layouts_manager
 * @property LogManager                    $log_manager
 * @property FieldsManager                 $fields_manager
 * @property Template                      $template
 * @property Event                         $event_manager
 * @property ConfigurationManager          $config_manager
 * @property TemplateManager               $template_manager
 * @property CaptchaManager               $captcha_manager
 * @property Ui                            $ui
 * @property Http                              $http
 * @property \Modules\Form\FormsManager                  $forms_manager
 * @property DataFieldsManager     $data_fields_manager
 * @property AttributesManager     $attributes_manager
 * @property Lang                  $lang_helper
 * @property PermalinkManager              $permalink_manager
 * @property ModuleManager              $module_manager
 * @property ModuleRepository              $module_repository
 * @property Translator                    $translator
 * @property MultilanguageRepository       $multilanguage_repository
 * @property TranslateManager       $translate_manager
 * @property Microweber       $microweber
 * @property PaymentMethodManager       $payment_method_manager
 * @property ShippingMethodManager      $shipping_method_manager
 * @property LaravelTemplatesFileRepository      $templates
 * @property LaravelModulesFileRepository $modules
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
