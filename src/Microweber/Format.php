<?php
namespace Microweber;
// Handles working with HTML output templates
class Format
{

    public $app;

    function __construct($app=null)
    {



        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw('application');
            }

        }


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
                    $retStr .= '<li>' . $key . ': ' . $this->array_to_ul($val) . '</li>';
                } else {
                    $retStr .= '<li>' . $key . ': ' . $val . '</li>';
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


            $date_format = $this->app->option->get('date_format', 'website');

            if ($date_format == false) {
                $date_format = "Y-m-d H:i:s";
            }
        }

        $date = date($date_format, strtotime($date));
        return $date;
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


    public function clean_html($var)
    {
        if (is_array($var)) {
            foreach ($var as $key => $val) {
                $output[$key] = $this->clean_html($val);
            }
        } else {
            $var = html_entity_decode($var);
            $var = strip_tags(trim($var));

            $output = stripslashes($var);
        }
        if (!empty($output))
            return $output;
    }


    public function replace_once($needle, $replace, $haystack)
    {
        // Looks for the first occurence of $needle in $haystack
        // and replaces it with $replace.
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            // Nothing found
            return $haystack;
        }
        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    public function auto_link($text)
    {
        $url_re = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@';
        $url_replacement = "<a href='$1' target='_blank'>$1</a>";

        return preg_replace($url_re, $url_replacement, $text);
    }

    public function  percent($num_amount, $num_total)
    {
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;
        $count = number_format($count2, 0);
        echo $count;
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


        //$var = unserialize($var);
        return $var;
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

    public function random_color()
    {

        return "#" . sprintf("%02X%02X%02X", mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    }


    public function notif($text, $class = 'success')
    {
        $to_print = '<div class="mw-notification mw-' . $class . ' "><div class="mw-notification-text mw-open-module-settings">';
        $to_print .= _e($text) . '</div></div>';

        return $to_print;
    }

    public function lnotif($text, $class = 'success')
    {
        $editmode_sess = mw('user')->session_get('editmode');

        if ($editmode_sess == true) {
            return $this->notif($text, $class);
        }
    }


    public function no_dashes($string)
    {

        $slug = preg_replace('/-/', ' ', $string);
        $slug = preg_replace('/_/', ' ', $slug);

        return $slug;
    }


}
