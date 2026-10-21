<?php
/**
 * Generate packages/microweber-template/src/ContentJson/SectionRenderer.php from
 * the external designs renderer (index.php), porting its section-render functions
 * as PRIVATE METHODS of the SectionRenderer class (no global/namespaced functions).
 *
 * Internal calls between the ported functions are rewritten to $this->method()
 * via a token walk; calls to PHP built-ins are left untouched.
 *
 * Usage: php extract-renderer.php /tmp/bt/index.php /abs/out/SectionRenderer.php
 */
declare(strict_types=1);

$src = $argv[1] ?? '/tmp/bt/index.php';
$out = $argv[2] ?? null;
if (!$out) { fwrite(STDERR, "usage: extract-renderer.php <index.php> <out.php>\n"); exit(1); }

$code = file_get_contents($src);
if ($code === false) { fwrite(STDERR, "cannot read $src\n"); exit(1); }

// Functions ported verbatim as methods (self-contained; no form-POST/CLI/CSS/page shell).
$wanted = [
    'clip','arr','slugify','slug_from','rich','icon','stars','default_value','img_tag',
    'brand','button_link','block_head','render_field','render_choices','render_form_grid',
    'form_open','success_box','social_list','contact_lines','footer_badge','art_svg',
    'render_navbar','render_hero_search','render_logo_cloud','render_info_card','render_cards',
    'render_media_float','render_reviews','render_faq','render_hero_form','render_split',
    'render_cta','render_testimonial','render_hero_person','render_offer','render_promo',
    'render_checklist','render_scores','render_mosaic','render_icons','render_newsletter',
    'render_hero_product','render_timeline','render_contact','render_checkout','render_footer',
    'render_section',
];
// e/asset/asset_exists/web_base are hand-written methods below; still part of the
// "our methods" set so internal call-sites to them are rewritten to $this->.
$ourSet = array_flip(array_merge($wanted, ['e', 'asset', 'asset_exists', 'web_base', 'render_body']));

$tokens = token_get_all($code);
$n = count($tokens);

