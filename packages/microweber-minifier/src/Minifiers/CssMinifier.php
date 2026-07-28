<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Minifiers;

/**
 * CSS minifier (comment stripping, whitespace collapse, zero-value shortening).
 *
 * Ported from the Microweber AssetOptimizationService / TemplateStackRenderer
 * implementation (originally based on CSS-JS-Booster by Christian Schaefer).
 */
class CssMinifier
{
    /**
     * @param  array<string, mixed>  $options
     */
    public static function minify(string $css, array $options = []): string
    {
        $removeComments = (bool) ($options['remove_comments'] ?? true);
        $shortenZeros = (bool) ($options['shorten_zeros'] ?? true);

        // Backup quoted strings first so comments/whitespace inside them are preserved
        $hit = [];
        preg_match_all('/(\'[^\']*?\'|"[^"]*?")/ims', $css, $hit, PREG_PATTERN_ORDER);
        $quoted = $hit[1];

        foreach ($quoted as $i => $quote) {
            $css = str_replace($quote, '##########' . $i . '##########', $css);
        }

        if ($removeComments) {
            $stripped = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
            $css = is_string($stripped) ? $stripped : $css;
        }

        $patterns = [
            // trailing semicolon of selector's last property
            ['/;[\s\r\n\t]*?}[\s\r\n\t]*/ims', "}\r\n"],
            // whitespace between semicolon and property-name
            ['/;[\s\r\n\t]*?([\r\n]?[^\s\r\n\t])/ims', ';$1'],
            // whitespace surrounding property-colon
            ['/[\s\r\n\t]*:[\s\r\n\t]*?([^\s\r\n\t])/ims', ':$1'],
            // whitespace surrounding selector-comma
            ['/[\s\r\n\t]*,[\s\r\n\t]*?([^\s\r\n\t])/ims', ',$1'],
            // whitespace surrounding opening brace
            ['/[\s\r\n\t]*{[\s\r\n\t]*?([^\s\r\n\t])/ims', '{$1'],
            // whitespace between numbers and units
            ['/([\d\.]+)[\s\r\n\t]+(px|em|pt|%)/ims', '$1$2'],
        ];

        foreach ($patterns as [$pattern, $replacement]) {
            $result = preg_replace($pattern, $replacement, $css);
            $css = is_string($result) ? $result : $css;
        }

        if ($shortenZeros) {
            $result = preg_replace('/([^\d\.]0)(px|em|pt|%)/ims', '$1', $css);
            $css = is_string($result) ? $result : $css;
        }

        // Constrain multiple unicode spaces
        $result = preg_replace('/\p{Zs}+/uims', ' ', $css);
        $css = is_string($result) ? $result : $css;

        // Remove newlines
        $css = str_replace(["\r\n", "\r", "\n"], '', $css);

        // Restore quoted strings
        foreach ($quoted as $i => $quote) {
            $css = str_replace('##########' . $i . '##########', $quote, $css);
        }

        return trim($css);
    }
}
