<?php

namespace MicroweberPackages\Modules\Shop\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Modules\Shop\Http\Livewire\Traits\ShopCategoriesTrait;
use MicroweberPackages\Modules\Shop\Http\Livewire\Traits\ShopCustomFieldsTrait;
use MicroweberPackages\Modules\Shop\Http\Livewire\Traits\ShopTagsTrait;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Option\Models\ModuleOption;

class ShopComponent extends Component
{
    use WithPagination;
    use ShopTagsTrait;
    use ShopCategoriesTrait;
    use ShopCustomFieldsTrait;

    public array $settings = [];
    public string $moduleId = "";
    public string $moduleType = "";
    public string $moduleTemplateNamespace = '';

    public $keywords;
    public $sort;
    public $direction;
    public $offers;
    public $limit;

    public $priceFrom;
    public $priceTo;

    public $minPrice;
    public $maxPrice;

    public $queryString = [
        'keywords',
        'category',
        'tags',
        'customFields',
        'limit',
        'sort',
        'direction',
        'priceFrom',
        'priceTo',
        'offers'
    ];


    public function updatedOffers()
    {
        $this->setPage(1);
    }

    public function updatedPriceFrom()
    {
        $this->setPage(1);
    }

    public function updatedPriceTo()
    {
        $this->setPage(1);
    }


    public function updatedKeywords()
    {
        $this->setPage(1);
    }

    public function filterLimit($limit)
    {
        $this->limit = $limit;

        $this->setPage(1);
    }

    public function filterSort($field, $direction)
    {
        $this->sort = $field;
        $this->direction = $direction;

        $this->setPage(1);
    }

