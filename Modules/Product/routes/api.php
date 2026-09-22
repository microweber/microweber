<?php
use  \Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Api\ProductsApiController;



Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])

    ->group(function () {

        Route::apiResource('product', \Modules\Product\Http\Controllers\Api\ProductApiController::class);
        Route::apiResource('product_variant', \Modules\Product\Http\Controllers\Api\ProductVariantApiController::class);

        // task-2026-09-21-createproduct — session-authed (admin) price save for the
        // Live Edit create dialogs (the token-guarded product store + the
        // whitelisted fields/save endpoint aren't reachable from the admin
        // session, so expose a minimal saver here).
        //
        // task-2026-09-22 — go through the Product MODEL, not save_custom_field:
        // CustomFieldPriceTrait stores `price` as a ProductPrice custom field and
        // `special_price` as an OFFER (a discount tied to that price). The old
        // save_custom_field('special_price', type:'price') wrote a SECOND price
        // field, so the shop rendered two prices / two Add-to-cart buttons instead
        // of a struck-through discount.
        Route::post('save_product_price', function (\Illuminate\Http\Request $request) {
            $id = intval($request->input('rel_id') ?: $request->input('id'));
            if (!$id) {
                return response()->json(['success' => false, 'message' => 'missing rel_id'], 422);
            }
            $product = \Modules\Product\Models\Product::find($id);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'not found'], 404);
            }
            // Drop any EXTRA price-type custom field left by the earlier (wrong)
            // save path. save_custom_field auto-renamed the second 'price' to
            // 'price-2' (or 'special_price'), and the shop renders every
            // price-type field as its own price / Add-to-cart. The one true
            // price is name_key='price' (kept/rebuilt by CustomFieldPriceTrait).
            $strayPriceFields = \Modules\CustomFields\Models\CustomField::where('rel_id', $id)
                ->where('type', 'price')
                ->where('name_key', '!=', 'price')
                ->get();
            foreach ($strayPriceFields as $cf) {
                $cf->fieldValue()->delete();
                $cf->delete();
            }

            $price = $request->input('price');
            if ($price !== null && $price !== '') {
                $product->price = (float) $price;
                $product->save();
            }
            // Reload so priceModel resolves the just-saved price when the trait
            // builds the offer for the special (discount) price.
            $special = $request->input('special_price');
            if ($special !== null && $special !== '') {
                $product = \Modules\Product\Models\Product::find($id);
                // Two things make a special price render as a DISCOUNT:
                //   • the Offer (via the `special_price` fillable) → the sale value
                //     that Content::getSpecialPriceAttribute() reads;
                //   • a content_data `special_price` row → Content::hasSpecialPrice()
                //     gates the struck-through old-price on this, NOT the offer.
                $product->special_price = (float) $special;
                $product->setContentData(['special_price' => (float) $special]);
                $product->save();
            }
            return response()->json(['success' => true, 'id' => $id]);
        })->name('save_product_price');


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

/*
|--------------------------------------------------------------------------
| Headless Module API (products)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php $modules loop. Reads
| are public; writes require a Passport admin-scoped token.
|
*/

Route::prefix('api/module/products')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.products.')
    ->group(function () {
        Route::get('/', [ProductsApiController::class, 'index'])->name('index');
        Route::get('/{product}', [ProductsApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/products')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:products:write'])
    ->name('api.module.products.')
    ->group(function () {
        Route::post('/', [ProductsApiController::class, 'store'])->name('store');
        Route::put('/{product}', [ProductsApiController::class, 'update'])->name('update');
        Route::patch('/{product}', [ProductsApiController::class, 'update'])->name('update.partial');
        Route::delete('/{product}', [ProductsApiController::class, 'destroy'])->name('destroy');
    });

