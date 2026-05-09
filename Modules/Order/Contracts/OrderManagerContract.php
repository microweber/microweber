<?php

declare(strict_types=1);

namespace Modules\Order\Contracts;

/**
 * AI-105 / TICKET-AY (cycle-118 2026-05-09): Module Contracts
 * Foundation — Phase 1 / second contract.
 *
 * Pins the public surface of `Modules\Order\Repositories\OrderManager`
 * so other modules can depend on the interface, not the concrete
 * implementation. Drift detection: ContractValidator (Phase 2) can
 * scan this interface against the implementation and flag drift
 * before it reaches a caller.
 *
 * The DI binding lives in `OrderServiceProvider::register()` and
 * `app(OrderManagerContract::class)` resolves to the singleton.
 */
interface OrderManagerContract
{
    /**
     * Place an order from a session cart payload.
     *
     * @param array $params Cart + customer + payment payload.
     * @return array|false  Order row on success, false on failure.
     */
    public function place_order($place_order = []);

    /**
     * Persist a partial / full order edit.
     *
     * @param array|false $params
     * @return mixed
     */
    public function save($params = false);

    /**
     * Get a single order by id.
     *
     * @param int|string $order_id
     * @return array|false
     */
    public function get_by_id($order_id);

    /**
     * Get a paged + filtered list of orders.
     *
     * @param array|false $params
     * @return array
     */
    public function get($params = false);

    /**
     * Get the line items of an order.
     *
     * @param int|string $order_id
     * @return array
     */
    public function get_items($order_id);

    /**
     * Delete an order (soft-delete by default).
     *
     * @param array $data Must include `id`.
     * @return mixed
     */
    public function delete_order($data);
}
