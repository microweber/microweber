<?php

namespace MicroweberPackages\Security;

use MicroweberPackages\Security\HtmlSanitizer\MwHtmlSanitizerReference;
use voku\helper\AntiXSS;

class XSSClean
{
    /**
     * The fully-configured AntiXSS instance, built once per request and reused.
     * Previously clean() did `new AntiXSS()` AND re-applied
     * the entire evil-tag/attribute/allow-list config on EVERY call; cleaning a
     * large structure (e.g. the admin content tree: ~7 fields × 1500 nodes ≈ 10k
     * calls) cost ~25s just constructing/configuring AntiXSS. The config never
     * depends on the input, so it is built once here. Static so it is shared
     * across every `new XSSClean()` (the global xss_clean() helper makes a fresh
     * instance per call).
     *
     * @var AntiXSS|null
     */
    protected static ?AntiXSS $antiXss = null;

    protected static function antiXss(): AntiXSS
    {
        if (self::$antiXss !== null) {
            return self::$antiXss;
        }

        // from https://portswigger.net/web-security/cross-site-scripting/cheat-sheet#ontransitionend
        $cleanStrings = MwHtmlSanitizerReference::getNotAllowedAttributes();

        $antiXss = new AntiXSS();
        $antiXss->addEvilHtmlTags($cleanStrings);
        $antiXss->addEvilAttributes($cleanStrings);
        $antiXss->addNeverAllowedOnEventsAfterwards($cleanStrings);

        $allowAttibutes = [
            'style',
            'href',
            'alt',
            'target',
            'srcset',
            'sizes',
            'title',
            'xlink:href',
        ];
        $antiXss->removeEvilAttributes($allowAttibutes);

        $allowTags = [
            'head',
            'header',
            'main',
            'aside',
            'img',
            'form',
            'svg',
            'title',
            'input',
            'button',
            'select',
            'option',
            'textarea',
            'picture',
            'source',
        ];

        $antiXss->removeEvilHtmlTags($allowTags);
        $allowRegex = [
            '<!--(.*)-->' => '&lt;!--$1--&gt;',
            '&lt;!--', '&lt;!--$1--&gt;'
        ];

        $antiXss->removeNeverAllowedRegex($allowRegex);

        $allowNotClosed = [
            'li',
            'ul',
            'textarea',
        ];
        $antiXss->removeDoNotCloseHtmlTags($allowNotClosed);

        return self::$antiXss = $antiXss;
    }

    /**
     * @param array<mixed> $array
     * @param array<string,mixed> $options
     * @return array<mixed>
     */
    public function cleanArray(array $array, array $options = []): array
    {
        $cleanedArray = [];
        foreach ($array as $key => $value) {
            if (is_string($key)) {
                $key = $this->clean($key, $options);
            }

            if (is_array($value)) {
                $cleanedArray[$key] = $this->cleanArray($value, $options);
            } else {
                $cleanedArray[$key] = $this->clean($value, $options);
            }
        }

        return $cleanedArray;
    }

    /**
     * @param mixed $html
     * @param array<string,mixed> $options Pass ['admin_mode' => true] to use the
     *        permissive rich-content path (keeps sanitized <style>/<iframe>) for
     *        trusted admin authoring. Omitted/false keeps the strict filter.
     * @return mixed
     */
    public function clean(mixed $html, array $options = []): mixed
    {
        if (is_array($html)) {
            return $this->cleanArray($html, $options);
        }

        // Trusted admin authoring (Live Edit / content save) may keep rich
        // markup — a sanitized <style> block and <iframe> embeds — that the
        // strict filter would otherwise escape. Everything else still goes
        // through the strict pass below unchanged.
        if ($this->isPermissive($options) && is_string($html) && $html !== '') {
            return $this->cleanRich($html);
        }

        $html = str_ireplace('{SITE_URL}', '___mw-site-url-temp-replace-on-clean___', $html);

        $html_to_return = self::antiXss()->xss_clean($html);
        $html_to_return = str_ireplace('___mw-site-url-temp-replace-on-clean___', '{SITE_URL}', $html_to_return);

        return $html_to_return;
    }

    /**
     * Run the base strict pass on a single string (used by the rich path for the
     * non-style/non-iframe remainder).
     */
    protected function cleanStrict(string $html): string
    {
        $html = str_ireplace('{SITE_URL}', '___mw-site-url-temp-replace-on-clean___', $html);
        $html = self::antiXss()->xss_clean($html);

        return str_ireplace('___mw-site-url-temp-replace-on-clean___', '{SITE_URL}', $html);
    }

