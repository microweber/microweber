<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Support;

use MicroweberPackages\Format\Facades\Format;


/**
 * Encode an associative array as HTML attributes.
 *
 * Standalone-safe replacement for Format::arrayToHtmlAttributes().
 */
class HtmlAttributes
{
    /**
     * @param  array<int|string, mixed>  $attributes
     */
    public static function toString(array $attributes = []): string
    {
        if ($attributes === []) {
            return '';
        }

        $pairs = [];

        foreach ($attributes as $key => $val) {
            if (is_int($key)) {
                $pairs[] = self::stringify($val);
                continue;
            }

            if (is_bool($val)) {
                if ($val) {
                    $pairs[] = $key;
                }
                continue;
            }

            if ($val === null) {
                continue;
            }

            if (is_array($val) || is_object($val)) {
                $encoded = json_encode($val, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $val = $encoded === false ? '' : $encoded;
            }

            $escaped = htmlspecialchars(self::stringify($val), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $pairs[] = $key . '="' . $escaped . '"';
        }

        return implode(' ', $pairs);
    }

    private static function stringify(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if ($value === null) {
            return '';
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            return (string) $value;
        }

        $encoded = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $encoded === false ? '' : $encoded;
    }
}
