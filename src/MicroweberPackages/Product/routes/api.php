<?php

Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Api')
    ->group(function () {
        Route::apiResource('product', 'ProductApiController');
        Route::apiResource('product_variant', 'ProductVariantApiController');


        Route::get('product_variant/parent/{id}', function($parentId) {

            $findProduct = \MicroweberPackages\Product\Models\Product::where('id', $parentId)->first();
            if ($findProduct != null) {
                $productVariants = [];
                $getProductVariants = $findProduct->variants()->get();
                if ($getProductVariants->count() > 0) {
                 foreach ($getProductVariants as $productVariant) {

                     $shortTitle = [];
                     $getContentDataVariant = $productVariant->contentDataVariant()->get();
                     if ($getContentDataVariant->count() > 0) {
                         foreach ($getContentDataVariant as $contentDataVariant) {
                             $getCustomFieldValue = \MicroweberPackages\CustomField\Models\CustomFieldValue::where('id', $contentDataVariant->custom_field_value_id)->first();
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
                $findProduct = \MicroweberPackages\Product\Models\Product::where('id', $productId)->first();
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
