<?php

namespace Modules\Cart\Exceptions;

/**
 * Exception thrown when cart item data is invalid
 *
 * @package Modules\Cart\Exceptions
 */
class InvalidCartItemException extends CartException
{
    /**
     * Create exception for missing required field
     *
     * @param string $field
     * @return static
     */
    public static function missingRequiredField(string $field): self
    {
        $message = sprintf('Cart item is missing required field: %s', $field);
        return new static($message, 400, ['field' => $field]);
    }

    /**
     * Create exception for invalid quantity
     *
     * @param int $qty
     * @param string $reason
     * @return static
     */
    public static function invalidQuantity(int $qty, string $reason = ''): self
    {
        $message = sprintf('Invalid cart quantity: %d', $qty);
        if ($reason) {
            $message .= sprintf(' (%s)', $reason);
        }
        return new static($message, 400, ['quantity' => $qty, 'reason' => $reason]);
    }

    /**
     * Create exception for product not found
     *
     * @param int $productId
     * @return static
     */
    public static function productNotFound(int $productId): self
    {
        $message = sprintf('Product not found with ID: %d', $productId);
        return new static($message, 404, ['product_id' => $productId]);
    }

    /**
     * Create exception for out of stock
     *
     * @param int $productId
     * @param int $requestedQty
     * @param int $availableQty
     * @return static
     */
    public static function outOfStock(
        int $productId,
        int $requestedQty,
        int $availableQty
    ): self {
        $message = sprintf(
            'Product %d is out of stock. Requested: %d, Available: %d',
            $productId,
            $requestedQty,
            $availableQty
        );
        return new static($message, 400, [
            'product_id' => $productId,
            'requested_quantity' => $requestedQty,
            'available_quantity' => $availableQty,
        ]);
    }
}
