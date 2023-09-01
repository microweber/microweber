<?php
namespace MicroweberPackages\Shop\FrontendFilter;

use MicroweberPackages\Blog\FrontendFilter\BaseFilter;
use MicroweberPackages\Blog\FrontendFilter\Traits\CustomFieldsTrait;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Shop\FrontendFilter\Traits\PriceFilter;

class ShopFilter extends BaseFilter {

    use PriceFilter, CustomFieldsTrait;

    // public $viewNamespace = 'shop';

    public function getMainPageId()
    {
        $contentFromId = get_option('content_from_id', $this->params['moduleId']);
        if ($contentFromId) {
            return $contentFromId;
        }

        $findFirstShop = Page::where('content_type', 'page')
            ->where('subtype','dynamic')
            ->where('is_shop', 1)
            ->first();

        if ($findFirstShop) {
            return $findFirstShop->id;
        }

        return 0;
    }
}
