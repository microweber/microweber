<?php

namespace MicroweberPackages\PhpQuery\Callbacks;

/**
 * CallbackParameterToReference can be used when we don't really want a callback,
 * only parameter passed to it. CallbackParameterToReference takes first
 * parameter's value and passes it to reference.
 *
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 */
class CallbackParameterToReference extends Callback
{
    /**
     * @param mixed $reference
     */
    public function __construct(&$reference)
    {
        $this->callback = &$reference;
    }
}