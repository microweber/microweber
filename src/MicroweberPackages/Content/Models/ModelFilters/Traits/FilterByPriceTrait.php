<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

trait FilterByPriceTrait
{
    public function discount($type = 1)
    {
        if ($type == 1 || $type == 'yes') {
            $this->query->whereHas('offer');
        } else if ($type == 'no') {
            $this->query->doesntHave('offer');
        }
    }

    public function sortPrice($direction)
    {
       //  $this->query->orderBy('id', $direction);
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

        if ($maxPrice == 0) {
           $maxPrice= 1000000000000;
        }

        if($minPrice == 0 and $maxPrice == 0) {
            return $this->query;
        }

       // $dbDriver = Config::get('database.default');
        $dbDriver = mw()->database_manager->get_sql_engine();

        $sql = $this->query->whereHas('customField', function (Builder $query) use ($minPrice, $maxPrice,$dbDriver) {
            $query->whereHas('fieldValuePrice', function ($query2) use ($minPrice, $maxPrice,$dbDriver) {
                $query2->where(function ($query3) use ($minPrice, $maxPrice,$dbDriver) {

                    if ($dbDriver == 'sqlite') {
                        $query3->whereRaw("CAST(value as INTEGER) >= {$minPrice}");
                        $query3->whereRaw("CAST(value as INTEGER) <= {$maxPrice}");
                    } else {
                        $query3->whereBetween('value', [$minPrice, $maxPrice]);
                    }

                    return $query3;
                });
                return $query2;

            });
        });

        return $sql;
    }


}
