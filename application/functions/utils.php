<?php













function mw_warning($text, $exit = false)
{
    return mw_warn($text, $exit);
}

function mw_warn($text, $exit = false)
{
    include(ADMIN_VIEWS_PATH . 'mw_warning.php');
    if ($exit == true) {
        die();
    }
}

function mw_notification($text, $exit = false)
{
    return mw_notif($text, $exit);
}

function mw_notif_le($text, $exit = false)
{
    return mw_notif_live_edit($text, $exit);
}

function mw_text_live_edit($text, $exit = false)
{
    $editmode_sess = mw('user')->session_get('editmode');

    if ($editmode_sess == true) {

        print $text;
    }
    if ($exit == true) {
        die();
    }


}

function mw_notif_live_edit($text, $exit = false)
{
    $editmode_sess = mw('user')->session_get('editmode');

    if ($editmode_sess == true) {
        $to_print = '<div class="mw-notification mw-success ">
		<div class="mw-notification-text mw-open-module-settings">' . $text . '</div>
		</div>';


        print $to_print;

    }

    if ($exit == true) {
        die();
    }


}

function mw_notif($text, $exit = false)
{
    include(ADMIN_VIEWS_PATH . 'mw_notification.php');
    if ($exit == true) {
        die();
    }
}


function mw_error($text, $exit = false)
{
    include(ADMIN_VIEWS_PATH . 'mw_error.php');
    if ($exit == true) {
        die();
    }
}

function debug_info()
{
    //if (c('debug_mode')) {

    return include(ADMIN_VIEWS_PATH . 'debug.php');
    // }
}






function is_arr($var)
{
    return isarr($var);
}

function isarr($var)
{
    if (is_array($var) and !empty($var)) {
        return true;
    } else {
        return false;
    }
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
 * @uses isarr()
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


    $paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
    $files = glob($path . $pattern, $flags);


    if (isarr($paths)) {
        foreach ($paths as $p) {
            $temp = rglob($pattern, false, $p . DS);

            if (isarr($temp) and isarr($files)) {
                $files = array_merge($files, $temp);
            } else if (isarr($temp)) {
                $files = $temp;
            }
        }
    }
    return $files;


}


function directory_tree_grep($q, $path)
{
    $ret = array();
    $fp = opendir($path);
    while ($f = readdir($fp)) {
        if (preg_match("#^\.+$#", $f))
            continue; // ignore symbolic links
        $file_full_path = $path . DS . $f;

        if (is_dir($file_full_path)) {
            $ret[] = directory_tree_grep($q, $file_full_path);
        } else if (stristr(file_get_contents($file_full_path), $q)) {
            $ret[] = $file_full_path;
        }
    }
    return $ret;
}


function array_values_recursive($ary)
{
    $lst = array();
    foreach (array_keys($ary) as $k) {
        $v = $ary[$k];
        if (is_scalar($v)) {
            $lst[] = $v;
        } elseif (is_array($v)) {
            $lst = array_merge($lst, array_values_recursive($v));
        }
    }
    return $lst;
}

