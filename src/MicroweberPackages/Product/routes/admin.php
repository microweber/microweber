<?php

use MicroweberPackages\Product\Product;
use MicroweberPackages\CustomField\CustomField;
use MicroweberPackages\CustomField\CustomFieldValue;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/


Route::get('admin/xxx-xxx', function () {

    $product = Product::find(10);
    //$contentData = $product->getContentData(['tlegon2', 'qty']);
    $product->deleteContentData(['tlegon2', 'laptop', 'test']);
    dd(1);
    //$product->deleteContentData(['tlegon2', 'laptop']);
    //dd($product->qty);
});

/*
//>>>MW Interface
$product->setContentData(['telefon' => 'nokia2', 'sku' => 5]);
$product->deleteContentData(['tlegon2', 'laptop', 'test']);
$contentData = $product->getContentData(['telefon', 'qty']);
$product->save();

$product = Product::whereContentData(['qty' => 'nolimit']);

//--
$customFieldValue = new CustomFieldValue();
$customFieldValue->position = 100;
$customFieldValue->value = 100;

$customField = CustomField::find(25);

$customFieldValue->customField()->associate($customField);
$customFieldValue->save();
//--

$product->price;
$product->qty;


*/

