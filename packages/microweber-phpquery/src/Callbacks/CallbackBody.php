<?php

namespace MicroweberPackages\PhpQuery\Callbacks;

/**
 * Shorthand for new Callback(create_function(...), ...);.
 *
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 */
class CallbackBody extends Callback
{
    public function __construct($paramList, $code, $param1 = null, $param2 = null, $param3 = null)
    {
        $params = func_get_args();
        $params = array_slice($params, 2);
        $this->callback = @create_function($paramList, $code);
        $this->params = $params;
    }
}