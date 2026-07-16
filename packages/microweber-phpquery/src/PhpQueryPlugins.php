<?php

namespace MicroweberPackages\PhpQuery;

use Exception;

/**
 * Plugins namespace class — dispatches method calls to registered plugin classes
 * or user-registered extend methods.
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

            return $return ?? $this;
        }

        if (isset(PhpQuery::$pluginsStaticMethods[$method])) {
            $class = PhpQuery::$pluginsStaticMethods[$method];
            $realClass = "phpQueryPlugin_$class";
            // Also check for namespaced plugin classes
            if (!class_exists($realClass, false)) {
                $realClass = "MicroweberPackages\\PhpQuery\\Plugins\\{$class}Plugin";
            }
            if (class_exists($realClass, false) && is_callable(array($realClass, $method))) {
                $return = call_user_func_array(
                    array($realClass, $method), $args
                );
                return $return ?? $this;
            }
        }

        throw new Exception("Method '{$method}' doesn't exist");
    }
}