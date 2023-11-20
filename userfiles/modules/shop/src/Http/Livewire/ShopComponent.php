<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire;

use Livewire\WithPagination;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;
use MicroweberPackages\Modules\Shop\Http\Livewire\Traits\ShopCategoriesTrait;
use MicroweberPackages\Modules\Shop\Http\Livewire\Traits\ShopCustomFieldsTrait;
use MicroweberPackages\Modules\Shop\Http\Livewire\Traits\ShopTagsTrait;
use MicroweberPackages\Product\Models\Product;

class ShopComponent extends ModuleSettingsComponent
{
    use WithPagination;
    use ShopTagsTrait;
    use ShopCategoriesTrait;
    use ShopCustomFieldsTrait;

    public $keywords;
    public $sort = '';
    public $direction = '';
    public $priceFrom;
    public $priceTo;
    public $limit = 10;

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
    ];

    public function keywordsUpdated()
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
        $productsQuery = Product::query();
      //  $productsQuery->where('is_active', 1);

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

        if (!empty($filters)) {
            $productsQuery->filter($filters);
        }

        $productsQueryAll = Product::query();
        $productsQueryAll->where('is_active', 1);
        $allProducts = $productsQueryAll->get();

        $availableTags = [];
        $availableCustomFieldsParents = [];
        $availableCustomFieldsValues = [];

        foreach ($allProducts as $product) {

            if (!empty($product->customField)) {
                foreach ($product->customField as $productCustomField) {
                    if ($productCustomField->name_key == 'price') {
                        continue;
                    }

                    $availableCustomFieldsParents[$productCustomField->id] = $productCustomField;

                    if (!empty($productCustomField->fieldValue)) {
                        foreach ($productCustomField->fieldValue as $fieldValue) {
                            $availableCustomFieldsValues[$fieldValue->custom_field_id][] = $fieldValue;
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

        $availableCustomFields = [];
        if (!empty($availableCustomFieldsParents)) {
            foreach ($availableCustomFieldsParents as $customFieldId => $customField) {
                $customFieldObject = new \stdClass();
                $customFieldObject->id = $customFieldId;
                $customFieldObject->name = $customField->name;
                $customFieldObject->name_key = $customField->name_key;
                $customFieldObject->values = $availableCustomFieldsValues[$customFieldId];
                $availableCustomFields[] = $customFieldObject;
            }
        }

        $products = $productsQuery->paginate($this->limit);

       return view('microweber-module-shop::livewire.shop.index', [
            'products' => $products,
            'filteredTags' => $this->getTags(),
            'filteredCustomFields'=>$this->getCustomFields(),
            'filteredCategory' => $this->getCategory(),
            'availableCustomFields'=>$availableCustomFields,
            'availableTags' => $availableTags,
            'availableCategories' => $this->getAvailableCategories(),
       ]);
    }

    public function getAvailableCategories()
    {
        $categoryQuery = Category::query();
       // $categoryQuery->where('rel_id', );
        $categoryQuery->orderBy('position');

        return $categoryQuery->where('parent_id',0)->get();
    }

}
