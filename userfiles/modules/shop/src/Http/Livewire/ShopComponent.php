<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
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
    public string $moduleId =  "";
    public string $moduleType =  "";
    public string $moduleTemplateNamespace = '';

    public $keywords;
    public $sort = '';
    public $direction = '';
    public $offers = '';
    public $limit = 10;

    public $priceFrom = 0;
    public $priceTo = 0;

    public $minPrice = 0;
    public $maxPrice = 1000;

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

    public function filterSort($field,$direction)
    {
        $this->sort = $field;
        $this->direction = $direction;

        $this->setPage(1);
    }

    public function render()
    {
        $filterSettings = [
            'disable_tags_filtering'=>false,
            'disable_categories_filtering'=>false,
            'disable_custom_fields_filtering'=>false,
            'disable_price_range_filtering'=>false,
            'disable_offers_filtering'=>false,
            'disable_sort_filtering'=>false,
            'disable_limit_filtering'=>false,
            'disable_keyword_filtering'=>false,
            'disable_search'=>false,
            'disable_pagination'=>false,
        ];

        $getModuleOptions = ModuleOption::where('option_group', $this->moduleId)->get();
        if (!empty($getModuleOptions)) {
            foreach ($getModuleOptions as $moduleOption) {
                $filterSettings[$moduleOption['option_key']] = $moduleOption['option_value'];
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
        $productsQueryAll->where('parent', $mainPageId);
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

        if (!empty($productPrices)) {
            $this->minPrice = min($productPrices);
            $this->maxPrice = max($productPrices);

            $this->minPrice = floor($this->minPrice) - 1;
            $this->maxPrice = floor($this->maxPrice) + 1;

            if (empty($this->priceFrom)) {
                $this->priceFrom = $this->minPrice;
            }
            if (empty($this->priceTo)) {
                $this->priceTo = $this->maxPrice;
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

        $products = $productsQuery->paginate($this->limit);

        if (empty($this->moduleTemplateNamespace)) {
            $this->moduleTemplateNamespace = 'microweber-module-shop::livewire.shop.index';
        }

       return view($this->moduleTemplateNamespace, [
            'filterSettings'=>$filterSettings,
            'products' => $products,
            'filteredTags' => $this->getTags(),
            'filteredCustomFields'=>$this->getCustomFields(),
            'filteredCategory' => $this->getCategory(),
            'availableCustomFields'=>$availableCustomFields,
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

        $findFirstShop = Page::where('content_type', 'page')
            ->where('subtype','dynamic')
            ->where('is_shop', 1)
            ->first();

        if ($findFirstShop) {
            return $findFirstShop->id;
        }

        return 0;
    }

    public function getAvailableCategories($mainPageId)
    {
        $categoryQuery = Category::query();
        $categoryQuery->where('rel_id', $mainPageId);
        $categoryQuery->orderBy('position');

        return $categoryQuery->where('parent_id',0)->get();
    }

}
