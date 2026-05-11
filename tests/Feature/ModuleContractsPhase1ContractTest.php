<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Module Contracts Foundation (Phase 1) regression coverage.
 *
 * Pins:
 *   - Cart, Order, and Product (InventoryService) contracts exist
 *     at the canonical `Modules/<X>/Contracts/` paths.
 *   - Each contract is an interface (not a concrete class).
 *   - Each module's ServiceProvider declares a singleton DI binding
 *     so callers can `app(Contract::class)` to receive the
 *     concrete implementation.
 *   - The Cart contract's method signatures match the actual
 *     CartManager surface (verified with grep against the impl).
 *
 * Contract test style: file-system reads only, no DB touch.
 */
class ModuleContractsPhase1ContractTest extends TestCase
{
    private const CONTRACTS = [
        'Cart'    => 'Modules/Cart/Contracts/CartManagerContract.php',
        'Order'   => 'Modules/Order/Contracts/OrderManagerContract.php',
        'Product' => 'Modules/Product/Contracts/InventoryServiceContract.php',
    ];

    private const PROVIDERS = [
        'Cart'    => 'Modules/Cart/Providers/CartServiceProvider.php',
        'Order'   => 'Modules/Order/Providers/OrderServiceProvider.php',
        'Product' => 'Modules/Product/Providers/ProductServiceProvider.php',
    ];

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function each_contract_file_exists_and_declares_an_interface(): void
    {
        foreach (self::CONTRACTS as $module => $rel) {
            $src = $this->read($rel);
            $this->assertMatchesRegularExpression(
                '/^interface\\s+\\w+Contract\\b/m',
                $src,
                "{$rel}: must declare an `interface ...Contract` (not a class)"
            );
            $this->assertStringContainsString(
                'AI-105',
                $src,
                "{$rel}: must carry the module-contracts audit-trail marker"
            );
        }
    }

    #[Test]
    public function cart_contract_signatures_match_actual_cart_manager_surface(): void
    {
        $src = $this->read(self::CONTRACTS['Cart']);

        // Real CartManager methods (verified via grep `public function`
        // in Modules/Cart/Repositories/CartManager.php). Mismatched
        // names would crash at DI-resolution time.
        foreach ([
            'public function get_cart',
            'public function get(',
            'public function update_cart',
            'public function remove_item',
            'public function update_item_qty',
            'public function empty_cart',
            'public function getTotal',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "CartManagerContract: must declare `{$needle}` to match the real CartManager"
            );
        }
    }

    #[Test]
    public function order_contract_declares_canonical_methods(): void
    {
        $src = $this->read(self::CONTRACTS['Order']);

        foreach ([
            'public function place_order',
            'public function save',
            'public function get_by_id',
            'public function get(',
            'public function get_items',
            'public function delete_order',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "OrderManagerContract: must declare `{$needle}`"
            );
        }
    }

    #[Test]
    public function inventory_contract_declares_canonical_methods(): void
    {
        $src = $this->read(self::CONTRACTS['Product']);

        foreach ([
            'public function getStock',
            'public function getAvailableQuantity',
            'public function getReservedQuantity',
            'public function hasStock',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "InventoryServiceContract: must declare `{$needle}`"
            );
        }
    }

    #[Test]
    public function each_provider_binds_its_contract(): void
    {
        $contractFqcn = [
            'Cart'    => '\\Modules\\Cart\\Contracts\\CartManagerContract::class',
            'Order'   => '\\Modules\\Order\\Contracts\\OrderManagerContract::class',
            'Product' => '\\Modules\\Product\\Contracts\\InventoryServiceContract::class',
        ];

        foreach (self::PROVIDERS as $module => $rel) {
            $src = $this->read($rel);
            $this->assertStringContainsString(
                $contractFqcn[$module],
                $src,
                "{$rel}: must reference `{$contractFqcn[$module]}` in a singleton binding"
            );

            // The binding must use the singleton method (not bind / scoped).
            $this->assertMatchesRegularExpression(
                '/->singleton\\(\\s*\\\\Modules\\\\' . preg_quote($module, '/') . '\\\\Contracts\\\\\\w+Contract::class/s',
                $src,
                "{$rel}: must bind the contract via `\$this->app->singleton(...)`"
            );
        }
    }

    #[Test]
    public function di_container_resolves_each_contract_to_concrete(): void
    {
        // Functional pin: ask the container to make() each contract,
        // assert the returned object actually implements the
        // declared interface. Catches misnamed bindings + signature
        // drift between contract and impl.
        $cases = [
            \Modules\Cart\Contracts\CartManagerContract::class,
            \Modules\Order\Contracts\OrderManagerContract::class,
            \Modules\Product\Contracts\InventoryServiceContract::class,
        ];

        foreach ($cases as $contract) {
            $instance = app($contract);
            $this->assertInstanceOf(
                $contract,
                $instance,
                "Container resolved {$contract} but returned an object that does NOT implement the interface"
            );
        }
    }
}
