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
        $pageId = $request->get('content-id');

        $limit = '';
        if (isset($_GET['limit'])) {
            $limit = (int) $_GET['limit'];
        }

        $orderBy = '';
        if (isset($_GET['orderBy'])) {
            $orderBy = $_GET['orderBy'];
        }

        $filters = [];
        $productPrices = [];
        $getProducts = \MicroweberPackages\Product\Models\Product::where('parent', $pageId)->get();
        if (!empty($getProducts)) {
            foreach ($getProducts as $product) {
                $productPrices[] = $product->price;
                $customFields = $product->customField()->with('fieldValue')->get();
                foreach ($customFields as $customField) {
                    $customFieldValues = $customField->fieldValue()->get();
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
                    $filters[$customField->name_key] = [
                        'type'=>$customField->type,
                        'name'=>$customField->name,
                        'options'=>$filterOptions
                    ];
                }
            }
        }

        asort($productPrices, SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
        $productsMinPrice = $productPrices[0];
        $productsMaxPrice = end($productPrices);

        $priceBetween = '';
        if (isset($_GET['priceBetween'])) {
            $priceBetween = $_GET['priceBetween'];
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
            'limit'=>$limit,
        ]);
    }

}
