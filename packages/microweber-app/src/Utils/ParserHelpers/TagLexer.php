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
            $open = $this->scanOpeningTag($html, $pos, $len);
            if ($open === null) {
                break;
            }

            [$start, $openEnd, $selfClosing] = $open;

            if ($selfClosing) {
                // <module ... /> — the tag is just the self-closing element.
                $tags[] = [
                    'tag' => substr($html, $start, $openEnd - $start + 1),
                    'offset' => $start,
                    'self_closing' => true,
                ];
                $pos = $openEnd + 1;
                continue;
            }

            // Non-self-closing <module ...>. A module tag is replaced ENTIRELY by
            // the module's rendered output (the module renders from its own
            // settings, not from inner HTML), so the matched tag must span the
            // whole <module ...> ... </module> element — otherwise only the
            // opening is replaced and the </module> is left behind (the "dangling
            // module close tag" bug, seen when saved edit-field content is injected
            // after the ></module> normalization has already run). Find the
            // MATCHING </module>, counting nested non-self-closing <module ...>
            // opens so "modules in modules" close at the right depth.
            $closeEnd = $this->findMatchingClose($html, $openEnd + 1, $len);
            if ($closeEnd !== null) {
                $tags[] = [
                    'tag' => substr($html, $start, $closeEnd - $start),
                    'offset' => $start,
                    'self_closing' => false,
                ];
                $pos = $closeEnd;
            } else {
                // No matching </module> — treat as the opening tag alone (legacy
                // behaviour) so a lone/opening-only tag still renders.
                $tags[] = [
                    'tag' => substr($html, $start, $openEnd - $start + 1),
                    'offset' => $start,
                    'self_closing' => false,
                ];
                $pos = $openEnd + 1;
            }
        }

        return $tags;
    }

    /**
     * Scan from $pos for the next `<module ...>` opening tag. Returns
     * [startOffset, endOffset (the closing `>`), isSelfClosing] or null when no
     * well-formed module opening is found before end of input.
     *
     * @return array{0:int,1:int,2:bool}|null
     */
    private function scanOpeningTag(string $html, int $pos, int $len): ?array
    {
        while ($pos < $len) {
            $start = stripos($html, '<module', $pos);
            if ($start === false) {
                return null;
            }

            // Char after "module" must be whitespace, / or > (not e.g. <moduleFoo).
            $afterModule = $start + 7; // strlen('<module')
            if ($afterModule < $len) {
                $ch = $html[$afterModule];
                if ($ch !== ' ' && $ch !== "\t" && $ch !== "\n" && $ch !== "\r"
                    && $ch !== '/' && $ch !== '>') {
                    $pos = $afterModule;
                    continue;
                }
            }

            // Scan forward to the closing '>' respecting quotes.
            $i = $afterModule;
            $inQuote = null;
            while ($i < $len) {
                $c = $html[$i];
                if ($inQuote !== null) {
                    if ($c === $inQuote) {
                        $inQuote = null;
                    } elseif ($c === '\\' && ($i + 1) < $len) {
                        $i++;
                    }
                } else {
                    if ($c === '"' || $c === "'") {
                        $inQuote = $c;
                    } elseif ($c === '>') {
                        $selfClosing = ($i > 0 && $html[$i - 1] === '/');

                        return [$start, $i, $selfClosing];
                    } elseif ($c === '<') {
                        // Another tag opens before this one closed — malformed.
                        break;
                    }
                }
                $i++;
            }

            // Malformed/unclosed — skip past this <module and keep looking.
            $pos = $afterModule;
        }

        return null;
    }

    /**
     * Find the end offset (one past the final '>') of the `</module>` that closes
     * an opening tag whose '>' ended at $from - 1, counting nested
     * non-self-closing `<module ...>` opens so nested modules match at the right
     * depth. Returns null when the element is unbalanced (no matching close).
     */
    private function findMatchingClose(string $html, int $from, int $len): ?int
    {
        $depth = 1;
        $pos = $from;

        while ($pos < $len) {
            $nextClose = stripos($html, '</module', $pos);
            if ($nextClose === false) {
                return null; // unbalanced
            }
            // Only a well-formed </module> (optionally with whitespace) counts.
            $afterClose = $this->matchClosingTagEnd($html, $nextClose, $len);

            $nextOpen = $this->scanOpeningTag($html, $pos, $len);

            // If an opening tag starts before the next close, it nests one deeper
            // (self-closing opens do not change depth).
            if ($nextOpen !== null && $nextOpen[0] < $nextClose) {
                if (!$nextOpen[2]) {
                    $depth++;
                }
                $pos = $nextOpen[1] + 1;
                continue;
            }

            if ($afterClose === null) {
                // "</module" that is not a real closing tag — skip it.
                $pos = $nextClose + 8; // strlen('</module')
                continue;
            }

            $depth--;
            if ($depth === 0) {
                return $afterClose;
            }
            $pos = $afterClose;
        }

        return null;
    }

    /**
     * If a well-formed `</module\s*>` starts at $pos, return the offset one past
     * its '>'; otherwise null.
     */
    private function matchClosingTagEnd(string $html, int $pos, int $len): ?int
    {
        $i = $pos + 8; // strlen('</module')
        while ($i < $len && ($html[$i] === ' ' || $html[$i] === "\t" || $html[$i] === "\n" || $html[$i] === "\r")) {
            $i++;
        }
        if ($i < $len && $html[$i] === '>') {
            return $i + 1;
        }

        return null;
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
