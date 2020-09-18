<?php

use MicroweberPackages\Product\Product;
use MicroweberPackages\CustomField\CustomField;
use MicroweberPackages\CustomField\CustomFieldValue;



Route::name('admin.')
    ->prefix('admin')
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Admin')
    ->middleware(['xss', 'admin'])
    ->group(function () {

        Route::resource('products', 'ProductsController');

    });

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/


Route::get('admin/xxx-xxx', function () {


//    //Create CustomField
//    $customField = new CustomField();
//    $customField->value = [23, 'blue4'];
//    $customField->type = 'price';
//    $customField->options = ['team1' => 'levski', 'team2' => 'cska'];
//    $customField->name = 'цена на едро';
//    $customField->rel_type = 'content';
//    $customField->rel_id = 19;
//    $customField->save();
//
//
//
//    dd('DONE Create CustomField');
//    //Create CustomField
//
//
//    //>>>>>CustomFieldValueSAVE
//    $customFieldValue = new CustomFieldValue();
//    //$customFieldValue->position = 100;
//    $customFieldValue->value = 47;
//
//    $customField = CustomField::find(23);
//
//    $customFieldValue->customField()->associate($customField);
//    $customFieldValue->save();
//    dd('DONE');
//    //<<<<<CustomFieldValueSAVE
//
//    $product = Product::find(10);
//
//    //dd($product->fieldValue); //type='price', name
//
////   $cf = $product->getCustomFields('izberi-cvqt');
////
//    //$cf = $product->getCustomFieldsValues();
//    $cf = $product->customFields;
//   foreach ($cf as $field ){
//        dd($field->fieldValue);
//       if($field->name_key == 'izberi-cvqt'){
//       dd($field->fieldValue->first()->value);
//       }
//       //dd($field->fieldValue);
//   }
//
//
//
//    $newCustomField = new CustomField();
//    $newCustomField->type = 'radio';
//    $newCustomField->name= 'Izberi cvqt';
//    $newCustomField->value= ['red','blue'];
//
//    $product->associate($newCustomField);
//
//
//
//
//
//
//
//    //$product->getCustomFields(); // price=10,
//    //dd($product->customFields);
//    dd($product->customFieldsValues);
//    dd(1);
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

