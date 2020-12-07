<?php

namespace MicroweberPackages\CustomField\Traits;

use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;
use MicroweberPackages\Product\Models\ProductPrice;
use MicroweberPackages\Product\Models\ProductSpecialPrice;

trait  CustomFieldPriceTrait {

    private $_addCustomFields = [];
    private $_addPriceField = null;
    private $_addSpecialPriceField = null;

    public function initializeCustomFieldsTrait()
    {
        $this->fillable[] = 'price'; // related with boot custom fields trait
        $this->fillable[] = 'special_price'; // related with boot custom fields trait
    }

    public static function bootCustomFieldsTrait()
    {
        static::saving(function ($model)  {
            if (isset($model->attributes['price'])) {
                $model->_addPriceField = $model->attributes['price'];
                unset($model->attributes['price']);
            }
            if (isset($model->attributes['special_price'])) {
                $model->_addSpecialPriceField = $model->attributes['special_price'];
                unset($model->attributes['special_price']);
            }
        });

        static::saved(function($model) {

            if (isset($model->_addPriceField)) {
                $price = ProductPrice::where('rel_id', $model->id)->where('rel_type',$model->getMorphClass())->first();

                if (!$price) {
                    $price = new ProductPrice();
                }

                $priceInputVal = trim($model->_addPriceField);
                if(is_numeric($priceInputVal)) {
                    $price->value = $priceInputVal;
                } else {
                    $price->value = intval($priceInputVal);
                }

                $price->rel_id = $model->id;
                $price->save();
            }

           /* if (!empty($model->_addCustomFields)) {
                foreach ($model->_addCustomFields as $customField) {

                    if (empty($customField['name_key'])) {
                        $customField['name_key'] = \Str::slug($customField['name'], '-');
                    } else {
                        $customField['name_key'] = \Str::slug($customField['name_key'], '-');
                    }

                    $findCustomField = $model->customField()->where('name_key', $customField['name_key'])->first();



                    if($customField['name_key'] == 'price' or ($findCustomField and $findCustomField->type == 'price') ){
                        if(is_array($customField['value'])){
                            $customField['value'] = reset($customField['value']);
                        }
                        $priceInputVal = trim($customField['value']);
                        if(is_numeric($priceInputVal)) {
                            $customField['value'] = $priceInputVal;
                        } else {
                            $customField['value'] = intval($priceInputVal);
                        }
                    }

                    if ($findCustomField) {
                        $findCustomField->value = $customField['value'];

                        $findCustomField->save();
                    } else {

                        $model->customField()->create([
                            'value' => $customField['value'],
                            'name' => $customField['name'],
                            'name_key' => $customField['name_key']
                        ]);
                    }
                }
                $model->refresh();
            }*/

        });
    }


}