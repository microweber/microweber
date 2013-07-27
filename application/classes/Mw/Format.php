<?php
namespace Mw;
// Handles working with HTML output templates
class Format
{

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
                    $retStr .= '<li>' . $key . ': ' . mw('format')->array_to_ul($val) . '</li>';
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


            $date_format = mw('option')->get('date_format', 'website');

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
                $output[$key] = mw('format')->clean_html($val);
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
     * @see mw('format')->base64_to_array()
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
     * @see mw('format')->array_to_base64()
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






}
