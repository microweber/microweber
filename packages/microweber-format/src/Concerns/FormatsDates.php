<?php

namespace MicroweberPackages\Format\Concerns;

/**
 * Extracted from FormatService to break up the god class. Composed back into
 * FormatService, so inter-method calls and shared state still work.
 */
trait FormatsDates
{
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
}