// Index of the previous / next significant (non-whitespace, non-comment) token.
$prevSig = static function (array $tokens, int $i): ?int {
    for ($j = $i - 1; $j >= 0; $j--) {
        $t = $tokens[$j];
        if (is_array($t) && in_array($t[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) { continue; }
        return $j;
    }
    return null;
};
$nextSig = static function (array $tokens, int $i, int $n): ?int {
    for ($j = $i + 1; $j < $n; $j++) {
        $t = $tokens[$j];
        if (is_array($t) && in_array($t[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) { continue; }
        return $j;
    }
    return null;
};

/** Rewrite one function's token range into a private-method source string. */
$emitMethod = static function (array $tokens, int $start, int $end, array $ourSet, $prevSig, $nextSig, int $n): string {
    $buf = '';
    for ($m = $start; $m <= $end; $m++) {
        $tk = $tokens[$m];
        if (is_array($tk) && $tk[0] === T_STRING && isset($ourSet[$tk[1]])) {
            $p = $prevSig($tokens, $m);
            $pTok = $p !== null ? $tokens[$p] : null;
            $isDef = is_array($pTok) && $pTok[0] === T_FUNCTION;
            $isMember = is_array($pTok) && in_array($pTok[0], [T_OBJECT_OPERATOR, T_DOUBLE_COLON], true);
            $isNullsafe = defined('T_NULLSAFE_OBJECT_OPERATOR') && is_array($pTok) && $pTok[0] === T_NULLSAFE_OBJECT_OPERATOR;
            $nx = $nextSig($tokens, $m, $n);
            $isCall = $nx !== null && $tokens[$nx] === '(';
            if ($isCall && !$isDef && !$isMember && !$isNullsafe) {
                $buf .= '$this->' . $tk[1];
                continue;
            }
        }
        $buf .= is_array($tk) ? $tk[1] : $tk;
    }
    // `function name(...)` -> `private function name(...)`
    $buf = preg_replace('/^(\s*)function\b/', '$1private function', ltrim($buf, "\n"));
    return $buf;
};

$methods = [];
for ($i = 0; $i < $n; $i++) {
    $t = $tokens[$i];
    if (is_array($t) && $t[0] === T_FUNCTION) {
        $j = $i + 1; $name = null;
        while ($j < $n) {
            if (is_array($tokens[$j]) && $tokens[$j][0] === T_STRING) { $name = $tokens[$j][1]; break; }
            if ($tokens[$j] === '(') break;
            $j++;
        }
        $k = $j;
        while ($k < $n && $tokens[$k] !== '{') { $k++; }
        if ($k >= $n) { continue; }
        $depth = 0; $endK = $k;
        for (; $k < $n; $k++) {
            if ($tokens[$k] === '{') { $depth++; }
            elseif ($tokens[$k] === '}') { $depth--; if ($depth === 0) { $endK = $k; break; } }
        }
        if ($name !== null && in_array($name, $wanted, true)) {
            $methods[$name] = $emitMethod($tokens, $i, $endK, $ourSet, $prevSig, $nextSig, $n);
        }
        $i = $endK;
    }
}

$missing = array_diff($wanted, array_keys($methods));
if ($missing) { fwrite(STDERR, "WARNING missing functions: " . implode(',', $missing) . "\n"); }

$body = '';
foreach ($wanted as $name) {
    if (isset($methods[$name])) { $body .= "\n    " . trim($methods[$name]) . "\n"; }
}

$class = <<<'PHP'
<?php

namespace MicroweberPackages\Template\ContentJson;

use DateTimeImmutable;

/**
 * Renders a content.json "sections" array into HTML for a page body.
 *
 * The per-section-type render methods below are GENERATED — ported verbatim from
 * the external designs renderer (the shared index.php that authored the JSON
 * designs) via scripts/designs/extract-renderer.php, with internal calls rewritten
 * to $this->method(). Regenerate after changing the renderer; do not hand-edit the
 * generated block. Only the form-POST handling, CLI/page-shell and CSS of the
 * original are intentionally omitted.
 *
 * The hand-written pieces are render()/renderSections() (the public entry points),
 * e()/asset()/asset_exists()/web_base() (adapted to Microweber's published template
 * asset path) and render_body() (replicates the original render_page() body order:
 * navbar / <main> sections / footer).
 *
 * $tpl carries the content.json page/data array plus two Microweber keys the
 * installer injects:
 *   - 'mw_asset_base' : public URL base for this template's assets, e.g. "/templates/alfa"
 *   - 'mw_asset_dir'  : absolute public dir, e.g. "/var/www/public/templates/alfa"
 */
class SectionRenderer
{
    /**
     * Render an ordered list of sections into a page body (navbar + <main> + footer).
     *
     * @param array $sections The page's "sections" array.
     * @param array $data     Design-level data (name/title/preview/messages/…) plus the
     *                        Microweber asset keys (mw_asset_base / mw_asset_dir).
     */
    public function render(array $sections, array $data = []): string
    {
        $tpl = $data;
        $tpl['sections'] = $sections;

        // Several ported methods use $tpl['slug'] for element ids / body class.
        // Default it from the asset base so callers never hit an undefined key.
        if (empty($tpl['slug'])) {
            $base = (string) ($tpl['mw_asset_base'] ?? '');
            $tpl['slug'] = $base !== '' ? basename($base) : 'design';
        }

        return $this->render_body($tpl, $sections, []);
    }

    /** Alias kept for readability where a single section list is rendered. */
    public function renderSections(array $sections, array $data = []): string
    {
        return $this->render($sections, $data);
    }

    private function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function web_base(): string
    {
        return '';
    }

    /** Microweber-adapted: resolve a design-relative path to the published template URL. */
    private function asset(array $tpl, string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path === '' || str_contains($path, '..')) {
            return '';
        }
        $parts = array_map('rawurlencode', explode('/', $path));
        $base = rtrim((string) ($tpl['mw_asset_base'] ?? ''), '/');
        return ($base !== '' ? $base . '/' : '/') . implode('/', $parts);
    }

    /** Microweber-adapted: check the published public dir for the asset. */
    private function asset_exists(array $tpl, string $path): bool
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path === '' || str_contains($path, '..')) {
            return false;
        }
        $dir = rtrim((string) ($tpl['mw_asset_dir'] ?? ''), '/');
        return $dir !== '' && is_file($dir . '/' . $path);
    }

    /**
     * Assemble a page body from an ordered section list, mirroring the original
     * render_page(): navbar and footer sit outside <main>, everything else inside.
     */
    private function render_body(array $tpl, array $sections, array $state = []): string
    {
        $html = '';
        $main = false;
        foreach ($sections as $section) {
            if (!is_array($section)) {
                continue;
            }
            $type = (string) ($section['type'] ?? '');
            if ($type === 'navbar') {
                if ($main) { $html .= '</main>'; $main = false; }
                $html .= $this->render_navbar($tpl, $section);
                continue;
            }
            if ($type === 'footer') {
                if ($main) { $html .= '</main>'; $main = false; }
                $html .= $this->render_footer($tpl, $section);
                continue;
            }
            if (!$main) { $html .= '<main id="content">'; $main = true; }
            $html .= $this->render_section($tpl, $section, $state);
        }
        if ($main) { $html .= '</main>'; }
        return $html;
    }

PHP;

file_put_contents($out, $class . $body . "}\n");
echo "Wrote $out (" . count($methods) . " ported methods, " . strlen($body) . " bytes)\n";
