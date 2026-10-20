<?php

declare(strict_types=1);

namespace Arcanedev\Support\Validation;

use Illuminate\Contracts\Validation\Rule as RuleContract;

/**
 * Class     Rule
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @deprecated Use the ValidationRule class instead.
 * @see \Illuminate\Contracts\Validation\ValidationRule
 */
abstract class Rule implements RuleContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The message that should be used when validation fails.
     *
     * @var string|array
     */
    protected $message;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the message that should be used when the rule fails.
     *
     * @param  string|array  $message
     *
     * @return $this
     */
    public function withMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return $this->message;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Fail if the validation rule.
     *
     * @param  string|array  $message
     *
     * @return bool
     */
    protected function fail($message): bool
    {
        $this->withMessage($message);

        return false;
    }
}
