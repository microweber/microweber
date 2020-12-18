<?php

function event_trigger($api_function, $data = false)
{
    return mw()->event_manager->trigger($api_function, $data);
}

/**
 * Adds event callback.
 *
 * @param $function_name
 * @param bool|mixed|callable $callback
 *
 * @return array|mixed|false
 */
function event_bind($function_name, $callback = false)
{
    return mw()->event_manager->on($function_name, $callback);
}
