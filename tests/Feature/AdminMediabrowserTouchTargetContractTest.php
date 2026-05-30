<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-30-mediabrowser — mw-media-browser primary + secondary CTA
 * touch-target floor on mobile (390x844 probe).
 *
 * DEFECT (probed at 390x844 on /admin/categories/create — but blast radius
 *   is every Filament resource that mounts mw-media-browser)
 *
 *   Primary "Choose from Media Library" pill button rendered at 233x36
 *   (8px short of 44px WCAG 2.5.5 floor). Sibling secondary
 *   "Upload from your device" underline-link button rendered at the same
 *   ~36px height. Both are hand-rolled Tailwind (`px-4 py-2` => 40px line
 *   height; lacking `min-h-[44px]`).
 *
 * FIX
 *   Inline style attribute injection WITH !important flag — guaranteed
 *   cascade-winner. Same constraint as task-2026-05-30-tplcust: Tailwind
 *   JIT does NOT pick up fresh `min-h-[44px]` arbitrary classes added
 *   post-build on this Blade; inline style without !important loses
 *   cascade against the sibling Tailwind utilities (which are themselves
 *   compiled with !important via the project's Tailwind preset).
 *
 *     - .mw-media-browser-primary-btn  carries style="min-height:44px !important;"
 *     - .mw-media-browser-secondary-btn carries style="min-height:44px !important;"
 *
 * SCOPE NOTE
 *   Surgical inline-style fix chosen because (a) the component is a
 *   reusable Filament form component shared across many resources, so a
 *   per-resource override is out of scope; (b) the `mw-media-browser-*`
 *   classes have no admin-CSS-bundle owner currently, so any new global
 *   rule would have to be coordinated with the design system — out of
 *   scope for this slice.
 */
class AdminMediabrowserTouchTargetContractTest extends TestCase
{
    private string $blade;
    private string $bladeStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blade = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/resources/views/filament-forms/components/mw-media-browser.blade.php'
        ));
        // Strip Blade {{-- ... --}} + PHP block comments before negative
        // assertions so docblock prose mentioning attribute strings does not
        // satisfy the source-presence assertions (UNIFORMITY-RULE per
        // task-7aa48a).
        $stripped = (string) preg_replace('~\{\{--.*?--\}\}~s', '', $this->blade);
        $stripped = (string) preg_replace('~/\*.*?\*/~s', '', $stripped);
        $this->bladeStripped = $stripped;
    }

    #[Test]
    public function primary_button_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/class="mw-media-browser-primary-btn[^"]*"[\s\S]*?style="min-height:44px !important;"/',
            $this->bladeStripped,
            'Primary "Choose from Media Library" button must carry inline style="min-height:44px !important;".'
        );
    }

    #[Test]
    public function secondary_button_carries_inline_min_height_44(): void
    {
        $this->assertMatchesRegularExpression(
            '/class="mw-media-browser-secondary-btn[^"]*"[\s\S]*?style="min-height:44px !important;"/',
            $this->bladeStripped,
            'Secondary "Upload from your device" button must carry inline style="min-height:44px !important;".'
        );
    }

    #[Test]
    public function mediabrowser_task_marker_present_in_blade(): void
    {
        $this->assertStringContainsString('task-2026-05-30-mediabrowser', $this->blade);
    }

    #[Test]
    public function no_legacy_unstyled_primary_btn_remains(): void
    {
        // Defence: pre-fix shape was the primary button WITHOUT any inline
        // style attribute (only the Tailwind utility class soup). Walk
        // each <button> element carrying the primary-btn class and assert
        // it carries the inline 44px style. Slice-per-element scan avoids
        // PCRE backtracking ambiguity from a single greedy regex.
        if (!preg_match_all('~<button\b[^>]*class="mw-media-browser-primary-btn[^"]*"[^>]*>~', $this->bladeStripped, $matches)) {
            $this->fail('Expected primary button in mw-media-browser.blade.php (none found).');
        }
        $this->assertCount(1, $matches[0], 'Expected exactly 1 primary button.');
        foreach ($matches[0] as $tag) {
            $this->assertStringContainsString(
                'style="min-height:44px !important;"',
                $tag,
                "Legacy unstyled primary button shape detected — missing inline 44px !important style:\n{$tag}"
            );
        }
    }

    #[Test]
    public function no_legacy_unstyled_secondary_btn_remains(): void
    {
        if (!preg_match_all('~<button\b[^>]*class="mw-media-browser-secondary-btn[^"]*"[^>]*>~', $this->bladeStripped, $matches)) {
            $this->fail('Expected secondary button in mw-media-browser.blade.php (none found).');
        }
        $this->assertCount(1, $matches[0], 'Expected exactly 1 secondary button.');
        foreach ($matches[0] as $tag) {
            $this->assertStringContainsString(
                'style="min-height:44px !important;"',
                $tag,
                "Legacy unstyled secondary button shape detected — missing inline 44px !important style:\n{$tag}"
            );
        }
    }
}
