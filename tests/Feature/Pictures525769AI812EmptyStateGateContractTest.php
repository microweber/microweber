<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-525769 / AI-812 — Pictures module empty-state
 * frontend leak fix across ALL 34 templates.
 *
 * Jira: https://microweber.atlassian.net/browse/AI-812
 * Priority: Medium
 *
 * Pre-fix: `<p class="mw-pictures-clean">No pictures added. Please
 * add pictures to the module.</p>` was rendered WITHOUT an
 * is_admin() gate. Anonymous visitors saw the admin-targeted copy
 * on any page with an empty Pictures module. Convention violation
 * — AI-104 + AI-780/780a/801/808 pattern gates editor placeholders
 * with is_admin().
 *
 * Recon delta: designer named default.blade.php; recon found the
 * same defect across 34 Pictures templates (project-wide leak).
 * Same precedent as AI-809 4-vs-6 delta — fix all hits in one
 * coherent slice per the legacy-Blade audit class lesson.
 *
 * Post-fix per template:
 *   @if (empty($data))
 *       @if (is_admin())
 *           <div class="mw-canvas-empty-state" data-mw-ai780-content-type="picture">
 *               <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
 *               <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
 *               <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
 *           </div>
 *       @endif
 *   @else
 *       ... existing render preserved ...
 *
 * Selector-self-match guard family (now 18+ session-recurrences):
 * the docblock above legitimately mentions the legacy strings
 * ("No pictures added", "Please add pictures to the module"). All
 * negative regression assertions pre-strip Blade `{{-- ... --}}`
 * comments + HTML `<!-- ... -->` comments before the absence
 * check so the per-template AI-812 docblock prose cannot
 * false-fail the absence guards.
 */
class Pictures525769AI812EmptyStateGateContractTest extends TestCase
{
    /**
     * All 34 Pictures templates that pre-fix carried the
     * unconditional `<p class="mw-pictures-clean">No pictures
     * added...` legacy line. Surfaced via:
     *   grep -rl 'No pictures added\. Please add pictures' \
     *       Modules/Pictures/resources/views/templates/
     */
    public static function allPicturesTemplatesProvider(): array
    {
        $templates = [
            'blog_pro',
            'button_gallery',
            'default',
            'masonry',
            'shop-inner',
            'shop-inner-templates',
            'shop-inner-templates-2',
            'simple',
            'skin-1',
            'skin-2',
            'skin-3',
            'skin-3-beauty',
            'skin-3-guest',
            'skin-4',
            'skin-5',
            'skin-6',
            'skin-7',
            'skin-8',
            'skin-9',
            'skin-10',
            'skin-11',
            'skin-12',
            'skin-13',
            'skin-14',
            'skin-14-ocean',
            'skin-15',
            'skin-16',
            'skin-17',
            'skin-18',
            'skin-19',
            'skin-20',
            'slick',
            'slider',
            'sliding-skin',
        ];

        $cases = [];
        foreach ($templates as $slug) {
            $cases[$slug] = [
                "Modules/Pictures/resources/views/templates/{$slug}.blade.php",
            ];
        }
        return $cases;
    }

