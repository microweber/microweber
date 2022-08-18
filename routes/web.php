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

    $product = \MicroweberPackages\Product\Models\Product::where('id', 15)->first();
    $getCustomFields = $product->customField()->where('type','radio')->get();

    $generatedProductVariants = [];
    foreach($getCustomFields as $customField) {

        $customFieldValues = [];
        $getCustomFieldValues = $customField->fieldValue()->get();
        foreach ($getCustomFieldValues as $getCustomFieldValue) {
            $customFieldValues[] = $getCustomFieldValue->value;
        }
        $generatedProductVariants[$customField->name_key] = $customFieldValues;
    }

    $generatedProductVariants = new \MicroweberPackages\Product\CartesianProduct($generatedProductVariants);

    dd($generatedProductVariants->asArray());

    foreach ($generatedProductVariants as $generatedProductVariant) {
        // $findProductVariant = \MicroweberPackages\Product\Models\ProductVariant::where('ma')
        $productVariant = new \MicroweberPackages\Product\Models\ProductVariant();
        $productVariant->parent = $product->id;
        $productVariant->save();
    }

});
