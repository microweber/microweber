<?php

use MicroweberPackages\PhpQuery\PhpQuery;

// Initialize libxml internal error handling
if (function_exists('libxml_use_internal_errors')) {
    libxml_use_internal_errors(true);
}

// Initialize plugins
if (!is_object(PhpQuery::$plugins)) {
    PhpQuery::$plugins = new \MicroweberPackages\PhpQuery\PhpQueryPlugins();
}

/**
 * Shortcut to PhpQuery::pq($arg1, $context)
 * Chainable.
 *
 * @see PhpQuery::pq()
 *
 * @return \MicroweberPackages\PhpQuery\PhpQueryObject|false
 */
if (!function_exists('pq')) {
    function pq($arg1, $context = null)
    {
        $args = func_get_args();

        return call_user_func_array(
            array(PhpQuery::class, 'pq'), $args
        );
    }
}

// Register backward-compatible global class aliases
// This ensures existing code using \phpQuery:: and \phpQueryObject:: keeps working
if (!class_exists('phpQuery', false)) {
    class_alias(\MicroweberPackages\PhpQuery\PhpQuery::class, 'phpQuery');
}
if (!class_exists('phpQueryObject', false)) {
    class_alias(\MicroweberPackages\PhpQuery\PhpQueryObject::class, 'phpQueryObject');
}
if (!class_exists('phpQueryEvents', false)) {
    class_alias(\MicroweberPackages\PhpQuery\Events\PhpQueryEvents::class, 'phpQueryEvents');
}
if (!class_exists('DOMEvent', false)) {
    class_alias(\MicroweberPackages\PhpQuery\Events\DOMEvent::class, 'DOMEvent');
}
if (!class_exists('DOMDocumentWrapper', false)) {
    class_alias(\MicroweberPackages\PhpQuery\Dom\DOMDocumentWrapper::class, 'DOMDocumentWrapper');
}
if (!class_exists('Callback', false)) {
    class_alias(\MicroweberPackages\PhpQuery\Callbacks\Callback::class, 'Callback');
}
if (!class_exists('CallbackBody', false)) {
    class_alias(\MicroweberPackages\PhpQuery\Callbacks\CallbackBody::class, 'CallbackBody');
}
if (!class_exists('CallbackReturnReference', false)) {
    class_alias(\MicroweberPackages\PhpQuery\Callbacks\CallbackReturnReference::class, 'CallbackReturnReference');
}
if (!class_exists('CallbackReturnValue', false)) {
    class_alias(\MicroweberPackages\PhpQuery\Callbacks\CallbackReturnValue::class, 'CallbackReturnValue');
}
if (!class_exists('CallbackParameterToReference', false)) {
    class_alias(\MicroweberPackages\PhpQuery\Callbacks\CallbackParameterToReference::class, 'CallbackParameterToReference');
}
if (!class_exists('CallbackParam', false)) {
    class_alias(\MicroweberPackages\PhpQuery\Callbacks\CallbackParam::class, 'CallbackParam');
}
if (!class_exists('ICallbackNamed', false)) {
    class_alias(\MicroweberPackages\PhpQuery\Callbacks\ICallbackNamed::class, 'ICallbackNamed');
}
if (!class_exists('phpQueryPlugins', false)) {
    class_alias(\MicroweberPackages\PhpQuery\PhpQueryPlugins::class, 'phpQueryPlugins');
}

// -- Multibyte Compatibility functions ---------------------------------------
// http://svn.iphonewebdev.com/lace/lib/mb_compat.php

/*
 *  mb_internal_encoding()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_internal_encoding')) {
    function mb_internal_encoding($enc)
    {
        return true;
    }
}

/*
 *  mb_regex_encoding()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_regex_encoding')) {
    function mb_regex_encoding($enc)
    {
        return true;
    }
}

/*
 *  mb_strlen()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_strlen')) {
    function mb_strlen($str)
    {
        return strlen($str);
    }
}

/*
 *  mb_strpos()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_strpos')) {
    function mb_strpos($haystack, $needle, $offset = 0)
    {
        return strpos($haystack, $needle, $offset);
    }
}
/*
 *  mb_stripos()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_stripos')) {
    function mb_stripos($haystack, $needle, $offset = 0)
    {
        return stripos($haystack, $needle, $offset);
    }
}

/*
 *  mb_substr()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_substr')) {
    function mb_substr($str, $start, $length = 0)
    {
        return substr($str, $start, $length);
    }
}

/*
 *  mb_substr_count()
 *
 *  Included for mbstring pseudo-compatability.
 */
if (!function_exists('mb_substr_count')) {
    function mb_substr_count($haystack, $needle)
    {
        return substr_count($haystack, $needle);
    }
}
