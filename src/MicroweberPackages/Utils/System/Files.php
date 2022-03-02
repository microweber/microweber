<?php

namespace MicroweberPackages\Utils\System;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;

$mw_static_option_groups = array();

class Files
{
    /**
     * Copies directory recursively.
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
            while (false !== ($readdirectory = $directory->read())) {
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
     * Returns a human readable filesize.
     *
     * @category Files
     *
     * @author      wesman20 (php.net)
     * @author      Jonas John
     *
     * @version     0.3
     *
     * @link        http://www.jonasjohn.de/snippets/php/readable-filesize.htm
     */
    public function file_size_nice($size)
    {
        // Adapted from: http://www.php.net/manual/en/function.filesize.php

        $mod = 1024;

        $units = explode(' ', 'B KB MB GB TB PB');
        for ($i = 0; $size > $mod; ++$i) {
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

//    public function dir_tree($path = '.', $params = false)
//    {
//        $params = parse_params($params);
//        $dir = $path;
//
//        return $this->directory_tree_build($dir, $params);
//    }

    public function md5_dir($path)
    {
        if (!file_exists($path)) {
            throw new \Exception('Directory doesn\'t exist.');
        }

        $directoryIterator = new \DirectoryIterator($path);
        $items = array();
        foreach ($directoryIterator as $fileInfo) {
            $filePath = $fileInfo->getPathname();
            if (!$fileInfo->isDot()) {
                if ($fileInfo->isFile()) {
                    $md = md5_file($filePath);
                    $filePath = normalize_path($filePath, false);
                    $items [$md] = $filePath;
                } else if ($fileInfo->isDir()) {
                    $more = $this->md5_dir($filePath);
                    foreach ($more as $k => $v) {
                        $items [$k] = $v;
                    }
                }
            }
        }
        return $items;
    }


    /**
     * get_files.
     *
     *  Get an array that represents directory and files
     *
     * @category    files module api
     *
     * @version 1.0
     *
     * @since 0.320
     *
     * @return mixed Array with files
     *
     * @param array $params = array()     the params
     * @param string $params ['directory']       The directory
     * @param string $params ['keyword']       If set it will seach the dir and subdirs
     */
    public function get($params)
    {
        if (is_admin() == false) {
            mw_error('Must be admin');
        }

        $params = parse_params($params);
        if (!isset($params['directory'])) {
            mw_error('You must define directory');
        } else {
            $directory = $params['directory'];
        }


        $filter_extensions = false;

        if (isset($params['extensions']) and is_string($params['extensions'])) {
            $filter_extensions = explode(',', $params['extensions']);
        }

        $hide_files = false;

        if (isset($params['hide_files']) and is_string($params['hide_files'])) {
            $hide_files = explode(',', $params['hide_files']);
        } else if (isset($params['hide_files']) and is_array($params['hide_files'])) {
            $hide_files = $params['hide_files'];
        }
        $restrict_path = false;

        if (isset($params['restrict_path']) and is_string($params['restrict_path'])) {
            $restrict_path = $params['restrict_path'];
        }

        if ($restrict_path) {
            if (!strstr($directory, $restrict_path)) {
                $directory = $restrict_path . $directory;
            }
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

                if(!empty($hide_files) && in_array($entryName,$hide_files)){
                    continue;
                }

                if ($entryName != '..' and $entryName != '.') {
                    $arrayItems_search[] = $entryName;
                }
            }

            closedir($myDirectory);
        }

        if (!empty($arrayItems_search)) {
//
            //usort($myarray, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));

            $arrayItems_f = array();
            $arrayItems_d = array();
            foreach ($arrayItems_search as $file) {
                if ($from_search == 0) {
                    $file = $directory . DS . $file;
                }
                if (is_file($file)) {

                    $skip = false;
                    $df = normalize_path($file, false);
                    $file_ext = get_file_extension($df);


                    if ($filter_extensions and !empty($filter_extensions)) {
                        $skip = true;
                        foreach ($filter_extensions as $filter_extension) {
                            if ($filter_extension == $file_ext) {
                                $skip = false;
                            }
                        }
//                        if(array_search($file_ext,$filter_extensions)){
//                           // $skip = false;
//                        }
                    }

                    if ($skip == false) {
                        if (!in_array($df, $arrayItems_f)) {
                            $arrayItems_f[] = $df;
                        }
                    }
                } else {
                    $df = normalize_path($file, 1);
                    if (!in_array($df, $arrayItems_d)) {
                        $arrayItems_d[] = $df;
                    }
                }
            }

            /*
            // Sort  by filetime
            $sortedFiles = array();
            foreach($arrayItems_f as $dir) {
            	$sortedFiles[filemtime($dir)] = $dir;
            }
            ksort($sortedFiles);
            $sortedFiles = array_reverse($sortedFiles);
            */


            if (isset($params['sort_by']) and strval($params['sort_by']) != '') {
                if (!isset($params['sort_order'])) {
                    $sort_params = explode(' ', $params['sort_by']);
                    if (isset($sort_params[1])) {
                        $params['sort_by'] = $sort_params[0];
                        $params['sort_order'] = $sort_params[1];
                    }
                }


                if (isset($params['sort_order']) and strval($params['sort_order']) != '') {
                    $ord = SORT_DESC;
                    if (strtolower($params['sort_order']) == 'asc') {
                        $ord = SORT_ASC;
                    }
                    $allowed_sort = array('basename', 'filemtime', 'filesize');
                    if (in_array($params['sort_by'], $allowed_sort)) {
                        array_multisort(array_map($params['sort_by'], $arrayItems_f), SORT_NUMERIC, $ord, $arrayItems_f);
                    }
                }
            }

            $arrayItems['files'] = $arrayItems_f;
            $arrayItems['dirs'] = $arrayItems_d;
        }


        return $arrayItems;
    }

    /**
     * Recursive glob().
     *
     * @category Files
     *
     * @uses is_array()
     *
     * @param int|string $pattern
     *                            the pattern passed to glob()
     * @param int $flags
     *                            the flags passed to glob()
     * @param string $path
     *                            the path to scan
     *
     * @return mixed
     *               an array of files in the given path matching the pattern.
     */
    public function rglob($pattern = '*', $flags = 0, $path = '')
    {
        if (!$path && ($dir = dirname($pattern)) != '.') {
            if ($dir == '\\' || $dir == '/') {
                $dir = '';
            }

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
                } elseif (is_array($temp)) {
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
            ++$level;
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
        --$level;
    }

    public function download_to_browser($filename)
    {
        if (file_exists($filename)) {
            $name = basename($filename);
            $ext = get_file_extension($filename);

            header('Cache-Control: public');
            if ($ext == 'zip') {
                header('Content-Type: application/zip');
                header('Content-Transfer-Encoding: Binary');
            } elseif ($ext == 'sql') {
                header('Content-type: text/plain; charset=utf-8');
            }
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . $name);
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            exit;
//            if (function_exists('mime_content_type')) {
//                $this->_readfile_laravel_chunked($filename);
//            } else {
//
//            }
            //$this->_readfile_chunked($filename);
        }
    }

    private function _readfile_chunked($filename, $retbytes = true)
    {
        $filename = str_replace('..', '', $filename);
        $chunk_size = 1024 * 1024;
        $buffer = '';
        $cnt = 0;
        $handle = fopen($filename, 'rb');
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

    private function _readfile_laravel_chunked($path, $name = null, array $headers = array())
    {
        if (is_null($name)) {
            $name = basename($path);
        }

        // Prepare the headers
        $headers = array_merge(array(
            'Content-Description' => 'File Transfer',
            'Content-Type' => \File::mime(\File::extension($path)),
            'Content-Transfer-Encoding' => 'binary',
            'Expires' => 0,
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
            'Content-Length' => \File::size($path),
        ), $headers);

        $response = new \Response('', 200, $headers);
        $response->header('Content-Disposition', $response->disposition($name));

        // If there's a session we should save it now
        if (\Config::get('session.driver') !== '') {
            \Session::save();
        }

        // Send the headers and the file
        ob_end_clean();
        $response->send_headers();

        if ($fp = fread($path, 'rb')) {
            while (!feof($fp) and (connection_status() == 0)) {
                echo fread($fp, 8192);
                flush();
            }
        }

        // Finish off, like Laravel would
        \Event::fire('laravel.done', array($response));
        $response->foundation->finish();

        exit;
    }


    function get_dangerous_files_extentions()
    {


        $dangerous = array(
            'php',
            'php5',
            'php7',
            'php72',
            'php73',
            'php74',
            'php8',
            'php81',
            'php82',
            'php56',
            'php4',
            'php3',
            'phps',
            'phpt',
            'ptml',
            'hphp',
            'html',
            'xhtml',
            'phtml',
            'shtml',
            'htm',
            'pl',
            'js',
            'cgi',
            'rb',
            'py',
            'alfa',
            'asp',
            'htaccess',
            'exe',
            'msi',
            'sh',
            'bat',
            'vbs',
            'vb',
            'lnk',
            'jsp',
            'jspx',

            // from http://www.file-extensions.org/filetype/extension/name/program-executable-files
            'action ',  //  Automator Action  Mac OS
            'apk',      //     Application  Android
            'app',      //     Executable  Mac OS
            'bat',      //     Batch File  Windows
            'bin',      //     Binary Executable  Windows, Mac OS, Linux
            'cmd',      //     Command Script  Windows
            'com',      //     Command File  Windows
            'command',  //   Terminal Command  Mac OS
            'cpl',      //     Control Panel Extension  Windows
            'csh',      //     C Shell Script  Mac OS, Linux
            'exe',      //     Executable  Windows
            'gadget',   //  Windows Gadget  Windows
            'inf1',     //      Setup Information File  Windows
            'ins',      //     Internet Communication Settings  Windows
            'inx',      //     InstallShield Compiled Script  Windows
            'ipa',      //     Application  iOS
            'isu',      //     InstallShield Uninstaller Script  Windows
            'job',      //     Windows Task Scheduler Job File  Windows
            'jse',      //     JScript Encoded File  Windows
            'ksh',      //     Unix Korn Shell Script  Linux
            'lnk',      //     File Shortcut  Windows
            'msc',      //     Microsoft Common Console Document  Windows
            'msi',      //     Windows Installer Package  Windows
            'msp',      //     Windows Installer Patch  Windows
            'mst',      //     Windows Installer Setup Transform File  Windows
            'osx',      //     Executable  Mac OS
            'out',      //     Executable  Linux
            'paf',      //     Portable Application Installer File  Windows
            'pif',      //     Program Information File  Windows
            'prg',      //     Executable  GEM
            'ps1',      //     Windows PowerShell Cmdlet  Windows
            'reg',      //     Registry Data File  Windows
            'rgs',      //     Registry Script  Windows
            'run',      //     Executable  Linux
            'sct',      //     Windows Scriptlet  Windows
            'shb',      //     Windows Document Shortcut  Windows
            'shs',      //     Shell Scrap Object  Windows
            'u3p',      //     U3 Smart Application  Windows
            'vb',       //    VBScript File  Windows
            'vbe',      //     VBScript Encoded Script  Windows
            'vbs',      //     VBScript File  Windows
            'vbscript', //    Visual Basic Script  Windows
            'workflow', //    Automator Workflow  Mac OS
            'ws',       //    Windows Script  Windows
            'wsf',      //  Windows Script  Windows

            // from http://fileinfo.com/filetypes/executable
            '0xe',        //    F-Secure Renamed Virus File',
            '73k',        //    TI-73 Application',
            '89k',        //    TI-89 Application',
            '8ck',        //    TI-83 and TI-84 Plus Application
            'a6p',        //    Authorware 6 Program',
            'a7r',        //    Authorware 7 Runtime File',
            'ac',        //    Autoconf Script',
            'acc',        //    GEM Accessory File',
            'acr',        //    ACRobot Script',
            'action',        //    Automator Action',
            'actm',        //    AutoCAD Action Macro File',
            'ahk',        //    AutoHotkey Script',
            'air',        //    Adobe AIR Installation Package',
            'apk',        //    Android Package File',
            'app',        //    Mac OS X Application',
            'app',        //    FoxPro Generated Application',
            'app',        //    Symbian OS Application',
            'applescripT',        //    AppleScript File',
            'arscript',        //    ArtRage Script',
            'asb',        //    Alphacam Stone VB Macro File',
            'azw2',        //    Kindle Active Content App File',
            'ba_',        //    Renamed BAT File',
            'bat',        //    DOS Batch File',
            'beam',        //    Compiled Erlang File',
            'bin',        //    Generic Binary Executable File',
            'bin',        //    Unix Executable File',
            'btm',        //    4DOS Batch File',
            'caction',        //    Automator Converter Action',
            'cel',        //    Celestia Script File',
            'celx',        //    Celestia Script',
            'cgi',        //    Common Gateway Interface Script',
            'cmd',        //    Windows Command File',
            'cof',        //    MPLAB COFF File',
            'com',        //    DOS Command File',
            'command',        //    Terminal Command File',
            'csh',        //    C Shell Script',
            'cyw',        //    Rbot.CYW Worm File',
            'dek',        //    Eavesdropper Batch File',
            'dld',        //    EdLog Compiled Program',
            'dmc',        //    Medical Manager Script',
            'dosexec',
            'ds',        //    TWAIN Data Source',
            'dxl',        //    Rational DOORS Script',
            'e_e',        //    Renamed EXE File',
            'ear',        //    Java Enterprise Archive File',
            'ebm',        //    EXTRA! Basic Macro',
            'ebs',        //    E-Run 1.x Script',
            'ebs2',        //    E-Run 2.0 Script File',
            'ecf',        //    SageCRM Component File',
            'eham',        //    ExtraHAM Executable File',
            'elf',        //    Nintendo Wii Game File',
            'es',        //    SageCRM Script File',
            'esh',        //    Extended Shell Batch File',
            'ex4',        //    MetaTrader Program File',
            'ex_',        //    Compressed Executable File',
            'ex_',        //    Renamed Windows Executable File',
            'exe',        //    Windows Executable File',
            'exe',        //    PortableApps.com Application',
            'exe1',        //    Renamed EXE File',
            'exopc',        //    ExoPC Application',
            'ezs',        //    EZ-R Stats Batch Script',
            'ezt',        //    EZT Malicious Worm File',
            'fas',        //    Compiled Fast-Load AutoLISP File'
            'fas',        //    QuickSilver Fast Save Lisp File',
            'fky',        //    FoxPro Macro',
            'fpi',        //    FPS Creator Intelligence Script',
            'frs',        //    Flash Renamer Script',
            'fxp',        //    FoxPro Compiled Program',
            'gadget',        //    Windows Gadget',
            'gpe',        //    GP2X Video Game',
            'gpu',        //    GP2X Utility Program',
            'gs',        //    Geosoft Script',
            'ham',        //    HAM Executable File',
            'hms',        //    HostMonitor Script File',
            'hpf',        //    HP9100A Program File',
            'hta',        //    HTML Application',
            'icd',        //    SafeDisc Encrypted Program',
            'iim',        //    iMacro Macro File',
            'ipa',        //    iOS Application',
            'ipf',        //    SMS Installer Script',
            'isu',        //    InstallShield Uninstaller Script'
            'ita',        //    VTech InnoTab Application File',
            'jar',        //    Java Archive File',
            'js',        //    JScript Executable Script',
            'jse',        //    JScript Encoded File',
            'jsx',        //    ExtendScript Script File',
            'kix',        //    KiXtart Script File',
            'ksh',        //    Unix Korn Shell Script',
            'lo',        //    Interleaf Compiled Lisp File',
            'ls',        //    LightWave LScript File',
            'm3g',        //    Mobile 3D Graphics Program',
            'mam',        //    Microsoft Access Macro',
            'mcr',        //    3ds Max Macroscript File',
            'mcr',        //    Tecplot Macro',
            'mel',        //    Maya Embedded Language File',
            'mem',        //    Macro Editor Macro',
            'mio',        //    MioEngine Application File',
            'mm',        //    NeXtMidas Macro File',
            'mpx',        //    FoxPro Compiled Menu Program',
            'mrc',        //    mIRC Script File',
            'mrp',        //    Mobile Application File',
            'ms',        //    3ds Max Script File',
            'ms',        //    Maxwell Script',
            'msl',        //    Magick Scripting Language File',
            'mxe',        //    Macro Express Playable Macro',
            'n',        //    Neko Bytecode File',
            'nexe',        //    Chrome Native Client Executable',
            'ore',        //    Ore Executable File',
            'osx',        //    PowerPC Executable File',
            'otm',        //    Outlook Macro File',
            'out',        //    Compiled Executable File',
            'paf',        //    Portable Application Installer Fi
            'paf.exe',        //    PortableApps.com Program File
            'pex',        //    ProBoard Executable File',
            'phar',        //    PHP Archive',
            'pif',        //    Program Information File',
            'plsc',        //    Messenger Plus! Live Script File'
            'plx',        //    Perl Executable File',
            'prc',        //    Palm Resource Code File',
            'prg',        //    Program File',
            'prg',        //    GEM Application',
            'ps1',        //    Windows PowerShell Cmdlet File',
            'pvd',        //    Instalit Script',
            'pwc',        //    PictureTaker File',
            'pyc',        //    Python Compiled File',
            'pyo',        //    Python Optimized Code',
            'qit',        //    QIT Trojan Horse File',
            'qpx',        //    FoxPro Compiled Query Program',
            'rbx',        //    Rembo-C Compiled Script',
            'rfu',        //    Remote Firmware Update',
            'rgs',        //    Registry Script',
            'rox',        //    Actuate Report Object Executable
            'rpj',        //    Real Pac Batch Job File',
            'run',        //    Linux Executable File',
            'rxe',        //    Lego Mindstorms NXT Executable Pr
            's2a',        //    SEAL2 Application',
            'sbs',        //    SPSS Script',
            'sca',        //    Scala Script File',
            'scar',        //    SCAR Script',
            'scb',        //    Scala Published Script',
            'scpt',        //    AppleScript Script File',
            'scptd',        //    AppleScript Script Bundle',
            'scr',        //    Script File',
            'script',        //    Generic Script File',
            'sct',        //    Windows Scriptlet',
            'seed',        //    Linux Preseed File',
            'shb',        //    Windows Document Shortcut',
            'smm',        //    Ami Pro Macro',
            'spr',        //    FoxPro Generated Screen File',
            'tcp',        //    Tally Compiled Program File',
            'thm',        //    Thermwood Macro File',
            'tms',        //    Telemate Script',
            'u3p',        //    U3 Smart Application',
            'udf',        //    Excel User Defined Function',
            'upx',        //    Ultimate Packer for eXecutables F
            'vb',        //    VBScript File',    	//
            'vbe',        //    VBScript Encoded Script File',
            'vbs',        //    VBScript File',
            'vbscript',        //    Visual Basic Script',
            'vdo',        //    Heathen Virus File',
            'vexe',        //    Virus Executable File',
            'vlx',        //    Compiled AutoLISP File',
            'vpm',        //    Vox Proxy Macro File',
            'vxp',        //    Mobile Application File',
            'wcm',        //    WordPerfect Macro',
            'widget',        //    Microsoft Windows Mobile Widget',
            'widget',        //    Yahoo! Widget',
            'wiz',        //    Microsoft Wizard File',
            'workflow',        //    Automator Workflow',
            'wpk',        //    WordPerfect Macro',
            'wpm',        //    WordPerfect Macro File',
            'ws',        //    Windows Script',
            'wsf',        //    Windows Script File',
            'wsh',        //    Windows Script Host Settings',
            'x86',        //    Linux Executable File',
            'xap',        //    Silverlight Application Package',
            'xbap',        //    XAML Browser Application File',
            'xlm',        //    Excel Macro',
            'xqt',        //    SuperCalc Macro File',
            'xys',        //    XYplorer Script File',
            'zl9',        //    ZoneAlarm Quarantined EXE File


            'swf',        //    Flash File

        );


        return $dangerous;
    }


    public function is_dangerous_file($file_name)
    {
        $dangerous = $this->get_dangerous_files_extentions();
        $is_ext = get_file_extension($file_name);
        $is_ext = strtolower($is_ext);

        if (in_array($is_ext, $dangerous)) {
            return true;
        }

    }


    function get_allowed_files_extensions_for_upload($fileTypes = 'images')
    {

        $are_allowed = '';
        switch ($fileTypes) {

            case 'img':
            case 'image':
            case 'images':
                $are_allowed .= ',png,gif,jpg,jpeg,tiff,bmp,svg';
                break;
            case 'video':
            case 'videos':
                $are_allowed .= ',avi,asf,mpg,mpeg,mp4,flv,mkv,webm,ogg,wma,mov,wmv';
                break;
            case 'file':
            case 'files':
                $are_allowed .= ',doc,docx,pdf,html,js,css,htm,rtf,txt,zip,gzip,rar,cad,xml,psd,xlsx,csv,7z';
                break;
            case 'documents':
            case 'doc':
                $are_allowed .= ',doc,docx,pdf,log,msg,odt,pages,rtf,tex,txt,wpd,wps,pps,ppt,pptx,xml,htm,html,xlr,xls,xlsx';
                break;
            case 'archives':
            case 'arc':
            case 'arch':
                $are_allowed .= ',zip,zipx,gzip,rar,gz,7z,cbr,tar.gz';
                break;
            case 'all':
                $are_allowed .= ',*';
                break;
            case '*':
                $are_allowed .= ',*';
                break;
            default:
                $are_allowed .= ',' . $fileTypes;
        }

        if($are_allowed){
            $are_allowed = explode(',',$are_allowed);
            array_unique($are_allowed);
            $are_allowed = array_filter($are_allowed);
            $are_allowed = implode(',', $are_allowed);
        }

        return $are_allowed;
    }
}