$_mw_directory_tree_iterate_directory_depth = 0;
function directory_tree_iterate_directory($it)
{
    global $_mw_directory_tree_iterate_directory_depth;
    $to_print = '';


    $to_print = '<ul class="directory_tree is_folder depth_' . $_mw_directory_tree_iterate_directory_depth . '">';
    for (; $it->valid(); $it->next()) {


        $file1 = ($it->getFilename());
        $file1 = ltrim($file1, '_');
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


        $link = $it->getSubPathname();

        $link = str_replace('\\', '/', $link);
        $class_path = str_replace('/', '--', $link);
        $class_path = str_replace(' ', '_', $class_path);
        $class_path = str_replace('.', '_', $class_path);


        $link_href = $file1;
        if ($link != false) {
            $link_href = "<a class='page_{$class_path} ' href='?file={$link}'>{$file1}</a>";
        }


// $it->getSize()
        if ($it->isDir() && !$it->isDot()) {


            if ($it->hasChildren()) {
                $_mw_directory_tree_iterate_directory_depth++;


                $to_print .= sprintf('<li class="directory_tree is_sub_folder depth_' . $_mw_directory_tree_iterate_directory_depth . '">%s</li>', $it->getFilename());


                $bleh = $it->getChildren();

                $to_print .= directory_tree_iterate_directory($bleh);


            }
        } elseif ($it->isFile() && !$it->isDot()) {
            $to_print .= '<li class="directory_tree is_file depth_' . $_mw_directory_tree_iterate_directory_depth . ' ' . $class_path . '">' . $link_href . "</li>\n";

        }
    }
    $to_print .= '</ul>';
    return $to_print;


    $to_print = '<ul>';
    foreach ($i as $path) {
        $depth2 = $i->getDepth();


        if ($path->getFilename() == '.') {
            $to_print .= $path->getPath() . PHP_EOL;
        }


        if ($path->isDir()) {
            $to_print .= '<ul class="directory_tree is_folder" >sdfdsf';
            $to_print .= directory_tree_iterate_directory($path);
            $to_print .= '</ul>';
        } else {
            $fn = $path->getFilename();
            $to_print .= '<li class="directory_tree is_file depth_' . $depth2 . '">' . $fn . '</li>';
        }
    }
    $to_print .= '</ul>';


    return $to_print;
}

function directory_tree($path = '.', $params = false)
{

    $params = parse_params($params);

    $dir = $path;

    return directory_tree_build($dir, $params);


}


function directory_tree_build($dir, $params = false)
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
        $url = curent_url(true, true);
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
                        //
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

                            if (isset($_REQUEST[$url_param]) and  urldecode($_REQUEST[$url_param]) == $link) {
                                $active_class = ' active ';
                            }


                            $file1 = "<a class='{$active_class}' href='{$url}?{$url_param}={$link}'>{$file1}</a>";

                        }


                        $h_start = ($level == 1) ? '<h2 class="' . $title_class . '">' : '<h3 class="' . $title_class . '">';
                        $h_close = ($level == 1) ? '</h2>' : '</h3>';
                        echo $h_start . $file1 . $h_close;
                        directory_tree_build($dir . '/' . $ff, $params);
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


    } else {


    }


    $level--;
}


function directory_tree_full_path($path = '.')
{
    $return = '';
    $queue = array();

    $is_hidden = substr($path, 0, 2);
    if ($is_hidden == '__') {
        return $return;
    }


    $return .= "<ul class='directory_tree '>";

    if (is_dir($path)) {
        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if (is_dir($path . $file) && $file != '.' && $file != '..')
                    $return .= directory_tree_printSubDir($file, $path, $queue);
                else if ($file != '.' && $file != '..')
                    $queue[] = $file;
                asort($queue);
            }

            $return .= directory_tree_printQueue($queue, $path);

            //	 $return = str_replace($path, '', $return);

        }
    } else {
        if (is_file($path)) {
            //	$path1 = dirname($path);
            $queue[] = $path;
            $return .= directory_tree_printQueue($queue);

        }
    }
    //$return = str_replace('dir_pdddath_replace--', 'page_', $return);

    $return .= "</ul>";

    return $return;
}


function directory_tree_printQueue($queue, $path = '')
{
    $return = '';
    if (empty($queue)) {
        return $return;
    }
    foreach ($queue as $file) {
        $return .= directory_tree_printFile($file, $path);
    }
    return $return;
}

