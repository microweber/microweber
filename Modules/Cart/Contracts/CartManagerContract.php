<?php

declare(strict_types=1);

namespace Modules\Cart\Contracts;

/**
 * AI-105 / TICKET-AY (cycle-113 2026-05-09): Module Contracts
 * Foundation — Phase 1 / first contract.
 *
 * The Cart Manager contract pins the public surface that other
 * modules rely on. By depending on this interface (not the
 * concrete `Modules\Cart\CartManager` implementation), callers
 * get:
 *
 *   - Compile-time / static-analysis friendliness (PHPStan can
 *     verify the call surface).
 *   - Mockability for tests without booting the full cart
 *     subsystem.
 *   - Drift detection — ContractValidator (AI-105 phase 2) can
 *     scan this interface against the implementation and flag
 *     drift before it reaches a caller.
 *
 * Phase 1 (this commit) declares the interface only. Phase 2
 * binds the existing `CartManager` to this contract in the
 * service container so callers can `app(CartManagerContract::class)`.
 *
 * Phase 3 adds `bin/contract:validate` artisan command + the
 * `ContractTestTrait` for the tests/Feature contract suite.
 *
 * Target (per the brief): all 4 core modules implement contracts.
 * This is the first; Posts, Product, Order follow.
 */
interface CartManagerContract
{
    /**
     * Get the current cart contents for the active session.
     *
     * @param array $params Optional filters (limit, order_by, etc.).
     * @return array Cart items (one entry per row).
     */
    public function get_cart(array $params = []): array;

    /**
     * Add an item to the active session's cart.
     *
     * @param array $params Must include rel_id (product id) + qty.
     * @return array|false The saved cart row, or false on failure.
     */
    public function add_to_cart(array $params);

    /**
     * Remove a cart item by its row id.
     *
     * @param int $itemId
     * @return bool
     */
    public function remove_from_cart(int $itemId): bool;

    /**
     * Update the quantity of a cart item.
     *
     * @param int $itemId
     * @param int $qty
     * @return bool
     */
    public function update_qty(int $itemId, int $qty): bool;

    /**
     * Empty the active session's cart.
     */
    public function empty_cart(): void;

    /**
     * Total amount across all items in the active session's cart.
     */
    public function getCartAmount(): float;
}
