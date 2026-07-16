<?php

namespace MicroweberPackages\PhpQuery\Callbacks;

/**
 * Legacy callback that historically built a closure from PHP-code **strings**
 * (a shim around the removed create_function()).
 *
 * Executing code supplied as a string — via create_function() or eval() — is an
 * arbitrary-code-execution risk, so that behaviour has been removed. The class
 * is retained only so the `CallbackBody` alias / any `class_exists()` probes
 * keep resolving; constructing it now throws.
 *
 * There are no callers in this codebase. If you need a callback, pass a real
 * Closure/callable to {@see Callback} instead:
 *
 *     new Callback(function ($node) { return ...; });
 *
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 */
class CallbackBody extends Callback
{
    /**
     * @param string $paramList Legacy parameter-list string (e.g. '$node')
     * @param string $code      Legacy PHP-code-body string
     */
    public function __construct($paramList, $code, $param1 = null, $param2 = null, $param3 = null)
    {
        throw new \BadMethodCallException(
            'CallbackBody built a callback by executing a PHP-code string (create_function/eval) and is '
            . 'no longer supported for security reasons. Pass a real Closure/callable to '
            . Callback::class . ' instead, e.g. new Callback(function ($node) { ... }).'
        );
    }
}
