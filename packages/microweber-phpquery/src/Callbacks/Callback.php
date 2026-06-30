<?php

namespace MicroweberPackages\PhpQuery\Callbacks;

/**
 * Callback class introduces currying-like pattern.
 *
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 *
 * @link http://code.google.com/p/phpquery/wiki/Callbacks#Param_Structures
 */
class Callback implements ICallbackNamed
{
    public $callback = null;
    public $params = null;
    protected $name;

    public function __construct($callback, $param1 = null, $param2 = null, $param3 = null)
    {
        $params = func_get_args();
        $params = array_slice($params, 1);
        if ($callback instanceof self) {
            // TODO implement recursion
        } else {
            $this->callback = $callback;
            $this->params = $params;
        }
    }

    public function getName()
    {
        return 'Callback: '.$this->name;
    }

    public function hasName()
    {
        return isset($this->name) && $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}