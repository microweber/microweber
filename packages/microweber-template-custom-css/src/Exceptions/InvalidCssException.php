<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Exceptions;

use Exception;

class InvalidCssException extends Exception
{
    /**
     * @param  list<string>  $errors
     */
    public function __construct(
        string $message = 'Invalid CSS',
        protected array $errors = [],
        int $code = 422,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return list<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
