<?php
if (!function_exists('file_size_nice')) {
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
	function file_size_nice($size)
	{
		// Adapted from: http://www.php.net/manual/en/function.filesize.php

		$mod = 1024;

		$units = explode(' ', 'B KB MB GB TB PB');
		for ($i = 0; $size > $mod; ++$i) {
			$size /= $mod;
		}

		return round($size, 2).' '.$units[$i];
	}
}

if (!function_exists('rglob')) {
    /**
     * Recursive glob().
     *
     * @category Files
     *
     * @uses is_array()
     *
     * @param int|string $pattern
     *                            the pattern passed to glob()
     * @param int        $flags
     *                            the flags passed to glob()
     * @param string     $path
     *                            the path to scan
     *
     * @return mixed
     *               an array of files in the given path matching the pattern.
     */
	function rglob($pattern = '*', $flags = 0, $path = '')
	{
		if (!$path && ($dir = dirname($pattern)) != '.') {
			if ($dir == '\\' || $dir == '/') {
				$dir = '';
			}

			return rglob(basename($pattern), $flags, $dir.DS);
		}

		$path = normalize_path($path, 1);
		$paths = glob($path.'*', GLOB_ONLYDIR | GLOB_NOSORT);
		$files = glob($path.$pattern, $flags);

		if (is_array($paths)) {
			foreach ($paths as $p) {
				$temp = rglob($pattern, false, $p.DS);
				if (is_array($temp) and is_array($files) and !empty($files)) {
					$files = array_merge($files, $temp);
				} elseif (is_array($temp) and !empty($temp)) {
					$files = $temp;
				}
			}
		}

		return $files;
	}
}

if (!function_exists('get_file_extension')) {

    /**
     * Returns extension from a filename.
     *
     * @param $pathToFile string filename
     *
     * @return string $filename extension
     *
     * @category Files
     */
    function get_file_extension($pathToFile)
    {
        $pathToFile = rtrim($pathToFile, '.');
        return substr($pathToFile, strrpos($pathToFile, '.') + 1);
    }
}

if (!function_exists('no_ext')) {
    /**
     * Returns a filename without extension.
     *
     * @param $filename The filename
     *
     * @return string $filename without extension
     *
     * @category Files
     */
	function no_ext($filename)
	{
		$filename = rtrim($filename, '.');
		$filebroken = explode('.', $filename);
		array_pop($filebroken);

		return implode('.', $filebroken);
	}
}

if (!function_exists('mkdir_recursive')) {

    /**
     * Makes directory recursive, returns TRUE if exists or made and false on error.
     *
     * @param string $pathname
     *                         The directory path.
     *
     * @return bool
     *              returns TRUE if exists or made or FALSE on failure.
     *
     * @category Files
     */
	function mkdir_recursive($pathname)
	{
		if ($pathname == '') {
			return false;
		}
		is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname));

		return is_dir($pathname) || @mkdir($pathname);
	}
}

if (!function_exists('rmdir_recursive')) {
	function rmdir_recursive($directory, $empty = true)
	{
		// if the path has a slash at the end we remove it here
		if (substr($directory, -1) == DIRECTORY_SEPARATOR) {
			$directory = substr($directory, 0, -1);
		}

		// if the path is not valid or is not a directory ...
		if (!is_dir($directory)) {
			// ... we return false and exit the function
			return false;

			// ... if the path is not readable
		} elseif (!is_readable($directory)) {
			// ... we return false and exit the function
			return false;

			// ... else if the path is readable
		} else {
			// we open the directory
			$handle = opendir($directory);

			// and scan through the items inside
			while (false !== ($item = readdir($handle))) {
				// if the filepointer is not the current directory
				// or the parent directory
				if ($item != '.' && $item != '..') {
					// we build the new path to delete
					$path = $directory.DIRECTORY_SEPARATOR.$item;

					// if the new path is a directory
					if (is_dir($path)) {

						// we call this function with the new path
						rmdir_recursive($path, $empty);
						// if the new path is a file
					} else {
						try {
							@unlink($path);
						} catch (Exception $e) {
						}
					}
				}
			}

			// close the directory
			closedir($handle);

			// if the option to empty is not set to true
			if ($empty == false) {
				@rmdir($directory);
			}

			// return success
			return true;
		}
	}
}


if (!function_exists('normalize_path')) {
    /**
     * Converts a path in the appropriate format for win or linux.
     *
     * @param string $path
     *                         The directory path.
     * @param bool $slash_it
     *                         If true, ads a slash at the end, false by default
     *
     * @return string The formatted string
     */
	function normalize_path($path, $slash_it = true)
	{
		$path_original = $path;
		$s = DIRECTORY_SEPARATOR;
		$path = preg_replace('/[\/\\\]/', $s, $path);
		$path = str_replace($s . $s, $s, $path);
		if (strval($path) == '') {
			$path = $path_original;
		}
		if ($slash_it == false) {
			$path = rtrim($path, DIRECTORY_SEPARATOR);
		} else {
			$path .= DIRECTORY_SEPARATOR;
			$path = rtrim($path, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR);
		}
		if (strval(trim($path)) == '' or strval(trim($path)) == '/') {
			$path = $path_original;
		}
		if ($slash_it == false) {
		} else {
			$path = $path . DIRECTORY_SEPARATOR;
			$path = reduce_double_slashes($path);
		}

		return $path;
	}
}

if (!function_exists('reduce_double_slashes')) {
    /**
     * Removes double slashes from sting.
     *
     * @param $str
     *
     * @return string
     */
    function reduce_double_slashes($str)
    {
        return preg_replace('#([^:])//+#', '\\1/', $str);
    }
}


if (!function_exists('directory_map')) {

    /**
     * Create a Directory Map.
     *
     *
     * Reads the specified directory and builds an array
     * representation of it.  Sub-folders contained with the
     * directory will be mapped as well.
     *
     * @param string     path to source
     * @param int        depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
     *
     * @return array
     * @link          http://codeigniter.com/user_guide/helpers/directory_helper.html
     *
     * @author        ExpressionEngine Dev Team
     *
     */
    function directory_map($source_dir, $directory_depth = 0, $hidden = false, $full_path = false)
    {
        if ($fp = @opendir($source_dir)) {
            $filedata = array();
            $new_depth = $directory_depth - 1;
            $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            while (false !== ($file = readdir($fp))) {
                // Remove '.', '..', and hidden files [optional]
                if (!trim($file, '.') or ($hidden == false && $file[0] == '.')) {
                    continue;
                }

                if (($directory_depth < 1 or $new_depth > 0) && @is_dir($source_dir . $file)) {
                    $filedata[$file] = directory_map($source_dir . $file . DIRECTORY_SEPARATOR, $new_depth, $hidden, $full_path);
                } else {
                    if ($full_path == false) {
                        $filedata[] = $file;
                    } else {
                        $filedata[] = $source_dir . $file;
                    }
                }
            }

            closedir($fp);

            return $filedata;
        }

        return false;
    }
}

if (!function_exists('url2dir')) {
    function url2dir($path)
    {
        if (trim($path) == '') {
            return false;
        }

        $path = str_ireplace(site_url(), MW_ROOTPATH, $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);

        return normalize_path($path, false);
    }
}

if (!function_exists('dir2url')) {
    function dir2url($path)
    {
        $path = str_ireplace(MW_ROOTPATH, '', $path);
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);

        //var_dump($path);
        return site_url($path);
    }
}
