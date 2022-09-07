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

    public function discounted()
    {

    }

    public function notDiscounted()
    {

    }

    public function sortPrice($direction)
    {
       //  $this->query->sort('id', $direction);
    }

    public function price($price)
    {

        return $this->query->whereHas('customField', function (Builder $query) use ($price) {
            $query->whereHas('fieldValuePrice', function ($query) use ($price) {

                $query->where(function ($query2) use ($price) {

                    $price = intval($price);
                     $query2->whereRaw("CAST(value as INTEGER) REGEXP '^[0-9]*$'");
                    $query2->whereRaw("CAST(value as INTEGER) = {$price}");
                    return $query2;
                });

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


        $sql = $this->query->whereHas('customField', function (Builder $query) use ($minPrice, $maxPrice) {
            $query->whereHas('fieldValuePrice', function ($query2) use ($minPrice, $maxPrice) {
                $query2->where(function ($query3) use ($minPrice, $maxPrice) {

                    if ($maxPrice) {
                        //$query3->whereRaw("CAST(value as INTEGER) != 0");
                        $query3->whereRaw("CAST(value as INTEGER) REGEXP '^[0-9]*$'");
                        $query3->whereBetween(\DB::raw('CAST(value as INTEGER)'), [$minPrice, $maxPrice]);
                    } else {
                        $query3->whereRaw("value REGEXP '^[0-9]*$'");
                    //    $query3->whereRaw("CAST(value as INTEGER) != 0");
                        $query3->whereRaw("CAST(value as INTEGER) >= {$minPrice}");
                    }


                    return $query3;
                });
                return $query2;

            });
        });

        return $sql;
    }


}
