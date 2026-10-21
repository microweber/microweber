<?php

namespace MicroweberPackages\Template\ContentJson;

/**
 * Converts a design's hardcoded colors into Microweber CSS custom properties so
 * the design can be recolored from the Live Edit editor (which edits --mw-* vars)
 * and carried as a portable color scheme in content.json.
 *
 * The brand/ink/background/accent roles are mapped onto Microweber's semantic
 * color vars (--mw-primary-color, --mw-heading-color, --mw-body-color,
 * --mw-paragraph-color, --mw-link-color, --mw-background-color,
 * --mw-section-background-color); every other distinct colour becomes a
 * --mw-tpl-color-N token. Each colour literal in the CSS is rewritten to
 * var(--token, <original literal>), so the literal is the fallback and the output
 * is visually identical until an editor overrides a var.
 */
class CssVarizer
{
    /** Semantic MW vars the Live Edit colour editor manipulates (role => var). */
    public const ROLE_VARS = [
        'primary' => '--mw-primary-color',
        'ink' => '--mw-heading-color',
        'background' => '--mw-background-color',
        'section' => '--mw-section-background-color',
    ];

    /**
     * @param string $css   The design CSS to varize.
     * @param array  $hints ['themeColor' => '#...', 'swatches' => ['#...', ...]]
     * @return array{css:string, root:string, scheme:array<string,string>}
     *   css    – CSS with colour literals rewritten to var(--token, literal)
     *   root   – a ":root { … }" block defining every token (defaults = the literals)
     *   scheme – token => hex map (the default colour scheme; stored in content.json)
     */
    public static function varize(string $css, array $hints = []): array
    {
        $colorRe = '/#[0-9a-fA-F]{6}\b|#[0-9a-fA-F]{3}\b|rgba?\([0-9.,\s%]*\)/';

        // 1. Collect distinct colours (canonical key => ['orig' => literal, 'count' => n]).
        $distinct = [];
        if (preg_match_all($colorRe, $css, $m)) {
            foreach ($m[0] as $lit) {
                $key = self::canon($lit);
                if ($key === null) { continue; }
                if (!isset($distinct[$key])) { $distinct[$key] = ['orig' => $lit, 'count' => 0]; }
                $distinct[$key]['count']++;
            }
        }
        if (!$distinct) {
            return ['css' => $css, 'root' => '', 'scheme' => []];
        }

        // 2. Assign a var token to each colour: semantic roles first, then tpl tokens.
        $assign = self::assign($distinct, $hints);   // canonKey => ['var' => string, 'hex' => string]

        // 3. Rewrite every colour literal to var(--token, <original>).
        $out = preg_replace_callback($colorRe, function ($mm) use ($assign) {
            $key = self::canon($mm[0]);
            if ($key === null || !isset($assign[$key])) { return $mm[0]; }
            return 'var(' . $assign[$key]['var'] . ', ' . $mm[0] . ')';
        }, $css);

        // 4. Build the :root scheme block + the scheme map, in a stable order.
        $scheme = [];
        foreach ($assign as $a) {
            // The first definition wins per var (role vars are unique; tpl tokens unique).
            if (!isset($scheme[$a['var']])) { $scheme[$a['var']] = $a['hex']; }
        }
        // Derived semantic vars that reference the primaries (so one edit cascades).
        $derived = [];
        if (isset($scheme['--mw-heading-color'])) {
            $derived['--mw-body-color'] = 'var(--mw-heading-color)';
            $derived['--mw-paragraph-color'] = 'var(--mw-heading-color)';
        }
        if (isset($scheme['--mw-primary-color'])) {
            $derived['--mw-link-color'] = 'var(--mw-primary-color)';
        }

        $lines = [];
        foreach ($scheme as $var => $hex) { $lines[] = "  {$var}: {$hex};"; }
        foreach ($derived as $var => $ref) { $lines[] = "  {$var}: {$ref};"; }
        $root = ":root {\n" . implode("\n", $lines) . "\n}";

        return ['css' => $out, 'root' => $root, 'scheme' => $scheme];
    }

    /** Assign var tokens to colours by role (primary/ink/background) + fallback tokens. */
    private static function assign(array $distinct, array $hints): array
    {
        $meta = [];
        foreach ($distinct as $key => $info) {
            $rgb = self::toRgb($key);
            if ($rgb === null) { $meta[$key] = null; continue; }
            $meta[$key] = $rgb + [
                'lum' => self::luminance($rgb),
                'sat' => self::saturation($rgb),
                'count' => $info['count'],
                'hex' => self::keyToHex($key, $info['orig']),
            ];
        }

        $assign = [];
        $used = [];

        // Solid-colour roles (primary/ink/background/section) only consider OPAQUE
        // colours — translucent rgba() overlays are not brand/text/background colours
        // and would otherwise win "darkest"/"lightest" (luminance ignores alpha).
        $opaque = array_filter($meta, fn ($v, $k) => $v !== null && $k[0] === '#', ARRAY_FILTER_USE_BOTH);

        // primary accent: a swatch/themeColor that is chromatic; else the most-used chromatic.
        $primary = self::pickPrimary($opaque, $hints);
        if ($primary !== null) {
            $assign[$primary] = ['var' => self::ROLE_VARS['primary'], 'hex' => $meta[$primary]['hex']];
            $used[$primary] = true;
        }

        // ink: darkest colour (text/headings).
        $ink = self::pickExtreme($opaque, $used, 'dark');
        if ($ink !== null) {
            $assign[$ink] = ['var' => self::ROLE_VARS['ink'], 'hex' => $meta[$ink]['hex']];
            $used[$ink] = true;
        }

        // background: lightest colour (page background / white).
        $bg = self::pickExtreme($opaque, $used, 'light');
        if ($bg !== null) {
            $assign[$bg] = ['var' => self::ROLE_VARS['background'], 'hex' => $meta[$bg]['hex']];
            $used[$bg] = true;
        }

        // section background: a light neutral that isn't the page background.
        $section = self::pickExtreme($opaque, $used, 'light');
        if ($section !== null && $meta[$section]['lum'] > 190) {
            $assign[$section] = ['var' => self::ROLE_VARS['section'], 'hex' => $meta[$section]['hex']];
            $used[$section] = true;
        }

        // Everything else → stable --mw-tpl-color-N tokens (ordered darkest→lightest).
        $rest = array_keys(array_diff_key($meta, $used));
        $rest = array_filter($rest, fn ($k) => $meta[$k] !== null);
        usort($rest, fn ($a, $b) => $meta[$a]['lum'] <=> $meta[$b]['lum']);
        $i = 1;
        foreach ($rest as $key) {
            $assign[$key] = ['var' => '--mw-tpl-color-' . $i, 'hex' => $meta[$key]['hex']];
            $i++;
        }

        return $assign;
    }

