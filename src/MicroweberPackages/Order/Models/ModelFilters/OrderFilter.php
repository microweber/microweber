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

    public function shippingCountry($shipping)
    {

    }

    public function id($id)
    {
        $id = intval($id);
        $this->query->where('id', $id);
    }

    public function isPaid($isPaid)
    {
        $isPaid = intval($isPaid);
        if($isPaid == 0){
            $this->query->where(function ($query) use ($isPaid) {
                $query->where('cart_orders.is_paid', 0)->orWhereNull('cart_orders.is_paid');
            });
        } else {
            $this->query->where('cart_orders.is_paid', '=', 1);
        }
    }

    public function isCompleted($isCompleted)
    {
        $isCompleted = intval($isCompleted);
        if($isCompleted == 0){
            $this->query->where(function ($query) {
                $query->where('cart_orders.order_completed', 0)->orWhereNull('order_completed');
            });
        } else {
            $this->query->where('cart_orders.order_completed', '=', 1);
        }
    }

    public function userId($userId)
    {
        $userId = intval($userId);
        $this->query->where('created_by', $userId);
    }

    public function customerId($customerId)
    {
        $customerId = intval($customerId);
        $this->query->where('customer_id', $customerId);
    }

    public function productId($productId)
    {

        $this->query->whereHas('cart', function ($query) use($productId) {
            $query->select('order_id');
            $query->where('rel_id', '=', $productId);

        });
//
//        $this->query->powerJoinWhereHas('cart', function ($query) use($productId) {
//            $query->where('cart.rel_id', '=', $productId);
//        });





        //  scopeJoinRelationshipUsingAlias(Builder $query, $relationName, $callback = null, bool $disableExtraConditions = false)

//        $this->query->joinRelationship('cart', [
//            'cart' => function ($join) {
//                $join->as('cart_alias');
//            }
//        ])->where('cart_alias.rel_id', '=', $productId);;
//        $this->query->joinRelationshipUsingAlias('cart', function ($join)  use($productId) {
//            $join->as('cart_alias');
//            $join->where('cart_alias.rel_id', '=', $productId);
//           // $join->withTrashed();
//        });

//        $this->query->joinRelationshipUsingAlias('cart', 'cart_alias_product', function ($query) use($productId) {
//            $query->where('cart_alias_product.rel_id', '=', $productId);
//        }, false);


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

        $this->query->where('first_name', 'LIKE', '%' . $keyword . '%');
        $this->query->orWhere('last_name', 'LIKE', '%' . $keyword . '%');

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
        } else {
           // $this->query->where($table . '.created_at', '>', $minDate);

        }


    }

    public function currency($currency)
    {
        $table = $this->getModel()->getTable();
        $this->query->where($table . '.currency', '=', $currency);

    }
}
