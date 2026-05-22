<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-31aeb1 — Live-edit toolbar CSS inconsistencies.
 *
 * AI-909: Canvas iframe focus outline — thick blue line between toolbar and
 * canvas when user clicks inside iframe. Fix: #live-editor-frame outline:none.
 *
 * AI-910: Page chip blue focus ring persists after mouse click while popover
 * is open. Fix: this.$el.blur() in open() before isOpen = true; keyboard flow
 * is unaffected (focus transferred to searchInput immediately after).
 *
 * Style: file-system reads only — no DB / Filament boot.
 */
class LiveEdit31aeb1AI909AI910ToolbarChromeFixes extends TestCase
{
    private const CSS_SRC = 'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css';
    private const PAGE_CHIP = 'packages/frontend-assets/resources/assets/ui/components/Toolbar/PageChip.vue';
    private const BUNDLE_CSS = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
    private const BUNDLE_JS  = 'public/vendor/microweber-packages/frontend-assets/build/frontend.js';

    private string $css;
    private string $cssStripped;
    private string $vue;
    private string $vueStripped;
    private string $bundleCss;
    private string $bundleJs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->css = (string) file_get_contents(base_path(self::CSS_SRC));
        $this->cssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->css) ?? $this->css;

        $this->vue = (string) file_get_contents(base_path(self::PAGE_CHIP));
        $s = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->vue) ?? $this->vue;
        $this->vueStripped = preg_replace('~//[^\n]*~', '', $s) ?? $s;

        $this->bundleCss = file_exists(base_path(self::BUNDLE_CSS))
            ? (string) file_get_contents(base_path(self::BUNDLE_CSS))
            : '';
        $this->bundleJs = file_exists(base_path(self::BUNDLE_JS))
            ? (string) file_get_contents(base_path(self::BUNDLE_JS))
            : '';
    }

    // ─── AI-909: iframe outline:none ─────────────────────────────────────

    #[Test]
    public function live_editor_frame_outline_none_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~#live-editor-frame\s*,[\s\S]*?outline\s*:\s*none~s',
            $this->cssStripped,
            'live-edit-classes.css must declare outline: none on #live-editor-frame.'
        );
    }

    #[Test]
    public function live_editor_frame_focus_outline_none_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~#live-editor-frame:focus\s*[,{]~s',
            $this->cssStripped,
            'live-edit-classes.css must include #live-editor-frame:focus in the outline:none rule.'
        );
    }

    #[Test]
    public function live_editor_frame_focus_visible_outline_none_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~#live-editor-frame:focus-visible~s',
            $this->cssStripped,
            'live-edit-classes.css must include #live-editor-frame:focus-visible in the outline:none rule.'
        );
    }

    #[Test]
    public function ai909_rule_present_in_served_css_bundle(): void
    {
        if ($this->bundleCss === '') {
            $this->markTestSkipped('CSS bundle absent — run npm run build in microweber-filament-theme.');
        }
        $this->assertStringContainsString(
            '#live-editor-frame',
            $this->bundleCss,
            'Served CSS bundle must include the #live-editor-frame outline:none rule.'
        );
    }

    #[Test]
    public function ai909_task_marker_in_css(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-31aeb1',
            $this->css,
            'live-edit-classes.css must carry the task-2026-05-22-31aeb1 marker.'
        );
    }

    // ─── AI-910: page chip blur on open ──────────────────────────────────

    #[Test]
    public function page_chip_open_method_calls_blur(): void
    {
        // Locate the open() method body and verify blur() is called before isOpen = true.
        $openPos = strpos($this->vueStripped, 'open()');
        $this->assertNotFalse($openPos, 'open() method must be present in PageChip.vue.');

        $openSlice = substr($this->vueStripped, $openPos, 600);
        $this->assertStringContainsString(
            'blur()',
            $openSlice,
            'open() method must call blur() to remove persistent focus ring on mouse-initiated opens.'
        );
    }

    #[Test]
    public function blur_called_before_is_open_set(): void
    {
        $openPos = strpos($this->vueStripped, 'open()');
        $this->assertNotFalse($openPos, 'open() method must be present.');

        $openSlice = substr($this->vueStripped, $openPos, 600);
        $blurPos   = strpos($openSlice, 'blur()');
        $isOpenPos = strpos($openSlice, 'isOpen = true');

        $this->assertNotFalse($blurPos,   'blur() must be present in open().');
        $this->assertNotFalse($isOpenPos, 'isOpen = true must be present in open().');
        $this->assertLessThan(
            $isOpenPos,
            $blurPos,
            'blur() must appear BEFORE isOpen = true — focus cleared before popover renders.'
        );
    }

    #[Test]
    public function search_input_still_focused_after_open(): void
    {
        // The searchInput.focus() call must still be present inside $nextTick
        // so keyboard users retain focus in the search field.
        $openPos = strpos($this->vueStripped, 'open()');
        $openSlice = substr($this->vueStripped, (int) $openPos, 600);

        $this->assertStringContainsString(
            'searchInput',
            $openSlice,
            'open() must still transfer focus to searchInput for keyboard users.'
        );
        $this->assertStringContainsString(
            'focus()',
            $openSlice,
            'open() must still call focus() on the search input.'
        );
    }

    #[Test]
    public function ai910_task_marker_in_vue(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-31aeb1',
            $this->vue,
            'PageChip.vue must carry the task-2026-05-22-31aeb1 marker.'
        );
    }
}
