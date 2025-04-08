<?php

namespace MicroweberPackages\App;

use MicroweberPackages\App\Managers\CacheManager;
use MicroweberPackages\App\Managers\ConfigurationManager;
use MicroweberPackages\App\Managers\Helpers\Lang;
use MicroweberPackages\App\Managers\NotificationsManager;
use MicroweberPackages\App\Managers\PermalinkManager;
use MicroweberPackages\App\Managers\Ui;
use MicroweberPackages\Database\DatabaseManager;
use MicroweberPackages\Event\Event;
use MicroweberPackages\Helper\Format;
use MicroweberPackages\Helper\HTMLClean;
use MicroweberPackages\Helper\UrlManager;
use MicroweberPackages\Helper\XSSSecurity;
use MicroweberPackages\LaravelModules\Repositories\LaravelModulesFileRepository;
use MicroweberPackages\LaravelTemplates\Repositories\LaravelTemplatesFileRepository;
use MicroweberPackages\Microweber\Repositories\MicroweberRepository;
use MicroweberPackages\Module\ModuleManager;
use MicroweberPackages\Module\Repositories\ModuleRepository;
use MicroweberPackages\Multilanguage\Repositories\MultilanguageRepository;
use MicroweberPackages\Multilanguage\TranslateManager;
use MicroweberPackages\Option\OptionManager;
use MicroweberPackages\Option\Repositories\OptionRepository;
use MicroweberPackages\Repository\RepositoryManager;
use MicroweberPackages\Template\LayoutsManager;
use MicroweberPackages\Template\TemplateManager;
use MicroweberPackages\Translation\Translator;
use MicroweberPackages\User\UserManager;
use MicroweberPackages\Utils\Http\Http;
use Modules\Attributes\Repositories\AttributesManager;
use Modules\Cart\Repositories\CartManager;
use Modules\Cart\Repositories\CartRepository;
use Modules\Checkout\Repositories\CheckoutManager;
use Modules\Content\Services\ContentManager;
use Modules\Content\Repositories\ContentRepository;
use Modules\ContentData\Repositories\DataFieldsManager;
use Modules\Country\Repositories\CountryManager;
use Modules\Coupons\Services\CouponService;
use Modules\CustomFields\Repositories\CustomFieldRepository;
use Modules\CustomFields\Services\FieldsManager;
use Modules\Invoice\Services\InvoiceService;
use Modules\Menu\Repositories\MenuRepository;
use Modules\Order\Repositories\OrderManager;
use Modules\Order\Repositories\OrderRepository;
use Modules\Payment\Services\PaymentMethodManager;
use Modules\Shipping\Services\ShippingMethodManager;
use Modules\Log\Services\LogManager;
use Modules\Shop\Services\ShopManager;


/**
 * Application class.
 *
 * Class that loads other classes
 *
 * @category Application
 * @desc
 *
 * @property UrlManager $url_manager
 * @property HTMLClean $html_clean
 * @property XSSSecurity $xss_security
 * @property Format $format
 * @property ContentManager $content_manager
 * @property RepositoryManager $repository_manager
 * @property ContentRepository $content_repository
 * @property \Modules\Category\Repositories\CategoryManager $category_manager
 * @property \Modules\Category\Repositories\CategoryRepository $category_repository
 * @property \Modules\Menu\Repositories\MenuManager $menu_manager
 * @property MenuRepository $menu_repository
 * @property \Modules\Media\Repositories\MediaManager $media_manager
 * @property \Modules\Media\Repositories\MediaRepository $media_repository
 * @property ShopManager $shop_manager
 * @property CartManager $cart_manager
 * @property CartRepository $cart_repository
 * @property CouponService $coupon_service
 * @property InvoiceService $invoice_service
 * @property OrderManager $order_manager
 * @property OrderRepository $order_repository
 * @property CustomFieldRepository $custom_field_repository
 * @property \Modules\Offer\Repositories\OfferRepository $offer_repository
 * @property \Modules\Tax\Services\TaxManager $tax_manager
 * @property CheckoutManager $checkout_manager
 * @property CountryManager $country_manager
 * @property OptionManager $option_manager
 * @property OptionRepository $option_repository
 * @property CacheManager $cache_manager
 * @property UserManager $user_manager
 * @property DatabaseManager $database_manager
 * @property NotificationsManager $notifications_manager
 * @property LayoutsManager $layouts_manager
 * @property FieldsManager $fields_manager
 * @property Event $event_manager
 * @property ConfigurationManager $config_manager
 * @property TemplateManager $template_manager
 * @property \Modules\Captcha\Services\CaptchaManager $captcha_manager
 * @property Ui $ui
 * @property Http $http
 * @property \Modules\Form\FormsManager $forms_manager
 * @property DataFieldsManager $data_fields_manager
 * @property AttributesManager $attributes_manager
 * @property Lang $lang_helper
 * @property PermalinkManager $permalink_manager
 * @property ModuleManager $module_manager
 * @property ModuleRepository $module_repository
 * @property Translator $translator
 * @property MultilanguageRepository $multilanguage_repository
 * @property TranslateManager $translate_manager
 * @property  MicroweberRepository $microweber
 * @property PaymentMethodManager $payment_method_manager
 * @property ShippingMethodManager $shipping_method_manager
 * @property LaravelTemplatesFileRepository $templates
 * @property LaravelModulesFileRepository $modules
 * @property LogManager $log_manager
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
