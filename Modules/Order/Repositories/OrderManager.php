<?php
/*
* This file is part of the Microweber framework.
*
* (c) Microweber CMS LTD
*
* For full license information see
* https://github.com/microweber/microweber/blob/master/LICENSE
*
*/

namespace Modules\Order\Repositories;

use Modules\Order\Models\Order;
use Modules\Order\Services\OrderService;

/**
 * OrderManager - Thin wrapper delegating to OrderService and Order model.
 *
 * Business logic lives in OrderService; query methods live on the Order model.
 * This class exists only for backward compatibility with app('order_manager') callers.
 */
class OrderManager implements \Modules\Order\Contracts\OrderManagerContract
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    /** @var string The database table name for orders */
    public $table = 'cart_orders';

    public function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    public function place_order($place_order = array())
    {
        return app(OrderService::class)->place_order($place_order);
    }

    public function save($params = false)
    {
        return app(OrderService::class)->save($params);
    }

    public function export_orders()
    {
        return app(OrderService::class)->export_orders();
    }

    public function update_quantities($order_id = false)
    {
        return app(OrderService::class)->update_quantities($order_id);
    }

    public function get_by_id($order_id)
    {
        return Order::getById($order_id);
    }

    public function get($params = false)
    {
        return Order::getOrders($params);
    }

    public function get_items($order_id)
    {
        return Order::getOrderItems($order_id);
    }

    public function order_items($order_id)
    {
        $items = Order::getOrderItems($order_id);
        return empty($items) ? false : $items;
    }

    public function delete_order($data)
    {
        return Order::deleteOrder($data);
    }
}
