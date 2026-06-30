<?php

namespace MicroweberPackages\Format\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string array_to_ul(array $arr, string $ul_tag = 'ul', string $li_tag = 'li')
 * @method static string array_to_table(array $array, bool $table = true)
 * @method static array  array_trim(array $variable)
 * @method static array  array_values(array $ary)
 * @method static string date(string $date, $date_format = false)
 * @method static array|false find_date(string $string)
 * @method static string get_date_format()
 * @method static array  get_supported_date_formats()
 * @method static string date_system_format(string $db_date)
 * @method static string get_date_db_format(string $str_date)
 * @method static array  split_dates(string $min, string $max, int $parts = 7, string $output = 'Y-m-d')
 * @method static array  available_date_formats()
 * @method static string|array add_slashes_recursive($variable)
 * @method static string|array strip_slashes_recursive($variable)
 * @method static string auto_link(string $text)
 * @method static string autolink(string $text)
 * @method static string human_filesize($bytes, int $dec = 2)
 * @method static string ago($time, bool $full = false)
 * @method static string string_between(string $string, string $start, string $end)
 * @method static string replace_once(string $needle, string $replace, string $haystack)
 * @method static string prep_url(string $str = '')
 * @method static mixed  percent($num_amount, $num_total, bool $format = true)
 * @method static float  amount_to_float(string $money)
 * @method static string limit(string $str, int $n = 500, string $end_char = '&#8230;')
 * @method static string lipsum(int $number_of_characters = 100)
 * @method static string random_color()
 * @method static string titlelize(string $string)
 * @method static string no_dashes(string $string)
 * @method static string array_to_base64($var)
 * @method static array|false base64_to_array($var)
 * @method static bool   is_base64(string $data)
 * @method static string encrypt(string $string)
 * @method static mixed  decrypt(string $string)
 * @method static string encode_ids($data)
 * @method static array  decode_ids(string $data)
 * @method static string|array clean_xss($var, bool $do_not_strip_tags = false)
 * @method static string|array clean_scripts($input)
 * @method static string|array|null clean_html($var, bool $do_not_strip_tags = false)
 * @method static string|array strip_unsafe($string, bool $img = false)
 * @method static bool   is_fqdn(string $fqdn)
 * @method static mixed  unvar_dump(string $str)
 * @method static array  hex_to_rgb(string $hex, $alpha = false)
 * @method static string arrayToHtmlAttributes(array $attributes = [])
 * @method static array  stringToTree(string $string, string $explodeSymbol = '>')
 * @method static string notif(string $text, $class = 'success')
 *
 * @see \MicroweberPackages\Format\Format
 */
class FormatFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'format';
    }
}