    /**
     * Whether this call should use the permissive (admin/rich) path. Subclasses
     * ({@see LiveEditXSSClean}) force it on; callers opt in via
     * ['admin_mode' => true] or ['allow_rich' => true].
     */
    protected function isPermissive(array $options): bool
    {
        return !empty($options['admin_mode']) || !empty($options['allow_rich']);
    }

    // ---------------------------------------------------------------------
    // Permissive rich-content path (trusted admin authoring).
    //
    // Keeps a sanitized <style> block and <iframe> embeds that the strict pass
    // would escape, while every strict protection (script/on*/javascript: href/
    // style="expression()") still applies to the rest of the markup.
    // ---------------------------------------------------------------------

    /** Opaque, alnum-only placeholder tokens survive the strict AntiXSS pass untouched. */
    private const STYLE_TOKEN = 'MWLIVEEDITSTYLEPLACEHOLDER';
    private const IFRAME_TOKEN = 'MWLIVEEDITIFRAMEPLACEHOLDER';
    private const TOKEN_END = 'ENDMWPLACEHOLDER';

    /** Generic on<word>= handler match (catches handlers we didn't enumerate). */
    private const EVENT_ATTR_REGEX = '/\son[a-z-]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i';

    protected function cleanRich(string $html): string
    {
        // 1. Lift <style> and <iframe> blocks out so the strict base pass can't
        //    escape them, replacing each with an alnum placeholder token.
        $styles = [];
        $iframes = [];

        $html = preg_replace_callback(
            '/<style\b[^>]*>(.*?)<\/style\s*>/is',
            function ($m) use (&$styles) {
                $i = count($styles);
                $styles[$i] = $this->sanitizeStyleBlock($m[1]);

                return self::STYLE_TOKEN . $i . self::TOKEN_END;
            },
            $html
        ) ?? $html;

        $html = preg_replace_callback(
            '/<iframe\b[^>]*>.*?<\/iframe\s*>|<iframe\b[^>]*\/?>/is',
            function ($m) use (&$iframes) {
                $sanitized = $this->sanitizeIframe($m[0]);
                if ($sanitized === null) {
                    return ''; // unsafe beyond repair → drop entirely
                }
                $i = count($iframes);
                $iframes[$i] = $sanitized;

                return self::IFRAME_TOKEN . $i . self::TOKEN_END;
            },
            $html
        ) ?? $html;

        // 2. Run the proven strict filter over everything else.
        $html = $this->cleanStrict($html);

        // 3. Re-insert the sanitized blocks.
        foreach ($styles as $i => $css) {
            $html = str_replace(self::STYLE_TOKEN . $i . self::TOKEN_END, '<style>' . $css . '</style>', $html);
        }
        foreach ($iframes as $i => $iframe) {
            $html = str_replace(self::IFRAME_TOKEN . $i . self::TOKEN_END, $iframe, $html);
        }

        return $html;
    }

