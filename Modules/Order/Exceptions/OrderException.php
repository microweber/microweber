<?php

namespace Modules\Order\Exceptions;

use Exception;

/**
 * Base exception for Order module operations
 *
 * @package Modules\Order\Exceptions
 */
class OrderException extends Exception
{
    /**
     * Additional context data for the exception
     *
     * @var array
     */
    protected $context = [];

    /**
     * OrderException constructor.
     *
     * @param string $message
     * @param int $code
     * @param array $context
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        array $context = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get the exception context
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Set the exception context
     *
     * @param array $context
     * @return $this
     */
    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * Create exception for database operation failures
     *
     * @param string $operation
     * @param string $table
     * @param \Throwable|null $previous
     * @return static
     */
    public static function databaseOperationFailed(
        string $operation,
        string $table,
        ?\Throwable $previous = null
    ): self {
        $message = sprintf('Database %s operation failed on table: %s', $operation, $table);
        return new static($message, 500, ['operation' => $operation, 'table' => $table], $previous);
    }

    /**
     * Create exception for order placement failures
     *
     * @param string $reason
     * @param array $orderData
     * @param \Throwable|null $previous
     * @return static
     */
    public static function orderPlacementFailed(
        string $reason,
        array $orderData = [],
        ?\Throwable $previous = null
    ): self {
        $message = sprintf('Failed to place order: %s', $reason);
        return new static($message, 500, ['order_data' => $orderData], $previous);
    }

    /**
     * Create exception for order not found
     *
     * @param int|string $orderId
     * @return static
     */
    public static function orderNotFound($orderId): self
    {
        $message = sprintf('Order not found: %s', $orderId);
        return new static($message, 404, ['order_id' => $orderId]);
    }

    /**
     * Create exception for invalid order data
     *
     * @param string $field
     * @param string $reason
     * @param \Throwable|null $previous
     * @return static
     */
    public static function invalidOrderData(
        string $field,
        string $reason,
        ?\Throwable $previous = null
    ): self {
        $message = sprintf('Invalid order data for field "%s": %s', $field, $reason);
        return new static($message, 400, ['field' => $field], $previous);
    }
}
