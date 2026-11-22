<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit\Concerns;

use MicroweberPackages\TemplateCustomCss\Facades\TemplateCustomCss;

/**
 * Shared by the Live-Edit tools that take model-authored HTML (add_section,
 * edit_html): an inline <script> never belongs in content HTML. The editor
 * canvas strips it (so the JS is silently lost), and a half-streamed <script>
 * chunk can corrupt the surrounding markup so attributes leak as visible text.
 *
 * These helpers EXTRACT inline JS from an HTML string and route it to the site
 * custom-JS layer (the apply_js file, userfiles/css/<Template>/live_edit.js) so
 * it is persisted and runs on the PUBLISHED page, returning the script-free HTML.
 * Kept in one place so the two tools can never drift apart.
 */
trait RoutesInlineJsToSiteJs
{
    /**
     * Remove every <script> from $html; the collected inner JS is returned by
     * reference in $extractedJs (trimmed). Returns the script-free HTML.
     */
    protected function stripAndCollectInlineScripts(string $html, string &$extractedJs): string
    {
        $extractedJs = '';
        $html = (string) preg_replace_callback(
            '~<script\b[^>]*>([\s\S]*?)</script\s*>~i',
            function ($m) use (&$extractedJs) {
                $extractedJs .= trim((string) ($m[1] ?? '')) . "\n";

                return '';
            },
            $html
        );
        // Drop any stray/unclosed <script ...> opening tag the pass above missed.
        $html = (string) preg_replace('~</?script[^>]*>~i', '', $html);
        $extractedJs = trim($extractedJs);

        return $html;
    }

    /**
     * Append JavaScript to the site custom-JS layer — the same destination as the
     * apply_js tool, so it is persisted and served (deferred, footer) on the
     * PUBLISHED page only. Best-effort: a failure here must never fail the
     * caller's edit. Returns true when something was actually saved.
     */
    protected function routeSectionJsToSiteJs(string $js): bool
    {
        $js = trim((string) preg_replace('~</?script[^>]*>~i', '', $js));
        if ($js === '') {
            return false;
        }

        try {
            $template = (string) (function_exists('template_name')
                ? template_name()
                : (function_exists('get_option') ? get_option('current_template', 'template') : ''));

            $manager = TemplateCustomCss::liveEdit();
            $existing = (string) $manager->getLiveEditJsContent($template);

            // Accumulate inside a marker block, mirroring ApplyJsTool so the
            // custom-JS file stays tidy across multiple calls.
            $block = "/* MW-AI-JS */\n" . $js . "\n/* /MW-AI-JS */";
            $combined = $existing !== '' ? rtrim($existing) . "\n\n" . $block . "\n" : $block . "\n";

            $manager->saveLiveEditJsContent($combined, $template === '' ? null : $template);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
