<?php


namespace MicroweberPackages\Product\Casts;

use MicroweberPackages\ContentData\ContentData;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

use Illuminate\Database\Eloquent\Model;


class ProductDataAttributes implements CastsAttributes
{

    /**
     * Transform the attribute from the underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $key
     * @param  mixed $value
     * @param  array $attributes
     * @return mixed
     */


    public function get($model, string $key, $value, array $attributes)
    {

     //   return $model->data();
        // dd($model->dataFields()->get());
        //dd($model,$key,$value,$attributes);


//dump($attributes);



        return $value;
//        return new ContentData(
//            $attributes
//        );
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $key
     * @param  mixed $value
     * @param  array $attributes
     * @return array|string
     */
    public function set($model, string $key, $value, array $attributes)
    {

        $field_names = $value->attributesToArray();

        if ($field_names) {
            foreach ($field_names as $field_name => $field_value) {
                if (!$value->isFillable($field_name)) {
                    $model->field_name = $field_name;
                    $model->field_value = $field_value;

                }
            }
        }
        return $model;

    }


}