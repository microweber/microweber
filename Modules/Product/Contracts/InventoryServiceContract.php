<?php

declare(strict_types=1);

namespace Modules\Product\Contracts;

/**
 * AI-105 / TICKET-AY (cycle-118 2026-05-09): Module Contracts
 * Foundation — Phase 1 / third contract.
 *
 * Pins the public surface of `Modules\Product\Services\InventoryService`
 * so other modules can depend on the interface, not the concrete
 * implementation. The brief asked for `ProductContract.php` under
 * `src/MicroweberPackages/Products/Contracts/`; mapping the brief's
 * intent to the actual layout — Product is a Module (no
 * `MicroweberPackages\Products\` namespace exists) and the canonical
 * "product" public surface IS the InventoryService (stock checks,
 * reservations, deductions). Other modules depend on InventoryService
 * for cart/checkout flows; this contract pins that integration
 * surface.
 *
 * The DI binding lives in `ProductServiceProvider::register()` and
 * `app(InventoryServiceContract::class)` resolves to the singleton.
 */
interface InventoryServiceContract
{
    public function getStock(int $productId, ?int $variantId = null): int;

    public function getAvailableQuantity(int $productId, ?int $variantId = null): int;

    public function getReservedQuantity(int $productId, ?int $variantId = null): int;

    public function hasStock(int $productId, int $quantity, ?int $variantId = null): bool;
}