    /**
     * Scrub a CSS string of the constructs that can execute script or break out
     * of the <style> block, leaving ordinary styling intact.
     */
    public function sanitizeStyleBlock(string $css): string
    {
        // Build a de-obfuscated copy for BOTH detection and stripping, so a
        // keyword hidden with HTML entities (`&#106;avascript:`) or CSS escapes
        // (`expr\65 ssion`) can't slip through. We output the de-obfuscated form
        // only when something dangerous is found; clean CSS keeps its original
        // text (minus comments) so legitimate escapes like `content:"\2013"` are
        // preserved for the common case.
        $decoded = html_entity_decode($css, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalised = preg_replace_callback('/\\\\([0-9a-fA-F]{1,6})\s?/', function ($m) {
            $cp = hexdec($m[1]);

            return ($cp > 0 && $cp < 0x110000) ? mb_chr($cp, 'UTF-8') : '';
        }, $decoded) ?? $decoded;

        // Strip CSS comments from both (comments can hide / split payloads).
        $scan = preg_replace('#/\*.*?\*/#s', '', $normalised) ?? $normalised;
        $cssNoComments = preg_replace('#/\*.*?\*/#s', '', $css) ?? $css;

        if ($this->cssHasDangerousVector($scan)) {
            // Operate on the de-obfuscated form so property-name vectors and
            // escaped keywords are actually removed.
            $css = $this->stripDangerousCssDeclarations($scan);
        } else {
            $css = $cssNoComments;
        }

        // CSS content never legitimately contains markup. Strip ANY embedded tag
        // outright — a `</style>` breakout, or a `<style ...>` / `<script>` opening
        // tag that an unclosed outer <style> swallowed into this block (which would
        // otherwise leave its `on*=` attributes as text) — then drop any stray `<`
        // so nothing can reconstitute a tag on re-insertion.
        $css = preg_replace('#<\s*/?[a-zA-Z!][^>]*>?#', '', $css) ?? $css;
        $css = str_replace('<', '', $css);

        return $css;
    }

    /**
     * Detect script-executing / binding CSS vectors. Carefully worded so modern
     * properties that merely contain a substring (scroll-behavior, overscroll-
     * behavior) are NOT flagged.
     */
    private function cssHasDangerousVector(string $css): bool
    {
        $patterns = [
            '/expression\s*\(/i',                                  // IE expression()
            '/(?<![-\w])-moz-binding\b/i',                         // XBL binding
            '/(?<![-\w])behaviou?r\s*:\s*[^;{}]*url/i',            // IE HTC behavior:url(), not scroll-behavior
            '/(?:javascript|vbscript|livescript|mocha)\s*:/i',     // script URL schemes anywhere
            '/url\s*\(\s*["\']?\s*(?:javascript|vbscript|livescript|mocha)\s*:/i',
            '/url\s*\(\s*["\']?\s*data\s*:\s*text\s*\/\s*html/i',  // data:text/html in url()
            '/@import\b/i',                                         // remote import / exfil
        ];
        foreach ($patterns as $p) {
            if (preg_match($p, $css)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Remove dangerous CSS while keeping surrounding valid declarations. Operates
     * on the de-obfuscated form produced by {@see sanitizeStyleBlock()}.
     */
    private function stripDangerousCssDeclarations(string $css): string
    {
        // Drop @import rules outright.
        $css = preg_replace('/@import\b[^;]*;?/i', '', $css) ?? $css;

        // Blank out dangerous url(...) payloads, keep the property using url().
        $css = preg_replace(
            '/url\s*\(\s*["\']?\s*(?:javascript|vbscript|livescript|mocha)\s*:[^)]*\)/i',
            'url()',
            $css
        ) ?? $css;
        $css = preg_replace(
            '/url\s*\(\s*["\']?\s*data\s*:\s*text\s*\/\s*html[^)]*\)/i',
            'url()',
            $css
        ) ?? $css;

        // Drop whole declarations whose PROPERTY is a binding/behavior/expression
        // vector (the keyword sits on the left of the colon here).
        $css = preg_replace(
            '/(?<![-\w])(?:-moz-binding|behaviou?r|expression)\s*:[^;{}]*;?/i',
            '',
            $css
        ) ?? $css;

        // Drop whole declarations whose VALUE carries a vector (keyword on the
        // right of the colon).
        $css = preg_replace(
            '/[a-z-]+\s*:\s*[^;{}]*(?:expression\s*\(|(?:javascript|vbscript|mocha|livescript)\s*:|-moz-binding)[^;{}]*;?/i',
            '',
            $css
        ) ?? $css;

        return $css;
    }

    /**
     * Return a sanitized <iframe> tag, or null if it must be dropped entirely.
     */
    public function sanitizeIframe(string $tag): ?string
    {
        if (!preg_match('/<iframe\b([^>]*)>/is', $tag, $m)) {
            return null;
        }
        $attrs = $m[1];

        $attrs = preg_replace(self::EVENT_ATTR_REGEX, '', $attrs) ?? $attrs;
        $attrs = preg_replace('/\ssrcdoc\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $attrs) ?? $attrs;

        $src = null;
        if (preg_match('/\ssrc\s*=\s*("([^"]*)"|\'([^\']*)\'|([^\s>]+))/i', $attrs, $sm)) {
            $src = ($sm[2] ?? '') !== '' ? $sm[2] : (($sm[3] ?? '') !== '' ? $sm[3] : ($sm[4] ?? ''));
        }
        if ($src !== null && !$this->isSafeIframeSrc($src)) {
            return null;
        }

        $attrs = trim(preg_replace('/\s+/', ' ', $attrs) ?? $attrs);

        return '<iframe ' . $attrs . '></iframe>';
    }

    private function isSafeIframeSrc(string $src): bool
    {
        $src = trim(html_entity_decode($src, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $probe = strtolower(preg_replace('/[\x00-\x20]+/', '', $src) ?? $src);

        if ($probe === '') {
            return false;
        }
        foreach (['javascript:', 'vbscript:', 'livescript:', 'mocha:', 'data:'] as $bad) {
            if (str_starts_with($probe, $bad)) {
                return false;
            }
        }
        if (str_starts_with($probe, 'http://') || str_starts_with($probe, 'https://')) {
            return true;
        }
        if (str_starts_with($src, '//') || str_starts_with($src, '/') || str_starts_with($probe, '{site_url}')) {
            return true;
        }
        if (!str_contains($probe, ':')) {
            return true; // scheme-less relative path
        }

        return false;
    }
}