    /**
     * Read file relative-to-base + pre-strip comments so docblock
     * prose mentioning legacy strings cannot false-fail absence
     * assertions (selector-self-match guard family).
     */
    private function executableTemplate(string $relativePath): string
    {
        $src = (string) file_get_contents(base_path($relativePath));
        // Strip Blade `{{-- ... --}}` + HTML `<!-- ... -->` comments
        // before absence assertions.
        $src = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $src);
        $src = preg_replace('~<!--[\s\S]*?-->~', '', $src);
        return $src;
    }

    private function rawTemplate(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  legacy admin-leak line is GONE in executable source
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('allPicturesTemplatesProvider')]
    public function legacy_no_pictures_p_tag_is_gone(string $relativePath): void
    {
        $exec = $this->executableTemplate($relativePath);
        $this->assertDoesNotMatchRegularExpression(
            '~<p class="mw-pictures-clean">No pictures added\. Please add pictures to the module\.</p>~',
            $exec,
            "AI-812: {$relativePath} MUST NOT carry the legacy `<p class=\"mw-pictures-clean\">No pictures added...` line in executable source (post-comment-strip)."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  is_admin() gate + AI-780a typed empty-state present
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('allPicturesTemplatesProvider')]
    public function is_admin_gate_wraps_empty_state(string $relativePath): void
    {
        $exec = $this->executableTemplate($relativePath);
        $this->assertStringContainsString(
            '@if (is_admin())',
            $exec,
            "AI-812: {$relativePath} MUST gate the empty-state in `@if (is_admin())` so anonymous visitors don't see admin-targeted copy."
        );
    }

    #[Test]
    #[DataProvider('allPicturesTemplatesProvider')]
    public function canvas_empty_state_block_present(string $relativePath): void
    {
        $exec = $this->executableTemplate($relativePath);
        // AI-780a typed empty-state shape (mirrors AI-808 Page module).
        $this->assertStringContainsString(
            'mw-canvas-empty-state',
            $exec,
            "AI-812: {$relativePath} MUST adopt the .mw-canvas-empty-state typed empty-state pattern (AI-780a lineage)."
        );
        $this->assertStringContainsString(
            'data-mw-ai780-content-type="picture"',
            $exec,
            "AI-812: {$relativePath} MUST carry data-mw-ai780-content-type=\"picture\" data attribute (explicit content-type for Pictures-module empty state)."
        );
    }

    #[Test]
    #[DataProvider('allPicturesTemplatesProvider')]
    public function empty_state_carries_picture_specific_strings(string $relativePath): void
    {
        $exec = $this->executableTemplate($relativePath);
        $this->assertStringContainsString(
            "__('No pictures yet')",
            $exec,
            "AI-812: {$relativePath} heading MUST be __('No pictures yet')."
        );
        $this->assertStringContainsString(
            "__('Add your first picture to fill this gallery.')",
            $exec,
            "AI-812: {$relativePath} body copy MUST mirror the AI-780a typed wording."
        );
        $this->assertStringContainsString(
            "__('+ Add picture')",
            $exec,
            "AI-812: {$relativePath} CTA label MUST be __('+ Add picture')."
        );
        $this->assertStringContainsString(
            "admin_url('media')",
            $exec,
            "AI-812: {$relativePath} CTA href MUST route to admin_url('media') per designer spec."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  task-id markers (audit grep contract)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    #[DataProvider('allPicturesTemplatesProvider')]
    public function task_id_marker_present(string $relativePath): void
    {
        $raw = $this->rawTemplate($relativePath);
        $this->assertStringContainsString(
            'task-2026-05-17-525769',
            $raw,
            "AI-812: {$relativePath} MUST carry the task-id marker for audit grep."
        );
        $this->assertStringContainsString(
            'AI-812',
            $raw,
            "AI-812: {$relativePath} MUST cite the ticket ID for audit grep."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  recon-surface guard — no NEW Pictures template can sneak
    // in with the legacy unconditional shape without being added to this
    // test's data provider
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function no_other_pictures_template_carries_unconditional_no_pictures_p(): void
    {
        $templatesDir = base_path('Modules/Pictures/resources/views/templates');
        $allBlades = glob($templatesDir . '/*.blade.php');
        $covered = array_map(
            fn ($p) => base_path($p[0]),
            array_values(static::allPicturesTemplatesProvider())
        );
        $uncovered = array_diff($allBlades, $covered);

        foreach ($uncovered as $file) {
            $raw = (string) file_get_contents($file);
            $exec = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $raw);
            $exec = preg_replace('~<!--[\s\S]*?-->~', '', $exec);

            // If an uncovered template carries the legacy line in
            // executable source, fail loudly — either add it to the
            // data provider AND ship the fix, or confirm it doesn't
            // exhibit the defect.
            if (preg_match('~<p class="mw-pictures-clean">No pictures added~', $exec)) {
                $this->fail(sprintf(
                    'AI-812: uncovered Pictures template %s carries the legacy unconditional `<p class="mw-pictures-clean">No pictures added...` line. ' .
                    'Add it to Pictures525769AI812EmptyStateGateContractTest::allPicturesTemplatesProvider() AND apply the is_admin() gate.',
                    basename($file)
                ));
            }
        }

        $this->addToAssertionCount(1);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E  precise audit count — 34 templates affected
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function exactly_34_pictures_templates_in_data_provider(): void
    {
        $this->assertCount(
            34,
            static::allPicturesTemplatesProvider(),
            'AI-812 fix shipped to exactly 34 Pictures templates per recon delta (designer named 1, recon found 34).'
        );
    }
}
