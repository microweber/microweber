<?php

namespace MicroweberPackages\Format\Concerns;

/**
 * Extracted from FormatService to break up the god class. Composed back into
 * FormatService, so inter-method calls and shared state still work.
 */
trait FormatsStrings
{
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
}
