<?php

declare(strict_types=1);

namespace Modules\Cart\Contracts;

/**
 * AI-105 / TICKET-AY: Module Contracts Foundation.
 *
 * Pins the public surface of `Modules\Cart\Repositories\CartManager`
 * so other modules can depend on the interface, not the concrete
 * implementation. Drift detection: ContractValidator (Phase 2) can
 * scan this interface against the implementation and flag drift
 * before it reaches a caller.
 *
 * The DI binding lives in `CartServiceProvider::register()` and
 * `app(CartManagerContract::class)` resolves to the singleton.
 *
 * Cycle-113: initial scaffold (with placeholder method names).
 * Cycle-118: signatures aligned with the actual CartManager
 *            implementation so the DI binding doesn't throw at
 *            resolution time. Pre-fix the contract declared
 *            `add_to_cart` / `remove_from_cart` / `update_qty` /
 *            `empty_cart(): void` / `getCartAmount` — the actual
 *            implementation uses `update_cart` / `remove_item` /
 *            `update_item_qty` / `empty_cart(): array` (the live
 *            method returns the cleared cart) / `getTotal` (lives
 *            on CartManager directly; getCartAmount lives on
 *            CartRepository, NOT CartManager).
 */
interface CartManagerContract
{
    /**
     * Get the current cart contents for the active session.
     *
     * @param array $params Optional filters (limit, order_by, etc.).
     * @return array Cart items (one entry per row).
     */
    public function get_cart($params = []): array;

    /**
     * Get cart rows. Alias for the legacy `get()` shape that the
     * service-locator pattern (`app('cart_manager')->get(...)`)
     * relies on.
     *
     * @param array $params
     * @return array
     */
    public function get($params = []): array;

    /**
     * Add or update a cart item.
     *
     * @param array $data Must include rel_id (product id) + qty.
     * @return array  The saved/updated cart row.
     */
    public function update_cart(array $data): array;

    /**
     * Remove a cart item.
     *
     * @param mixed $data Either an id, or an array containing `id`.
     * @return array
     */
    public function remove_item($data): array;

    /**
     * Update the quantity of an existing cart item.
     *
     * @param array $data Must include `id` + `qty`.
     * @return array
     */
    public function update_item_qty(array $data): array;

    /**
     * Empty the active session's cart. Returns the cleared cart so
     * callers can render a "0 items" state without re-querying.
     *
     * @return array
     */
    public function empty_cart(): array;

    /**
     * Sum all cart-item prices across the active session.
     *
     * @return float
     */
    public function getTotal(): float;
}
