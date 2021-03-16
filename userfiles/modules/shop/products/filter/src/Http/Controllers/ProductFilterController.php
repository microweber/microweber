<?php

namespace MicroweberPackages\Shop\Products\Filter\Http\Controllers;

use Illuminate\Http\Request;

class ProductFilterController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return '';
        $pageId = $request->get('content-id');
        $orderBy = $request->get('orderBy','');
        $limit = $request->get('limit','');
        $priceBetween = $request->get('priceBetween','');
        $customFields = $request->get('customFields','');

        $filters = [];
        $productPrices = [];
        $queryProduct = \MicroweberPackages\Product\Models\Product::query();

        if ($pageId > 0) {
            $queryProduct->where('parent', $pageId);
        }

        $getProducts = $queryProduct->get();

        if (!empty($getProducts)) {
            foreach ($getProducts as $product) {
                $productPrices[] = $product->price;
                $productCustomFields = $product->customField()->with('fieldValue')->get();
                foreach ($productCustomFields as $productCustomField) {
                    $customFieldValues = $productCustomField->fieldValue()->get();
                    if (empty($customFieldValues)) {
                        continue;
                    }
                    $filterOptions = [];
                    foreach ($customFieldValues as $customFieldValue) {
                        $filterOptions[] = [
                            'id'=>$customFieldValue->id,
                            'value'=>$customFieldValue->value,
                        ];
                    }
                    $filters[$productCustomField->name_key] = [
                        'type'=>$productCustomField->type,
                        'name'=>$productCustomField->name,
                        'options'=>$filterOptions
                    ];
                }
            }
        }


        $productsMinPrice = 0;
        $productsMaxPrice = 0;
        if (isset($productPrices[0])) {
            asort($productPrices, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
            $productsMinPrice = $productPrices[0];
            $productsMaxPrice = end($productPrices);
        }

        $getMinPrice = $priceBetween;
        $getMaxPrice = false;
        if (strpos($priceBetween, ',') !== false) {
            $priceRange = explode(',', $priceBetween);
            $getMinPrice = $priceRange[0];
            $getMaxPrice = $priceRange[1];
        }

        $getMinPrice = intval($getMinPrice);
        $getMaxPrice = intval($getMaxPrice);

        return view('productFilter::index', [
            'currencySymbol'=>mw()->shop_manager->currency_symbol(),
            'getMinPrice'=>$getMinPrice,
            'getMaxPrice'=>$getMaxPrice,
            'priceBetween'=>$priceBetween,
            'productsMinPriceRounded'=>round($productsMinPrice),
            'productsMaxPriceRounded'=>round($productsMaxPrice),
            'filters'=>$filters,
            'orderBy'=>$orderBy,
            'customFields'=>$customFields,
            'limit'=>$limit,
        ]);
    }

}
