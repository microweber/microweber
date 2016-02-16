<?php

/**
 * Returns a human readable filesize
 * @package Utils
 * @category Files
 * @author      wesman20 (php.net)
 * @author      Jonas John
 * @version     0.3
 * @link        http://www.jonasjohn.de/snippets/php/readable-filesize.htm
 */
function file_size_nice($size)
{
    // Adapted from: http://www.php.net/manual/en/function.filesize.php

    $mod = 1024;

    $units = explode(' ', 'B KB MB GB TB PB');
    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }

    return round($size, 2) . ' ' . $units[$i];
}



/**
 *
 *
 * Recursive glob()
 *
 * @access public
 * @package Utils
 * @category Files
 *
 * @uses is_array()
 * @param int|string $pattern
 * the pattern passed to glob()
 * @param int $flags
 * the flags passed to glob()
 * @param string $path
 * the path to scan
 * @return mixed
 * an array of files in the given path matching the pattern.
 */
function rglob($pattern = '*', $flags = 0, $path = '')
{
    if (!$path && ($dir = dirname($pattern)) != '.') {
        if ($dir == '\\' || $dir == '/')
            $dir = '';
        return rglob(basename($pattern), $flags, $dir . DS);
    }

    $path = normalize_path($path, 1);
    $paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
    $files = glob($path . $pattern, $flags);


    if (is_array($paths)) {
        foreach ($paths as $p) {
            $temp = rglob($pattern, false, $p . DS);
            if (is_array($temp) and is_array($files) and !empty($files)) {
                $files = array_merge($files, $temp);
            } else if (is_array($temp) and !empty($temp)) {
                $files = $temp;
            }
        }
    }

    return $files;


}



/**
 * Returns extension from a filename
 *
 * @param $LoSFileName Your filename
 *
 * @return string  $filename extension
 * @package  Utils
 * @category Files
 */
function get_file_extension($LoSFileName) {
    $LoSFileName = rtrim($LoSFileName, '.');
    $LoSFileExtensions = substr($LoSFileName, strrpos($LoSFileName, '.') + 1);

    return $LoSFileExtensions;
}

/**
 * Returns a filename without extension
 *
 * @param $filename The filename
 *
 * @return string  $filename without extension
 * @package  Utils
 * @category Files
 */
function no_ext($filename) {
    $filename = rtrim($filename, '.');
    $filebroken = explode('.', $filename);
    array_pop($filebroken);

    return implode('.', $filebroken);

}

function url2dir($path) {
    if (trim($path)==''){
        return false;
    }

    $path = str_ireplace(site_url(), MW_ROOTPATH, $path);
    $path = str_replace('\\', '/', $path);
    $path = str_replace('//', '/', $path);

    return normalize_path($path, false);
}


function dir2url($path) {
    $path = str_ireplace(MW_ROOTPATH, '', $path);
    $path = str_replace('\\', '/', $path);
    $path = str_replace('//', '/', $path);

    //var_dump($path);
    return site_url($path);
}


/**
 * Makes directory recursive, returns TRUE if exists or made and false on error
 *
 * @param string $pathname
 *            The directory path.
 *
 * @return boolean
 *          returns TRUE if exists or made or FALSE on failure.
 *
 * @package  Utils
 * @category Files
 */
function mkdir_recursive($pathname) {
    if ($pathname==''){
        return false;
    }
    is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname));

    return is_dir($pathname) || @mkdir($pathname);
}
