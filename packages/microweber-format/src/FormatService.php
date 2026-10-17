<?php

namespace MicroweberPackages\Format;




use MicroweberPackages\Format\Concerns\EncodesData;
use MicroweberPackages\Format\Concerns\FormatsDates;
use MicroweberPackages\Format\Concerns\SanitizesHtml;
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
class FormatService
{
    use EncodesData;

    use FormatsDates;

    use SanitizesHtml;

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

    // ─── Security / sanitisation (delegates to microweber-security) ─

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