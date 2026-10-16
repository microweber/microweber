<?php

namespace MicroweberPackages\App\Utils\ParserHelpers;

/**
 * Quote-aware tokenizer for <module .../> tags in HTML.
 *
 * Replaces the brittle /<module[^>]*>/ regex with a state-machine
 * approach that correctly handles:
 *  - `>` inside quoted attribute values (e.g. title="a > b")
 *  - `<` inside quoted attribute values (e.g. data-tpl="a<b")
 *  - Tags spanning multiple lines
 *  - Self-closing (/>) and non-self-closing module tags
 *  - Odd whitespace (tabs, multiple spaces)
 */
class TagLexer
{
    /**
     * Find all <module ...> or <module .../> tags in the given HTML.
     *
     * Returns an array of associative arrays with keys:
     *   'tag'    => the full tag string including < and >
     *   'offset' => byte offset in the input where the tag starts
     *   'self_closing' => bool, true if ends with />
     *
     * @param string $html
     * @return array<int, array{tag: string, offset: int, self_closing: bool}>
     */
    public function findModuleTags(string $html): array
    {
        $tags = [];
        $len = strlen($html);
        $pos = 0;

        while ($pos < $len) {
            // Find next potential <module (case-insensitive)
            $start = stripos($html, '<module', $pos);
            if ($start === false) {
                break;
            }

            // Check the character after "module" — must be whitespace, / or >
            // to distinguish from e.g. <moduleFoo
            $afterModule = $start + 7; // strlen('<module') = 7
            if ($afterModule < $len) {
                $ch = $html[$afterModule];
                if ($ch !== ' ' && $ch !== "\t" && $ch !== "\n" && $ch !== "\r"
                    && $ch !== '/' && $ch !== '>') {
                    $pos = $afterModule;
                    continue;
                }
            }

            // Now scan forward respecting quotes
            $i = $afterModule;
            $inQuote = null; // null = not in quote, '"' or "'" = in quote
            $found = false;
            $selfClosing = false;

            while ($i < $len) {
                $c = $html[$i];

                if ($inQuote !== null) {
                    // Inside a quoted string — only the matching quote exits
                    if ($c === $inQuote) {
                        $inQuote = null;
                    }
                    // Handle escaped quotes: \"  inside "..."
                    elseif ($c === '\\' && ($i + 1) < $len) {
                        $i++; // skip the escaped character
                    }
                } else {
                    // Outside quotes
                    if ($c === '"' || $c === "'") {
                        $inQuote = $c;
                    } elseif ($c === '>') {
                        // Check if self-closing
                        $selfClosing = ($i > 0 && $html[$i - 1] === '/');
                        $found = true;
                        break;
                    } elseif ($c === '<') {
                        // Another tag starts before this one closed — malformed
                        break;
                    }
                }
                $i++;
            }

            if ($found) {
                $tagStr = substr($html, $start, $i - $start + 1);
                $tags[] = [
                    'tag' => $tagStr,
                    'offset' => $start,
                    'self_closing' => $selfClosing,
                ];
                $pos = $i + 1;
            } else {
                // Malformed or unclosed tag — skip past <module
                $pos = $afterModule;
            }
        }

        return $tags;
    }

    /**
     * Check whether the given HTML contains at least one <module> tag.
     */
    public function hasModuleTags(string $html): bool
    {
        return !empty($this->findModuleTags($html));
    }

    /**
     * Extract just the tag strings (convenience method).
     *
     * @return string[]
     */
    public function extractTagStrings(string $html): array
    {
        return array_column($this->findModuleTags($html), 'tag');
    }
}
