<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Browse / search the Font Awesome icon set the Live-Edit icon picker offers,
 * so the model can discover a VALID icon name before placing one.
 *
 * Sourced from the exact same list the visual icon picker uses
 * (packages/frontend-assets/resources/assets/components/icon_selector.js — the
 * `fontAwesome` set), so every name returned is one that actually renders in the
 * vendored FA6 bundle (including the two FA4-only glyphs ported into it,
 * `tripadvisor` and `meanpath`). The model pairs this with set_icon
 * (set/change an element's icon) or drops `<i class="fa fa-NAME"></i>` into an
 * add_section. Read-only, no side effects.
 */
class SearchIconsTool extends BaseTool
{
    protected string $domain = 'liveedit';

    /** Parsed icon-name cache (the picker list is static per request). */
    private static ?array $iconCache = null;

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'search_icons',
            'Search the Font Awesome icons available in this site (the same set the '
            . 'icon picker offers) to find a valid icon NAME to use. Pass a query '
            . 'like "rocket", "cart", "arrow", "social", "phone" and get back '
            . 'matching names (e.g. "cart-shopping", "rocket", "tripadvisor"). Use '
            . 'the returned name with set_icon, or as <i class="fa fa-NAME"></i> '
            . 'inside add_section. Always search here first instead of guessing a '
            . 'name — a wrong name renders an empty box.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'query',
                type: PropertyType::STRING,
                description: 'What the icon should depict, e.g. "rocket", "shopping cart", '
                    . '"phone", "star". Matches icon names (hyphens or spaces both work). '
                    . 'Empty returns a short sample of common icons.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of names to return (default 40, max 100).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $query = strtolower(trim((string) ($args['query'] ?? '')));
        // Normalise "shopping cart" / "fa-cart" -> "cart" tokens for matching.
        $query = ltrim(preg_replace('/^fa[sbrl]?-?/', '', $query) ?? $query, '-');
        $query = str_replace(' ', '-', $query);

        $limit = (int) ($args['limit'] ?? 40);
        if ($limit < 1) {
            $limit = 40;
        }
        $limit = min($limit, 100);

        $icons = self::icons();
        if (empty($icons)) {
            return $this->handleError('The icon list could not be read.');
        }

        if ($query === '') {
            $sample = ['rocket', 'star', 'heart', 'check', 'arrow-right', 'phone',
                'envelope', 'cart-shopping', 'user', 'magnifying-glass', 'bars',
                'house', 'gear', 'bolt', 'shield', 'circle-check'];
            $sample = array_values(array_intersect($sample, $icons));
            return json_encode([
                'count' => count($sample),
                'total_available' => count($icons),
                'icons' => $sample,
                'hint' => 'Pass a query (e.g. "cart", "social", "arrow") to search all '
                    . count($icons) . ' icons.',
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        // Rank: exact name, then prefix, then substring — so the best match leads.
        $exact = [];
        $prefix = [];
        $contains = [];
        foreach ($icons as $name) {
            if ($name === $query) {
                $exact[] = $name;
            } elseif (str_starts_with($name, $query)) {
                $prefix[] = $name;
            } elseif (str_contains($name, $query)) {
                $contains[] = $name;
            }
        }
        $matches = array_slice(array_merge($exact, $prefix, $contains), 0, $limit);

        return json_encode([
            'query' => $query,
            'count' => count($matches),
            'icons' => $matches,
            'usage' => 'set_icon(selector, icon="' . ($matches[0] ?? 'NAME') . '")'
                . ' or <i class="fa fa-' . ($matches[0] ?? 'NAME') . '"></i>',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * The icon names the picker offers — parsed once from icon_selector.js so the
     * model's choices stay in lockstep with the visual picker and the bundle.
     */
    private static function icons(): array
    {
        if (self::$iconCache !== null) {
            return self::$iconCache;
        }
        self::$iconCache = [];

        $path = base_path(
            'packages/frontend-assets/resources/assets/components/icon_selector.js'
        );
        if (!is_file($path) || !is_readable($path)) {
            return self::$iconCache;
        }
        $js = (string) file_get_contents($path);

        // The FA list is a single `const icons = [ '...','...' ];` array. Grab it,
        // then pull every single-quoted token out of that slice only.
        if (!preg_match('/const\s+icons\s*=\s*\[(.*?)\]\s*;/s', $js, $m)) {
            return self::$iconCache;
        }
        if (preg_match_all("/'([a-z0-9][a-z0-9-]*)'/", $m[1], $names)) {
            // Unique, preserve first-seen order.
            self::$iconCache = array_values(array_unique($names[1]));
        }

        return self::$iconCache;
    }
}
