<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace MicroweberPackages\Order\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use MicroweberPackages\Content\Models\ModelFilters\Traits\FilterByDateBetweenTrait;
use MicroweberPackages\Content\Models\ModelFilters\Traits\OrderByTrait;
use Modules\Product\Models\Product;

class OrderFilter extends ModelFilter
{
    use OrderByTrait;
    use FilterByDateBetweenTrait;

    public function customer($filter)
    {
        if (!is_array($filter)) {
            $filter = json_decode($filter, true);
        }

        if (!empty($filter)) {
            if (isset($filter['customer_id'])) {
                $this->customerId($filter['customer_id']);
            } else if (isset($filter['email'])) {
                $this->query->where('email', $filter['email']);
            } else if (isset($filter['first_name'])) {
                $this->query->where('first_name', $filter['first_name']);
            }
        }
    }

    public function shippingState($state)
    {
        $state = trim($state);
        if (!empty($state)) {
            $this->query->where('state', $state);
        }
    }

    public function shippingCity($city)
    {
        $city = trim($city);
        if (!empty($city)) {
            $this->query->where('city', $city);
        }
    }

    public function shippingCountry($country)
    {
        $country = trim($country);
        if (!empty($country)) {
            $this->query->where('country', $country);
        }
    }

    public function shippingZip($zip)
    {
        $zip = trim($zip);

        if (!empty($zip)) {
            $this->query->where('zip', $zip);
        }
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
        $productId = intval($productId);

        $this->query->whereIn('cart_orders.id', function ($subQuery) use ($productId) {
            $subQuery->select('cart.order_id')->from('cart')
                ->where('cart.rel_type', morph_name(\Modules\Content\Models\Content::class))
                ->where('cart.rel_id', $productId);
        });

    }

    public function categoryId($categoryId)
    {
        $categoryId = intval($categoryId);


        $this->query->whereHas('cart', function ($query) use($categoryId) {
            $query->select('cart.order_id');
             $query->whereNotNull('cart.order_id');
          //  $query->whereIn('cart.rel_id', Product::select(['content.id'])->whereHas('orders')->whereCategoryIds([$categoryId]));
            $query->whereIn('cart.rel_id',
               // Product::select(['content.id'])->joinRelationship('orders')->with('categoryItems')->whereCategoryIds([$categoryId])->select(['content.id'])
                Product::select(['content.id'])->has('categoryItems')->has('orders')->whereCategoryIds([$categoryId])->select(['content.id'])
            );
        });

    }

    public function orderStatus($orderStatus)
    {
        if ($orderStatus == 'any') {
            return;
        }

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

        if (is_numeric($keyword)) {
            $this->query->where('id', $keyword);
        } else {
            $this->query->whereIn('cart_orders.id', function ($subQuery) use ($keyword) {
                $subQuery->select('cart.order_id')->from('cart')
                    ->where('cart.rel_type', morph_name(\Modules\Content\Models\Content::class))
                    ->whereNotNull('cart.order_id')
                    ->whereIn('cart.rel_id', function ($subQueryProduct) use ($keyword) {
                        $subQueryProduct->select('content.id')->from('content')->where('content.title', 'LIKE', '%' . $keyword . '%');
                    });
            });
        }

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

    public function currency($currency)
    {
        $table = $this->getModel()->getTable();
        $this->query->where($table . '.currency', '=', $currency);

    }
}
