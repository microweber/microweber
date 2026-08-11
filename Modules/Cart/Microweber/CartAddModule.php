<?php

namespace Modules\Cart\Microweber;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
use Modules\Cart\Filament\CartAddModuleSettings;

class CartAddModule extends BaseModule
{
    public static string $name = 'Add to cart';
    public static string $module = 'shop/cart_add';
    public static string $icon = 'heroicon-o-shopping-cart';

    public static string $settingsComponent = CartAddModuleSettings::class;
    public static string $templatesNamespace = 'modules.cart::templates';

    public function render($params = [])
    {
        // Load module assets
        //  $this->loadAssets();
        $viewData = $this->getViewData();
        $for_id = false;
        $for = 'content';

        // Handle content-id from params
        if (isset($params['rel_type']) && trim(strtolower($params['rel_type'])) == 'post') {
            $params['content-id'] = post_id();
            $for = 'content';
        }

        if (isset($params['rel_type']) && trim(strtolower($params['rel_type'])) == 'page') {
            $params['content-id'] = page_id();
            $for = 'content';
        }

        if (isset($params['content_id'])) {
            $params['content-id'] = $params['content_id'];
            $for = 'content';
        }

        if (isset($params['product_id'])) {
            $params['content-id'] = $params['product_id'];
            $for = 'content';
        }

        if (isset($params['content-id'])) {
            $for_id = $params['content-id'];
        }

        if (isset($params['for'])) {
            $for = $params['for'];
        }

        if ($for_id == false) {
            $for_id = content_id();
        }

        // Get button text from module options or params
        $button_text = $params['button_text'] ?? $this->getOption('button_text', 'Add to cart');

        // Get content data and check stock
        $content_data = content_data($for_id);
        $in_stock = true;

        if (isset($content_data['qty']) && $content_data['qty'] != 'nolimit' && intval($content_data['qty']) == 0) {
            $in_stock = false;
        }

        // Get prices data
        $data = false;
        $prices_data = false;

        if ($for_id !== false && $for !== false) {
            $prices_data = app()->shop_manager->get_product_prices($for_id, true);

            if ($prices_data) {
                $data = [];
                foreach ($prices_data as $price_data) {
                    if (isset($price_data['name'])) {
                        $data[$price_data['name']] = $price_data['value'];
                    }
                }
            }
        }

        // Get product title
        $title = "Product";
        if (isset($params['content-id'])) {
            $product = get_content_by_id($params["content-id"]);
            if ($product && isset($product['title'])) {
                $title = $product['title'];
            }
        }
        $viewData['params'] = $params;
        $viewData['for_id'] = $for_id;
        $viewData['for'] = $for;
        $viewData['data'] = $data;
        $viewData['in_stock'] = $in_stock;
        $viewData['button_text'] = $button_text;
        $viewData['title'] = $title;


        $viewName = $this->getViewName($viewData['template'] ?? 'default');

        return view($viewName, $viewData);
    }


}
