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
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes){
        return new ContentData(
            $attributes['sku']
        );
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array|string
     */
    public function set($model, string $key, $value, array $attributes){


        die();
    }


}