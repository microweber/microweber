<?php


namespace Microweber\Utils;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;

$mw_static_option_groups = array();

class Files
{

    /**
     * Copies directory recursively
     *
     * @param $source
     * @param $destination
     */
    public function copy_directory($source, $destination)
    {
        static $copies;
        if (is_dir($source)) {
            @mkdir($destination);
            $directory = dir($source);
            while (FALSE !== ($readdirectory = $directory->read())) {
                if ($readdirectory == '.' || $readdirectory == '..') {
                    continue;
                }

                $PathDir = $source . DIRECTORY_SEPARATOR . $readdirectory;
                if (is_dir($PathDir)) {
                    $this->copy_directory($PathDir, $destination . DIRECTORY_SEPARATOR . $readdirectory);
                    continue;
                }
                $copies[] = $destination . DIRECTORY_SEPARATOR . $readdirectory;
                copy($PathDir, $destination . DIRECTORY_SEPARATOR . $readdirectory);
            }

            $directory->close();
        } else {
            $copies[] = $destination;
            copy($source, $destination);
        }
        return $copies;
    }

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


    public function rmdir($dirPath)
    {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }
        @rmdir($dirPath);
    }


    public function dir_tree($path = '.', $params = false)
    {
        $params = parse_params($params);
        $dir = $path;
        return $this->directory_tree_build($dir, $params);
    }

    /**
     * get_files
     *
     *  Get an array that represents directory and files
     *
     * @package        modules
     * @subpackage    files
     * @subpackage    files\api
     * @category    files module api
     * @version 1.0
     * @since 0.320
     * @return mixed Array with files
     *
     * @param array $params = array()     the params
     * @param string $params ['directory']       The directory
     * @param string $params ['keyword']       If set it will seach the dir and subdirs
     */
    public function get($params)
    {
        if (is_admin() == false) {
            mw_error("Must be admin");
        }

        $params = parse_params($params);
        if (!isset($params['directory'])) {
            mw_error("You must define directory");
        } else {
            $directory = $params['directory'];
        }
        $from_search = 0;
        $arrayItems = array();
        if (isset($params['search']) and strval($params['search']) != '') {
            $from_search = 1;
            $arrayItems_search = $this->rglob($pattern = DS . '*' . $params['search'] . '*', $flags = 0, $directory);

        } else {


            if (!is_dir($directory . DS)) {
                return false;
            }

            $arrayItems_search = array();
            $myDirectory = opendir($directory . DS);

            while ($entryName = readdir($myDirectory)) {
                if ($entryName != '..' and $entryName != '.') {
                    $arrayItems_search[] = $entryName;
                }

            }

            closedir($myDirectory);


        }

        if (!empty($arrayItems_search)) {
            if (isset($params['sort_by']) and strval($params['sort_by']) != '') {
                if (isset($params['sort_order']) and strval($params['sort_order']) != '') {

                    $ord = SORT_DESC;
                    if (strtolower($params['sort_order']) == 'asc') {
                        $ord = SORT_ASC;
                    }

                    array_multisort(array_map($params['sort_by'], $arrayItems_search), SORT_NUMERIC, $ord, $arrayItems_search);
                    //	d($arrayItems_search);
                }
            }
            //usort($myarray, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));

            $arrayItems_f = array();
            $arrayItems_d = array();
            foreach ($arrayItems_search as $file) {
                if ($from_search == 0) {
                    $file = $directory . DS . $file;
                }
                if (is_file($file)) {
                    $df = normalize_path($file, false);
                    if (!in_array($df, $arrayItems_f)) {
                        $arrayItems_f[] = $df;
                    }
                } else {
                    $df = normalize_path($file, 1);
                    if (!in_array($df, $arrayItems_d)) {
                        $arrayItems_d[] = $df;
                    }
                }
            }
            $arrayItems['files'] = $arrayItems_f;
            $arrayItems['dirs'] = $arrayItems_d;
        }

        return $arrayItems;
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
    public function rglob($pattern = '*', $flags = 0, $path = '')
    {

        if (!$path && ($dir = dirname($pattern)) != '.') {
            if ($dir == '\\' || $dir == '/')
                $dir = '';
            return $this->rglob(basename($pattern), $flags, $dir . DS);
        }


        if (stristr($path, '_notes') or stristr($path, '.git') or stristr($path, '.svn')) {
            return false;
        }


        $paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
        $files = glob($path . $pattern, $flags);


        if (is_array($paths)) {
            foreach ($paths as $p) {
                $temp = array();
                if (is_dir($p) and is_readable($p)) {
                    $temp = $this->rglob($pattern, false, $p . DS);

                }

                if (is_array($temp) and is_array($files)) {
                    $files = array_merge($files, $temp);
                } else if (is_array($temp)) {
                    $files = $temp;
                }
            }
        }
        return $files;


    }

    public function dir_tree_build($dir, $params = false)
    {

        $params = parse_params($params);
        $class = 'directory_tree';
        if (isset($params['class'])) {
            $class = $params['class'];
        }


        $title_class = 'is_folder';
        if (isset($params['title_class'])) {
            $title_class = $params['title_class'];
        }


        $basedir = '';
        if (isset($params['dir_name'])) {
            $basedir = $params['dir_name'];
        }


        $max_depth = 100;
        if (isset($params['max_depth'])) {
            $max_depth = $params['max_depth'];
        }

        $url_param = 'file';
        if (isset($params['url_param'])) {
            $url_param = $params['url_param'];
        }

        if (isset($params['url'])) {
            $url = $params['url'];
        } else {
            $url = mw()->url->current(true, true);
        }


        static $level = 0;

        if ($max_depth > $level) {

            $level++;
            $ffs = scandir($dir);
            echo '<ul class="' . $class . ' depth_' . $level . '">';
            foreach ($ffs as $ff) {
                $is_hidden = substr($ff, 0, 1);
                if ($is_hidden == '_') {

                } else {


                    $file1 = $ff;


                    if (strlen($file1) > 3) {
                        $pos = strpos($file1, '_', 1);

                        if ($pos != false) {
                            $substr = substr($file1, 0, $pos);
                            if (intval($substr) > 0) {
                                $file1 = substr($file1, $pos, strlen($file1));
                                $file1 = ltrim($file1, '_');
                            }

                        }
                    }

                    $file1 = str_replace('_', ' ', $file1);


                    if ($ff != '.' && $ff != '..') {
                        echo '<li class="' . $class . ' depth_' . $level . '">';
                        if (is_dir($dir . '/' . $ff)) {

                            $is_index = $dir . DS . $ff . DS . 'index.php';
                            $link_href = '';

                            if (is_file($is_index)) {
                                $link = $dir . '/' . $ff . '/index.php';
                                if (trim($basedir) != '') {
                                    $link = normalize_path($link, false);
                                    $basedir = normalize_path($basedir, false);
                                    $link = str_replace($basedir . DS, '', $link);
                                    $link = str_replace('\\', '/', $link);
                                    $link = urlencode($link);


                                }
                                $active_class = '';

                                if (isset($_REQUEST[$url_param]) and urldecode($_REQUEST[$url_param]) == $link) {
                                    $active_class = ' active ';
                                }


                                $file1 = "<a class='{$active_class}' href='{$url}?{$url_param}={$link}'>{$file1}</a>";

                            }


                            $h_start = ($level == 1) ? '<h2 class="' . $title_class . '">' : '<h3 class="' . $title_class . '">';
                            $h_close = ($level == 1) ? '</h2>' : '</h3>';
                            echo $h_start . $file1 . $h_close;
                            $this->dir_tree_build($dir . '/' . $ff, $params);
                        } else {
                            $file1 = no_ext($file1);

                            $link = $dir . '/' . $ff;

                            if (trim($basedir) != '') {
                                $link = normalize_path($link, false);
                                $basedir = normalize_path($basedir, false);
                                $link = str_replace($basedir . DS, '', $link);

                            }


                            $link = str_replace('\\', '/', $link);
                            $class_path = str_replace('/', '--', $link);
                            $class_path = str_replace(' ', '_', $class_path);
                            $class_path = str_replace('.', '_', $class_path);
                            $active_class = '';
                            if (isset($_REQUEST[$url_param]) and urldecode($_REQUEST[$url_param]) == $link) {
                                $active_class = ' active ';
                            }


                            $link_href = $file1;
                            if ($link != false) {
                                $link = urlencode($link);
                                $link_href = "<a class='{$active_class} page_{$class_path} ' href='{$url}?{$url_param}={$link}'>{$file1}</a>";
                            }

                            echo $link_href;
                        }
                        echo '</li>';
                    }
                }
            }
            echo '</ul>';
        }
        $level--;
    }


    function download_to_browser($filename)
    {
        if (file_exists($filename)) {
            $name = basename($filename);
            $ext = get_file_extension($filename);

            header('Cache-Control: public');
            if ($ext == 'zip') {
                header("Content-Type: application/zip");
                header("Content-Transfer-Encoding: Binary");
            } else if ($ext == 'sql') {
                header("Content-type: text/plain; charset=utf-8");
            }
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . $name);
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            exit;
            //$this->_readfile_chunked($filename);

        }
    }

    private function _readfile_chunked($filename, $retbytes = TRUE)
    {

        $filename = str_replace('..', '', $filename);
        $chunk_size = 1024 * 1024;
        $buffer = "";
        $cnt = 0;
        $handle = fopen($filename, "rb");
        if ($handle === false) {
            return false;
        }
        while (!feof($handle)) {
            $buffer = fread($handle, $chunk_size);
            echo $buffer;
            ob_flush();
            flush();
            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt; // return num. bytes delivered like readfile() does.
        }
        return $status;
    }

}





