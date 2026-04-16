<?php

namespace Modules\Order\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Order\Models\Order;

class OrderStatsService
{
    public function getOrdersTotalSumForPeriod($params = []): int
    {
        $orders = $this->getDefaultQueryForStats($params);

        $sum = $orders->sum('cart_orders.amount');
        if ($sum) {
            return (int) ceil($sum);
        }
        return 0;
    }

    public function getOrdersCountForPeriod($params = []): int
    {
        $orders = $this->getDefaultQueryForStats($params);

        $sum = $orders->count('cart_orders.id');
        if ($sum) {
            return intval($sum);
        }
        return 0;
    }

    public function getBestSellingCategoriesForPeriod($params = []): array
    {
        $categories = [];
        $products = $this->getBestSellingProductsForPeriod($params);

        if (!$products) {
            return $categories;
        }

        $contentIds = [];
        foreach ($products as $product) {
            if (!empty($product['content_id'])) {
                $contentIds[] = $product['content_id'];
            }
        }

        if (empty($contentIds)) {
            return $categories;
        }

        $contentCategories = $this->batchLoadCategoriesForContentIds($contentIds);

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

        if (!empty($categories)) {
            usort($categories, function ($a, $b) {
                return ($b['orders_amount_rounded'] ?? 0) <=> ($a['orders_amount_rounded'] ?? 0);
            });
        }

        return $categories;
    }

    /**
     * Batch load categories for multiple content IDs to prevent N+1 queries.
     */
    protected function batchLoadCategoriesForContentIds(array $contentIds): array
    {
        if (empty($contentIds)) {
            return [];
        }

        $contentIds = array_unique($contentIds);

        $categoryItems = DB::table('categories_items')
            ->select('rel_id', 'parent_id')
            ->where('rel_type', morph_name(\Modules\Content\Models\Content::class))
            ->whereIn('rel_id', $contentIds)
            ->get()
            ->groupBy('rel_id');

        $allCategoryIds = [];
        foreach ($categoryItems as $items) {
            foreach ($items as $item) {
                $allCategoryIds[] = $item->parent_id;
            }
        }
        $allCategoryIds = array_unique($allCategoryIds);

        $categoriesData = [];
        if (!empty($allCategoryIds)) {
            $categoriesResult = DB::table('categories')
                ->whereIn('id', $allCategoryIds)
                ->get();

            foreach ($categoriesResult as $category) {
                $categoriesData[$category->id] = (array) $category;
            }
        }

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

    public function getBestSellingProductsForPeriod($params = []): array
    {
        $orders = $this->getDefaultQueryForStats($params);
        $orders->where('cart.rel_type', morph_name(\Modules\Content\Models\Content::class));

        $orders->join('cart as cart', function ($join) use ($params) {
            $join->on('cart.order_id', '=', 'cart_orders.id');
            $join->whereNotNull('cart.order_id');
            $join->where('cart_orders.is_paid', '=', 1);

            if (isset($params['productId']) and !empty($params['productId'])) {
                $join->where('cart.rel_id', '=', $params['productId']);
            }
        });
        if (isset($params['productId']) and !empty($params['productId'])) {
            $orders->where('cart.rel_id', '=', $params['productId']);
        }
        $fullTableNameCart = app()->database_manager->real_table_name('cart');
        $fullTableNameOrders = app()->database_manager->real_table_name('cart_orders');

        $orders->select(
            'cart.rel_id as content_id',
            DB::raw("count(" . $fullTableNameCart . ".rel_id) as orders_count"),
            DB::raw("sum(" . $fullTableNameOrders . ".amount) as orders_amount")
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

        return [];
    }

    public function getOrderItemsCountForPeriod($params = []): int
    {
        $orders = $this->getDefaultQueryForStats($params);

        $orders->join('cart', function ($join) use ($params) {
            $join->on('cart.order_id', '=', 'cart_orders.id');
            $join->whereNotNull('cart.order_id');
            $join->where('cart_orders.is_paid', '=', 1);

            if (isset($params['productId']) and !empty($params['productId'])) {
                $join->where('cart.rel_id', '=', $params['productId']);
            }
        });

        if (isset($params['productId']) and !empty($params['productId'])) {
            $orders->where('cart.rel_id', '=', $params['productId']);
        }
        $orders->where('cart.rel_type', morph_name(\Modules\Content\Models\Content::class));
        $sum = $orders->count('cart_orders.id');

        if ($sum) {
            return intval($sum);
        }
        return 0;
    }

    public function getOrdersCountGroupedByDate($params = []): array
    {
        $groupByFields = 'date';
        if (isset($params['period_group'])) {
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

        $dbDriver = mw()->database_manager->get_sql_engine();

        if ($dbDriver == 'sqlite') {
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

    protected function getDefaultQueryForStats($params)
    {
        $orders = Order::query();
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

        $orders->filter($params);
        return $orders;
    }
}
