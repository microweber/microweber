<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace MicroweberPackages\Order\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByKeywordTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByTitleTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByUrlTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\OrderByTrait;

class OrderFilter extends ModelFilter
{
    use OrderByTrait;

    public function id($id)
    {
        $this->query->where('id', $id);
    }

    public function productId($productId)
    {
        $table = $this->getModel()->getTable();

    /*    return $this->query->where(function ($query) use ($productId,$table) {
            return $query->join('cart', function ($join) use ($productId,$table) {
                $join->on('cart.order_id', '=', $table . '.id')->where('cart.rel_id', $productId);
            });
        });*/
        return $this->query->where(function ($query) use ($productId,$table) {
            return $query->join('cart', 'cart.order_id', '=', $table . '.id')->where('rel_id', $productId);
        });
    }

    public function orderStatus($orderStatus)
    {
        $this->query->where('order_status', $orderStatus);
    }

    public function keyword($keyword)
    {
        $keyword = trim($keyword);
        if (empty($keyword)) {
            return;
        }

        $model = $this->getModel();
        $searchInFields = $model->getFillable();

        return $this->query->where(function ($query) use ($model, $searchInFields, $keyword) {

            $query->whereHas('cart', function ($query) use ($keyword) {
                $query->where('title', 'LIKE', '%' . $keyword . '%');
            });

            $searchInFields = $model->getFillable();
            foreach ($searchInFields as $field) {
                $query->orWhere($field, 'LIKE', '%' . $keyword . '%');
            }
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

        $this->query->where('amount', '>', $minPrice);
        $this->query->where('amount', '<', $maxPrice);

    }

    public function dateBetween($date)
    {
        $minDate = $date;
        $maxDate = false;

        if (strpos($date, ',') !== false) {
            $date = explode(',', $date);
            $minDate = $date[0];
            $maxDate = $date[1];
        }

        $table = $this->getModel()->getTable();

        $this->query->where($table.'.created_at', '>', $minDate);
        $this->query->where($table.'.created_at', '<', $maxDate);

    }
}
