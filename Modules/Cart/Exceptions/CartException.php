<?php

namespace Modules\Cart\Exceptions;

use Exception;

/**
 * Base exception for Cart module operations
 *
 * @package Modules\Cart\Exceptions
 */
class CartException extends Exception
{
    /**
     * Additional context data for the exception
     *
     * @var array
     */
    protected $context = [];

    /**
     * CartException constructor.
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
}
