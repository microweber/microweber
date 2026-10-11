<?php

declare(strict_types=1);

namespace MicroweberPackages\Filament\Support;

/**
 * task-2026-05-17-4905c8 / AI-785 — code-identifier → display-name helper.
 * Jira: https://microweber.atlassian.net/browse/AI-785
 *
 * Designer's R10-2 audit caught CamelCase code identifiers leaking
 * to the user surface in the Modules grid (e.g. "LayoutContent" /
 * "AiWizard" / "CustomFields"). These are folder names from
 * `Modules/<Name>/` that get passed through to admin form labels +
 * marketplace cards without ever going through a display layer.
 *
 * This helper centralises the camelCase → "Display name" transform
 * so every admin form that surfaces raw module identifiers can call
 * one canonical function. Designer's recurring "small refactor
 * opportunity to enforce a display-name layer at FormResource +
 * Module config layer" — first installment.
 *
 * **Rules:**
 *   - Insert a space before each uppercase letter (except the first).
 *   - Lowercase every word EXCEPT a known-acronym whitelist (AI, SEO,
 *     FAQ, URL, UI, API, CSS, JS, HTML, RTL, LTR, CMS, CSV, PDF, SMTP,
 *     MX, CDN, SVG, JSON, XML, ID, GDPR, ROI, KPI, GDP).
 *   - Acronym-prefix CamelCase like "AiWizard" → tokenise on the
 *     boundary before "Wizard"; recognise "Ai" as `AI`; result is
 *     "AI Wizard".
 *   - snake_case + kebab-case + spaces all normalise to the same
 *     output as the CamelCase equivalent so a resource calling the
 *     helper on already-normalised input is idempotent.
 *
 * **Examples:**
 *   - "LayoutContent"  → "Layout content"
 *   - "AiWizard"       → "AI wizard"
 *   - "CustomFields"   → "Custom fields"
 *   - "SeoSettings"    → "SEO settings"
 *   - "Faq"            → "FAQ"
 *   - "RTLDirection"   → "RTL direction"   (consecutive caps treated as acronym run)
 *   - "post"           → "Post"            (idempotent)
 *   - "my_first_post"  → "My first post"
 *   - "header-menu"    → "Header menu"
 */
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
