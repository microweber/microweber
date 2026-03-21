<?php

namespace Modules\Cart\Exceptions;

/**
 * Exception thrown when a cart or cart item is not found
 *
 * @package Modules\Cart\Exceptions
 */
class CartNotFoundException extends CartException
{
    /**
     * Create exception for cart not found by ID
     *
     * @param int $cartId
     * @return static
     */
    public static function byId(int $cartId): self
    {
        $message = sprintf('Cart not found with ID: %d', $cartId);
        return new static($message, 404, ['cart_id' => $cartId]);
    }

    /**
     * Create exception for cart item not found
     *
     * @param int|string $itemId
     * @return static
     */
    public static function itemNotFound($itemId): self
    {
        $message = sprintf('Cart item not found: %s', $itemId);
        return new static($message, 404, ['item_id' => $itemId]);
    }

    /**
     * Create exception for cart not found by session
     *
     * @param string $sessionId
     * @return static
     */
    public static function bySession(string $sessionId): self
    {
        $message = 'Cart not found for current session';
        return new static($message, 404, ['session_id' => $sessionId]);
    }

    /**
     * Create exception for cart not found by order
     *
     * @param int $orderId
     * @return static
     */
    public static function byOrder(int $orderId): self
    {
        $message = sprintf('Cart not found for order ID: %d', $orderId);
        return new static($message, 404, ['order_id' => $orderId]);
    }
}
