<?php
/**
 * Microweber Helper Functions
 *
 * @author      Microweber Team
 * @author      Peter Ivanov
 * @author      Bozhidar Slaveykov
 *
 * @version     0.1
 *
 * @link        http://www.microweber.com
 */
 

if (!function_exists('array_set')) {
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array   $array
     * @param  string  $key
     * @param  mixed   $value
     * @return array
     */
	function array_set(&$array, $key, $value)
	{
		if (is_null($key)) {
			return $array = $value;
		}

		$keys = explode('.', $key);

		while (count($keys) > 1) {
			$key = array_shift($keys);

			// If the key doesn't exist at this depth, we will just create an empty array
			// to hold the next value, allowing us to create the arrays to hold final
			// values at the correct depth. Then we'll keep digging into the array.
			if (! isset($array[$key]) || ! is_array($array[$key])) {
				$array[$key] = [];
			}

			$array = &$array[$key];
		}

		$array[array_shift($keys)] = $value;

		return $array;
	}
}

if (!function_exists('array_unique_recursive')) {
	function array_unique_recursive($array)
	{
	    $array = array_unique($array, SORT_REGULAR);

	    foreach ($array as $key => $elem) {
		if (is_array($elem)) {
		    $array[$key] = array_unique_recursive($elem);
		}
	    }

	    return $array;
	}
}
