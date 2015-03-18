<?php


namespace Microweber\Utils;

class Format
{


    /**
     * Prints an array in unordered list - <ul>
     *
     * @param array $arr
     * @return string
     * @package Utils
     * @category Arrays
     */
    public function array_to_ul($arr)
    {
        $retStr = '<ul>';
        if (is_array($arr)) {
            foreach ($arr as $key => $val) {

                $key = str_replace('_', ' ', $key);
                $key = ucwords($key);

                if (is_array($val)) {
                    if (!empty($val)) {
                        if (is_numeric($key)) {
                            $retStr .= '<ul>';
                            $retStr .= '<li>' . $this->array_to_ul($val) . '</li>';
                            $retStr .= '</ul>';
                        } else {
                            $retStr .= '<li>' . $key . ': ' . $this->array_to_ul($val) . '</li>';

                        }
                    }
                } else {
                    if (is_string($val) != false and trim($val) != '') {
                        $retStr .= '<li>' . $key . ': ' . $val . '</li>';
                    }

                }
            }
        }
        $retStr .= '</ul>';
        return $retStr;
    }

    /**
     * Formats a date by given pattern
     *
     * @param $date Your date
     * @param bool|string $date_format The format for example 'Y-m-d'
     * @return bool|string $date The formatted date
     *
     * @package Utils
     * @category Date
     */
    public function date($date, $date_format = false)
    {
        if ($date_format == false) {
            $date_format = mw()->option_manager->get('date_format', 'website');
            if ($date_format == false) {
                $date_format = "Y-m-d H:i:s";
            }
        }
        $date = date($date_format, strtotime($date));
        return $date;
    }

    function add_slashes_recursive($variable)
    {
        if (is_string($variable))
            return addslashes($variable);
        elseif (is_array($variable))
            foreach ($variable as $i => $value)
                $variable[$i] = $this->add_slashes_recursive($value);
        return $variable;
    }

    function strip_slashes_recursive($variable)
    {
        if (is_string($variable))
            return stripslashes($variable);
        if (is_array($variable))
            foreach ($variable as $i => $value)
                $variable[$i] = $this->strip_slashes_recursive($value);
        return $variable;
    }

    public function auto_link($text)
    {
        return $this->autolink($text);
    }

    //http://stackoverflow.com/a/1971451/731166

    function autolink($text)
    {


        $pattern = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
        return preg_replace_callback($pattern, array($this, 'auto_link_text_callback'), $text);
    }

    function auto_link_text_callback($matches)
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