function directory_tree_printFile($file, $path)
{
    $return = '';
    $file1 = no_ext($file);
    $file1 = ltrim($file1, '_');


    if ($file1 != 'index') {
        if (strlen($file1) > 3) {
            $pos = strpos($file1, '_', 1);

            if ($pos != false) {
                $substr = substr($file1, 0, $pos);
                if (intval($substr) > 0) {
                    $file1 = substr($file1, $pos, strlen($file1));
                    $file1 = ltrim($file1, '_');
                }
                //
            }
        }

        $file1 = str_replace('_', ' ', $file1);

        //$fp = dirname($file);

        $path = str_replace('\\', '/', $path);
        $class_path = '--' . str_replace('/', '--', $file);
        $class_path = str_replace(' ', '_', $class_path);
        $class_path = str_replace('.', '_', $class_path);

        if ($file1 != '') {
            $return .= "<li class='directory_tree is_file'><a class='dir_path_replace{$class_path} ' href=\"?file=" . $path . $file . "\">$file1</a></li>";
        }
    }


    return $return;
}

function directory_tree_printSubDir($dir, $path)
{
    $path = str_replace('\\', '/', $path);

    $file1 = $dir;
    $pos = strpos($file1, '_', 1);

    if ($pos != false) {
        $substr = substr($file1, 0, $pos);
        if (intval($substr) > 0) {
            $file1 = substr($file1, $pos, strlen($file1));
            $file1 = ltrim($file1, '_');
        }
        //
    }


    $file1 = str_replace('_', ' ', $file1);


    $class_path = 'page_' . str_replace(DS, '--', $path . $dir);
    ;
    $class_path = str_replace(' ', '_', $class_path);
    $class_path = str_replace('.', '_', $class_path);

    $return = '';
    $return .= "<li class='directory_tree is_folder {$class_path} '><a href=\"?path=" . $path . $dir . "\" class=\"{$class_path}\">$file1</a>";
    $return .= directory_tree_full_path($path . $dir . DS);
    $return = str_replace('dir_path_replace', $class_path, $return);


    $return .= "</li>";
    return $return;
}


// ------------------------------------------------------------------------

/**
 * Create a Directory Map
 *
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @author        ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/helpers/directory_helper.html
 * @access    public
 * @param    string    path to source
 * @param    int        depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return    array
 */
