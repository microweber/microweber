<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('product-variants', function() {

    $getProduct = \MicroweberPackages\Product\Models\Product::where('id', 15)->first();
    $getCustomFields = $getProduct->customField()->where('type','radio')->get();

    $generatedProductVariants = [];
    foreach($getCustomFields as $customField) {

        $customFieldValues = [];
        $getCustomFieldValues = $customField->fieldValue()->get();
        foreach ($getCustomFieldValues as $getCustomFieldValue) {
            $customFieldValues[] = $getCustomFieldValue->value;
        }
        $generatedProductVariants[$customField->name_key] = $customFieldValues;
    }

    $cartesianProduct = new \MicroweberPackages\Product\CartesianProduct($generatedProductVariants);
    foreach ($cartesianProduct->asArray() as $cartesianProduct) {
        $cartesianProductVariantValues = [];
        foreach ($cartesianProduct as $cartesianProductKey=>$cartesianProductValue) {
            $cartesianProductVariantValues[] = $cartesianProductValue;
        }

        $productVariantUrl = $getProduct->url .'-'. str_slug(implode('-',$cartesianProductVariantValues));

        $productVariant = \MicroweberPackages\Product\Models\ProductVariant::where('url', $productVariantUrl)->first();
        if ($productVariant == null) {
            $productVariant = new \MicroweberPackages\Product\Models\ProductVariant();
        }

        $productVariant->name = $getProduct->name . ' - ' . implode(', ', $cartesianProductVariantValues);
        $productVariant->url = $productVariantUrl;
        $productVariant->parent = $getProduct->id;
        $productVariant->save();
    }

});
