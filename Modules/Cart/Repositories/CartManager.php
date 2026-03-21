<?php

namespace Modules\Cart\Repositories;

use Modules\Cart\Services\CartService;
use Modules\Cart\Services\CartTotalsService;
use Modules\Cart\Services\CartCouponService;

/**
 * CartManager - Facade/Adapter for cart services
 *
 * This class serves as a backward-compatible wrapper that delegates
 * to specialized service classes:
 * - CartService: Core cart operations (get, add, update, remove)
 * - CartTotalsService: Totals calculation (sum, totals, tax)
 * - CartCouponService: Discount/coupon logic
 *
 * @deprecated Use the service classes directly instead
 */
class CartManager
{
    /** @var \MicroweberPackages\App\LaravelApplication */
    public $app;

    /** @var CartService */
    protected $cartService;

    /** @var CartTotalsService */
    protected $totalsService;

    /** @var CartCouponService */
    protected $couponService;

    public $table = 'cart';
    public $coupon_data = false;

    public function __construct($app = null)
    {
        $this->app = $app ?: app();
        $this->cartService = new CartService($this->app);
        $this->totalsService = new CartTotalsService($this->app);
        $this->couponService = new CartCouponService($this->app);
    }

    /**
     * Get cart items.
     *
     * @param array|string $params
     * @return array
     */
    public function get_cart($params = []): array
    {
        return $this->cartService->getCart($params);
    }

    /**
     * Alias for get_cart.
     *
     * @param array|string $params
     * @return array
     */
    public function get($params = []): array
    {
        return $this->cartService->getCart($params);
    }

    /**
     * Sum cart items.
     *
     * @param bool $return_amount
     * @return float|int
     */
    public function sum($return_amount = true)
    {
        return $this->totalsService->sum($return_amount);
    }

    /**
     * Get total amount.
     *
     * @return float
     */
    public function getTotal(): float
    {
        return $this->totalsService->total();
    }

    /**
     * Alias for getTotal.
     *
     * @return float
     */
    public function total(): float
    {
        return $this->totalsService->total();
    }

    /**
     * Calculate cart totals.
     *
     * @param string $return
     * @return array|mixed
     */
    public function totals($return = 'all')
    {
        return $this->totalsService->totals($return);
    }

    /**
     * Get tax amount.
     *
     * @return float
     */
    public function get_tax(): float
    {
        return $this->totalsService->getTax();
    }

    /**
     * Get discount value.
     *
     * @return float|false
     */
    public function get_discount()
    {
        return $this->couponService->getDiscountValue();
    }

    /**
     * Get discount type.
     *
     * @return string|false
     */
    public function get_discount_type()
    {
        return $this->couponService->getDiscountType();
    }

    /**
     * Get discount text.
     *
     * @return string
     */
    public function get_discount_text(): string
    {
        return $this->couponService->getDiscountText();
    }

    /**
     * Get discount value.
     *
     * @return float|false
     */
    public function get_discount_value()
    {
        return $this->couponService->getDiscountValue();
    }

    /**
     * Set coupon data.
     *
     * @param mixed $data
     * @return void
     */
    public function set_coupon_data($data): void
    {
        $this->coupon_data = $data;
    }

    /**
     * Update cart (add item).
     *
     * @param array $data
     * @return array
     */
    public function update_cart(array $data): array
    {
        return $this->cartService->updateCart($data);
    }

    /**
     * Remove item from cart.
     *
     * @param array|int $data
     * @return array
     */
    public function remove_item($data): array
    {
        return $this->cartService->removeItem($data);
    }

    /**
     * Alias for remove_item.
     *
     * @param array|int $data
     * @return array
     */
    public function remove_cart_item($data): array
    {
        return $this->cartService->removeItem($data);
    }

    /**
     * Update item quantity.
     *
     * @param array $data
     * @return array
     */
    public function update_item_qty(array $data): array
    {
        return $this->cartService->updateItemQty($data);
    }

    /**
     * Empty the cart.
     *
     * @return array
     */
    public function empty_cart(): array
    {
        return $this->cartService->emptyCart();
    }

    /**
     * Delete cart by params.
     *
     * @param array|string $params
     * @return void
     */
    public function delete_cart($params): void
    {
        $this->cartService->deleteCart($params);
    }

    /**
     * Get cart items by order ID.
     *
     * @param int $order_id
     * @return array
     */
    public function get_by_order_id($order_id): array
    {
        return $this->cartService->getByOrderId(intval($order_id));
    }

    /**
     * Recover cart from order.
     *
     * @param int|false $ord_id
     * @return void
     */
    public function recover_cart($ord_id = false): void
    {
        $this->cartService->recoverCart($ord_id);
    }

    /**
     * Check if product is in stock.
     *
     * @param int $content_id
     * @return bool
     */
    public function is_product_in_stock($content_id): bool
    {
        return $this->cartService->isProductInStock(intval($content_id));
    }

    /**
     * Get cart item image.
     *
     * @param int $cartItemId
     * @return string|false
     */
    public function get_cart_item_image($cartItemId)
    {
        return $this->cartService->getCartItemImage(intval($cartItemId));
    }

    /**
     * Get table name.
     *
     * @return string
     */
    public function table_name(): string
    {
        return 'cart';
    }

    /**
     * Get coupon data from session.
     *
     * @return array|false
     */
    public function couponCodeGetDataFromSession()
    {
        return $this->couponService->getCouponDataFromSession();
    }

    /**
     * Check if coupon is valid.
     *
     * @param string $coupon_code
     * @return bool
     */
    public function couponCodeCheckIfValid($coupon_code): bool
    {
        return $this->couponService->isCouponValid($coupon_code);
    }
}
