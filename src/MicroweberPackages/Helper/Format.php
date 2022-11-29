<?php
namespace MicroweberPackages\Helper;

use GrahamCampbell\SecurityCore\Security;
use Illuminate\Contracts\Encryption\DecryptException;
use Crypt;
use MicroweberPackages\CustomField\Models\CustomField;


require_once MW_PATH . 'Utils' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'phpQuery.php';


class Format
{



    /**
     * Prints an array in unordered list - <ul>.
     *
     * @param array $arr
     *
     * @return string
     *
     * @category Arrays
     */
    public function array_to_ul($arr, $ul_tag = 'ul', $li_tag = 'li')
    {
        $has_items = false;
        $retStr = '<' . $ul_tag . '>';
        if (is_array($arr)) {
            foreach ($arr as $key => $val) {
                if (!is_array($key) and $val) {
                    $key = str_replace('_', ' ', $key);
                    $key = ucwords($key);

                    if (is_array($val)) {
                        if (!empty($val)) {
                            $has_items = true;
                            if (is_numeric($key)) {
                                $retStr .= '<' . $ul_tag . '>';
                                $retStr .= '<' . $li_tag . '>' . $this->array_to_ul($val, $ul_tag, $li_tag) . '</' . $li_tag . '>';
                                $retStr .= '</' . $ul_tag . '>';
                            } else {
                                $retStr .= '<' . $li_tag . '><span>' . $key . ':</span> ' . $this->array_to_ul($val, $ul_tag, $li_tag) . '</' . $li_tag . '>';
                            }
                        }
                    } else {
                        if (is_string($val) != false and trim($val) != '') {
                            $has_items = true;
                            $print_key = $key;
                            if (is_numeric($key)) {
                                $retStr .= '<' . $li_tag . '><span></span> ' . $val . '</' . $li_tag . '>';
                            } else {
                                $retStr .= '<' . $li_tag . '><span>' . $print_key . ':</span> ' . $val . '</' . $li_tag . '>';

                            }

                        }
                    }
                } else {
                    if (!empty($val)) {
                        $has_items = true;
                        $retStr .= $this->array_to_ul($val, $ul_tag, $li_tag);
                    }
                }
            }
        }
        $retStr .= '</' . $ul_tag . '>';
        if ($has_items) {
            return $retStr;
        }
    }

