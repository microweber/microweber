<?php

namespace MicroweberPackages\PhpQuery;

use Exception;

/**
 * Plugins static namespace class.
 *
 * @author Tobiasz Cudnik <tobiasz.cudnik/gmail.com>
 */
class PhpQueryPlugins
{
    public function __call($method, $args)
    {
        if (isset(PhpQuery::$extendStaticMethods[$method])) {
            $return = call_user_func_array(
                PhpQuery::$extendStaticMethods[$method], $args
            );
        } elseif (isset(PhpQuery::$pluginsStaticMethods[$method])) {
            $class = PhpQuery::$pluginsStaticMethods[$method];
            $realClass = "phpQueryPlugin_$class";
            $return = call_user_func_array(
                array($realClass, $method), $args
            );

            return isset($return) ? $return : $this;
        } else {
            throw new Exception("Method '{$method}' doesnt exist");
        }
    }
}