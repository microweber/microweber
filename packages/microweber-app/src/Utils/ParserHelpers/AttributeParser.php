<?php

namespace MicroweberPackages\App\Utils\ParserHelpers;

/**
 * Parses HTML-style attribute strings into key→value maps.
 *
 * Fixes over the legacy regex:
 *  - Supports digits in attribute names (data-col-2)
 *  - Handles escaped quotes inside values (title="say \"hi\"")
 *  - Unquoted values are trimmed correctly
 *  - Duplicate attributes: first wins (HTML spec)
 *  - Attribute values containing =, <, > are handled
 */
class AttributeParser
{
    /**
     * Parse an attribute string (or a full <tag ...> string) into key→value pairs.
     *
     * @param string $input  A tag string like '<module type="foo" data-col-2="bar"/>'
     *                       or just the attribute portion 'type="foo" data-col-2="bar"'
     * @return array<string, string>
     */
    public function parse(string $input): array
    {
        $attrs = [];
        $len = strlen($input);
        $pos = 0;

        while ($pos < $len) {
            // Skip whitespace
            $pos = $this->skipWhitespace($input, $pos, $len);
            if ($pos >= $len) {
                break;
            }

            // Skip < tag-name at start, </tag> closing tags, and /> or > at end
            $ch = $input[$pos];
            if ($ch === '<') {
                $pos++;
                // Check for closing tag </...>
                if ($pos < $len && $input[$pos] === '/') {
                    // Skip everything until >
                    while ($pos < $len && $input[$pos] !== '>') {
                        $pos++;
                    }
                    if ($pos < $len) {
                        $pos++; // skip the >
                    }
                } else {
                    // Opening tag — skip the tag name
                    $pos = $this->skipTagName($input, $pos, $len);
                }
                continue;
            }
            if ($ch === '/' || $ch === '>') {
                $pos++;
                continue;
            }

            // Try to read an attribute name
            $nameStart = $pos;
            while ($pos < $len && $this->isAttrNameChar($input[$pos])) {
                $pos++;
            }

            if ($pos === $nameStart) {
                // No attribute name found — skip this character
                $pos++;
                continue;
            }

            $name = substr($input, $nameStart, $pos - $nameStart);

            // Skip whitespace
            $pos = $this->skipWhitespace($input, $pos, $len);

            // Check for = sign
            if ($pos < $len && $input[$pos] === '=') {
                $pos++; // skip =
                $pos = $this->skipWhitespace($input, $pos, $len);

                // Read the value
                if ($pos < $len && ($input[$pos] === '"' || $input[$pos] === "'")) {
                    // Quoted value
                    $quote = $input[$pos];
                    $pos++; // skip opening quote
                    $value = '';
                    while ($pos < $len) {
                        if ($input[$pos] === '\\' && ($pos + 1) < $len && $input[$pos + 1] === $quote) {
                            // Escaped quote
                            $value .= $quote;
                            $pos += 2;
                        } elseif ($input[$pos] === $quote) {
                            $pos++; // skip closing quote
                            break;
                        } else {
                            $value .= $input[$pos];
                            $pos++;
                        }
                    }
                } else {
                    // Unquoted value — read until whitespace or >.
                    // A '/' is kept (so type=shop/products survives), EXCEPT a
                    // trailing '/' that is the tag's self-closing slash
                    // (type=layouts/>  →  layouts, not "layouts/").
                    $valueStart = $pos;
                    while ($pos < $len) {
                        $c = $input[$pos];
                        if ($c === ' ' || $c === "\t" || $c === "\n" || $c === "\r"
                            || $c === '>') {
                            break;
                        }
                        // Treat '/' as the end of the value only when it is the
                        // self-closing slash, i.e. immediately followed by '>'
                        // or by trailing whitespace/end of input.
                        if ($c === '/') {
                            $next = ($pos + 1) < $len ? $input[$pos + 1] : '';
                            if ($next === '' || $next === '>' || $next === ' '
                                || $next === "\t" || $next === "\n" || $next === "\r") {
                                break;
                            }
                        }
                        $pos++;
                    }
                    $value = substr($input, $valueStart, $pos - $valueStart);
                }
            } else {
                // Boolean attribute (no value)
                $value = $name;
            }

            // First wins for duplicate attributes (HTML spec)
            if (!array_key_exists($name, $attrs)) {
                $attrs[$name] = $value;
            }
        }

        return $attrs;
    }

    /**
     * Check if a character is valid in an attribute name.
     * Allows: a-z, A-Z, 0-9, -, _
     */
    private function isAttrNameChar(string $ch): bool
    {
        return ($ch >= 'a' && $ch <= 'z')
            || ($ch >= 'A' && $ch <= 'Z')
            || ($ch >= '0' && $ch <= '9')
            || $ch === '-'
            || $ch === '_';
    }

    private function skipWhitespace(string $input, int $pos, int $len): int
    {
        while ($pos < $len && ($input[$pos] === ' ' || $input[$pos] === "\t"
                || $input[$pos] === "\n" || $input[$pos] === "\r")) {
            $pos++;
        }
        return $pos;
    }

    private function skipTagName(string $input, int $pos, int $len): int
    {
        // Skip the tag name (e.g., "module", "div")
        while ($pos < $len && $input[$pos] !== ' ' && $input[$pos] !== "\t"
            && $input[$pos] !== "\n" && $input[$pos] !== "\r"
            && $input[$pos] !== '>' && $input[$pos] !== '/') {
            $pos++;
        }
        return $pos;
    }

    /**
     * Convenience: get the module type from parsed attributes.
     * Checks 'type', 'data-type', 'module', 'data-module' in priority order.
     */
    public function getModuleType(array $attrs): ?string
    {
        return $attrs['type'] ?? $attrs['data-type'] ?? $attrs['module'] ?? $attrs['data-module'] ?? null;
    }

    /**
     * Convenience: get edit field attributes.
     */
    public function getEditFieldAttributes(array $attrs): array
    {
        $field = $attrs['field'] ?? $attrs['data-field'] ?? null;
        $rel = $attrs['rel'] ?? $attrs['data-rel'] ?? null;
        $relId = $attrs['rel_id'] ?? $attrs['rel-id'] ?? $attrs['data-rel-id'] ?? $attrs['data-id'] ?? null;

        return [
            'field' => $field,
            'rel' => $rel,
            'rel_id' => $relId,
        ];
    }
}
