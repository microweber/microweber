<?php

declare(strict_types=1);

namespace MicroweberPackages\Filament\Support;

final class AdminDisplayName
{
    /**
     * Acronym whitelist. Lowercased keys → desired display form.
     * Add an acronym HERE rather than inline in a resource — single
     * source of truth keeps "AI" vs "Ai" decisions consistent across
     * the admin surface.
     */
    public const ACRONYMS = [
        'ai'   => 'AI',
        'seo'  => 'SEO',
        'faq'  => 'FAQ',
        'url'  => 'URL',
        'ui'   => 'UI',
        'api'  => 'API',
        'css'  => 'CSS',
        'js'   => 'JS',
        'html' => 'HTML',
        'rtl'  => 'RTL',
        'ltr'  => 'LTR',
        'cms'  => 'CMS',
        'csv'  => 'CSV',
        'pdf'  => 'PDF',
        'smtp' => 'SMTP',
        'mx'   => 'MX',
        'cdn'  => 'CDN',
        'svg'  => 'SVG',
        'json' => 'JSON',
        'xml'  => 'XML',
        'id'   => 'ID',
        'gdpr' => 'GDPR',
        'roi'  => 'ROI',
        'kpi'  => 'KPI',
        'gdp'  => 'GDP',
    ];

    /**
     * Normalise a code identifier to a human-readable display name.
     *
     * Pure function: no DB, no Laravel boot required.
     */
    public static function format(?string $raw): string
    {
        $value = trim((string) $raw);
        if ($value === '') {
            return '';
        }
        // Step 1: collapse snake_case + kebab-case + multiple spaces
        // into single-space-separated tokens.
        $spaced = preg_replace('/[\-_]+/', ' ', $value) ?? $value;

        // Step 2: split CamelCase / consecutive-cap acronym runs.
        // Two passes:
        //   (a) insert space before a Capital that follows a lowercase
        //       letter or digit ("layoutContent" → "layout Content")
        //   (b) insert space before a Capital that is followed by a
        //       lowercase AND preceded by another Capital
        //       ("RTLDirection" → "RTL Direction")
        $spaced = preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $spaced) ?? $spaced;
        $spaced = preg_replace('/([A-Z]+)([A-Z][a-z])/', '$1 $2', $spaced) ?? $spaced;

        // Step 3: collapse runs of whitespace, trim.
        $spaced = trim(preg_replace('/\s+/', ' ', $spaced) ?? $spaced);
        if ($spaced === '') {
            return '';
        }

        // Step 4: token-by-token formatting — acronym lookup wins,
        // first token gets Title case, subsequent tokens lowercase.
        $tokens = explode(' ', $spaced);
        $output = [];
        foreach ($tokens as $idx => $token) {
            $lower = mb_strtolower($token);
            if (isset(self::ACRONYMS[$lower])) {
                $output[] = self::ACRONYMS[$lower];
            } elseif ($idx === 0) {
                $output[] = mb_strtoupper(mb_substr($lower, 0, 1)) . mb_substr($lower, 1);
            } else {
                $output[] = $lower;
            }
        }
        return implode(' ', $output);
    }
}
