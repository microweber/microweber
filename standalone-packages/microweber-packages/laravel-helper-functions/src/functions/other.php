<?php


if (!function_exists('params_stripslashes_array')) {
    function params_stripslashes_array($array)
    {
        return is_array($array) ? array_map('params_stripslashes_array', $array) : stripslashes($array);
    }
}

if (!function_exists('br2nl')) {
    function br2nl($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }
}


if (!function_exists('mb_trim')) {
    function mb_trim($string, $charlist = null)
    {
        if (is_null($charlist)) {
            return trim($string);
        } else {
            $charlist = str_replace('/', '\/', preg_quote($charlist));
            return preg_replace("/(^[$charlist]+)|([$charlist]+$)/us", '', $string);
        }
    }
}
if (!function_exists('__ewchar_to_utf8')) {
    function __ewchar_to_utf8($matches)
    {
        $ewchar = $matches[1];
        $binwchar = hexdec($ewchar);
        $wchar = chr(($binwchar >> 8) & 0xFF) . chr(($binwchar) & 0xFF);

        return iconv('unicodebig', 'utf-8', $wchar);
    }
}
if (!function_exists('special_unicode_to_utf8')) {
    function special_unicode_to_utf8($str)
    {
        return preg_replace_callback("/\\\u([[:xdigit:]]{4})/i", '__ewchar_to_utf8', $str);
    }
}


/**
 * Encode arbitrary data into base-62
 * Note that because base-62 encodes slightly less than 6 bits per character (actually 5.95419631038688), there is some wastage
 * In order to make this practical, we chunk in groups of up to 8 input chars, which give up to 11 output chars
 * with a wastage of up to 4 bits per chunk, so while the output is not quite as space efficient as a
 * true multiprecision conversion, it's orders of magnitude faster
 * Note that the output of this function is not compatible with that of a multiprecision conversion, but it's a practical encoding implementation
 * The encoding overhead tends towards 37.5% with this chunk size; bigger chunk sizes can be slightly more space efficient, but may be slower
 * Base-64 doesn't suffer this problem because it fits into exactly 6 bits, so it generates the same results as a multiprecision conversion
 * Requires PHP 5.3.2 and gmp 4.2.0
 * @param string $data Binary data to encode
 * @return string Base-62 encoded text (not chunked or split)
 */
if (!function_exists('base62_encode')) {
    function base62_encode($data)
    {
        $outstring = '';
        $l = strlen($data);
        for ($i = 0; $i < $l; $i += 8) {
            $chunk = substr($data, $i, 8);
            $outlen = ceil((strlen($chunk) * 8) / 6); //8bit/char in, 6bits/char out, round up
            $x = bin2hex($chunk);  //gmp won't convert from binary, so go via hex
            $w = gmp_strval(gmp_init(ltrim($x, '0'), 16), 62); //gmp doesn't like leading 0s
            $pad = str_pad($w, $outlen, '0', STR_PAD_LEFT);
            $outstring .= $pad;
        }
        return $outstring;
    }
}

/**
 * Decode base-62 encoded text into binary
 * Note that because base-62 encodes slightly less than 6 bits per character (actually 5.95419631038688), there is some wastage
 * In order to make this practical, we chunk in groups of up to 11 input chars, which give up to 8 output chars
 * with a wastage of up to 4 bits per chunk, so while the output is not quite as space efficient as a
 * true multiprecision conversion, it's orders of magnitude faster
 * Note that the input of this function is not compatible with that of a multiprecision conversion, but it's a practical encoding implementation
 * The encoding overhead tends towards 37.5% with this chunk size; bigger chunk sizes can be slightly more space efficient, but may be slower
 * Base-64 doesn't suffer this problem because it fits into exactly 6 bits, so it generates the same results as a multiprecision conversion
 * Requires PHP 5.3.2 and gmp 4.2.0
 * @param string $data Base-62 encoded text (not chunked or split)
 * @return string Decoded binary data
 */
if (!function_exists('base62_decode')) {
    function base62_decode($data)
    {
        $outstring = '';
        $l = strlen($data);
        for ($i = 0; $i < $l; $i += 11) {
            $chunk = substr($data, $i, 11);
            $outlen = floor((strlen($chunk) * 6) / 8); //6bit/char in, 8bits/char out, round down
            $y = gmp_strval(gmp_init(ltrim($chunk, '0'), 62), 16); //gmp doesn't like leading 0s
            $pad = str_pad($y, $outlen * 2, '0', STR_PAD_LEFT); //double output length as as we're going via hex (4bits/char)
            $outstring .= pack('H*', $pad); //same as hex2bin
        }
        return $outstring;
    }
}

if (!function_exists('hashClosure')) {
    function hashClosure($f)
    {
        $rf = new \ReflectionFunction($f);
        $pseudounique = $rf->getFileName() . $rf->getEndLine();
        return crc32($pseudounique);
    }
}


