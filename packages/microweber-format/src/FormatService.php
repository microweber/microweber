<?php

namespace MicroweberPackages\Format;






use MicroweberPackages\Format\Concerns\FormatsStrings;
use MicroweberPackages\Format\Concerns\FormatsArrays;
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
    use FormatsStrings;

    use FormatsArrays;

    use EncodesData;

    use FormatsDates;

    use SanitizesHtml;

    // ─── Array helpers ───────────────────────────────────────────────

    // ─── Date helpers ────────────────────────────────────────────────

    // ─── String helpers ──────────────────────────────────────────────

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