    public function render()
    {
        $productCardSettings = [
            'hide_price' => false
        ];

        $filterSettings = [
            'disable_tags_filtering' => false,
            'disable_keyword_filtering' => false,
            'disable_sort_filtering' => false,
            'disable_limit_filtering' => false,
            'disable_categories_filtering' => false,
            'disable_custom_fields_filtering' => false,
            'disable_price_range_filtering' => false,
            'disable_offers_filtering' => false,
            'disable_search' => false,
            'disable_pagination' => false,
        ];

        $getModuleOptions = ModuleOption::where('option_group', $this->moduleId)->get();
        if (!empty($getModuleOptions)) {
            foreach ($getModuleOptions as $moduleOption) {
                $filterSettings[$moduleOption['option_key']] = $moduleOption['option_value'];
            }
        }

        if (isset($filterSettings['hide_price'])) {
            $productCardSettings['hide_price'] = $filterSettings['hide_price'];
        }

        $limit = $this->limit;
        if (isset($filterSettings['default_limit'])) {
            $limit = $filterSettings['default_limit'];
        }

        if (isset($filterSettings['default_sort'])) {
            if (str_contains(',', $filterSettings['default_sort'])) {
                $defaultSortUndot = explode(',', $filterSettings['default_sort']);
                if (!empty($defaultSortUndot)) {
                    $this->sort = $defaultSortUndot[0];
                    $this->direction = $defaultSortUndot[1];
                }
            }
        }

        $mainPageId = $this->getMainPageId();
        $productsQuery = Product::query();

        if ($mainPageId > 0) {
            $productsQuery->where('parent', $mainPageId);
        }

        $productsQuery->where('is_active', 1);
        $productsQuery->where('is_deleted', 0);

        $filters = [];
        if (!empty($this->keywords)) {
            $filters['keyword'] = $this->keywords;
        }
        if (!empty($this->tags)) {
            $filters['tags'] = $this->tags;
        }
        if (!empty($this->sort) && !empty($this->direction)) {
            $filters['orderBy'] = $this->sort . ',' . $this->direction;
        }
        if (!empty($this->category)) {
            $filters['category'] = $this->category;
        }
        if (!empty($this->customFields)) {
            $filters['customFields'] = $this->customFields;
        }
        if (!empty($this->priceFrom) || !empty($this->priceTo)) {
            $filters['priceBetween'] = $this->priceFrom . ',' . $this->priceTo;
        }
        if (!empty($this->offers)) {
            $filters['offers'] = $this->offers;
        }

        if (!empty($filters)) {
            $productsQuery->filter($filters);
        }

        $productsQueryAll = Product::query();
        if ($mainPageId > 0) {
            $productsQueryAll->where('parent', $mainPageId);
        }
        $productsQueryAll->where('is_active', 1);
        $allProducts = $productsQueryAll->get();

        $availableTags = [];
        $availableCustomFieldsParents = [];
        $availableCustomFieldsValues = [];

        $productPrices = [];
        foreach ($allProducts as $product) {

            $productPrices[] = $product->price;

            if ($product->hasSpecialPrice()) {
                $productPrices[] = $product->special_price;
            }

            if (!empty($product->customField)) {
                foreach ($product->customField as $productCustomField) {
                    if ($productCustomField->name_key == 'price') {
                        continue;
                    }

                    $availableCustomFieldsParents[$productCustomField->name_key] = $productCustomField;

                    if (!empty($productCustomField->fieldValue)) {
                        foreach ($productCustomField->fieldValue as $fieldValue) {
                            $availableCustomFieldsValues[$productCustomField->name_key][$fieldValue->value] = $fieldValue;
                        }
                    }
                }
            }

            $getTags = $product->tags;
            if (!empty($getTags)) {
                foreach ($getTags as $tag) {
                    $availableTags[$tag->tag_name] = $tag->tag_slug;
                }
            }
        }

        $minPrice = 0;
        $maxPrice = 0;
        $priceFrom = 0;
        $priceTo = 0;
        if (!empty($productPrices)) {
            $minPrice = min($productPrices);
            $maxPrice = max($productPrices);
            if ($minPrice and is_numeric($minPrice)) {
                $minPrice = floor($minPrice);
            } else {
                $minPrice = 0;
            }

            if ($maxPrice and is_numeric($maxPrice)) {
                $maxPrice = floor($maxPrice) + 1;
            }



            if (empty($priceFrom)) {
                $priceFrom = $minPrice;
            }
            if (empty($priceTo)) {
                $priceTo = $maxPrice;
            }
        }

        $availableCustomFields = [];
        if (!empty($availableCustomFieldsParents)) {
            foreach ($availableCustomFieldsParents as $customFieldNameKey => $customField) {

                if (get_option('disable_custom_field_' . $customFieldNameKey, $this->moduleId) == 1) {
                    continue;
                }
                if (!isset($availableCustomFieldsValues[$customFieldNameKey])) {
                    continue;
                }

                $customFieldObject = new \stdClass();
                $customFieldObject->name = $customField->name;
                $customFieldObject->name_key = $customFieldNameKey;
                $customFieldObject->values = $availableCustomFieldsValues[$customFieldNameKey];
                $availableCustomFields[] = $customFieldObject;
            }
        }

        $products = $productsQuery->paginate($limit);

        if (empty($this->moduleTemplateNamespace)) {
            $this->moduleTemplateNamespace = 'microweber-module-shop::livewire.shop.index';
        }

        $filteredCategory = $this->getCategory();

        $breadcrumb = [];
        $breadcrumb[] = [
            'name' => _e('Home', true),
            'link' => site_url()
        ];
        $findShopPage = Page::where('id', $this->getMainPageId())->first();
        if ($findShopPage) {
            $breadcrumb[] = [
                'name' => $findShopPage->name,
                'link' => ''
            ];
        } else {
            $breadcrumb[] = [
                'name' => _e('Shop', true),
                'link' => ''
            ];
        }
        if (!empty($filteredCategory)) {
            $findCategory = Category::where('id', $filteredCategory)->first();
            if ($findCategory) {
                $breadcrumb[] = [
                    'name' => $findCategory->title,
                    'link' => category_link($findCategory->id)
                ];
            }
        }

        return view($this->moduleTemplateNamespace, [
            'breadcrumb' => $breadcrumb,
            'products' => $products,
            'productCardSettings' => $productCardSettings,
            'filteredPriceFrom' => $priceFrom,
            'filteredPriceTo' => $priceTo,
            'filteredMinPrice' => $minPrice,
            'filteredMaxPrice' => $maxPrice,
            'filterSettings' => $filterSettings,
            'filteredTags' => $this->getTags(),
            'filteredCustomFields' => $this->getCustomFields(),
            'filteredCategory' => $filteredCategory,
            'availableCustomFields' => $availableCustomFields,
            'availableTags' => $availableTags,
            'availableCategories' => $this->getAvailableCategories($mainPageId),
        ]);
    }

    public function getMainPageId()
    {
        $contentFromId = get_option('content_from_id', $this->moduleId);
        if ($contentFromId) {
            return $contentFromId;
        }

        return 0;
    }

    public function getAvailableCategories($mainPageId)
    {
        $categoryQuery = Category::query();
        if ($mainPageId > 0) {
            $categoryQuery->where('rel_id', $mainPageId);
        } else {
            $shopIds = [];
            $getAllShopPages = app()->content_repository->getAllShopPages();
            if (!empty($getAllShopPages)) {
                foreach ($getAllShopPages as $shopPage) {
                    $shopIds[] = $shopPage['id'];
                }
            }
            $categoryQuery->whereIn('rel_id', $shopIds);
        }

        $categoryQuery->orderBy('position');
        $categoryQuery->where('parent_id', 0);

        return $categoryQuery->get();
    }

}
