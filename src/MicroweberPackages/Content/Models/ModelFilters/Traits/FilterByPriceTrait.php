<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterByPriceTrait
{

    protected $_minPriceFilter = false;
    protected $_maxPriceFilter = false;


    public function price($price)
    {

        return $this->query->whereHas('customField', function (Builder $query) use ($price) {
            $query->whereHas('fieldValue', function ($query) use ($price) {
                $query->where(\DB::raw('value = CAST("'.$price.'" AS UNSIGNED)'));
            });
        });
    }

    public function priceBetween($price)
    {
        $minPrice = $price;
        $maxPrice = false;

        if (strpos($price, ',') !== false) {
            $price = explode(',', $price);
            $minPrice = $price[0];
            $maxPrice = $price[1];
        }

        $minPrice = intval($minPrice);
        $maxPrice = intval($maxPrice);

        dump($maxPrice);

        $sql = $this->query->whereHas('customField', function (Builder $query) use ($minPrice, $maxPrice) {
            $query->whereHas('fieldValue', function ($query) use ($minPrice, $maxPrice) {
                if ($maxPrice) {
                   //$query->whereRaw("CAST(value as SIGNED) != 0 AND value < 73114");
                 // $query->where(\DB::raw('CAST(value AS UNSIGNED)'), '<', 4);
                   //$query->whereBetween(\DB::raw('CAST(value AS UNSIGNED)'), [$minPrice, $maxPrice]);
                //  $query->whereBetween('value', [$minPrice, $maxPrice]);
                } else {
                 //   $query->where(\DB::raw('value >= CAST("'.$minPrice.'" AS UNSIGNED)'));
                }
            });
        });

       dump($sql->toSql());

        return $sql;
    }


}
