<?php

namespace Modules\Order\Repositories;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Repository\Repositories\AbstractRepository;
use Modules\Order\Models\Order;

class OrderRepository extends AbstractRepository
{
    /**
     * Specify Models class name
     *
     * @return string
     */
    public $model = Order::class;

    public function getOrderCurrencies($params = [])
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {
            return Order::getOrderCurrencies();
        });
    }

    public function getOrdersTotalSumForPeriod($params = [])
    {

        $orders = $this->getDefaultQueryForStats($params);

        $sum = $orders->sum('cart_orders.amount');
        if ($sum) {
            return ceil($sum);
        }
        return 0;
    }
    public function getOrdersCountForPeriod($params = [])
    {

        $orders = $this->getDefaultQueryForStats($params);

        $sum = $orders->select('cart_orders.id');
        $sum = $orders->count('cart_orders.id');
        if ($sum) {
            return intval($sum);
        }
        return 0;
    }
    public function getBestSellingCategoriesForPeriod($params = [])
    {
        $categories = [];
        $products = $this->getBestSellingProductsForPeriod($params);

        if (!$products) {
            return $categories;
        }

        // Collect all content IDs for batch category loading
        $contentIds = [];
        foreach ($products as $product) {
            if (!empty($product['content_id'])) {
                $contentIds[] = $product['content_id'];
            }
        }

        if (empty($contentIds)) {
            return $categories;
        }

        // Batch load all categories for all products in a single query
        $contentCategories = $this->batchLoadCategoriesForContentIds($contentIds);

        // Process products with pre-loaded categories
        foreach ($products as $product) {
            if (empty($product['content_id'])) {
                continue;
            }

            $contentId = $product['content_id'];
            if (!isset($contentCategories[$contentId])) {
                continue;
            }

            foreach ($contentCategories[$contentId] as $category) {
                if (empty($category['id'])) {
                    continue;
                }

                // Skip non-root categories
                if (($category['parent_id'] ?? 0) != 0) {
                    continue;
                }

                $categoryId = $category['id'];
                if (!isset($categories[$categoryId])) {
                    $categories[$categoryId] = $category;
                    $categories[$categoryId]['orders_count'] = 0;
                    $categories[$categoryId]['orders_amount'] = 0;
                    $categories[$categoryId]['orders_amount_rounded'] = 0;
                }

                $categories[$categoryId]['orders_count'] += $product['orders_count'] ?? 0;
                $categories[$categoryId]['orders_amount'] += $product['orders_amount'] ?? 0;
                $categories[$categoryId]['orders_amount_rounded'] += $product['orders_amount_rounded'] ?? 0;
            }
        }

        // Sort by orders_amount_rounded descending
        if (!empty($categories)) {
            usort($categories, function ($a, $b) {
                return ($b['orders_amount_rounded'] ?? 0) <=> ($a['orders_amount_rounded'] ?? 0);
            });
        }

        return $categories;
    }

    /**
     * Batch load categories for multiple content IDs to prevent N+1 queries
     *
     * @param array $contentIds Array of content IDs
     * @return array Associative array with content_id as key and categories array as value
     */
    protected function batchLoadCategoriesForContentIds(array $contentIds): array
    {
        if (empty($contentIds)) {
            return [];
        }

        $contentIds = array_unique($contentIds);

        // Get all category items for all content IDs in a single query
        $categoryItems = DB::table('categories_items')
            ->select('rel_id', 'parent_id')
            ->where('rel_type', morph_name(\Modules\Content\Models\Content::class))
            ->whereIn('rel_id', $contentIds)
            ->get()
            ->groupBy('rel_id');

        // Collect all unique category IDs
        $allCategoryIds = [];
        foreach ($categoryItems as $items) {
            foreach ($items as $item) {
                $allCategoryIds[] = $item->parent_id;
            }
        }
        $allCategoryIds = array_unique($allCategoryIds);

        // Batch load all categories in a single query
        $categoriesData = [];
        if (!empty($allCategoryIds)) {
            $categoriesResult = DB::table('categories')
                ->whereIn('id', $allCategoryIds)
                ->get();

            foreach ($categoriesResult as $category) {
                $categoriesData[$category->id] = (array) $category;
            }
        }

        // Map categories to content IDs
        $result = [];
        foreach ($contentIds as $contentId) {
            $result[$contentId] = [];
            if (isset($categoryItems[$contentId])) {
                foreach ($categoryItems[$contentId] as $item) {
                    if (isset($categoriesData[$item->parent_id])) {
                        $result[$contentId][] = $categoriesData[$item->parent_id];
                    }
                }
            }
        }

        return $result;
    }

    public function getBestSellingProductsForPeriod($params = [])
    {
        $orders = $this->getDefaultQueryForStats($params);
        $orders->where('cart.rel_type', morph_name(\Modules\Content\Models\Content::class));

        $orders->join('cart as cart', function ($join) use ($params) {
            $join->on('cart.order_id', '=', 'cart_orders.id');
            $join->whereNotNull('cart.order_id');
            $join->where('cart_orders.is_paid', '=', 1);

            if(isset($params['productId']) and !empty($params['productId'])){
                $join->where('cart.rel_id', '=', $params['productId']);
            }
        }) ;
        if(isset($params['productId']) and !empty($params['productId'])){
            $orders->where('cart.rel_id', '=', $params['productId']);
        }
        $fullTableNameCart = app()->database_manager->real_table_name('cart');
        $fullTableNameOrders = app()->database_manager->real_table_name('cart_orders');

        $orders->select('cart.rel_id as content_id',
            DB::raw("count(".$fullTableNameCart.".rel_id) as orders_count"),
            DB::raw("sum(".$fullTableNameOrders.".amount) as orders_amount")
        );

        $orders->groupBy('cart.rel_id');
        $orders->orderBy('orders_count', 'desc');

        $data = $orders->get();
        if ($data) {
            $data = $data->toArray();

            if (!empty($data)) {
                array_walk($data, function (&$a, $b) {
                    if (isset($a['orders_amount'])) {
                        $a['orders_amount_rounded'] = ceil($a['orders_amount']);
                    }
                });

            }


            return $data;
        }
    }

    public function getOrderItemsCountForPeriod($params = [])
    {

        // todo finish the query
        $orders = $this->getDefaultQueryForStats($params);

        $orders->join('cart', function ($join) use ($params) {
            $join->on('cart.order_id', '=', 'cart_orders.id');
            $join->whereNotNull('cart.order_id');
            $join->where('cart_orders.is_paid', '=', 1);

            if(isset($params['productId']) and !empty($params['productId'])){
                $join->where('cart.rel_id', '=', $params['productId']);
            }
        });


        if(isset($params['productId']) and !empty($params['productId'])){
            $orders->where('cart.rel_id', '=', $params['productId']);
        }
        // $orders->joinRelationship('cart');
        $orders->where('cart.rel_type', morph_name(\Modules\Content\Models\Content::class));
        // $orders->select(DB::raw('COUNT( cart.rel_id ) as "count"') );
        // $orders->groupBy('cart.rel_id');
        // $sum = $orders->count('cart.order_id');
        $sum = $orders->count('cart_orders.id' );

        if ($sum) {
            return intval($sum);
        }
        return 0;
    }
    public function getOrdersCountGroupedByDate($params = [])
    {

        $groupByFields = 'date';
        if(isset($params['period_group'])){
            switch ($params['period_group']) {
                case 'daily':
                    $groupByFields = 'date';
                    break;
                case 'weekly':
                    $groupByFields = 'date_year_month_week';
                    break;
                case 'monthly':
                    $groupByFields = 'date_year_month';
                    break;
                case 'yearly':
                    $groupByFields = 'date_year';
                    break;
            }
        }

        $orders = $this->getDefaultQueryForStats($params);


        $groupByFields = 'date';
        if(isset($params['period_group'])){
            switch ($params['period_group']) {
                case 'daily':
                    $groupByFields = 'date';
                    break;
                case 'weekly':
                    $groupByFields = 'date_year_month_week';
                    break;
                case 'monthly':
                    $groupByFields = 'date_year_month';
                    break;
                case 'yearly':
                    $groupByFields = 'date_year';
                    break;
            }
        }

        $orders = $this->getDefaultQueryForStats($params);

        $orders->groupBy($groupByFields);

        $orders->orderBy('date', 'desc');


        // where extract(year from date_order) < 2015


        $dbDriver = mw()->database_manager->get_sql_engine();


        if($dbDriver == 'sqlite'){
            $data = $orders->get([
                DB::raw('sum( amount ) as amount'),
                DB::raw('strftime( \'%Y\',created_at ) as date_year'),
                DB::raw('strftime( \'%m\',created_at ) as date_month'),
                DB::raw('strftime( \'%Y-%m-%d\',created_at ) as date'),
                DB::raw("strftime(created_at, '%Y-%m') date_year_month"),
                DB::raw("strftime(created_at, '%Y-%m-%u') date_year_month_week"),
                DB::raw("strftime(created_at, '%Y %M Week %u') date_year_month_week_display"),

                DB::raw('COUNT( * ) as "count"'),

            ])->toArray();
        } else {
            $data = $orders->get([
                DB::raw('sum( amount ) as amount'),
                DB::raw('YEAR( created_at ) as date_year'),
                DB::raw('MONTH( created_at ) as date_month'),
                DB::raw('DATE( created_at ) as date'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') date_year_month"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%u') date_year_month_week"),
                DB::raw("DATE_FORMAT(created_at, '%Y %M Week %u') date_year_month_week_display"),
                DB::raw('COUNT( * ) as "count"'),


            ])->toArray();
        }


        if (!empty($data)) {
            array_walk($data, function (&$a, $b) {
                if (isset($a['amount'])) {
                    $a['amount_rounded'] = ceil($a['amount']);
                }
            });

        }

        return $data;
    }

    public function getDefaultQueryForStats($params)
    {

        $orders = $this->getModel()->newQuery();
        $params = array_merge($params, [
            'isPaid' => 1,
            'isCompleted' => 1,
        ]);
        $dateSting = '';
        if (isset($params['from']) and $params['from']) {
            $params['from'] = Carbon::parse(strtotime($params['from']))->format('Y-m-d') . ' 00:00:01';
            $dateSting = $params['from'];
        }
        if (isset($params['to']) and $params['to']) {
            $params['to'] = Carbon::parse(strtotime($params['to']))->format('Y-m-d') . ' 23:59:59';
            $dateSting .= ',' . $params['to'];
        }
        if (isset($params['limit']) and $params['limit']) {
            $orders->limit(intval($params['limit']));
        }
        if ($dateSting) {
            $params['dateBetween'] = $dateSting;
        }

        $orders->filter(
            $params
        );
        return $orders;
    }
}