if (!function_exists('mergeScreenshotParts')) {
    function mergeScreenshotParts($files, $outputFilename = 'full-screenshot.png')
    {

        $targetHeight = 0;

        $allImageSizes = [];
        foreach ($files as $file) {
            $imageSize = getimagesize($file);
            $allImageSizes[] = [
                'file' => $file,
                'width' => $imageSize[0],
                'height' => $imageSize[1],
            ];
            $targetHeight += $imageSize[1];
        }

        $targetWidth = $allImageSizes[0]['width'];
        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        $i = 0;
        foreach ($allImageSizes as $imageSize) {

            $mergeFile = imagecreatefrompng($imageSize['file']);

            $destinationY = 0;
            if ($i > 0) {
                $destinationY = $imageSize['height'] * $i;
            }

            imagecopymerge($targetImage, $mergeFile, 0, $destinationY, 0, 0, $imageSize['width'], $imageSize['height'], 100);
            imagedestroy($mergeFile);
            $i++;
        }

        imagepng($targetImage, $outputFilename, 8);
    }
}

if (!function_exists('sanitize_path')) {
    function sanitize_path($path)
    {
        $path = str_replace('..', '-', $path);
        $path = str_replace('./', '-', $path);
        $path = str_replace('.\\', '-', $path);
        $path = str_replace(';', '-', $path);
        $path = str_replace('&&', '-', $path);
        $path = str_replace('|', '-', $path);
        $path = str_replace('>', '-', $path);

        return $path;
    }
}

if (!function_exists('array_map_recursive')) {
    function array_map_recursive($callback, $array)
    {
        $func = function ($item) use (&$func, &$callback) {
            return is_array($item) ? array_map($func, $item) : call_user_func($callback, $item);
        };

        return array_map($func, $array);
    }
}


if (!function_exists('is_cli')) {
    function is_cli()
    {
        static $is;
        if ($is !== null) {
            return $is;
        }

        if (!empty($_SERVER) and isset($_SERVER['SERVER_SOFTWARE']) and isset($_SERVER['SERVER_PROTOCOL'])) {
            $is = false;
            return $is;
        }

        $php_sapi_name = false;
        if (defined('PHP_SAPI')) {
            $php_sapi_name = PHP_SAPI;
        } else if (function_exists('php_sapi_name')) {
            $php_sapi_name = php_sapi_name();
        }


        if (function_exists('php_sapi_name') and
            $php_sapi_name === 'apache2handler'
        ) {
            $is = false;
            return false;
        }


        if (
            defined('STDIN')
            or $php_sapi_name === 'cli'
            or $php_sapi_name === 'cli-server'
            or array_key_exists('SHELL', $_ENV)

        ) {
            $is = true;
            return true;
        }


        $is = false;
        return false;
    }
}


if (!function_exists('php_can_use_func')) {
    /**
     * Function to check if you can use a PHP function
     */
    function php_can_use_func($func_name)
    {
        if (!defined('INI_SYSTEM_CHECK_DISABLED')) {
            define('INI_SYSTEM_CHECK_DISABLED', ini_get('disable_functions'));
        }


        //if ($func_name == 'putenv') {
        $available = true;
        if (ini_get('safe_mode')) {
            $available = false;
        } else {
            $d = INI_SYSTEM_CHECK_DISABLED;
            $s = ini_get('suhosin.executor.func.blacklist');
            if ("$d$s") {
                $array = preg_split('/,\s*/', "$d,$s");
                if (in_array($func_name, $array)) {
                    $available = false;
                }
            }
        }

        if (str_contains(INI_SYSTEM_CHECK_DISABLED, (string)$func_name)) {
            return false;
        }

        return $available;
        //}

        if (!strstr(INI_SYSTEM_CHECK_DISABLED, (string)$func_name)) {
            return true;
        }

    }
}


if (!function_exists('autoload_add_namespace')) {
    function autoload_add_namespace($dirname, $namespace)
    {
        spl_autoload_register(function ($class) use ($dirname, $namespace) {
            $prefix = $namespace;
            $base_dir = $dirname;
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }
            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        });
    }
}

if (!function_exists('autoload_add')) {
    function autoload_add($dirname)
    {
        set_include_path($dirname .
            PATH_SEPARATOR . get_include_path());
    }
}


if (!function_exists('mw_var')) {
// stores vars in memory
    function mw_var($key, $new_val = false)
    {
        static $mw_var_storage;
        $contstant = ($key);
        if ($new_val == false) {
            if (isset($mw_var_storage[$contstant]) != false) {
                return $mw_var_storage[$contstant];
            } else {
                return false;
            }
        } else {
            //if (isset($mw_var_storage[$contstant]) == false) {
            $mw_var_storage[$contstant] = $new_val;

            return $new_val;
            //}
        }

        return false;
    }
}



/**
 * Returns the current microtime.
 *
 * @return bool|string $date The current microtime
 *
 * @category Date
 *
 * @link     http://www.webdesign.org/web-programming/php/script-execution-time.8722.html#ixzz2QKEAC7PG
 */
if (!function_exists('microtime_float')) {
    function microtime_float()
    {
        list($msec, $sec) = explode(' ', microtime());
        $microtime = (float)$msec + (float)$sec;

        return $microtime;
    }
}


if (!function_exists('d')) {
    function d($dump)
    {
        var_dump($dump);
    }
}