function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE, $full_path = false)
{
    if ($fp = @opendir($source_dir)) {
        $filedata = array();
        $new_depth = $directory_depth - 1;
        $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        while (FALSE !== ($file = readdir($fp))) {
            // Remove '.', '..', and hidden files [optional]
            if (!trim($file, '.') OR ($hidden == FALSE && $file[0] == '.')) {
                continue;
            }

            if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir . $file)) {
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

    return FALSE;
}


/**
 * PHP5 - Fast, recursive, directory profiler
 * @param string $dir Path to a readable directory path
 * @version 1.0
 */
function directory_profile($dir)
{
    static $info = array();
    if (is_dir($dir = rtrim($dir, "/\\"))) {
        foreach (scandir($dir) as $item) {
            if ($item != "." && $item != "..") {
                $info['all'][] = $absPath = $dir . DIRECTORY_SEPARATOR . $item;
                $stat = stat($absPath);
                switch ($stat['mode'] & 0170000) {
                    case 0010000:
                        $info['files'][] = $absPath;
                        break;
                    case 0040000:
                        $info['directories'][] = $absPath;
                        profile($absPath);
                        break;
                    case 0120000:
                        $info['links'][] = $absPath;
                        break;
                    case 0140000:
                        $info['sockets'][] = $absPath;
                        break;
                    case 0010000:
                        $info['pipes'][] = $absPath;
                        break;
                }
            }
        }
    }
    //clearstatcache();
    return $info;
}


function lipsum($number_of_characters = false)
{
    if ($number_of_characters == false) {
        $number_of_characters = 100;
    }

    $lipsum = array(
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis justo et sapien varius gravida. Fusce porttitor consectetur risus ut tincidunt. Maecenas pellentesque nulla sodales enim consectetur commodo. Aliquam non dui leo, adipiscing posuere metus. Duis adipiscing auctor lorem ut pulvinar. Donec non magna massa, feugiat commodo felis. Donec ut nibh elit. Nulla pellentesque nulla diam, vitae consectetur neque.',
        'Etiam sed lorem augue. Vivamus varius tristique bibendum. Phasellus vitae tempor augue. Maecenas consequat commodo euismod. Aenean a lorem nec leo dignissim ultricies sed quis nisi. Fusce pellentesque tellus lectus, eu varius felis. Mauris lacinia facilisis metus, sed sollicitudin quam faucibus id.',
        'Donec ultrices cursus erat, non pulvinar lectus consectetur eu. Proin sodales risus a ante aliquet vel cursus justo viverra. Duis vel leo felis. Praesent hendrerit, sem vitae scelerisque blandit, enim neque pulvinar mi, vel lobortis elit dui vel dui. Donec ac sem sed neque consequat egestas. Curabitur pellentesque consequat ante, quis laoreet enim gravida eu. Donec varius, nisi non bibendum pellentesque, felis metus pretium ipsum, non vulputate eros magna ac sapien. Donec tincidunt porta tortor, et ornare enim facilisis vitae. Nulla facilisi. Cras ut nisi ac dolor lacinia tempus at sed eros. Integer vehicula arcu in augue adipiscing accumsan. Morbi placerat consectetur sapien sed gravida. Sed fringilla elit nisl, nec molestie felis. Nulla aliquet diam vitae diam iaculis porttitor.',
        'Integer eget tortor nulla, non dapibus erat. Sed ultrices consectetur quam at scelerisque. Nullam varius hendrerit nisl, ac cursus mi bibendum eu. Phasellus varius fermentum massa, sit amet ornare quam malesuada in. Quisque ac massa sem. Nulla eu erat metus, non tincidunt nibh. Nam consequat interdum nulla, at congue libero tincidunt eget. Sed cursus nulla eu felis faucibus porta. Nam sed lacus eros, nec pellentesque lorem. Sed dapibus, sapien mattis sollicitudin bibendum, libero augue dignissim felis, eget elementum felis nulla in velit. Donec varius, lectus non suscipit sollicitudin, urna est hendrerit nulla, vel vehicula arcu sem volutpat sapien. Ut nisi ipsum, accumsan vestibulum pulvinar eu, sodales id lacus. Nulla iaculis eros sit amet lectus tincidunt mattis. Ut eu nisl sit amet eros vestibulum imperdiet ut vel lorem. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
        'In hac habitasse platea dictumst. Aenean vehicula auctor eros non tincidunt. Donec tempor arcu ac diam sagittis mattis. Aenean eget augue nulla, non volutpat lorem. Praesent ut cursus magna. Mauris consequat suscipit nisi. Integer eu venenatis ligula. Maecenas leo risus, lacinia et auctor aliquet, aliquet in mi.',
        'Aliquam tincidunt dapibus augue, et vulputate dui aliquet et. Praesent pharetra mauris eu justo dignissim venenatis ornare nec nisl. Aliquam justo quam, varius eget congue vel, congue eget est. Ut nulla felis, luctus imperdiet molestie et, commodo vel nulla. Morbi at nulla dapibus enim bibendum aliquam non et ipsum. Phasellus sed cursus justo. Praesent sit amet metus lorem. Vivamus ut lorem dapibus turpis rhoncus pharetra. Donec in lacus sagittis nisl tempor sagittis quis a orci. Nam volutpat condimentum ante ac facilisis. Cras sem magna, vulputate id consequat rhoncus, suscipit non justo. In fringilla dignissim cursus.',
        'Nunc fringilla orci tellus, et euismod lorem. Ut quis turpis lacus, ac elementum lorem. Praesent fringilla, metus nec tincidunt consequat, sem sapien hendrerit nisi, nec feugiat libero risus a nisl. Duis arcu magna, ullamcorper et semper vitae, tincidunt nec libero. Etiam sed lacus ante. In imperdiet arcu eget elit commodo ut malesuada sem congue. Quisque porttitor porta sagittis. Nam porta elit sit amet mauris fermentum eu feugiat ipsum pretium. Maecenas sollicitudin aliquam eros, ut pretium nunc faucibus quis. Mauris id metus vitae libero viverra adipiscing quis ut nulla. Pellentesque posuere facilisis nibh, facilisis vehicula felis facilisis nec.',
        'Etiam pharetra libero nec erat pellentesque laoreet. Sed eu libero nec nisl vehicula convallis nec non orci. Aenean tristique varius nisl. Cras vel urna eget enim placerat vehicula quis sed velit. Quisque lacinia sagittis lectus eget sagittis. Pellentesque cursus suscipit massa vel ultricies. Quisque hendrerit lobortis elit interdum feugiat. Sed posuere volutpat erat vel lobortis. Vivamus laoreet mattis varius. Fusce tincidunt accumsan lorem, in viverra lectus dictum eu. Integer venenatis tristique dolor, ac porta lacus pellentesque pharetra. Suspendisse potenti. Ut dolor dolor, sollicitudin in auctor nec, facilisis non justo. Mauris cursus euismod gravida. In at orci in sapien laoreet euismod.',
        'Mauris purus urna, vulputate in malesuada ac, varius eget ante. Integer ultricies lacus vel magna dictum sit amet euismod enim dictum. Aliquam iaculis, ipsum at tempor bibendum, dolor tortor eleifend elit, sed fermentum magna nibh a ligula. Phasellus ipsum nisi, porta quis pellentesque sit amet, dignissim vel felis. Quisque condimentum molestie ligula, ac auctor turpis facilisis ac. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent molestie leo velit. Sed sit amet turpis massa. Donec in tortor quis metus cursus iaculis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hac habitasse platea dictumst. Proin leo nisl, faucibus non sollicitudin et, commodo id diam. Aliquam adipiscing, lorem a fringilla blandit, felis dui tristique ligula, vitae eleifend orci diam eget quam. Aliquam vulputate gravida leo eget eleifend. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;',
        'Etiam et consectetur justo. Integer et ante dui, quis rutrum massa. Fusce nibh nisl, congue sit amet tempor vitae, ornare et nisi. Nulla mattis nisl ut ligula sagittis aliquam. Curabitur ac augue at velit facilisis venenatis quis sit amet erat. Donec lacus elit, auctor sed lobortis aliquet, accumsan nec mi. Quisque non est ante. Morbi vehicula pulvinar magna, quis luctus tortor varius et. Donec hendrerit nulla posuere odio lobortis interdum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dapibus magna id ante sodales tempus. Maecenas at eleifend nulla.',
        'Sed eget gravida magna. Quisque vulputate diam nec libero faucibus vitae fringilla ligula lobortis. Aenean congue, dolor ut dapibus fermentum, justo lectus luctus sem, et vestibulum lectus orci non mauris. Vivamus interdum mauris at diam scelerisque porta mollis massa hendrerit. Donec condimentum lacinia bibendum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam neque dolor, faucibus sed varius sit amet, vulputate vitae nunc.',
        'Etiam in lorem congue nunc sollicitudin rhoncus vel in metus. Integer luctus semper sem ut interdum. Sed mattis euismod diam, at porta mauris laoreet quis. Nam pellentesque enim id mi vestibulum gravida in vel libero. Nulla facilisi. Morbi fringilla mollis malesuada. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum sagittis consectetur auctor. Phasellus convallis turpis eget diam tristique feugiat. In consectetur quam faucibus purus suscipit euismod quis sed quam. Curabitur eget sodales dui. Quisque egestas diam quis sapien aliquet tincidunt.',
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam velit est, imperdiet ac posuere non, dictum et nunc. Duis iaculis lacus in libero lacinia ut consectetur nisi facilisis. Fusce aliquet nisl id eros dapibus viverra. Phasellus eget ultrices nisl. Nullam euismod tortor a metus hendrerit convallis. Donec dolor magna, fringilla in sollicitudin sit amet, tristique eget elit. Praesent adipiscing magna in ipsum vulputate non lacinia metus vestibulum. Aenean dictum suscipit mollis. Nullam tristique commodo dapibus. Fusce in tellus sapien, at vulputate justo. Nam ornare, lorem sit amet condimentum ultrices, ipsum velit tempor urna, tincidunt convallis sapien enim eget leo. Proin ligula tellus, ornare vitae scelerisque vitae, fringilla fermentum sem. Phasellus ornare, diam sed luctus condimentum, nisl felis ultricies tortor, ac tempor quam lacus sit amet lorem. Nunc egestas, nibh ornare dictum iaculis, diam nisl fermentum magna, malesuada vestibulum est mauris quis nisl. Ut vulputate pharetra laoreet.',
        'Donec mattis mauris et dolor commodo et pellentesque libero congue. Sed tristique bibendum augue sed auctor. Sed in ante enim. In sed lectus massa. Nulla imperdiet nisi at libero faucibus sagittis ac ac lacus. In dui purus, sollicitudin tempor euismod euismod, dapibus vehicula elit. Aliquam vulputate, ligula non dignissim gravida, odio elit ornare risus, a euismod est odio nec ipsum. In hac habitasse platea dictumst. Mauris posuere ultrices mattis. Etiam vitae leo vitae nunc porta egestas at vitae nibh. Sed pharetra, magna nec bibendum aliquam, dolor sapien consequat neque, sit amet euismod orci elit vitae enim. Sed erat metus, laoreet quis posuere id, congue id velit. Mauris ac velit vel ipsum dictum ornare eget vitae arcu. Donec interdum, neque at lacinia imperdiet, ante libero convallis quam, pellentesque faucibus quam dolor id est. Ut cursus facilisis scelerisque. Sed vitae ligula in purus malesuada porta.',
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec vestibulum vestibulum metus. Integer ultrices ultricies pellentesque. Nulla gravida nisl a magna gravida ullamcorper. Vestibulum accumsan eros vel massa euismod in aliquam felis suscipit. Ut et purus enim, id congue ante. Mauris magna lectus, varius porta pellentesque quis, dignissim in est. Nulla facilisi. Nullam in malesuada mauris. Ut fermentum orci neque. Aliquam accumsan justo a lacus vestibulum fermentum. Donec molestie, quam id adipiscing viverra, massa velit aliquam enim, vitae dapibus turpis libero id augue. Quisque mi magna, mollis vel tincidunt nec, adipiscing sed metus. Maecenas tincidunt augue quis felis dapibus nec elementum justo fringilla. Sed eget massa at sapien tincidunt porta eu id sapien.'
    );
    $rand = rand(0, (sizeof($lipsum) - 1));

    return mw('format')->limit($lipsum[$rand], $number_of_characters, '');
}

api_expose('pixum_img');


function _d($a)
{
    $rand = uniqid();
    //echo "<div style='display:none' id='d-" . $rand . "'>";
    //var_dump($a);
    $js = json_encode($a);
    //print "</div>";
    print "<script>
	$(document).ready(function(){
		window.console && console.log

		&& console.log(" . $js . ");
	});
</script>
";


    //print "<script>$(document).ready(function(){var x = mw.$('#d-" . $rand . "').html();var xx = mw.tools.modal.init({html:'<pre>'+x+'</pre>'});$(xx.main).css({left:'auto',right:0})});</script>";
}


function mw_date($date)
{
    $date_format = mw('option')->get('date_format', 'website');
    if ($date_format == false) {
        $date_format = "Y-m-d H:i:s";
    }

    if (isset($date) and  trim($date) != '') {
        $date = date($date_format, strtotime($date));
        return $date;
    }
}


// Thanks to http://www.eval.ca/articles/php-pluralize (MIT license)
//           http://dev.rubyonrails.org/browser/trunk/activesupport/lib/active_support/inflections.rb (MIT license)
//           http://www.fortunecity.com/bally/durrus/153/gramch13.html
//           http://www2.gsu.edu/~wwwesl/egw/crump.htm
//
// Changes (12/17/07)
//   Major changes
//   --
//   Fixed irregular noun algorithm to use regular expressions just like the original Ruby source.
//       (this allows for things like fireman -> firemen
//   Fixed the order of the singular array, which was backwards.
//
//   Minor changes
//   --
//   Removed incorrect pluralization rule for /([^aeiouy]|qu)ies$/ => $1y
//   Expanded on the list of exceptions for *o -> *oes, and removed rule for buffalo -> buffaloes
//   Removed dangerous singularization rule for /([^f])ves$/ => $1fe
//   Added more specific rules for singularizing lives, wives, knives, sheaves, loaves, and leaves and thieves
//   Added exception to /(us)es$/ => $1 rule for houses => house and blouses => blouse
//   Added excpetions for feet, geese and teeth
//   Added rule for deer -> deer

// Changes:
//   Removed rule for virus -> viri
//   Added rule for potato -> potatoes
//   Added rule for *us -> *uses
function pluralize($string)
{

    return MWText::pluralize($string);
}

class MWText
{
    static $plural = array(
        '/(quiz)$/i' => "$1zes",
        '/^(ox)$/i' => "$1en",
        '/([m|l])ouse$/i' => "$1ice",
        '/(matr|vert|ind)ix|ex$/i' => "$1ices",
        '/(x|ch|ss|sh)$/i' => "$1es",
        '/([^aeiouy]|qu)y$/i' => "$1ies",
        '/(hive)$/i' => "$1s",
        '/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
        '/(shea|lea|loa|thie)f$/i' => "$1ves",
        '/sis$/i' => "ses",
        '/([ti])um$/i' => "$1a",
        '/(tomat|potat|ech|her|vet)o$/i' => "$1oes",
        '/(bu)s$/i' => "$1ses",
        '/(alias)$/i' => "$1es",
        '/(octop)us$/i' => "$1i",
        '/(ax|test)is$/i' => "$1es",
        '/(us)$/i' => "$1es",
        '/s$/i' => "s",
        '/$/' => "s"
    );
    static $singular = array(
        '/(quiz)zes$/i' => "$1",
        '/(matr)ices$/i' => "$1ix",
        '/(vert|ind)ices$/i' => "$1ex",
        '/^(ox)en$/i' => "$1",
        '/(alias)es$/i' => "$1",
        '/(octop|vir)i$/i' => "$1us",
        '/(cris|ax|test)es$/i' => "$1is",
        '/(shoe)s$/i' => "$1",
        '/(o)es$/i' => "$1",
        '/(bus)es$/i' => "$1",
        '/([m|l])ice$/i' => "$1ouse",
        '/(x|ch|ss|sh)es$/i' => "$1",
        '/(m)ovies$/i' => "$1ovie",
        '/(s)eries$/i' => "$1eries",
        '/([^aeiouy]|qu)ies$/i' => "$1y",
        '/([lr])ves$/i' => "$1f",
        '/(tive)s$/i' => "$1",
        '/(hive)s$/i' => "$1",
        '/(li|wi|kni)ves$/i' => "$1fe",
        '/(shea|loa|lea|thie)ves$/i' => "$1f",
        '/(^analy)ses$/i' => "$1sis",
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => "$1$2sis",
        '/([ti])a$/i' => "$1um",
        '/(n)ews$/i' => "$1ews",
        '/(h|bl)ouses$/i' => "$1ouse",
        '/(corpse)s$/i' => "$1",
        '/(us)es$/i' => "$1",
        '/s$/i' => ""
    );
    static $irregular = array(
        'move' => 'moves',
        'foot' => 'feet',
        'goose' => 'geese',
        'sex' => 'sexes',
        'child' => 'children',
        'man' => 'men',
        'tooth' => 'teeth',
        'person' => 'people'
    );
    static $uncountable = array(
        'sheep',
        'fish',
        'deer',
        'series',
        'species',
        'money',
        'rice',
        'information',
        'equipment'
    );

    public static function singularize($string)
    {
        // save some time in the case that singular and plural are the same
        if (in_array(strtolower($string), self::$uncountable))
            return $string;

        // check for irregular plural forms
        foreach (self::$irregular as $result => $pattern) {
            $pattern = '/' . $pattern . '$/i';

            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        // check for matches using regular expressions
        foreach (self::$singular as $pattern => $result) {
            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        return $string;
    }

    public static function pluralize_if($count, $string)
    {
        if ($count == 1)
            return "1 $string";
        else
            return $count . " " . self::pluralize($string);
    }

    public static function pluralize($string)
    {
        // save some time in the case that singular and plural are the same
        if (in_array(strtolower($string), self::$uncountable))
            return $string;

        // check for irregular singular forms
        foreach (self::$irregular as $pattern => $result) {
            $pattern = '/' . $pattern . '$/i';

            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        // check for matches using regular expressions
        foreach (self::$plural as $pattern => $result) {
            if (preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        return $string;
    }
}


function random_color()
{
    return "#" . sprintf("%02X%02X%02X", mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
}


if (!function_exists('put_ini_file')) {
    function put_ini_file($file, $array, $i = 0)
    {
        $str = "";
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $str .= str_repeat(" ", $i * 2) . "[$k]" . PHP_EOL;
                $str .= put_ini_file("", $v, $i + 1);
            } else
                $str .= str_repeat(" ", $i * 2) . "$k = $v" . PHP_EOL;
        }
        if ($file)
            return file_put_contents($file, $str);
        else
            return $str;
    }
}


function fastimagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $quality = 3)
{
    // Plug-and-Play fastimagecopyresampled function replaces much slower imagecopyresampled.
    // Just include this function and change all "imagecopyresampled" references to "fastimagecopyresampled".
    // Typically from 30 to 60 times faster when reducing high resolution images down to thumbnail size using the default quality setting.
    // Author: Tim Eckel - Date: 09/07/07 - Version: 1.1 - Project: FreeRingers.net - Freely distributable - These comments must remain.
    //
    // Optional "quality" parameter (defaults is 3). Fractional values are allowed, for example 1.5. Must be greater than zero.
    // Between 0 and 1 = Fast, but mosaic results, closer to 0 increases the mosaic effect.
    // 1 = Up to 350 times faster. Poor results, looks very similar to imagecopyresized.
    // 2 = Up to 95 times faster.  Images appear a little sharp, some prefer this over a quality of 3.
    // 3 = Up to 60 times faster.  Will give high quality smooth results very close to imagecopyresampled, just faster.
    // 4 = Up to 25 times faster.  Almost identical to imagecopyresampled for most images.
    // 5 = No speedup. Just uses imagecopyresampled, no advantage over imagecopyresampled.

    if (empty($src_image) || empty($dst_image) || $quality <= 0) {
        return false;
    }
    if ($quality < 5 && (($dst_w * $quality) < $src_w || ($dst_h * $quality) < $src_h)) {
        $temp = imagecreatetruecolor($dst_w * $quality + 1, $dst_h * $quality + 1);
        imagecopyresized($temp, $src_image, 0, 0, $src_x, $src_y, $dst_w * $quality + 1, $dst_h * $quality + 1, $src_w, $src_h);
        imagecopyresampled($dst_image, $temp, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $dst_w * $quality, $dst_h * $quality);
        imagedestroy($temp);
    } else imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
    return true;
}


function is_fqdn($FQDN)
{
    return (!empty($FQDN) && preg_match('/(?=^.{1,254}$)(^(?:(?!\d|-)[a-z0-9\-]{1,63}(?<!-)\.)+(?:[a-z]{2,})$)/i', $FQDN) > 0);
}