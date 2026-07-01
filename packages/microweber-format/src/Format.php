<?php

namespace MicroweberPackages\Format;

use Illuminate\Support\Facades\Crypt;
use MicroweberPackages\Security\HtmlClean;
use MicroweberPackages\Security\XSSClean;

/**
 * Format – string, array, date and encoding utilities.
 *
 * Designed as a standalone Laravel package accessible via app()->format or
 * the Format facade.  All methods are intentionally instance methods so the
 * class can be resolved from the container as a singleton.
 */
class Format
{
    // ─── Array helpers ───────────────────────────────────────────────

    /**
     * Prints an array as an unordered HTML list (<ul>).
     */
    public function array_to_ul(array $arr, string $ul_tag = 'ul', string $li_tag = 'li'): string
    {
        $has_items = false;
        $retStr = '<' . $ul_tag . '>';

        foreach ($arr as $key => $val) {
            if (!is_array($key) && $val) {
                $key = str_replace('_', ' ', (string) $key);
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
                    if (is_string($val) && trim($val) !== '') {
                        $has_items = true;
                        if (is_numeric($key)) {
                            $retStr .= '<' . $li_tag . '><span></span> ' . $val . '</' . $li_tag . '>';
                        } else {
                            $retStr .= '<' . $li_tag . '><span>' . $key . ':</span> ' . $val . '</' . $li_tag . '>';
                        }
                    }
                }
            } else {
                if (!empty($val)) {
                    $has_items = true;
                    $retStr .= $this->array_to_ul((array) $val, $ul_tag, $li_tag);
                }
            }
        }

        $retStr .= '</' . $ul_tag . '>';