            for ($k = 0; $k < count($url_string_components); $k++) {
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

    public function ago($time, $granularity = 2)
    {
        $date = strtotime($time);
        $difference = time() - $date;
        $retval = '';
        $periods = array(
            'decade' => 315360000,
            'year' => 31536000,
            'month' => 2628000,
            'week' => 604800,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1
        );
        foreach ($periods as $key => $value) {
            if ($difference >= $value) {
                $time = floor($difference / $value);
                $difference %= $value;
                $retval .= ($retval ? ' ' : '') . $time . ' ';
                $retval .= (($time > 1) ? $key . 's' : $key);
                $granularity--;
            }
            if ($granularity == '0') {
                break;
            }
        }

        if ($retval == '') {
            return '1 second ago';
        }

        return '' . $retval . ' ago';
    }

    public function clean_xss($var, $do_not_strip_tags = false)
    {
        if (is_array($var)) {
            foreach ($var as $key => $val) {
                $output[$key] = $this->clean_xss($val, $do_not_strip_tags);
            }
        } else {

            $var = $this->strip_unsafe($var);
            $var = htmlentities($var, ENT_QUOTES, "UTF-8");
            $var = str_ireplace("<script>", '', $var);
            $var = str_ireplace("</script>", '', $var);

            $var = str_replace('<?', '&lt;?', $var);
            $var = str_replace('?>', '?&gt;', $var);
            $var = str_ireplace("<module", '&lt;module', $var);
            $var = str_ireplace("<Microweber", '&lt;Microweber', $var);

            $var = str_ireplace("javascript:", '', $var);
            $var = str_ireplace("vbscript:", '', $var);
            $var = str_ireplace("livescript:", '', $var);
            $var = str_ireplace("HTTP-EQUIV=", '', $var);
            $var = str_ireplace("\0075\0072\\", '', $var);

            if ($do_not_strip_tags == false) {
                $var = strip_tags(trim($var));
            }

            $output = $var;
            return $output;
        }
        return $output;

    }


    function clean_scripts($input)
    {
        if (is_array($input)) {
            $output = array();
            foreach ($input as $var => $val) {
                $output[$var] = $this->clean_scripts($val);
            }
        } elseif (is_string($input)) {
            $search = array(
                '@<script[^>]*?>.*?</script>@si', // Strip out javascript

                '@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments
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
            $var = $this->strip_unsafe($var);
            $var = htmlentities($var, ENT_QUOTES, "UTF-8");
            $var = str_ireplace("<script>", '', $var);
            $var = str_ireplace("</script>", '', $var);
            $var = str_replace('<?', '&lt;?', $var);
            $var = str_replace('?>', '?&gt;', $var);
            $var = str_ireplace("<module", '&lt;module', $var);
            $var = str_ireplace("<Microweber", '&lt;Microweber', $var);
            $var = str_ireplace("\0075\0072\\", '', $var);
            if ($do_not_strip_tags == false) {
                $var = strip_tags(trim($var));
            }
            $output = $var;
            return $output;
        }
        return $output;
    }

    function strip_unsafe($string, $img = false)
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
                '/<title(.*?)<\/title>/is',
                //'/<pre(.*?)<\/pre>/is',
                '/<audio(.*?)<\/audio>/is',
                '/<video(.*?)<\/video>/is',
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
                '/<head(.*?)>/is',
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
                '/<\/html>/is');

            // Remove graphic too if the user wants
            if ($img == true) {
                $unsafe[] = '/<img(.*?)>/is';
            }
            // Remove these tags and all parameters within them
            $string = preg_replace($unsafe, "", $string);
            return $string;
        }
    }

    public function string_between($string, $start, $end)
    {
        $string = " " . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return "";
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

    function prep_url($str = '')
    {
        if ($str === 'http://' OR $str === 'https://' OR $str === '') {
            return '';
        }
        $url = parse_url($str);
        if (!$url OR !isset($url['scheme'])) {
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
                return 'https://' . $str;
            } else {
                return 'http://' . $str;
            }
        }
        return $str;
    }

    public function percent($num_amount, $num_total)
    {
        if ($num_amount == 0 or $num_total == 0) {
            return 0;
        }
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;
        $count = number_format($count2, 0);
        return $count;
    }

    /**
     * Encodes a variable with json_encode and base64_encode
     *
     * @param mixed $var Your $var
     * @return string Your encoded $var
     * @package Utils
     * @category Strings
     * @see $this->base64_to_array()
     */
    function array_to_base64($var)
    {
        if ($var == '') {
            return '';
        }

        $var = json_encode($var);
        $var = base64_encode($var);
        return $var;
    }

    /**
     * Decodes a variable with base64_decode and json_decode
     *
     * @param string $var Your var that has been put trough encode_var
     * @return string|array Your encoded $var
     * @package Utils
     * @category Strings
     * @see $this->array_to_base64()
     */
    function base64_to_array($var)
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

    function titlelize($string)
    {

        $slug = preg_replace('/-/', ' ', $string);
        $slug = preg_replace('/_/', ' ', $slug);
        $slug = ucwords($slug);
        return $slug;
    }

    function array_values($ary)
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
            'Mauris purus urna, vulputate in malesuada ac, varius eget ante. Integer ultricies lacus vel magna dictum sit amet euismod enim dictum. Aliquam iaculis, ipsum at tempor bibendum, dolor tortor eleifend elit, sed fermentum magna nibh a ligula. Phasellus ipsum nisi, porta quis pellentesque sit amet, dignissim vel felis. Quisque condimentum molestie ligula, ac auctor turpis facilisis ac. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent molestie leo velit. Sed sit amet turpis massa. Donec in tortor quis metus cursus iaculis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hac habitasse platea dictumst. Proin leo nisl, faucibus non sollicitudin et, commodo id diam. Aliquam adipiscing, lorem a fringilla blandit, felis dui tristique ligula, vitae eleifend orci diam eget quam. Aliquam vulputate gravida leo eget eleifend. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;'
        );
        $rand = rand(0, (sizeof($lipsum) - 1));

        return $this->limit($lipsum[$rand], $number_of_characters, '');
    }

    /**
     *
     * Limits a string to a number of characters
     *
     * @param $str
     * @param int $n
     * @param string $end_char
     * @return string
     * @package Utils
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
        $out = "";
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

        return "#" . sprintf("%02X%02X%02X", mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    }

    public function lnotif($text, $class = 'success')
    {
        $editmode_sess = mw()->user_manager->session_get('editmode');

        if ($editmode_sess == false) {
            if (defined('IN_EDITOR_TOOLS') and IN_EDITOR_TOOLS != false) {
                $editmode_sess = true;
            }

        }


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

    function unvar_dump($str)
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
            ';'
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

    function is_base64($data)
    {
        $decoded = base64_decode($data, true);
        if (false === $decoded || base64_encode($decoded) != $data) {
            return false;
        }
        return true;
    }

    function is_fqdn($FQDN)
    {
        return (!empty($FQDN) && preg_match('/(?=^.{1,254}$)(^(?:(?!\d|-)[a-z0-9\-]{1,63}(?<!-)\.)+(?:[a-z]{2,})$)/i', $FQDN) > 0);
    }

}