    function array_to_table($array, $table = true)
    {
        $out = '';
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!isset($tableHeader)) {
                    $tableHeader =
                        '<th>' .
                        implode('</th><th>', array_keys($value)) .
                        '</th>';
                }
                array_keys($value);
                $out .= '<tr>';
                $out .= $this->array_to_table($value, false);
                $out .= '</tr>';
            } else {
                $out .= "<td>$value</td>";
            }
        }

        if ($table) {
            return '<table>' . $tableHeader . $out . '</table>';
        } else {
            return $out;
        }
    }


    /*
     * Formats a date by given pattern.
     *
     * @param             $date        Your date
     * @param bool|string $date_format The format for example 'Y-m-d'
     *
     * @return bool|string $date The formatted date
     *
     * @category Date
    */
    public function date($date, $date_format = false)
    {
        if ($date_format == false) {
            if (isset(app()->option_manager)) {
                $date_format = app()->option_manager->get('date_format', 'website');
                if ($date_format == false) {
                    $date_format = 'Y-m-d H:i:s';
                }
            }
        }
        $date = date($date_format, strtotime($date));

        return $date;
    }


    /**
     * Find Date in a String
     *
     * @author   Etienne Tremel
     * @license  http://creativecommons.org/licenses/by/3.0/ CC by 3.0
     * @link     http://www.etiennetremel.net
     * @version  0.2.0
     *
     *
     *
     * @param string  find_date( ' some text 01/01/2012 some text' ) or find_date( ' some text October 5th 86 some text' )
     * @return mixed  false if no date found else array: array( 'day' => 01, 'month' => 01, 'year' => 2012 )
     */
    function find_date($string)
    {
        $shortenize = function ($string) {
            return substr($string, 0, 3);
        };
        // Define month name:
        $month_names = array(
            "january",
            "february",
            "march",
            "april",
            "may",
            "june",
            "july",
            "august",
            "september",
            "october",
            "november",
            "december"
        );
        $short_month_names = array_map($shortenize, $month_names);
        // Define day name
        $day_names = array(
            "monday",
            "tuesday",
            "wednesday",
            "thursday",
            "friday",
            "saturday",
            "sunday"
        );
        $short_day_names = array_map($shortenize, $day_names);
        // Define ordinal number
        $ordinal_number = ['st', 'nd', 'rd', 'th'];
        $day = "";
        $month = "";
        $year = "";
        // Match dates: 01/01/2012 or 30-12-11 or 1 2 1985
        preg_match('/([0-9]?[0-9])[\.\-\/ ]+([0-1]?[0-9])[\.\-\/ ]+([0-9]{2,4})/', $string, $matches);
        if ($matches) {
            if ($matches[1])
                $day = $matches[1];
            if ($matches[2])
                $month = $matches[2];
            if ($matches[3])
                $year = $matches[3];
        }
        // Match dates: Sunday 1st March 2015; Sunday, 1 March 2015; Sun 1 Mar 2015; Sun-1-March-2015
        preg_match('/(?:(?:' . implode('|', $day_names) . '|' . implode('|', $short_day_names) . ')[ ,\-_\/]*)?([0-9]?[0-9])[ ,\-_\/]*(?:' . implode('|', $ordinal_number) . ')?[ ,\-_\/]*(' . implode('|', $month_names) . '|' . implode('|', $short_month_names) . ')[ ,\-_\/]+([0-9]{4})/i', $string, $matches);
        if ($matches) {
            if (empty($day) && $matches[1])
                $day = $matches[1];
            if (empty($month) && $matches[2]) {
                $month = array_search(strtolower($matches[2]), $short_month_names);
                if (!$month)
                    $month = array_search(strtolower($matches[2]), $month_names);
                $month = $month + 1;
            }
            if (empty($year) && $matches[3])
                $year = $matches[3];
        }
        // Match dates: March 1st 2015; March 1 2015; March-1st-2015
        preg_match('/(' . implode('|', $month_names) . '|' . implode('|', $short_month_names) . ')[ ,\-_\/]*([0-9]?[0-9])[ ,\-_\/]*(?:' . implode('|', $ordinal_number) . ')?[ ,\-_\/]+([0-9]{4})/i', $string, $matches);
        if ($matches) {
            if (empty($month) && $matches[1]) {
                $month = array_search(strtolower($matches[1]), $short_month_names);
                if (!$month)
                    $month = array_search(strtolower($matches[1]), $month_names);
                $month = $month + 1;
            }
            if (empty($day) && $matches[2])
                $day = $matches[2];
            if (empty($year) && $matches[3])
                $year = $matches[3];
        }
        // Match month name:
        if (empty($month)) {
            preg_match('/(' . implode('|', $month_names) . ')/i', $string, $matches_month_word);
            if ($matches_month_word && $matches_month_word[1])
                $month = array_search(strtolower($matches_month_word[1]), $month_names);
            // Match short month names
            if (empty($month)) {
                preg_match('/(' . implode('|', $short_month_names) . ')/i', $string, $matches_month_word);
                if ($matches_month_word && $matches_month_word[1])
                    $month = array_search(strtolower($matches_month_word[1]), $short_month_names);
            }
            $month = $month + 1;
        }
        // Match 5th 1st day:
        if (empty($day)) {
            preg_match('/([0-9]?[0-9])(' . implode('|', $ordinal_number) . ')/', $string, $matches_day);
            if ($matches_day && $matches_day[1])
                $day = $matches_day[1];
        }
        // Match Year if not already setted:
        if (empty($year)) {
            preg_match('/[0-9]{4}/', $string, $matches_year);
            if ($matches_year && $matches_year[0])
                $year = $matches_year[0];
        }
        if (!empty ($day) && !empty ($month) && empty($year)) {
            preg_match('/[0-9]{2}/', $string, $matches_year);
            if ($matches_year && $matches_year[0])
                $year = $matches_year[0];
        }
        // Day leading 0
        if (1 == strlen($day))
            $day = '0' . $day;
        // Month leading 0
        if (1 == strlen($month))
            $month = '0' . $month;
        // Check year:
        if (2 == strlen($year) && $year > 20)
            $year = '19' . $year;
        else if (2 == strlen($year) && $year < 20)
            $year = '20' . $year;
        $date = array(
            'year' => $year,
            'month' => $month,
            'day' => $day
        );
        // Return false if nothing found:
        if (empty($year) && empty($month) && empty($day))
            return false;
        else
            return $date;
    }

    function get_date_format()
    {
        $date_format_set = get_option('date_format', 'website');
        $date_format_default = 'm/d/Y h:i a';
        $date_format = '';
        if ($date_format_set && (strstr($date_format_set, '/') || strstr($date_format_set, '-'))) {
            $date_format = str_replace('-', '/', $date_format_set);
            if (strstr($date_format, 'd/m')) {
                $date_format = 'd/m/Y h:i a';
            } else {
                $date_format = $date_format_default;
            }
        } else {
            $date_format = $date_format_default;
        }
        return $date_format;
    }

    function date_system_format($db_date)
    {
        $date_format = $this->get_date_format();
        $date = date_create($db_date);
        return date_format($date, $date_format);
    }

    function get_date_db_format($str_date)
    {
        $date_format_set = get_option('date_format', 'website');
        $date_db_format = 'Y-m-d H:i:s';
        $date_format_default = 'm/d/Y h:i a';
        $str_db_date = '';
        if (strstr($str_date, '/') || strstr($str_date, '-') || strstr($str_date, '.')) {
            $str_date = str_replace('-', '/', $str_date);
            $str_date = str_replace('.', '/', $str_date);
        }
        if ($date_format_set) {
            $date = $this->find_date($str_date);
            $str_db_date = $date['year'] . '-' . $date['month'] . '-' . $date['day'];
        } elseif ($dateTime = \DateTime::createFromFormat($date_format_default, $str_date)) {
            $str_db_date = $dateTime->format($date_db_format);
        } else {
            $str_db_date = '0000-00-00 00:00:00';
        }
        return $str_db_date;
    }


    public function array_trim($variable)
    {
        $result = array_map('trim', $variable);

        return $result;
    }

    public function add_slashes_recursive($variable)
    {
        if (is_string($variable)) {
            return addslashes($variable);
        } elseif (is_array($variable)) {
            foreach ($variable as $i => $value) {
                $variable[$i] = $this->add_slashes_recursive($value);
            }
        }

        return $variable;
    }

    public function strip_slashes_recursive($variable)
    {
        if (is_string($variable)) {
            return stripslashes($variable);
        }
        if (is_array($variable)) {
            foreach ($variable as $i => $value) {
                $variable[$i] = $this->strip_slashes_recursive($value);
            }
        }

        return $variable;
    }

    public function auto_link($text)
    {
        return $this->autolink($text);
    }

    //http://stackoverflow.com/a/1971451/731166

    public function autolink($text)
    {
        $pattern = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';

        return preg_replace_callback($pattern, array($this, 'auto_link_text_callback'), $text);
    }

    public function auto_link_text_callback($matches)
    {
        $max_url_length = 150;
        $max_depth_if_over_length = 2;
        $ellipsis = '&hellip;';

        $url_full = $matches[0];
        $url_short = '';

        if (strlen($url_full) > $max_url_length) {
            $parts = parse_url($url_full);

            $url_short = $parts['scheme'] . '://' . preg_replace('/^www\./', '', $parts['host']) . '/';

            $path_components = explode('/', trim($parts['path'], '/'));
            foreach ($path_components as $dir) {
                $url_string_components[] = $dir . '/';
            }

            if (!empty($parts['query'])) {
                $url_string_components[] = '?' . $parts['query'];
            }

            if (!empty($parts['fragment'])) {
                $url_string_components[] = '#' . $parts['fragment'];
            }

            for ($k = 0; $k < count($url_string_components); ++$k) {
                $curr_component = $url_string_components[$k];
                if ($k >= $max_depth_if_over_length || strlen($url_short) + strlen($curr_component) > $max_url_length) {
                    if ($k == 0 && strlen($url_short) < $max_url_length) {
                        // Always show a portion of first directory
                        $url_short .= substr($curr_component, 0, $max_url_length - strlen($url_short));
                    }
                    $url_short .= $ellipsis;
                    break;
                }
                $url_short .= $curr_component;
            }
        } else {
            $url_short = $url_full;
        }

        // return "<a rel=\"nofollow\" href=\"$url_full\" target='_blank'>$url_short</a>";

        return "<a href=\"$url_full\">$url_short</a>";
    }

    public function human_filesize($bytes, $dec = 2)
    {
        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    public function ago($time, $full = false)
    {

        $now = new \DateTime;

        if (is_int($time)) {
            $date = $time;
            $ago = new \DateTime("@" . $time);
        } else {
            // $date = strtotime($time);
            $ago = new \DateTime($time);
        }


        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function clean_xss($var, $do_not_strip_tags = false, $evil = null, $method = 'process')
    {
        static $sec;

        if ($sec == false) {
            $sec = new XSSSecurity($evil);
        }

        if (is_array($var)) {
            foreach ($var as $key => $val) {
                $output[$key] = $this->clean_xss($val, $do_not_strip_tags, $evil, $method);
            }
        } else {



            // get svg
            $pq = \phpQuery::newDocument($var);
            $svgs_to_remove = $pq->find('svg');
            $replaces_strings_svg = [];
            if ($svgs_to_remove) {
                foreach ($svgs_to_remove as $elem) {
                  $elem = pq($elem);
                   $svg_string =  $elem->htmlOuter();
                    $svg_string_rep = 'replaced_svg_'.md5($svg_string);
                    $replaces_strings_svg[$svg_string_rep] = $svg_string;
                }
            }
            if($replaces_strings_svg){
                // replace svg tags with placeholder string
                foreach ($replaces_strings_svg as $key => $value) {
                    $var = str_replace($value, $key, $var);
                }
            }



            $var = $sec->clean($var);


            if($replaces_strings_svg){
                foreach ($replaces_strings_svg as $key => $value) {
                    // clean svg
                    $sanitizer = new \enshrined\svgSanitize\Sanitizer();
                    $dirtySVG = $value;
                    $cleanSVG = $sanitizer->sanitize($dirtySVG);
                    $cleanSVG = str_replace('<?xml version="1.0" encoding="UTF-8"?>'."\n", '', $cleanSVG);
                    $cleanSVG = str_replace('&lt;?xml version="1.0" encoding="UTF-8"?&gt;'."\n", '', $cleanSVG);

                    $value = $cleanSVG;
                    // put back svg
                    $var = str_replace($key,$value, $var);
                }
            }




            $var = str_ireplace('<script>', '', $var);
            $var = str_ireplace('</script>', '', $var);

            $var = str_replace('<?', '&lt;?', $var);
            $var = str_replace('?>', '?&gt;', $var);
            /*  $var = str_ireplace('<module', '&lt;module', $var);
              $var = str_ireplace('<Microweber', '&lt;Microweber', $var);*/

            $var = str_ireplace('javascript:', '', $var);
            $var = str_ireplace('vbscript:', '', $var);
            $var = str_ireplace('livescript:', '', $var);
            $var = str_ireplace('HTTP-EQUIV=', '', $var);
            $var = str_ireplace("\0075\0072\\", '', $var);

            if ($do_not_strip_tags == false) {
                $var = strip_tags(trim($var));
            }

            $output = $var;

            return $output;
        }
        if (isset($output)) {
            return $output;
        }
    }

    public function clean_scripts($input)
    {
        if (is_array($input)) {
            $output = array();
            foreach ($input as $var => $val) {
                $output[$var] = $this->clean_scripts($val);
            }
        } elseif (is_string($input)) {
            $search = array(
                '@<script[^>]*?>.*?</script>@si', // Strip out javascript

                '@<![\s\S]*?--[ \t\n\r]*>@', // Strip multi-line comments
            );
            $output = preg_replace($search, '', $input);
        } else {
            return $input;
        }

        return $output;
    }

    public function clean_html($var, $do_not_strip_tags = false)
    {
        if (is_array($var)) {
            foreach ($var as $key => $val) {
                $output[$key] = $this->clean_html($val, $do_not_strip_tags);
            }
        } else {

            $path = false;

            if (function_exists('mw_cache_path')) {
                $path = mw_cache_path() . '/html_purifier';
            }

            if (function_exists('storage_path')) {
                $path = storage_path() . '/html_purifier';
            }

            $var = $this->strip_unsafe($var);
            $config = \HTMLPurifier_Config::createDefault();

            if ($path) {
                $config->set('Cache.SerializerPath', $path);
            }
           // $purifier = new \HTMLPurifier($config);

//         Absolute path with no trailing slash to store serialized definitions in.
//        Default is within the HTML Purifier library inside DefinitionCache/Serializer. This path must be writable by the webserver.

            if ($path) {
                if (!is_dir($path)) {
                    mkdir_recursive($path);
                }
            }

//            if (is_string($var)) {
//                $var = Security::create()->clean($var);
//            }



            $purifier = new \HTMLPurifier($config);
            //Cache.SerializerPath
             $var = $purifier->purify($var);
            // $var = htmlentities($var, ENT_QUOTES, 'UTF-8');
            $var = str_ireplace('<script>', '', $var);
            $var = str_ireplace('</script>', '', $var);
            $var = str_replace('<?', '&lt;?', $var);
            $var = str_replace('?>', '?&gt;', $var);
            //     $var = str_ireplace('<module', '&lt;module', $var);
            //   $var = str_ireplace('<Microweber', '&lt;Microweber', $var);
            $var = str_ireplace("\0075\0072\\", '', $var);
            if ($do_not_strip_tags == false) {
                $var = strip_tags(trim($var));
            }
            $output = $var;

            return $output;
        }
        if (isset($output)) {
            return $output;
        }
    }

    public function strip_unsafe($string, $img = false)
    {

        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = $this->strip_unsafe($val, $img);
            }

            return $string;
        } else {

            // Unsafe HTML tags that members may abuse
            $unsafe = array(
                '/<iframe(.*?)<\/iframe>/is',
               // '/<title(.*?)<\/title>/is',
                //'/<pre(.*?)<\/pre>/is',
              //  '/<audio(.*?)<\/audio>/is',
             //   '/<video(.*?)<\/video>/is',
                '/<frame(.*?)<\/frame>/is',
                '/<frameset(.*?)<\/frameset>/is',
                '/<object(.*?)<\/object>/is',
                '/<script(.*?)<\/script>/is',
                '/<embed(.*?)<\/embed>/is',
                '/<applet(.*?)<\/applet>/is',
                '/<meta(.*?)>/is',
                '/<!doctype(.*?)>/is',
                '/<link(.*?)>/is',
                '/<style(.*?)<\/style>/is',
                '/<body(.*?)>/is',
                '/<\/body>/is',
               // '/<head(.*?)>/is',
                '/<\/head>/is',
                '/onload="(.*?)"/is',
                '/onunload="(.*?)"/is',
                '/onafterprint="(.*?)"/is',
                '/onbeforeprint="(.*?)"/is',
                '/onbeforeunload="(.*?)"/is',
                '/onerrorNew="(.*?)"/is',
                '/onhaschange="(.*?)"/is',
                '/onoffline="(.*?)"/is',
                '/ononline="(.*?)"/is',
                '/onpagehide="(.*?)"/is',
                '/onpageshow="(.*?)"/is',
                '/onpopstate="(.*?)"/is',
                '/onredo="(.*?)"/is',
                '/onresize="(.*?)"/is',
                '/onstorage="(.*?)"/is',
                '/onundo="(.*?)"/is',
                '/onunload="(.*?)"/is',
                '/onblur="(.*?)"/is',
                '/onchange="(.*?)"/is',
                '/oncontextmenu="(.*?)"/is',
                '/onfocus="(.*?)"/is',
                '/onformchange="(.*?)"/is',
                '/onforminput="(.*?)"/is',
                '/oninput="(.*?)"/is',
                '/oninvalid="(.*?)"/is',
                '/onreset="(.*?)"/is',
                '/onselect="(.*?)"/is',
                '/onblur="(.*?)"/is',
                '/onsubmit="(.*?)"/is',
                '/onkeydown="(.*?)"/is',
                '/onkeypress="(.*?)"/is',
                '/onkeyup="(.*?)"/is',
                '/onclick="(.*?)"/is',
                '/ondblclick="(.*?)"/is',
                '/ondrag="(.*?)"/is',
                '/ondragend="(.*?)"/is',
                '/ondragenter="(.*?)"/is',
                '/ondragleave="(.*?)"/is',
                '/ondragover="(.*?)"/is',
                '/ondragstart="(.*?)"/is',
                '/ondrop="(.*?)"/is',
                '/onmousedown="(.*?)"/is',
                '/onmousemove="(.*?)"/is',
                '/onmouseout="(.*?)"/is',
                '/onmouseover="(.*?)"/is',
                '/onmousewheel="(.*?)"/is',
                '/onmouseup="(.*?)"/is',
                '/ondragleave="(.*?)"/is',
                '/onabort="(.*?)"/is',
                '/oncanplay="(.*?)"/is',
                '/oncanplaythrough="(.*?)"/is',
                '/ondurationchange="(.*?)"/is',
                '/onended="(.*?)"/is',
                '/onerror="(.*?)"/is',
                '/onloadedmetadata="(.*?)"/is',
                '/onloadstart="(.*?)"/is',
                '/onpause="(.*?)"/is',
                '/onplay="(.*?)"/is',
                '/onabort="(.*?)"/is',
                '/onplaying="(.*?)"/is',
                '/onprogress="(.*?)"/is',
                '/onratechange="(.*?)"/is',
                '/onreadystatechange="(.*?)"/is',
                '/onseeked="(.*?)"/is',
                '/onseeking="(.*?)"/is',
                '/onstalled="(.*?)"/is',
                '/onsuspend="(.*?)"/is',
                '/ontimeupdate="(.*?)"/is',
                '/onvolumechange="(.*?)"/is',
                '/onwaiting="(.*?)"/is',
                '/href="javascript:[^"]+"/',
                '/href=javascript:/is',
                '/<html(.*?)>/is',
                '/<iframe(.*?)>/is',
                '/<iframe(.*?)/is',
                '/<\/html>/is',);

            // Remove graphic too if the user wants
            if ($img == true) {
                $unsafe[] = '/<img(.*?)>/is';
            }
            // Remove these tags and all parameters within them
            $string = preg_replace($unsafe, '', $string);

            return $string;
        }
    }

    public function string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }

    public function replace_once($needle, $replace, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return $haystack;
        }

        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    public function prep_url($str = '')
    {
        if ($str === 'http://' or $str === 'https://' or $str === '') {
            return '';
        }
        $url = parse_url($str);
        if (!$url or !isset($url['scheme'])) {
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
                return 'https://' . $str;
            } else {
                return 'http://' . $str;
            }
        }

        return $str;
    }

    public function percent($num_amount, $num_total, $format = true)
    {
        if ($num_amount == 0 or $num_total == 0) {
            return 0;
        }
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;

        if (!$format) {
            return $count2;
        }
        $count = number_format($count2, 0);

        return $count;
    }


    public function amount_to_float($money)
    {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '', $stringWithCommaOrDot);

        return (float)str_replace(',', '.', $removedThousendSeparator);
    }

    /**
     * Encodes a variable with json_encode and base64_encode.
     *
     * @param mixed $var Your $var
     *
     * @return string Your encoded $var
     *
     * @category Strings
     *
     * @see      $this->base64_to_array()
     */
    public function array_to_base64($var)
    {
        if ($var == '') {
            return '';
        }

        $var = json_encode($var);
        $var = base64_encode($var);

        return $var;
    }

    /**
     * Decodes a variable with base64_decode and json_decode.
     *
     * @param string $var Your var that has been put trough encode_var
     *
     * @return string|array Your encoded $var
     *
     * @category Strings
     *
     * @see      $this->array_to_base64()
     */
    public function base64_to_array($var)
    {
        if (is_array($var)) {
            return $var;
        }
        if ($var == '') {
            return false;
        }

        $var = base64_decode($var);
        try {
            $var = @json_decode($var, 1);
        } catch (Exception $exc) {
            return false;
        }

        return $var;
    }

    public function titlelize($string)
    {
        $slug = preg_replace('/-/', ' ', $string);
        $slug = preg_replace('/_/', ' ', $slug);
        $slug = ucwords($slug);

        return $slug;
    }

    public function array_values($ary)
    {
        $lst = array();
        foreach (array_keys($ary) as $k) {
            $v = $ary[$k];
            if (is_scalar($v)) {
                $lst[] = $v;
            } elseif (is_array($v)) {
                $lst = array_merge($lst, $this->array_values($v));
            }
        }

        return $lst;
    }

    public function lipsum($number_of_characters = false)
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
        );
        $rand = rand(0, (sizeof($lipsum) - 1));

        return $this->limit($lipsum[$rand], $number_of_characters, '');
    }

    /**
     * Limits a string to a number of characters.
     *
     * @param        $str
     * @param int $n
     * @param string $end_char
     *
     * @return string
     *
     * @category Strings
     */
    public function limit($str, $n = 500, $end_char = '&#8230;')
    {
        if (strlen($str) < $n) {
            return $str;
        }
        $str = strip_tags($str);
        $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));
        if (strlen($str) <= $n) {
            return $str;
        }
        $out = '';
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';
            if (strlen($out) >= $n) {
                $out = trim($out);

                return (strlen($out) == strlen($str)) ? $out : $out . $end_char;
            }
        }
    }

    public function random_color()
    {
        return '#' . sprintf('%02X%02X%02X', mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    }

    public function lnotif($text, $class = 'success')
    {
        $editmode_sess = mw()->user_manager->session_get('editmode');


        if (defined('MW_BACKEND') and MW_BACKEND != false) {
            return false;
        }
        if (defined('IN_EDIT') and IN_EDIT != false) {
            $editmode_sess = true;
        }
        // if ($editmode_sess == false) {
        if (defined('IN_EDITOR_TOOLS') and IN_EDITOR_TOOLS != false) {
            $editmode_sess = true;
        }
        //}

        if ($editmode_sess == true) {
            return $this->notif($text, $class);
        }
    }

    public function notif($text, $class = 'success')
    {
        if ($class === true) {
            $to_print = '<div><div class="mw-notification-text mw-open-module-settings">';
            $to_print = $to_print . ($text) . '</div></div>';
        } else {
            $to_print = '<div class="mw-notification mw-' . $class . ' "><div class="mw-notification-text mw-open-module-settings">';
            $to_print = $to_print . $text . '</div></div>';
        }

        return $to_print;
    }

    public function no_dashes($string)
    {
        $slug = preg_replace('/-/', ' ', $string);
        $slug = preg_replace('/_/', ' ', $slug);

        return $slug;
    }

    public function unvar_dump($str)
    {
        if (strpos($str, "\n") === false) {
            //Add new lines:
            $regex = array(
                '#(\\[.*?\\]=>)#',
                '#(string\\(|int\\(|float\\(|array\\(|NULL|object\\(|})#',
            );
            $str = preg_replace($regex, "\n\\1", $str);
            $str = trim($str);
        }
        $regex = array(
            '#^\\040*NULL\\040*$#m',
            '#^\\s*array\\((.*?)\\)\\s*{\\s*$#m',
            '#^\\s*string\\((.*?)\\)\\s*(.*?)$#m',
            '#^\\s*int\\((.*?)\\)\\s*$#m',
            '#^\\s*float\\((.*?)\\)\\s*$#m',
            '#^\\s*\[(\\d+)\\]\\s*=>\\s*$#m',
            '#\\s*?\\r?\\n\\s*#m',
        );
        $replace = array(
            'N',
            'a:\\1:{',
            's:\\1:\\2',
            'i:\\1',
            'd:\\1',
            'i:\\1',
            ';',
        );
        $serialized = preg_replace($regex, $replace, $str);
        $func = create_function(
            '$match',
            'return "s:".strlen($match[1]).":\\"".$match[1]."\\"";'
        );
        $serialized = preg_replace_callback(
            '#\\s*\\["(.*?)"\\]\\s*=>#',
            $func,
            $serialized
        );
        $func = create_function(
            '$match',
            'return "O:".strlen($match[1]).":\\"".$match[1]."\\":".$match[2].":{";'
        );
        $serialized = preg_replace_callback(
            '#object\\((.*?)\\).*?\\((\\d+)\\)\\s*{\\s*;#',
            $func,
            $serialized
        );
        $serialized = preg_replace(
            array('#};#', '#{;#'),
            array('}', '{'),
            $serialized
        );

        return unserialize($serialized);
    }

    public function is_base64($data)
    {
        $decoded = base64_decode($data, true);
        if (false === $decoded || base64_encode($decoded) != $data) {
            return false;
        }

        return true;
    }

    public function is_fqdn($FQDN)
    {
        return !empty($FQDN) && preg_match('/(?=^.{1,254}$)(^(?:(?!\d|-)[a-z0-9\-]{1,63}(?<!-)\.)+(?:[a-z]{2,})$)/i', $FQDN) > 0;
    }

   public function render_item_custom_fields_data($item)
    {

        if (isset($item['custom_fields_data']) and $item['custom_fields_data'] != '') {
            $item['custom_fields_data'] = $this->base64_to_array($item['custom_fields_data']);
            if (isset($item['custom_fields_data']) and is_array($item['custom_fields_data']) and !empty($item['custom_fields_data'])) {

                $itemCustomFields = $item['custom_fields_data'];

                $getCustomFields = CustomField::where('rel_id', $item['rel_id'])->get();
                if ($getCustomFields !== null) {
                    foreach($getCustomFields as $customField) {
                       if (isset($itemCustomFields[$customField->name])) {
                            $customFieldValues = $customField->fieldValue()->get();
                            if ($customFieldValues !== null) {
                                $selectedCustomField = $itemCustomFields[$customField->name];
                                $customFieldValuesOrdered = [];
                                foreach ($customFieldValues as $customFieldValue) {
                                    $customFieldValuesOrdered[] = $customFieldValue->value;
                                }
                                if (!is_array($selectedCustomField) && isset($customFieldValuesOrdered[$selectedCustomField])) {
                                    $itemCustomFields[$customField->name] = $customFieldValuesOrdered[$selectedCustomField];
                                }
                            }
                        }
                    }
                }

                $tmp_val = $this->array_to_ul($itemCustomFields);
                $item['custom_fields'] = $tmp_val;
            }
        }

        return $item;
    }


    public function encrypt($string)
    {
        return Crypt::encrypt($string);
    }

    public function decrypt($string)
    {
        return Crypt::decrypt($string);
    }


    public function encode_ids($data)
    {
        $hashids = new \Hashids\Hashids();
        return $hashids->encode($data);
    }

    public function decode_ids($data)
    {
        $hashids = new \Hashids\Hashids();
        return $hashids->decode($data);
    }


    function split_dates($min, $max, $parts = 7, $output = "Y-m-d")
    {
        $dataCollection[] = date($output, strtotime($min));
        $diff = (strtotime($max) - strtotime($min)) / $parts;
        $convert = strtotime($min) + $diff;

        for ($i = 1; $i < $parts; $i++) {
            $dataCollection[] = date($output, $convert);
            $convert += $diff;
        }
        $dataCollection[] = date($output, strtotime($max));
        return $dataCollection;
    }


    public function text_to_image($text)
    {
        $options = array();
        if (is_array($text)) {
            $options = $text;
            if (isset($options['text'])) {
                $text = $options['text'];
            } else {
                $text = 'Hello world!';
            }

        }


        $simple_text_image = new lib\SimpleTextImage($text);
        if (isset($options['font_size'])) {
            $simple_text_image->setFontSize(intval($options['font_size']));
        }

        if (isset($options['padding'])) {
            $simple_text_image->setPadding(intval($options['padding']));
        }

        if (isset($options['bg_color'])) {
            $color = $options['bg_color'];
            $rgb = $this->hex_to_rgb($color);
            $simple_text_image->setBackground($rgb['r'], $rgb['g'], $rgb['b']);
        }

        if (isset($options['fg_color'])) {
            $color = $options['fg_color'];
            $rgb = $this->hex_to_rgb($color);
            $simple_text_image->setForeground($rgb['r'], $rgb['g'], $rgb['b']);
        }

        // Enable output buffering
        ob_start();
        $simple_text_image->render('png');
        $imagedata = ob_get_contents();

        ob_end_clean();


        return 'data:image/png;base64,' . base64_encode($imagedata);

    }

    public function hex_to_rgb($hex, $alpha = false)
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 6) {
            $rgb['r'] = hexdec(substr($hex, 0, 2));
            $rgb['g'] = hexdec(substr($hex, 2, 2));
            $rgb['b'] = hexdec(substr($hex, 4, 2));
        } else if (strlen($hex) == 3) {
            $rgb['r'] = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $rgb['g'] = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $rgb['b'] = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $rgb['r'] = '0';
            $rgb['g'] = '0';
            $rgb['b'] = '0';
        }
        if ($alpha) {
            $rgb['a'] = $alpha;
        }
        return $rgb;
    }


    public function available_date_formats() {

        $formats = [];

        $formats[] = [
            'php' => 'Y-m-d',
            'js' => 'yyyy-m-d',
        ];

        $formats[] = [
            'php' => 'd-m-Y',
            'js' => 'd-m-yyyy',
        ];

        $formats[] = [
            'php' => 'm/d/y',
            'js' => 'm/d/yyyy',
        ];

        $formats[] = [
            'php' => 'd/m/Y',
            'js' => 'd/m/yyyy',
        ];

        $formats[] = [
            'php' => 'F j, Y',
            'js' => 'F j, yyyy',
        ];

        $formats[] = [
            'php' => 'F, Y',
            'js' => 'F, yyyy',
        ];

        $formats[] = [
            'php' => 'l, F jS, Y',
            'js' => 'l, F jS, yyyy',
        ];

        $formats[] = [
            'php' => 'M j, Y',
            'js' => 'M j, yyyy',
        ];


        $formats[] = [
            'php' => 'Y/m/d',
            'js' => 'yyyy/m/d',
        ];

        $formats[] = [
            'php' => 'D-M-Y',
            'js' => 'dd-M-yyyy',
        ];

        return $formats;
    }
}