    private static function pickPrimary(array $meta, array $hints): ?string
    {
        $candidates = [];
        foreach ([$hints['themeColor'] ?? null] as $hint) {
            if ($hint) { $k = self::canon($hint); if ($k !== null) { $candidates[] = $k; } }
        }
        foreach (($hints['swatches'] ?? []) as $sw) {
            $k = self::canon($sw); if ($k !== null) { $candidates[] = $k; }
        }
        // Prefer a chromatic hint (saturated, mid luminance).
        $best = null; $bestScore = -1;
        foreach ($candidates as $k) {
            if (!isset($meta[$k]) || $meta[$k] === null) { continue; }
            $score = $meta[$k]['sat'];
            if ($meta[$k]['lum'] > 20 && $meta[$k]['lum'] < 235) { $score += 0.2; }
            if ($score > $bestScore && $meta[$k]['sat'] >= 0.25) { $best = $k; $bestScore = $score; }
        }
        if ($best !== null) { return $best; }

        // Fallback: most-used chromatic colour across the CSS.
        foreach ($meta as $k => $v) {
            if ($v === null || $v['sat'] < 0.25) { continue; }
            $score = $v['count'] * $v['sat'];
            if ($score > $bestScore) { $best = $k; $bestScore = $score; }
        }
        return $best;
    }

    private static function pickExtreme(array $meta, array $used, string $dir): ?string
    {
        $best = null; $bestLum = $dir === 'dark' ? 1e9 : -1;
        foreach ($meta as $k => $v) {
            if ($v === null || isset($used[$k])) { continue; }
            if ($dir === 'dark' && $v['lum'] < $bestLum) { $best = $k; $bestLum = $v['lum']; }
            if ($dir === 'light' && $v['lum'] > $bestLum) { $best = $k; $bestLum = $v['lum']; }
        }
        return $best;
    }

    /** Canonicalise a colour literal to a comparison key (6-hex, or normalised rgba()). */
    private static function canon(string $lit): ?string
    {
        $lit = trim(strtolower($lit));
        if ($lit === '' || $lit === 'transparent' || $lit === 'inherit' || $lit === 'currentcolor') {
            return null;
        }
        if ($lit[0] === '#') {
            $hex = ltrim($lit, '#');
            if (strlen($hex) === 3) { $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2]; }
            if (strlen($hex) !== 6 || !ctype_xdigit($hex)) { return null; }
            return '#' . $hex;
        }
        if (str_starts_with($lit, 'rgb')) {
            if (preg_match('/rgba?\(\s*(\d+)[,\s]+(\d+)[,\s]+(\d+)(?:[,\s\/]+([\d.]+%?))?\s*\)/', $lit, $m)) {
                $a = isset($m[4]) ? $m[4] : '1';
                // Opaque rgb() collapses to its hex so it shares a var with the hex form.
                if ($a === '1' || $a === '1.0' || $a === '100%') {
                    return sprintf('#%02x%02x%02x', (int) $m[1], (int) $m[2], (int) $m[3]);
                }
                return "rgba({$m[1]},{$m[2]},{$m[3]},{$a})";
            }
            return null;
        }
        return null;
    }

    private static function keyToHex(string $key, string $orig): string
    {
        // For opaque colours the key is already #rrggbb; keep rgba() literals as-is.
        return $key[0] === '#' ? $key : $orig;
    }

    private static function toRgb(string $key): ?array
    {
        if ($key[0] === '#') {
            return [
                'r' => hexdec(substr($key, 1, 2)),
                'g' => hexdec(substr($key, 3, 2)),
                'b' => hexdec(substr($key, 5, 2)),
            ];
        }
        if (preg_match('/rgba?\((\d+),(\d+),(\d+)/', $key, $m)) {
            return ['r' => (int) $m[1], 'g' => (int) $m[2], 'b' => (int) $m[3]];
        }
        return null;
    }

    private static function luminance(array $rgb): float
    {
        return 0.2126 * $rgb['r'] + 0.7152 * $rgb['g'] + 0.0722 * $rgb['b'];
    }

    private static function saturation(array $rgb): float
    {
        $r = $rgb['r'] / 255; $g = $rgb['g'] / 255; $b = $rgb['b'] / 255;
        $max = max($r, $g, $b); $min = min($r, $g, $b);
        if ($max === $min) { return 0.0; }
        $l = ($max + $min) / 2;
        $d = $max - $min;
        return $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
    }
}
