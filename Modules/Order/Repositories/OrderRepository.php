<?php

namespace Modules\Order\Repositories;

use MicroweberPackages\Repository\Repositories\AbstractRepository;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderStatsService;

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
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {
            return app(OrderStatsService::class)->getOrdersTotalSumForPeriod($params);
        });
    }

    public function getOrdersCountForPeriod($params = [])
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {
            return app(OrderStatsService::class)->getOrdersCountForPeriod($params);
        });
    }

    public function getBestSellingCategoriesForPeriod($params = [])
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {
            return app(OrderStatsService::class)->getBestSellingCategoriesForPeriod($params);
        });
    }

    public function getBestSellingProductsForPeriod($params = [])
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {
            return app(OrderStatsService::class)->getBestSellingProductsForPeriod($params);
        });
    }

    public function getOrderItemsCountForPeriod($params = [])
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {
            return app(OrderStatsService::class)->getOrderItemsCountForPeriod($params);
        });
    }

    public function getOrdersCountGroupedByDate($params = [])
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {
            return app(OrderStatsService::class)->getOrdersCountGroupedByDate($params);
        });
    }
}
