<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-94 / AI-83 / TICKET-WW — Image picker AI tab CSP + a11y
 * regression coverage.
 *
 * Pins:
 *   - The 6+ inline `style=""` attributes in the AI tab's HTML
 *     template string (built inside `filepicker.js` ai: function)
 *     are gone — replaced by class-based styling so strict
 *     `style-src 'self'` is satisfied.
 *   - The aspect-ratio swatch lookup uses per-ratio classes
 *     (`mw-aspect-ratio-${slug}`) instead of an inline
 *     `style="aspect-ratio: ${o.css}; ..."`. The new option also
 *     carries a `data-aspect-ratio` attribute exposing the raw
 *     value to CSS / JS hooks.
 *   - The Generate button gains `aria-busy="true"` while the model
 *     runs and links to a sibling `<output role="status"
 *     aria-live="polite">` so screen-reader users hear "Generating
 *     image…" / "Image generated".
 *   - The corresponding CSS rules exist in default.css.
 *
 * Style after the cycle-52..93 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class ImagePickerAiTabCspContractTest extends TestCase
{
    private const PICKER_JS  = 'packages/frontend-assets/resources/assets/components/filepicker.js';
    private const PICKER_CSS = 'packages/frontend-assets/resources/assets/css/microweber/css/default.css';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    /**
     * Return only the AI tab's source slice so audit-trail comments in
     * other parts of the file (e.g. the URL tab cycle-83 prior fix)
     * don't leak into the negative assertions.
     */
    private function aiTabSource(): string
    {
        $src = $this->read(self::PICKER_JS);
        $start = strpos($src, 'ai: function ()');
        $this->assertNotFalse($start, 'filepicker.js must define an `ai: function ()` block');

        // Walk forward to the matching `desktop: function ()` which is
        // the next sibling component.
        $end = strpos($src, 'desktop: function ()', $start);
        $this->assertNotFalse($end, 'filepicker.js must define a `desktop: function ()` block AFTER `ai:`');

        return substr($src, $start, $end - $start);
    }

    #[Test]
    public function ai_tab_drops_inline_style_attributes(): void
    {
        $aiTab = $this->aiTabSource();

        // Strip JS comments first because the audit-trail comment
        // mentions the literal pre-fix `style="..."` strings.
        $stripped = preg_replace('!//.*$!m', '', $aiTab);
        $stripped = preg_replace('!/\\*[\\s\\S]*?\\*/!', '', $stripped);

        // Each of the 5 pre-fix inline-style sites must be gone.
        $this->assertStringNotContainsString(
            'style="max-width: 400px;margin: auto"',
            $stripped,
            'AI tab: inline `style="max-width: 400px;margin: auto"` must be replaced by `mw-filepicker-ai-tab-wrap` class'
        );
        $this->assertStringNotContainsString(
            'style="width: 200px"',
            $stripped,
            'AI tab: inline `style="width: 200px"` must be replaced by `mw-filepicker-ai-field-narrow` class'
        );
        $this->assertStringNotContainsString(
            'style="aspect-ratio:',
            $stripped,
            'AI tab: inline `style="aspect-ratio: ..."` must be replaced by `mw-aspect-ratio-${slug}` class'
        );
        $this->assertStringNotContainsString(
            'style="display: none"',
            $stripped,
            'AI tab: inline `style="display: none"` on the reference-image preview must be replaced by `d-none` class'
        );
    }

    #[Test]
    public function ai_tab_uses_aspect_ratio_class_and_data_attr(): void
    {
        $aiTab = $this->aiTabSource();

        // Positive: the option emits both the class (CSS hook) and
        // the data-attribute (JS / future-CSS hook).
        $this->assertMatchesRegularExpression(
            '/<option value="\\$\\{o\\.value\\}" data-aspect-ratio="\\$\\{o\\.value\\}">/',
            $aiTab,
            'AI tab: aspect-ratio <option> must carry both `value` and `data-aspect-ratio` attrs'
        );

        $this->assertMatchesRegularExpression(
            '/class="mw-filepicker-aspect-ratio-icon mw-aspect-ratio-\\$\\{o\\.slug\\}"/',
            $aiTab,
            'AI tab: aspect-ratio swatch must use `mw-aspect-ratio-${o.slug}` class lookup'
        );

        // Each aspectRatio entry must define a `slug` field.
        $this->assertMatchesRegularExpression(
            '/\\{ ?value: "16:9", slug: "16-9"/',
            $aiTab,
            'AI tab: aspectRatio entries must declare a kebab-case `slug` for the per-ratio class lookup'
        );
    }

    #[Test]
    public function ai_tab_generate_button_has_aria_busy_and_status_region(): void
    {
        $aiTab = $this->aiTabSource();

        // The Generate <button> must declare aria-describedby pointing
        // at the status output id.
        $this->assertMatchesRegularExpression(
            '/<button[^>]*\\bid="\\$\\{generateId\\}"[^>]*\\baria-describedby="\\$\\{statusId\\}"/',
            $aiTab,
            'AI tab: Generate button must carry aria-describedby="${statusId}" so SR users get the live status'
        );

        // The status region must use role=status + aria-live=polite.
        $this->assertMatchesRegularExpression(
            '/<output[^>]*\\bid="\\$\\{statusId\\}"[^>]*\\brole="status"[^>]*\\baria-live="polite"/',
            $aiTab,
            'AI tab: status region must be <output role="status" aria-live="polite">'
        );

        // The submit() body must toggle aria-busy and write the
        // localized status text on both start and finish.
        $this->assertStringContainsString(
            "generateBtn.setAttribute(\"aria-busy\", \"true\")",
            $aiTab,
            'AI tab: submit() must set aria-busy=true on the Generate button before the model call'
        );
        $this->assertStringContainsString(
            "generateBtn.removeAttribute(\"aria-busy\")",
            $aiTab,
            'AI tab: submit() must remove aria-busy after the model call resolves'
        );
        $this->assertStringContainsString(
            'mw.lang("Generating image…")',
            $aiTab,
            'AI tab: submit() must announce "Generating image…" via the status output'
        );
        $this->assertStringContainsString(
            'mw.lang("Image generated")',
            $aiTab,
            'AI tab: submit() must announce "Image generated" via the status output after success'
        );
    }

    #[Test]
    public function default_css_carries_lifted_ai_tab_rules(): void
    {
        $cssSrc = $this->read(self::PICKER_CSS);

        $this->assertStringContainsString(
            'AI-83 / TICKET-WW (cycle-94',
            $cssSrc,
            'default.css: must carry the AI-83 audit-trail block'
        );

        // The 5 lifted rules must exist.
        $this->assertMatchesRegularExpression(
            '/\\.mw-filepicker-ai-tab-wrap\\s*\\{[^}]*max-width:\\s*400px/',
            $cssSrc,
            'default.css: must carry .mw-filepicker-ai-tab-wrap max-width rule'
        );
        $this->assertMatchesRegularExpression(
            '/\\.mw-filepicker-ai-field-narrow\\s*\\{[^}]*width:\\s*200px/',
            $cssSrc,
            'default.css: must carry .mw-filepicker-ai-field-narrow width rule'
        );
        $this->assertMatchesRegularExpression(
            '/\\.mw-aspect-ratio-16-9\\s*\\{[^}]*aspect-ratio:\\s*16\\s*\\/\\s*9/',
            $cssSrc,
            'default.css: must carry per-ratio class .mw-aspect-ratio-16-9'
        );
        $this->assertMatchesRegularExpression(
            '/\\.mw-filepicker-ai-status\\s*\\{[^}]*clip:\\s*rect\\(/',
            $cssSrc,
            'default.css: must carry visually-hidden status rule (clip:rect(...) sr-only pattern)'
        );
        $this->assertMatchesRegularExpression(
            '/\\.mw-filepicker-ai-generate-btn\\[aria-busy="true"\\]\\s*\\{/',
            $cssSrc,
            'default.css: must carry [aria-busy=true] dim/disabled hint for Generate button'
        );
    }
}