        return $has_items ? $retStr : '';
    }

    /**
     * Convert a 2-D array to an HTML table.
     */
    public function array_to_table(array $array, bool $table = true): string
    {
        $out = '';
        $tableHeader = '';

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if ($tableHeader === '') {
                    $tableHeader = '<th>' . implode('</th><th>', array_keys($value)) . '</th>';
                }
                $out .= '<tr>' . $this->array_to_table($value, false) . '</tr>';
            } else {
                $out .= "<td>{$value}</td>";
            }
        }

        return $table ? '<table>' . $tableHeader . $out . '</table>' : $out;
    }

    /**
     * Trim all values in an array.
     */
    public function array_trim(array $variable): array
    {
        return array_map('trim', $variable);
    }

    /**
     * Recursively flatten array values (scalar only).
     */
    public function array_values(array $ary): array
    {
        $lst = [];
        foreach ($ary as $v) {
            if (is_scalar($v)) {
                $lst[] = $v;
            } elseif (is_array($v)) {
                $lst = array_merge($lst, $this->array_values($v));
            }
        }
        return $lst;
    }

    // ─── Date helpers ────────────────────────────────────────────────

    /**
     * Format a date string using a given (or configured) format.
     */
    public function date(string $date, $date_format = false): string
    {
        if ($date_format === false) {
            if (function_exists('app') && isset(app()->option_manager)) {
                $date_format = app()->option_manager->get('date_format', 'website');
            }
            if (!$date_format) {
                $date_format = 'Y-m-d H:i:s';
            }
        }

        return date($date_format, strtotime($date));
    }

    /**
     * Find a date inside a natural-language string.
     *
     * @return array{year: string, month: string, day: string}|false
     */
    public function find_date(string $string)
    {
        $shortenize = function (string $s): string {
            return substr($s, 0, 3);
        };

        $month_names = [
            'january', 'february', 'march', 'april', 'may', 'june',
            'july', 'august', 'september', 'october', 'november', 'december',
        ];
        $short_month_names = array_map($shortenize, $month_names);

        $day_names = [
            'monday', 'tuesday', 'wednesday', 'thursday',
            'friday', 'saturday', 'sunday',
        ];
        $short_day_names = array_map($shortenize, $day_names);

        $ordinal_number = ['st', 'nd', 'rd', 'th'];

        $day = '';
        $month = '';
        $year = '';

        // Match dates: 01/01/2012 or 30-12-11 or 1 2 1985
        preg_match('/([0-9]?[0-9])[\.\-\/ ]+([0-1]?[0-9])[\.\-\/ ]+([0-9]{2,4})/', $string, $matches);
        if ($matches) {
            if (!empty($matches[1])) $day = $matches[1];
            if (!empty($matches[2])) $month = $matches[2];
            if (!empty($matches[3])) $year = $matches[3];
        }

        // Match dates: Sunday 1st March 2015
        preg_match(
            '/(?:(?:' . implode('|', $day_names) . '|' . implode('|', $short_day_names) . ')[ ,\-_\/]*)?([0-9]?[0-9])[ ,\-_\/]*(?:' . implode('|', $ordinal_number) . ')?[ ,\-_\/]*(' . implode('|', $month_names) . '|' . implode('|', $short_month_names) . ')[ ,\-_\/]+([0-9]{4})/i',
            $string,
            $matches
        );
        if ($matches) {
            if (empty($day) && !empty($matches[1])) $day = $matches[1];
            if (empty($month) && !empty($matches[2])) {
                $month = array_search(strtolower($matches[2]), $short_month_names);
                if ($month === false) $month = array_search(strtolower($matches[2]), $month_names);
                $month = $month + 1;
            }
            if (empty($year) && !empty($matches[3])) $year = $matches[3];
        }

        // Match dates: March 1st 2015
        preg_match(
            '/(' . implode('|', $month_names) . '|' . implode('|', $short_month_names) . ')[ ,\-_\/]*([0-9]?[0-9])[ ,\-_\/]*(?:' . implode('|', $ordinal_number) . ')?[ ,\-_\/]+([0-9]{4})/i',
            $string,
            $matches
        );
        if ($matches) {
            if (empty($month) && !empty($matches[1])) {
                $month = array_search(strtolower($matches[1]), $short_month_names);
                if ($month === false) $month = array_search(strtolower($matches[1]), $month_names);
                $month = $month + 1;
            }
            if (empty($day) && !empty($matches[2])) $day = $matches[2];
            if (empty($year) && !empty($matches[3])) $year = $matches[3];
        }

        // Match month name
        if (empty($month)) {
            preg_match('/(' . implode('|', $month_names) . ')/i', $string, $matches_month_word);
            if ($matches_month_word && !empty($matches_month_word[1])) {
                $month = array_search(strtolower($matches_month_word[1]), $month_names);
            }
            if (empty($month) || $month === false) {
                preg_match('/(' . implode('|', $short_month_names) . ')/i', $string, $matches_month_word);
                if ($matches_month_word && !empty($matches_month_word[1])) {
                    $month = array_search(strtolower($matches_month_word[1]), $short_month_names);
                }
            }
            if ($month !== false && $month !== '') {
                $month = $month + 1;
            }
        }

        // Match ordinal day
        if (empty($day)) {
            preg_match('/([0-9]?[0-9])(' . implode('|', $ordinal_number) . ')/', $string, $matches_day);
            if ($matches_day && !empty($matches_day[1])) $day = $matches_day[1];
        }

        // Match 4-digit year
        if (empty($year)) {
            preg_match('/[0-9]{4}/', $string, $matches_year);
            if ($matches_year && !empty($matches_year[0])) $year = $matches_year[0];
        }

        // Fallback: 2-digit year
        if (!empty($day) && !empty($month) && empty($year)) {
            preg_match('/[0-9]{2}/', $string, $matches_year);
            if ($matches_year && !empty($matches_year[0])) $year = $matches_year[0];
        }

        // Leading zeros
        if (strlen((string) $day) === 1) $day = '0' . $day;
        if (strlen((string) $month) === 1) $month = '0' . $month;

        // Expand 2-digit year
        if (strlen((string) $year) === 2) {
            $year = ((int) $year > 20) ? '19' . $year : '20' . $year;
        }

        if (empty($year) && empty($month) && empty($day)) {
            return false;
        }

        return [
            'year'  => (string) $year,
            'month' => (string) $month,
            'day'   => (string) $day,
        ];
    }

    /**
     * Return the configured date display format.
     */
    public function get_date_format(): string
    {
        $date_format_set = function_exists('get_option') ? get_option('date_format', 'website') : null;
        $date_format_default = 'm/d/Y h:i a';

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

    /**
     * Return supported date format strings.
     *
     * @return string[]
     */
    public function get_supported_date_formats(): array
    {
        return [
            'Y-m-d H:i:s', 'Y-m-d H:i', 'd-m-Y H:i:s', 'd-m-Y H:i',
            'm/d/y', 'm/d/Y', 'd/m/Y', 'F j, Y g:i a', 'F j, Y', 'F, Y',
            'l, F jS, Y', 'M j, Y @ G:i', 'Y/m/d \a\t g:i A',
            'Y/m/d \a\t g:ia', 'Y/m/d g:i:s A', 'Y/m/d', 'g:i a',
            'g:i:s a', 'D-M-Y', 'D-M-Y H:i',
        ];
    }

    /**
     * Format a DB date using the system display format.
     */
    public function date_system_format(string $db_date): string
    {
        $date_format = $this->get_date_format();
        $date = @date_create($db_date);
        if (!$date) {
            return $db_date;
        }
        return date_format($date, $date_format);
    }

    /**
     * Convert a display-format date string into DB format (Y-m-d H:i:s).
     */
    public function get_date_db_format(string $str_date): string
    {
        $date_format_set = function_exists('get_option') ? get_option('date_format', 'website') : null;
        $date_db_format = 'Y-m-d H:i:s';
        $date_format_default = 'm/d/Y h:i a';

        if (strstr($str_date, '/') || strstr($str_date, '-') || strstr($str_date, '.')) {
            $str_date = str_replace('-', '/', $str_date);
            $str_date = str_replace('.', '/', $str_date);
        }

        if ($date_format_set) {
            $date = $this->find_date($str_date);
            if ($date) {
                return $date['year'] . '-' . $date['month'] . '-' . $date['day'];
            }
        }

        $dateTime = \DateTime::createFromFormat($date_format_default, $str_date);
        if ($dateTime) {
            return $dateTime->format($date_db_format);
        }

        return '0000-00-00 00:00:00';
    }

    /**
     * Split a date range into $parts evenly-spaced dates.
     *
     * @return string[]
     */
    public function split_dates(string $min, string $max, int $parts = 7, string $output = 'Y-m-d'): array
    {
        $dataCollection = [date($output, strtotime($min))];
        $diff = (strtotime($max) - strtotime($min)) / $parts;
        $convert = strtotime($min) + $diff;

        for ($i = 1; $i < $parts; $i++) {
            $dataCollection[] = date($output, (int) $convert);
            $convert += $diff;
        }

        $dataCollection[] = date($output, strtotime($max));

        return $dataCollection;
    }

    /**
     * Available date formats with PHP & JS equivalents.
     *
     * @return array<int, array{php: string, js: string}>
     */
    public function available_date_formats(): array
    {
        return [
            ['php' => 'Y-m-d',       'js' => 'yyyy-m-d'],
            ['php' => 'd-m-Y',       'js' => 'd-m-yyyy'],
            ['php' => 'm/d/y',       'js' => 'm/d/yyyy'],
            ['php' => 'd/m/Y',       'js' => 'd/m/yyyy'],
            ['php' => 'F j, Y',      'js' => 'F j, yyyy'],
            ['php' => 'F, Y',        'js' => 'F, yyyy'],
            ['php' => 'l, F jS, Y',  'js' => 'l, F jS, yyyy'],
            ['php' => 'M j, Y',      'js' => 'M j, yyyy'],
            ['php' => 'Y/m/d',       'js' => 'yyyy/m/d'],
            ['php' => 'D-M-Y',       'js' => 'dd-M-yyyy'],
        ];
    }

    // ─── String helpers ──────────────────────────────────────────────

    /**
     * Recursively add slashes.
     *
     * @param string|array $variable
     * @return string|array
     */
    public function add_slashes_recursive($variable)
    {
        if (is_string($variable)) {
            return addslashes($variable);
        }
        if (is_array($variable)) {
            foreach ($variable as $i => $value) {
                $variable[$i] = $this->add_slashes_recursive($value);
            }
        }
        return $variable;
    }

    /**
     * Recursively strip slashes.
     *
     * @param string|array $variable
     * @return string|array
     */
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

    /**
     * Auto-link URLs in text (alias).
     */
    public function auto_link(string $text): string
    {
        return $this->autolink($text);
    }

    /**
     * Auto-link URLs in text.
     */
    public function autolink(?string $text): string
    {
        // Null-tolerant (back-compat): callers such as Comment::getCommentBodyAttribute()
        // pass a possibly-null DB column; the pre-extraction method accepted null.
        if ($text === null || $text === '') {
            return (string) $text;
        }

        $pattern = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';

        return preg_replace_callback($pattern, [$this, 'auto_link_text_callback'], $text);
    }

    /**
     * Callback for autolink.
     */
    public function auto_link_text_callback(array $matches): string
    {
        $max_url_length = 150;
        $max_depth_if_over_length = 2;
        $ellipsis = '&hellip;';

        $url_full = $matches[0];
        $url_short = '';

        if (strlen($url_full) > $max_url_length) {
            $parts = parse_url($url_full);

            $url_short = ($parts['scheme'] ?? 'http') . '://' . preg_replace('/^www\./', '', $parts['host'] ?? '') . '/';

            $url_string_components = [];
            if (!empty($parts['path'])) {
                $path_components = explode('/', trim($parts['path'], '/'));
                foreach ($path_components as $dir) {
                    $url_string_components[] = $dir . '/';
                }
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
                    if ($k === 0 && strlen($url_short) < $max_url_length) {
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

        return "<a rel=\"nofollow\" href=\"{$url_full}\" target='_blank'>{$url_short}</a>";
    }

    /**
     * Human-readable file size.
     */
    public function human_filesize($bytes, int $dec = 2): string
    {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen((string) $bytes) - 1) / 3);

        return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . ($size[$factor] ?? '');
    }

    /**
     * Human-readable "time ago" string.
     */
    public function ago($time, bool $full = false): string
    {
        $now = new \DateTime();

        if (is_int($time)) {
            $ago = new \DateTime("@{$time}");
        } else {
            $ago = new \DateTime($time);
        }

        $diff = $now->diff($ago);

        $weeks = (int) floor($diff->d / 7);
        $days = $diff->d - ($weeks * 7);

        $string = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        ];

        $values = [
            'y' => $diff->y,
            'm' => $diff->m,
            'w' => $weeks,
            'd' => $days,
            'h' => $diff->h,
            'i' => $diff->i,
            's' => $diff->s,
        ];

        foreach ($string as $k => &$v) {
            if ($values[$k]) {
                $v = $values[$k] . ' ' . $v . ($values[$k] > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    /**
     * Return text between two markers.
     */
    public function string_between(string $string, string $start, string $end): string
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini === 0 || $ini === false) {
            return '';
        }
        $ini += strlen($start);
        $endPos = strpos($string, $end, $ini);
        if ($endPos === false) {
            return '';
        }
        $len = $endPos - $ini;

        return substr($string, $ini, $len);
    }

    /**
     * Replace only the first occurrence of $needle in $haystack.
     */
    public function replace_once(string $needle, string $replace, string $haystack): string
    {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return $haystack;
        }

        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    /**
     * Prepare a URL – ensure it has a scheme.
     */
    public function prep_url(string $str = ''): string
    {
        if ($str === 'http://' || $str === 'https://' || $str === '') {
            return '';
        }

        $url = parse_url($str);
        if (!$url || !isset($url['scheme'])) {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            return $scheme . '://' . $str;
        }

        return $str;
    }

    /**
     * Calculate percentage of $num_amount in $num_total.
     */
    public function percent($num_amount, $num_total, bool $format = true)
    {
        if ($num_amount == 0 || $num_total == 0) {
            return 0;
        }

        $count2 = ($num_amount / $num_total) * 100;

        return $format ? number_format($count2, 0) : $count2;
    }

    /**
     * Convert a money string to a float.
     */
    public function amount_to_float(string $money): float
    {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);
        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;
        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, max(0, $separatorsCountToBeErased));
        $removedThousandSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '', $stringWithCommaOrDot);

        return (float) str_replace(',', '.', $removedThousandSeparator);
    }

    /**
     * Limit a string to $n characters (word-boundary aware).
     */
    public function limit(string $str, int $n = 500, string $end_char = '&#8230;'): string
    {
        if (strlen($str) < $n) {
            return $str;
        }

        $str = strip_tags($str);
        $str = preg_replace("/\s+/", ' ', str_replace(["\r\n", "\r", "\n"], ' ', $str));

        if (strlen($str) <= $n) {
            return $str;
        }

        $out = '';
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';
            if (strlen($out) >= $n) {
                $out = trim($out);
                return (strlen($out) === strlen($str)) ? $out : $out . $end_char;
            }
        }

        return trim($out);
    }

    /**
     * Generate lorem ipsum text.
     */
    public function lipsum(int $number_of_characters = 100): string
    {
        $lipsum = [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis justo et sapien varius gravida. Fusce porttitor consectetur risus ut tincidunt. Maecenas pellentesque nulla sodales enim consectetur commodo. Aliquam non dui leo, adipiscing posuere metus. Duis adipiscing auctor lorem ut pulvinar.',
            'Etiam sed lorem augue. Vivamus varius tristique bibendum. Phasellus vitae tempor augue. Maecenas consequat commodo euismod. Aenean a lorem nec leo dignissim ultricies sed quis nisi. Fusce pellentesque tellus lectus, eu varius felis. Mauris lacinia facilisis metus, sed sollicitudin quam faucibus id.',
            'Donec ultrices cursus erat, non pulvinar lectus consectetur eu. Proin sodales risus a ante aliquet vel cursus justo viverra. Duis vel leo felis. Praesent hendrerit, sem vitae scelerisque blandit, enim neque pulvinar mi, vel lobortis elit dui vel dui.',
            'Integer eget tortor nulla, non dapibus erat. Sed ultrices consectetur quam at scelerisque. Nullam varius hendrerit nisl, ac cursus mi bibendum eu. Phasellus varius fermentum massa, sit amet ornare quam malesuada in.',
            'In hac habitasse platea dictumst. Aenean vehicula auctor eros non tincidunt. Donec tempor arcu ac diam sagittis mattis. Aenean eget augue nulla, non volutpat lorem. Praesent ut cursus magna.',
        ];

        $rand = mt_rand(0, count($lipsum) - 1);

        return $this->limit($lipsum[$rand], $number_of_characters, '');
    }

    /**
     * Generate a random hex color.
     */
    public function random_color(): string
    {
        return '#' . sprintf('%02X%02X%02X', mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
    }

    /**
     * Convert dashes & underscores to title case.
     */
    public function titlelize(string $string): string
    {
        $slug = preg_replace('/-/', ' ', $string);
        $slug = preg_replace('/_/', ' ', $slug);

        return ucwords($slug);
    }

    /**
     * Remove dashes and underscores (replace with spaces).
     */
    public function no_dashes(string $string): string
    {
        $slug = preg_replace('/-/', ' ', $string);
        return preg_replace('/_/', ' ', $slug);
    }

    // ─── Encoding helpers ────────────────────────────────────────────

    /**
     * Encode an array/value to base64-encoded JSON.
     */
    public function array_to_base64($var): string
    {
        if ($var === '' || $var === null) {
            return '';
        }

        return base64_encode(json_encode($var));
    }

    /**
     * Decode a base64-encoded JSON string back to an array.
     *
     * @return array|false
     */
    public function base64_to_array($var)
    {
        if (is_array($var)) {
            return $var;
        }
        if ($var === '' || $var === null) {
            return false;
        }

        $var = base64_decode($var, true);
        if ($var === false) {
            return false;
        }

        try {
            $decoded = @json_decode($var, true);
            return is_array($decoded) ? $decoded : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check whether a string is valid base64.
     */
    public function is_base64(string $data): bool
    {
        $decoded = base64_decode($data, true);
        if ($decoded === false || base64_encode($decoded) !== $data) {
            return false;
        }
        return true;
    }

    /**
     * Encrypt a string using Laravel's Crypt.
     */
    public function encrypt(string $string): string
    {
        return Crypt::encrypt($string);
    }

    /**
     * Decrypt a string using Laravel's Crypt.
     *
     * @return mixed
     */
    public function decrypt(string $string)
    {
        return Crypt::decrypt($string);
    }

    /**
     * Encode IDs using Hashids.
     */
    public function encode_ids($data): string
    {
        $hashids = new \Hashids\Hashids();
        return $hashids->encode($data);
    }

    /**
     * Decode Hashids back to IDs.
     */
    public function decode_ids(string $data): array
    {
        $hashids = new \Hashids\Hashids();
        return $hashids->decode($data);
    }

    // ─── Security / sanitisation (delegates to microweber-security) ─

    /**
     * Clean a value of XSS content.
     *
     * @param string|array $var
     * @return string|array
     */
    public function clean_xss($var, bool $do_not_strip_tags = false)
    {
        $xss = new XSSClean();

        if (is_array($var)) {
            $output = [];
            foreach ($var as $key => $val) {
                $output[$key] = $this->clean_xss($val, $do_not_strip_tags);
            }
            return $output;
        }

        $var = $xss->clean($var);

        $var = str_ireplace('<script>', '', $var);
        $var = str_ireplace('</script>', '', $var);
        $var = str_replace('<?', '&lt;?', $var);
        $var = str_replace('?>', '?&gt;', $var);
        $var = str_ireplace('javascript:', '', $var);
        $var = str_ireplace('vbscript:', '', $var);
        $var = str_ireplace('livescript:', '', $var);
        $var = str_ireplace('HTTP-EQUIV=', '', $var);
        $var = str_ireplace("\0075\0072\\", '', $var);

        if (!$do_not_strip_tags) {
            $var = strip_tags(trim($var));
        }

        return $var;
    }

    /**
     * Strip script tags from a string or array.
     *
     * @param string|array $input
     * @return string|array
     */
    public function clean_scripts($input)
    {
        if (is_array($input)) {
            $output = [];
            foreach ($input as $var => $val) {
                $output[$var] = $this->clean_scripts($val);
            }
            return $output;
        }

        if (is_string($input)) {
            return preg_replace(
                [
                    '@<script[^>]*?>.*?</script>@si',
                    '@<![\s\S]*?--[ \t\n\r]*>@',
                ],
                '',
                $input
            );
        }

        return $input;
    }

    /**
     * Clean HTML via HTMLPurifier.
     *
     * @param string|array $var
     * @return string|array|null
     */
    public function clean_html($var, bool $do_not_strip_tags = false)
    {
        if (is_array($var)) {
            $output = [];
            foreach ($var as $key => $val) {
                $output[$key] = $this->clean_html($val, $do_not_strip_tags);
            }
            return $output;
        }

        $path = null;
        if (function_exists('storage_path')) {
            $path = storage_path() . '/html_purifier';
        } elseif (function_exists('mw_cache_path')) {
            $path = mw_cache_path() . '/html_purifier';
        }
        if (!$path) {
            $path = sys_get_temp_dir() . '/html_purifier';
        }

        $var = $this->strip_unsafe($var);
        $config = \HTMLPurifier_Config::createDefault();

        if ($path) {
            $config->set('Cache.SerializerPath', $path);
            if (!is_dir($path)) {
                @mkdir($path, 0755, true);
            }
        }

        $purifier = new \HTMLPurifier($config);
        $var = $purifier->purify($var);

        $var = str_ireplace('<script>', '', $var);
        $var = str_ireplace('</script>', '', $var);
        $var = str_replace('<?', '&lt;?', $var);
        $var = str_replace('?>', '?&gt;', $var);
        $var = str_ireplace("\0075\0072\\", '', $var);

        if (!$do_not_strip_tags) {
            $var = strip_tags(trim($var));
        }

        return $var;
    }

    /**
     * Strip unsafe HTML tags (iframes, scripts, event handlers, etc.).
     *
     * @param string|array $string
     * @return string|array
     */
    public function strip_unsafe($string, bool $img = false)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = $this->strip_unsafe($val, $img);
            }
            return $string;
        }

        $unsafe = [
            '/<iframe(.*?)<\/iframe>/is',
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
            '/<\/html>/is',
        ];

        if ($img) {
            $unsafe[] = '/<img(.*?)>/is';
        }

        return preg_replace($unsafe, '', (string) $string);
    }

    // ─── Validation / checks ─────────────────────────────────────────

    /**
     * Check if a string is a fully-qualified domain name.
     */
    public function is_fqdn(string $fqdn): bool
    {
        return !empty($fqdn)
            && (bool) preg_match(
                '/^(?=.{1,254}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/i',
                $fqdn
            );
    }

    // ─── Serialisation / var_dump reverse ────────────────────────────

    /**
     * Reverse a var_dump() output back to a PHP value.
     *
     * Fixed: replaces deprecated create_function() with anonymous functions.
     *
     * @return mixed
     */
    public function unvar_dump(string $str)
    {
        if (strpos($str, "\n") === false) {
            $regex = [
                '#(\\[.*?\\]=>)#',
                '#(string\\(|int\\(|float\\(|array\\(|NULL|object\\(|})#',
            ];
            $str = preg_replace($regex, "\n\\1", $str);
            $str = trim($str);
        }

        $regex = [
            '#^\\040*NULL\\040*$#m',
            '#^\\s*array\\((.*?)\\)\\s*{\\s*$#m',
            '#^\\s*string\\((.*?)\\)\\s*(.*?)$#m',
            '#^\\s*int\\((.*?)\\)\\s*$#m',
            '#^\\s*float\\((.*?)\\)\\s*$#m',
            '#^\\s*\[(\\d+)\\]\\s*=>\\s*$#m',
            '#\\s*?\\r?\\n\\s*#m',
        ];
        $replace = [
            'N',
            'a:\\1:{',
            's:\\1:\\2',
            'i:\\1',
            'd:\\1',
            'i:\\1',
            ';',
        ];

        $serialized = preg_replace($regex, $replace, $str);

        // Replace create_function with anonymous functions (PHP 8 compatible)
        $serialized = preg_replace_callback(
            '#\\s*\\["(.*?)"\\]\\s*=>#',
            function ($match) {
                return 's:' . strlen($match[1]) . ':"' . $match[1] . '"';
            },
            $serialized
        );

        $serialized = preg_replace_callback(
            '#object\\((.*?)\\).*?\\((\\d+)\\)\\s*{\\s*;#',
            function ($match) {
                return 'O:' . strlen($match[1]) . ':"' . $match[1] . '":' . $match[2] . ':{';
            },
            $serialized
        );

        $serialized = preg_replace(
            ['#};#', '#{;#'],
            ['}', '{'],
            $serialized
        );

        return @unserialize($serialized, ['allowed_classes' => false]);
    }

    // ─── Color helpers ───────────────────────────────────────────────

    /**
     * Convert a hex color to RGB array.
     *
     * @return array{r: int|string, g: int|string, b: int|string, a?: mixed}
     */
    public function hex_to_rgb(string $hex, $alpha = false): array
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) === 6) {
            $rgb = [
                'r' => hexdec(substr($hex, 0, 2)),
                'g' => hexdec(substr($hex, 2, 2)),
                'b' => hexdec(substr($hex, 4, 2)),
            ];
        } elseif (strlen($hex) === 3) {
            $rgb = [
                'r' => hexdec(str_repeat(substr($hex, 0, 1), 2)),
                'g' => hexdec(str_repeat(substr($hex, 1, 1), 2)),
                'b' => hexdec(str_repeat(substr($hex, 2, 1), 2)),
            ];
        } else {
            $rgb = ['r' => 0, 'g' => 0, 'b' => 0];
        }

        if ($alpha !== false) {
            $rgb['a'] = $alpha;
        }

        return $rgb;
    }

    // ─── HTML attribute helpers ──────────────────────────────────────

    /**
     * Convert an array to HTML attributes string.
     */
    public function arrayToHtmlAttributes(array $attributes = []): string
    {
        if (empty($attributes)) {
            return '';
        }

        $attributePairs = [];
        foreach ($attributes as $key => $val) {
            if (is_int($key)) {
                $attributePairs[] = $val;
            } else {
                $val = htmlspecialchars($val, ENT_QUOTES);
                $attributePairs[] = "{$key}=\"{$val}\"";
            }
        }

        return implode(' ', $attributePairs);
    }

    // ─── Tree builder ────────────────────────────────────────────────

    /**
     * Convert a "Courses > PHP > Array" style path to a nested array.
     */
    public function stringToTree(string $string, string $explodeSymbol = '>'): array
    {
        $result = [];
        $itemParts = explode($explodeSymbol, $string);
        $last = &$result;

        for ($i = 0; $i < count($itemParts); $i++) {
            $part = trim($itemParts[$i]);
            if ($i + 1 < count($itemParts)) {
                $last = &$last[$part];
            } else {
                $last[$part] = [];
            }
        }

        return $result;
    }

    // ─── Notification helpers (kept for backward compat) ─────────────

    /**
     * Live-edit notification.
     */
    public function lnotif(string $text, string $class = 'success')
    {
        if (!function_exists('app') || !isset(app()->user_manager)) {
            return false;
        }

        $editmode_sess = app()->user_manager->session_get('editmode');

        if (defined('MW_BACKEND') && MW_BACKEND) {
            return false;
        }
        if (defined('NO_EDITMODDE') && NO_EDITMODDE) {
            return false;
        }
        if (defined('IN_EDIT') && IN_EDIT) {
            $editmode_sess = true;
        }
        if (defined('IN_EDITOR_TOOLS') && IN_EDITOR_TOOLS) {
            $editmode_sess = true;
        }

        if ($editmode_sess) {
            return $this->notif($text, $class);
        }

        return false;
    }

    /**
     * Notification HTML.
     */
    public function notif(string $text, $class = 'success'): string
    {
        if ($class === true) {
            return '<div><div class="mw-notification-text mw-open-module-settings">' . $text . '</div></div>';
        }

        return '<div class="mw-notification mw-' . $class . ' "><div class="mw-notification-text mw-open-module-settings">' . $text . '</div></div>';
    }

    /**
     * Render custom-fields data for an item (Microweber CMS).
     *
     * Resolves the CustomField Eloquent model defensively (class_exists guard),
     * so the package stays installable/usable standalone — when the CMS model
     * is absent the item is returned unchanged.
     */
    public function render_item_custom_fields_data(array $item): array
    {
        if (isset($item['custom_fields_data']) && $item['custom_fields_data'] !== '') {
            // custom_fields_data is stored as JSON (Cart model 'array' cast). It may
            // already be an array (read via the cast) or a JSON string (read raw);
            // fall back to the legacy base64 decoding for rows persisted by the old
            // design so historical carts keep rendering.
            $cfd = $item['custom_fields_data'];
            if (is_string($cfd)) {
                $decoded = json_decode($cfd, true);
                $cfd = is_array($decoded) ? $decoded : $this->base64_to_array($cfd);
            }
            $item['custom_fields_data'] = $cfd;

            if (isset($item['custom_fields_data']) && is_array($item['custom_fields_data']) && !empty($item['custom_fields_data'])) {
                $itemCustomFields = $item['custom_fields_data'];

                if (class_exists(\Modules\CustomFields\Models\CustomField::class) && isset($item['rel_id'])) {
                    $getCustomFields = \Modules\CustomFields\Models\CustomField::where('rel_id', $item['rel_id'])->get();
                    if ($getCustomFields !== null) {
                        foreach ($getCustomFields as $customField) {
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
                }

                $tmp_val = $this->array_to_ul($itemCustomFields);
                $item['custom_fields'] = $tmp_val;
            }
        }

        return $item;
    }

    /**
     * Generate a text-to-image data URI (Microweber CMS).
     *
     * Resolves the SimpleTextImage lib defensively (class_exists guard) so the
     * package stays standalone-safe — returns an empty string when the lib is
     * not present.
     */
    public function text_to_image($text): string
    {
        $options = [];
        if (is_array($text)) {
            $options = $text;
            $text = $options['text'] ?? 'Hello world!';
        }

        if (!class_exists(\MicroweberPackages\Helper\lib\SimpleTextImage::class)) {
            return '';
        }

        $simple_text_image = new \MicroweberPackages\Helper\lib\SimpleTextImage($text);
        if (isset($options['font_size'])) {
            $simple_text_image->setFontSize(intval($options['font_size']));
        }
        if (isset($options['padding'])) {
            $simple_text_image->setPadding(intval($options['padding']));
        }
        if (isset($options['bg_color'])) {
            $rgb = $this->hex_to_rgb($options['bg_color']);
            $simple_text_image->setBackground($rgb['r'], $rgb['g'], $rgb['b']);
        }
        if (isset($options['fg_color'])) {
            $rgb = $this->hex_to_rgb($options['fg_color']);
            $simple_text_image->setForeground($rgb['r'], $rgb['g'], $rgb['b']);
        }

        ob_start();
        $simple_text_image->render('png');
        $imagedata = ob_get_contents();
        ob_end_clean();

        return 'data:image/png;base64,' . base64_encode($imagedata);
    }
}