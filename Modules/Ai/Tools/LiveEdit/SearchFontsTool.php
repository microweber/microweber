<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Browse / search the font catalog the site's font picker offers (Google +
 * system fonts), so the model can pick a REAL font family before applying one.
 *
 * Sourced from TemplateFontsManager::getAvailableFonts() — the exact catalog the
 * visual picker reads (~1087 Google families) — so a returned family always
 * resolves. Pair with set_font (enable + apply to the design). Read-only.
 */
class SearchFontsTool extends BaseTool
{
    protected string $domain = 'fonts';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'search_fonts',
            'Search the fonts available on this site (the same catalog the font '
            . 'picker offers) to find a valid font family NAME to use. Pass a query '
            . 'like "poppins", "serif", "handwriting", "mono" and get back matching '
            . 'families (with their category). Use a returned family with set_font. '
            . 'Always search here first instead of guessing a font name.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'query',
                type: PropertyType::STRING,
                description: 'Font name or style to look for, e.g. "poppins", "roboto", '
                    . '"serif", "display". Empty returns a short sample of popular fonts.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of families to return (default 40, max 100).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $query = strtolower(trim((string) ($args['query'] ?? '')));
        $limit = (int) ($args['limit'] ?? 40);
        if ($limit < 1) {
            $limit = 40;
        }
        $limit = min($limit, 100);

        $manager = $this->manager();
        if ($manager === null) {
            return $this->handleError('The font manager is not available on this site.');
        }

        // family (lowercased) => ['family' => original, 'category' => ...]
        $catalog = [];
        foreach ($manager->getAvailableFonts() as $font) {
            $family = is_array($font) ? (string) ($font['family'] ?? '') : '';
            if ($family === '') {
                continue;
            }
            $key = strtolower($family);
            if (!isset($catalog[$key])) {
                $catalog[$key] = [
                    'family' => $family,
                    'category' => is_array($font) && isset($font['category']) ? $font['category'] : null,
                ];
            }
        }
        if ($catalog === []) {
            return $this->handleError('The font catalog could not be read.');
        }

        if ($query === '') {
            $sample = ['Poppins', 'Roboto', 'Montserrat', 'Open Sans', 'Lato', 'Inter',
                'Playfair Display', 'Merriweather', 'Oswald', 'Raleway', 'Nunito', 'Jost'];
            $present = [];
            foreach ($sample as $f) {
                if (isset($catalog[strtolower($f)])) {
                    $present[] = $catalog[strtolower($f)];
                }
            }

            return json_encode([
                'count' => count($present),
                'total_available' => count($catalog),
                'fonts' => $present,
                'hint' => 'Pass a query (e.g. "serif", "poppins", "mono") to search all '
                    . count($catalog) . ' fonts.',
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        // Rank: exact, then prefix, then substring (match family or category).
        $exact = [];
        $prefix = [];
        $contains = [];
        foreach ($catalog as $key => $row) {
            $cat = strtolower((string) ($row['category'] ?? ''));
            if ($key === $query) {
                $exact[] = $row;
            } elseif (str_starts_with($key, $query)) {
                $prefix[] = $row;
            } elseif (str_contains($key, $query) || $cat === $query) {
                // Category only on an exact match, so "serif" returns serif fonts,
                // not every "sans-serif" one.
                $contains[] = $row;
            }
        }
        $matches = array_slice(array_merge($exact, $prefix, $contains), 0, $limit);

        return json_encode([
            'query' => $query,
            'count' => count($matches),
            'fonts' => $matches,
            'usage' => 'set_font(family="' . ($matches[0]['family'] ?? 'NAME') . '", scope="all")',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function manager(): ?TemplateFontsManager
    {
        if (!class_exists(TemplateFontsManager::class) || !app()->bound(TemplateFontsManager::class)) {
            return null;
        }

        try {
            return app(TemplateFontsManager::class);
        } catch (\Throwable) {
            return null;
        }
    }
}
