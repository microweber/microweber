<?php

namespace MicroweberPackages\PhpQuery\Callbacks;

/**
 * Callback type which on execution returns reference passed during creation.
 *
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 */
class CallbackReturnReference extends Callback implements ICallbackNamed
{
    protected $reference;

    public function __construct(&$reference, $name = null)
    {
        $this->reference = &$reference;
        $this->callback = array($this, 'callback');
    }

    public function callback()
    {
        return $this->reference;
    }

    public function getName()
    {
        return 'Callback: '.$this->name;
    }

    public function hasName()
    {
        return isset($this->name) && $this->name;
    }
}