<?php

namespace MicroweberPackages\App\Utils\ParserHelpers;

/**
 * Shields content regions from module parsing.
 *
 * Regions that must NOT be parsed for <module> tags:
 *   - <script>...</script>
 *   - <textarea>...</textarea>
 *   - <code>...</code>
 *   - <pre>...</pre>
 *   - <style>...</style>
 *   - <select>...</select>
 *   - <input .../> (void, but value attributes)
 *   - <option>...</option>
 *   - <optgroup>...</optgroup>
 *   - HTML comments <!-- ... -->
 *   - Blade comments {{-- ... --}}
 *
 * Each region is replaced with a unique placeholder, and can be
 * restored byte-for-byte after parsing.
 */
class ContentProtector
{
    /** @var array<string, string> placeholder → original content */
    private array $replacements = [];

    /** @var array<string, string> comment placeholder → original */
    private array $commentReplacements = [];

    /** @var int counter for unique placeholders */
    private int $counter = 0;

    /**
     * Protect all known regions in the given content.
     * Returns the content with placeholders.
     */
    public function protect(string $content): string
    {
        // 1. Protect Blade comments first (before HTML comments, since
        //    {{-- may contain <!-- internally)
        $content = $this->protectBladeComments($content);

        // 2. Protect HTML comments
        $content = $this->protectHtmlComments($content);

        // 3. Protect paired tags
        $protectedTags = ['script', 'textarea', 'code', 'pre', 'style', 'select', 'optgroup'];
        foreach ($protectedTags as $tag) {
            $content = $this->protectPairedTag($content, $tag);
        }

        // 4. Protect void/self-contained tags whose ATTRIBUTES may legitimately
        //    contain a <module> string (e.g. <input value="<module .../>">). The
        //    legacy phpQuery flow ignored attribute content; the string-based
        //    lexer would otherwise parse the module inside the attribute.
        foreach (['input'] as $tag) {
            $content = $this->protectVoidTag($content, $tag);
        }

        return $content;
    }

    /**
     * Protect a void / self-contained tag (no closing tag), quote-aware so a
     * `>` inside an attribute value (e.g. value="<module .../>") does not end
     * the tag early.
     */
    private function protectVoidTag(string $content, string $tag): string
    {
        $needle = '<' . strtolower($tag);
        $len = strlen($content);
        $out = '';
        $i = 0;

        while ($i < $len) {
            $start = stripos($content, $needle, $i);
            if ($start === false) {
                $out .= substr($content, $i);
                break;
            }
            // Must be a real tag boundary: next char after the name is space/>//.
            $after = $start + strlen($needle);
            $boundary = $after < $len ? $content[$after] : '>';
            if (!in_array($boundary, [' ', "\t", "\n", "\r", '>', '/'], true)) {
                $out .= substr($content, $i, $after - $i);
                $i = $after;
                continue;
            }

            // Scan quote-aware to the closing '>'.
            $j = $after;
            $quote = null;
            $end = false;
            while ($j < $len) {
                $ch = $content[$j];
                if ($quote !== null) {
                    if ($ch === $quote) {
                        $quote = null;
                    } elseif ($ch === '\\' && ($j + 1) < $len) {
                        $j++;
                    }
                } elseif ($ch === '"' || $ch === "'") {
                    $quote = $ch;
                } elseif ($ch === '>') {
                    $end = true;
                    break;
                }
                $j++;
            }

            $out .= substr($content, $i, $start - $i);
            if (!$end) {
                // Malformed / unterminated — leave the rest untouched.
                $out .= substr($content, $start);
                break;
            }

            $value = substr($content, $start, $j - $start + 1);
            $placeholder = $this->makePlaceholder('tag_' . $tag);
            $this->replacements[$placeholder] = $value;
            $out .= $placeholder;
            $i = $j + 1;
        }

        return $out;
    }

    /**
     * Restore all placeholders back to original content.
     */
    public function restore(string $content): string
    {
        // A protected region can contain ANOTHER region's placeholder (e.g. a
        // <script> inside a <textarea>: the script is protected first, then the
        // textarea — so the script placeholder lives inside the textarea's stored
        // value). A single insertion-order pass would restore the inner one before
        // the outer expands and re-introduces it. Loop until no placeholder marker
        // remains (bounded to avoid any pathological cycle).
        $all = $this->replacements + $this->commentReplacements;
        if (empty($all)) {
            return $content;
        }

        $maxPasses = count($all) + 2;
        for ($pass = 0; $pass < $maxPasses; $pass++) {
            if (strpos($content, '<mw-protected') === false) {
                break;
            }
            $before = $content;
            foreach ($all as $placeholder => $original) {
                if (strpos($content, $placeholder) !== false) {
                    $content = str_replace($placeholder, $original, $content);
                }
            }
            if ($content === $before) {
                break; // no progress — avoid spinning on an orphan placeholder
            }
        }

        return $content;
    }

    /**
     * Get all tag replacements (for backward compatibility with old code).
     */
    public function getReplacements(): array
    {
        return $this->replacements;
    }

    /**
     * Get comment replacements separately.
     */
    public function getCommentReplacements(): array
    {
        return $this->commentReplacements;
    }

    /**
     * Reset internal state.
     */
    public function reset(): void
    {
        $this->replacements = [];
        $this->commentReplacements = [];
        $this->counter = 0;
    }

    /**
     * Protect a paired HTML tag (e.g., <script>...</script>).
     * Uses a state machine instead of regex for robustness.
     */
    private function protectPairedTag(string $content, string $tag): string
    {
        $pattern = "/\<" . preg_quote($tag, '/') . "(\s[^>]*)?\>.*?\<\/" . preg_quote($tag, '/') . "\>/is";
        preg_match_all($pattern, $content, $matches);

        if (!empty($matches[0])) {
            foreach ($matches[0] as $value) {
                if ($value !== '') {
                    $placeholder = $this->makePlaceholder('tag_' . $tag);
                    $content = str_replace($value, $placeholder, $content);
                    $this->replacements[$placeholder] = $value;
                }
            }
        }

        return $content;
    }

    /**
     * Protect HTML comments <!-- ... -->.
     */
    private function protectHtmlComments(string $content): string
    {
        $pattern = '/<!--[\s\S]*?-->/';
        preg_match_all($pattern, $content, $matches);

        if (!empty($matches[0])) {
            foreach ($matches[0] as $value) {
                if ($value !== '') {
                    $placeholder = $this->makePlaceholder('html_comment');
                    $content = str_replace($value, $placeholder, $content);
                    $this->commentReplacements[$placeholder] = $value;
                }
            }
        }

        return $content;
    }

    /**
     * Protect Blade comments {{-- ... --}}.
     */
    private function protectBladeComments(string $content): string
    {
        $pattern = '/\{\{--[\s\S]*?--\}\}/';
        preg_match_all($pattern, $content, $matches);

        if (!empty($matches[0])) {
            foreach ($matches[0] as $value) {
                if ($value !== '') {
                    $placeholder = $this->makePlaceholder('blade_comment');
                    $content = str_replace($value, $placeholder, $content);
                    $this->commentReplacements[$placeholder] = $value;
                }
            }
        }

        return $content;
    }

    /**
     * Generate a unique placeholder string.
     */
    private function makePlaceholder(string $type): string
    {
        $this->counter++;
        return '<mw-protected data-type="' . $type . '" data-id="' . $this->counter . '"/>';
    }
}
