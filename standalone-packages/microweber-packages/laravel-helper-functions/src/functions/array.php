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


/**
 * Trims an entire array recursively.
 *
 * @param array $Input
 *                     Input array
 *
 * @return array|string
 * @version  0.2
 *
 * @link     http://www.jonasjohn.de/snippets/php/trim-array.htm
 *
 * @category Arrays
 *
 * @author   Jonas John
 *
 */
if (! function_exists('array_trim')) {
    function array_trim($Input)
    {
        if (!is_array($Input)) {
            return trim($Input);
        }

        return array_map('array_trim', $Input);
    }
}



if (!function_exists('parse_params')) {
    function parse_params($params)
    {
        $params2 = array();
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
            unset($params2);
        }

        return $params;
    }
}




if (! function_exists('array_search_multidimensional')){
    function array_search_multidimensional($array, $column, $key)
    {
        return (array_search($key, array_column($array, $column)));
    }
}



if (!function_exists('array_recursive_diff')) {
    function array_recursive_diff($aArray1, $aArray2) {
        $aReturn = array();

        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = array_recursive_diff($mValue, $aArray2[$mKey]);
                    if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }
        return $aReturn;
    }
}




