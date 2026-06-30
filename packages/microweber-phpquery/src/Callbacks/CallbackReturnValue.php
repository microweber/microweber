<?php

namespace MicroweberPackages\PhpQuery\Callbacks;

/**
 * Callback type which on execution returns value passed during creation.
 *
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 */
class CallbackReturnValue extends Callback implements ICallbackNamed
{
    protected $value;
    protected $name;

    public function __construct($value, $name = null)
    {
        $this->value = &$value;
        $this->name = $name;
        $this->callback = array($this, 'callback');
    }

    public function callback()
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->getName();
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