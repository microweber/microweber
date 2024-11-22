<?php
use  \Illuminate\Support\Facades\Route;



Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])

    ->group(function () {

        Route::apiResource('product', \Modules\Product\Http\Controllers\Api\ProductApiController::class);
        Route::apiResource('product_variant', \Modules\Product\Http\Controllers\Api\ProductVariantApiController::class);


    });


Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])
    ->group(function () {

        Route::post('product_variant/parent/{id}/options', function($parentId) {
            $findProduct = \Modules\Product\Models\Product::where('id', $parentId)->first();
            if ($findProduct != null) {
                $findProduct->setCustomField(
                    [
                        'type'=>'radio',
                        'name'=>'',
                        'value'=>[],
                    ]
                );
                $findProduct->save();
            }
        });

        Route::get('product_variant/parent/{id}/options', function($parentId) {

            $findProduct = \Modules\Product\Models\Product::where('id', $parentId)->first();
            if ($findProduct != null) {
                $customFields = [];
                $getCustomFields = $findProduct->customField()->where('type', 'radio')->orderBy('id','asc')->get();
                if ($getCustomFields->count() > 0) {
                    foreach ($getCustomFields as $customField) {

                        $customFieldValues = [];
                        $getCustomFieldValues = $customField->fieldValue()->get();
                        if ($getCustomFieldValues->count() > 0) {
                            foreach ($getCustomFieldValues as $customFieldValue) {
                                $customFieldValues[] = $customFieldValue->value;
                            }
                        }

                        $customFields[] = [
                            'option_id'=>$customField->id,
                            'option_name'=>$customField->name,
                            'option_values'=>$customFieldValues,
                        ];
                    }
                }

                return $customFields;
            }

        });

        Route::get('product_variant/parent/{id}', function($parentId) {

            $findProduct = \Modules\Product\Models\Product::where('id', $parentId)->first();
            if ($findProduct != null) {
                $productVariants = [];
                $getProductVariants = $findProduct->variants()->get();
                if ($getProductVariants->count() > 0) {
                 foreach ($getProductVariants as $productVariant) {

                     $shortTitle = [];
                     $getContentDataVariant = $productVariant->contentDataVariant()->get();
                     if ($getContentDataVariant->count() > 0) {
                         foreach ($getContentDataVariant as $contentDataVariant) {
                             $getCustomFieldValue = \Modules\CustomFields\Models\CustomFieldValue::where('id', $contentDataVariant->custom_field_value_id)->first();
                             if ($getCustomFieldValue != null) {
                                 $shortTitle[] = $getCustomFieldValue->value;
                             }
                         }
                     }

                     $productVariants[] = [
                       'id'=>$productVariant->id,
                       'title'=>$productVariant->title,
                       'short_title'=>implode(', ', $shortTitle),
                       'price'=>$productVariant->price,
                       'qty'=>$productVariant->qty,
                       'sku'=>$productVariant->sku,
                       'currency'=> get_currency_code(),
                     ];
                 }
                }

                return $productVariants;
            }

        });

        Route::post('product_variant_save', function() {

            $options = request()->post('options', []);
            if (!empty($options)) {

                $productId = request()->post('product_id', 0);
                $findProduct = \Modules\Product\Models\Product::where('id', $productId)->first();
                if ($findProduct != null) {

                    $customFields = [];
                    foreach ($options as $option) {
                        if (empty($option['option_name']) || empty($option['option_values'])) {
                            continue;
                        }
                        $customFields[] = [
                            'type'=>'radio',
                            'name'=>$option['option_name'],
                            'value'=>$option['option_values'],
                            'options'=>['for_variants'=>true]
                        ];
                    }

                    $findProduct->setCustomFields($customFields);
                    $findProduct->save();
                    $findProduct->generateVariants();

                    return $findProduct;
                }
            }

        });

    });
