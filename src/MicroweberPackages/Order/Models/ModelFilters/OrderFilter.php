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
        $id = intval($id);
        $this->query->where('id', $id);
    }

    public function isPaid($isPaid)
    {
        $isPaid = intval($isPaid);
        $this->query->where('is_paid', '=', $isPaid);
    }

    public function customerId($customerId)
    {
        $customerId = intval($customerId);

        $this->query->where('created_by', $customerId);
    }

    public function productId($productId)
    {
        $this->query->whereHas('cart', function ($query) use($productId) {
            $query->where('rel_id', '=', $productId);
        });

    }

    public function orderStatus($orderStatus)
    {
        $orderStatus = trim($orderStatus);
        if (!empty($orderStatus)) {
            $this->query->where('order_status', $orderStatus);
        }
    }

    public function keyword($keyword)
    {
        $keyword = trim($keyword);
        if (empty($keyword)) {
            return;
        }

      /*  return $this->query->where(function ($query) use ($keyword) {
            $query->whereHas('cart', function ($query) use ($keyword) {
                $query->where('title', 'LIKE', '%' . $keyword . '%');
            });
        });*/
    }


    public function amountBetween($price)
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


        if ($minPrice && $maxPrice) {
            $this->query->whereBetween('amount', [$minPrice, $maxPrice]);
        } else if ($minPrice) {
            $this->query->where('amount', '>=', $minPrice);
        } else if ($maxPrice) {
            $this->query->where('amount', '<=', $maxPrice);
        }

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

        if (!empty($minDate)) {
            $this->query->where($table . '.created_at', '>', $minDate);
        }

        if (!empty($maxDate)) {
            $this->query->where($table . '.created_at', '<', $maxDate);
        }

    }
}
