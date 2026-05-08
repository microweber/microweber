<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-92 / AI-80 / TICKET-AK — Accordion default skin CSP bundle +
 * BS5 markup regression coverage.
 *
 * Pins:
 *   - `Modules/Accordion/resources/views/templates/default.blade.php`
 *     no longer carries any inline `<script>` or `<style>` block —
 *     instead it loads `accordion-skin.js` + `accordion-skin.css`
 *     via a single `@once <link rel="stylesheet"> <script src=...
 *     defer></script>` block.
 *   - The bundles exist at `Modules/Accordion/resources/assets/`
 *     (the public mirror is gitignored on purpose — `php artisan
 *     module:publish` copies them on deploy).
 *   - The accordion header now uses the BS5 canonical structure
 *     `<h5 class="accordion-header"><button class="accordion-button">…</button></h5>`
 *     instead of the pre-fix `<div class="card-header"><button><h5>…</h5></button></div>`
 *     (which was invalid HTML — buttons can't hold flow content).
 *   - The `style="font-size:24px"` inline attribute on the chevron
 *     `<i>` is gone (moved to `.mw-accordion-chevron` rule).
 *
 * Style after the cycle-52..91 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class AccordionCspBundleContractTest extends TestCase
{
    private const ACC_BLADE = 'Modules/Accordion/resources/views/templates/default.blade.php';
    private const ACC_CSS   = 'Modules/Accordion/resources/assets/css/accordion-skin.css';
    private const ACC_JS    = 'Modules/Accordion/resources/assets/js/accordion-skin.js';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function accordion_default_drops_inline_script_and_style(): void
    {
        // Strip Blade {{-- ... --}} comments first so audit-trail
        // text doesn't trigger a false positive.
        $src = $this->read(self::ACC_BLADE);
        $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);

        // Negative: no inline `<style ...>` block.
        $this->assertDoesNotMatchRegularExpression(
            '/<style\\b/',
            $stripped,
            self::ACC_BLADE . ': must NOT contain any inline <style> block (CSP-violation)'
        );

        // Negative: no inline `<script>` block (only `<script src=...>`
        // with a same-origin URL is allowed).
        $this->assertDoesNotMatchRegularExpression(
            '/<script\\b(?![^>]*\\bsrc=)/',
            $stripped,
            self::ACC_BLADE . ': must NOT contain any inline <script> body (CSP-violation)'
        );
    }

    #[Test]
    public function accordion_default_loads_skin_bundles_via_once_block(): void
    {
        $src = $this->read(self::ACC_BLADE);

        $this->assertStringContainsString(
            '@once',
            $src,
            self::ACC_BLADE . ': must wrap the bundle <link>+<script> in @once'
        );
        $this->assertStringContainsString(
            "<link rel=\"stylesheet\" href=\"{{ asset('modules/accordion/css/accordion-skin.css') }}\">",
            $src,
            self::ACC_BLADE . ': must load accordion-skin.css'
        );
        $this->assertStringContainsString(
            "<script src=\"{{ asset('modules/accordion/js/accordion-skin.js') }}\" defer></script>",
            $src,
            self::ACC_BLADE . ': must load accordion-skin.js (with defer so the chevron handler attaches after DOMContentLoaded)'
        );
        $this->assertStringContainsString(
            '@endonce',
            $src,
            self::ACC_BLADE . ': must close the @once with @endonce'
        );
    }

    #[Test]
    public function accordion_default_uses_bs5_h5_button_canonical_markup(): void
    {
        // BS5 docs: `<h5 class="accordion-header"><button class="accordion-button">…</button></h5>`.
        // Pre-fix the heading was inside the button — invalid HTML.
        $src = $this->read(self::ACC_BLADE);
        $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);

        // Positive: h5 wraps the button (with accordion-header class).
        $this->assertMatchesRegularExpression(
            '/<h5\\s[^>]*class="[^"]*accordion-header[^"]*"[^>]*>\\s*<button\\s[^>]*class="[^"]*accordion-button/',
            $stripped,
            self::ACC_BLADE . ': must use BS5 canonical `<h5 class="accordion-header"><button class="accordion-button">…` shape'
        );

        // Negative: button must not contain a nested heading element.
        $this->assertDoesNotMatchRegularExpression(
            '/<button\\s[^>]*>[\\s\\S]*?<h[1-6]\\b/',
            $stripped,
            self::ACC_BLADE . ': button must not wrap a heading (invalid HTML — buttons hold phrasing content only)'
        );
    }

    #[Test]
    public function accordion_default_drops_inline_chevron_font_size(): void
    {
        // Pre-fix the chevron icon carried `style="font-size:24px"` —
        // a CSP `style-src 'self'` violation. Lifted to
        // `.mw-accordion-chevron` rule in the stylesheet.
        $src = $this->read(self::ACC_BLADE);
        $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);

        $this->assertStringNotContainsString(
            'style="font-size:',
            $stripped,
            self::ACC_BLADE . ': inline `style="font-size:..."` on chevron must be gone'
        );

        $cssSrc = $this->read(self::ACC_CSS);
        $this->assertMatchesRegularExpression(
            '/\\.mw-accordion-chevron\\s*\\{[^}]*font-size:\\s*24px/',
            $cssSrc,
            self::ACC_CSS . ': must carry the lifted `.mw-accordion-chevron { font-size: 24px }` rule'
        );
    }

    #[Test]
    public function accordion_skin_assets_carry_audit_trail(): void
    {
        $cssSrc = $this->read(self::ACC_CSS);
        $jsSrc  = $this->read(self::ACC_JS);

        $this->assertStringContainsString(
            'AI-80 / TICKET-AK (cycle-92',
            $cssSrc,
            self::ACC_CSS . ': must carry the AI-80 audit-trail header'
        );
        $this->assertStringContainsString(
            'AI-80 / TICKET-AK (cycle-92',
            $jsSrc,
            self::ACC_JS . ': must carry the AI-80 audit-trail header'
        );
    }

    #[Test]
    public function accordion_skin_js_uses_delegated_listener_not_per_instance(): void
    {
        // Pre-fix the inline script bound to `#accordion-sk-{$params['id']}`
        // per instance. The extracted bundle uses ONE document-level
        // jQuery delegated handler against
        // `.mw-accordion-faq-skin-card > .collapse` so multi-instance
        // pages don't bind 5+ duplicate handlers.
        $jsSrc = $this->read(self::ACC_JS);

        $this->assertMatchesRegularExpression(
            '/\\$\\(document\\)\\.on\\(\\s*[\'"]shown\\.bs\\.collapse hidden\\.bs\\.collapse[\'"]/',
            $jsSrc,
            self::ACC_JS . ': must use $(document).on(...) delegated binding, not per-id .on()'
        );

        $this->assertStringContainsString(
            ".mw-accordion-faq-skin-card > .collapse",
            $jsSrc,
            self::ACC_JS . ': delegate selector must scope to .mw-accordion-faq-skin-card collapse panels'
        );

        // Negative: no per-id selector pattern.
        $this->assertDoesNotMatchRegularExpression(
            '/\\$\\(\'#accordion-sk-/',
            $jsSrc,
            self::ACC_JS . ': must NOT bind per-instance `#accordion-sk-…` selectors (drift from the cycle-29 multi-instance fix)'
        );
    }
}
