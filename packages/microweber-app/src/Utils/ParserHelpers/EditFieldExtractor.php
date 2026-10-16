<?php

namespace MicroweberPackages\App\Utils\ParserHelpers;

/**
 * Extracts and identifies editable field regions from HTML markup.
 *
 * An edit field is any element with class="edit" and attributes:
 *   - field="..."  (or data-field)
 *   - rel="..."    (or data-rel): content, global, inherit, module, page, post
 *   - rel-id="..." (or rel_id, data-rel-id, data-id): optional content/category ID
 *
 * This class handles:
 *  - Finding all .edit regions in the HTML
 *  - Extracting their field/rel/rel_id attributes
 *  - Determining scoping (content vs global vs inherit vs module)
 *  - Detecting nested edit fields
 */
class EditFieldExtractor
{
    private AttributeParser $attrParser;

    public function __construct(?AttributeParser $attrParser = null)
    {
        $this->attrParser = $attrParser ?? new AttributeParser();
    }

    /**
     * Find all edit field opening tags in the HTML.
     *
     * Returns an array of associative arrays:
     *   [
     *     'tag'      => full opening tag string,
     *     'field'    => field name,
     *     'rel'      => rel type (content, global, inherit, module, page, post),
     *     'rel_id'   => rel ID or null,
     *     'offset'   => byte offset in the HTML,
     *   ]
     *
     * @param string $html
     * @return array
     */
    public function findEditFields(string $html): array
    {
        $fields = [];

        // Match elements with class containing "edit"
        // This uses a regex to find opening tags with class="...edit..."
        $pattern = '/<([a-z][a-z0-9]*)\s[^>]*class\s*=\s*["\'][^"\']*\bedit\b[^"\']*["\'][^>]*>/is';
        preg_match_all($pattern, $html, $matches, PREG_OFFSET_CAPTURE);

        if (!empty($matches[0])) {
            foreach ($matches[0] as $idx => $match) {
                $tag = $match[0];
                $offset = $match[1];
                $tagName = $matches[1][$idx][0] ?? 'div';

                $attrs = $this->attrParser->parse($tag);
                $efAttrs = $this->attrParser->getEditFieldAttributes($attrs);

                if ($efAttrs['field'] !== null) {
                    // 'end' = byte offset just past this element's matching
                    // close tag, so callers can tell whether a module is truly
                    // INSIDE the field (open < pos < end) rather than merely
                    // after the open tag (which mis-scopes a closed sibling).
                    $end = $this->findMatchingClose($html, $tagName, $offset + strlen($tag));

                    $fields[] = [
                        'tag' => $tag,
                        'tag_name' => $tagName,
                        'field' => $efAttrs['field'],
                        'rel' => $efAttrs['rel'] ?? 'page',
                        'rel_id' => $efAttrs['rel_id'],
                        'offset' => $offset,
                        'end' => $end,
                    ];
                }
            }
        }

        return $fields;
    }

    /**
     * Find the byte offset just past the matching close tag for an element,
     * honouring nesting of same-named tags. Returns strlen($html) if the
     * element is unbalanced (treat the remainder as inside).
     */
    private function findMatchingClose(string $html, string $tagName, int $from): int
    {
        $len = strlen($html);
        $tagName = strtolower($tagName);
        $openNeedle = '<' . $tagName;
        $closeNeedle = '</' . $tagName;
        $depth = 1;
        $i = $from;

        while ($i < $len) {
            $nextOpen = stripos($html, $openNeedle, $i);
            $nextClose = stripos($html, $closeNeedle, $i);

            if ($nextClose === false) {
                return $len; // unbalanced
            }

            if ($nextOpen !== false && $nextOpen < $nextClose) {
                // Only count it as a nested open if it's a real tag boundary
                // (next char is whitespace, >, or /), not e.g. <divider>.
                $after = $nextOpen + strlen($openNeedle);
                $ch = $after < $len ? $html[$after] : '>';
                if ($ch === ' ' || $ch === "\t" || $ch === "\n" || $ch === "\r"
                    || $ch === '>' || $ch === '/') {
                    $depth++;
                }
                $i = $after;
            } else {
                $depth--;
                $i = $nextClose + strlen($closeNeedle);
                if ($depth === 0) {
                    $gt = strpos($html, '>', $i);
                    return $gt === false ? $len : $gt + 1;
                }
            }
        }

        return $len;
    }

    /**
     * Check if the HTML contains any edit fields that need parsing.
     */
    public function hasEditFields(string $html): bool
    {
        return !empty($this->findEditFields($html));
    }

    /**
     * Determine the content ID for a given edit field based on its rel type.
     *
     * @param string      $rel        The rel type
     * @param string|null $relId      Explicit rel-id from the tag
     * @param int|null    $currentContentId  The current page/content ID
     * @param callable|null $getInheritedParent  Callback to resolve inherited parent
     * @return int|null
     */
    public function resolveContentId(
        string   $rel,
        ?string  $relId = null,
        ?int     $currentContentId = null,
        ?callable $getInheritedParent = null
    ): ?int {
        switch ($rel) {
            case 'content':
            case 'page':
            case 'post':
            case 'product':
                if ($relId !== null && $relId !== '') {
                    return (int)$relId;
                }
                return $currentContentId;

            case 'inherit':
                $baseId = ($relId !== null && $relId !== '') ? (int)$relId : $currentContentId;
                if ($getInheritedParent !== null && $baseId !== null) {
                    $inherited = $getInheritedParent($baseId);
                    return $inherited ?: $baseId;
                }
                return $baseId;

            case 'global':
                return null;

            case 'module':
                return null;

            default:
                return $currentContentId;
        }
    }

    /**
     * Determine the scope key for module ID allocation.
     *
     * @param string   $rel
     * @param int|null $contentId
     * @return string
     */
    public function getScopeKey(string $rel, ?int $contentId): string
    {
        if ($rel === 'global' || $rel === 'module' || $contentId === null) {
            return 'global';
        }
        return (string)$contentId;
    }
}
