<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-170 / AI-228 (2026-05-10) — admin Rich Text Editor toolbar
 * touch-target floor.
 *
 * agent-test's Drunk Designer modal audit found ALL ~30 admin RTE
 * toolbar buttons measure 32×32 — Bold, Italic, Underline, Link,
 * Heading 2/3, Align, Blockquote, Code, Lists, Table, Undo/Redo,
 * Attach files, table sub-buttons. Filament's base style sets
 * `.fi-fo-rich-editor-tool` to `h-8 min-w-8` = 32×32.
 *
 * Note: PM's email mentioned `.tiptap-toolbar button` but in this
 * project the admin RTE is Filament's native Rich Editor — the
 * actual class is `.fi-fo-rich-editor-tool`. Verified by playwright
 * probe at /admin/products/create at 390×844: all 20 visible
 * toolbar buttons measure 32×32 with min-width: 32px before fix,
 * 44×44 with min-width/min-height: 44px after fix.
 *
 * Cycle-170 fix:
 *   Floor `body.fi-panel-admin .fi-fo-rich-editor-tool` (and
 *   defensive `.fi-fo-rich-editor-toolbar button`) to 44×44
 *   !important. The toolbar wrapper is already
 *   `display: flex; flex-wrap: wrap` so bumping each button to
 *   44×44 just causes natural row wrapping; no special 2-row
 *   layout needed (PM's preemptive concern).
 */
class Ai228AdminRichEditorToolbar44ContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_170_anchor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        $this->assertMatchesRegularExpression('/[Cc]ycle-170/', $src,
            'mobile-touch.css MUST carry the cycle-170 anchor.');
        $this->assertStringContainsString('AI-228', $src,
            'mobile-touch.css MUST carry the AI-228 anchor.');
    }

    #[Test]
    public function ai_228_rich_editor_tool_floored_to_44(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');
        // Floor scoped to body.fi-panel-admin so the rule does not
        // bleed to other Filament panels (checkout, etc.).
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-fo-rich-editor-tool[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor body.fi-panel-admin '
            . '.fi-fo-rich-editor-tool to min-height:44px !important '
            . 'so admin RTE toolbar buttons meet WCAG 2.5.5 / iOS HIG '
            . '44 floor (was 32×32).'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-fo-rich-editor-tool[\s\S]{0,400}min-width:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST floor min-width:44px !important on '
            . 'admin RTE toolbar buttons.'
        );
        // Defensive duplicate selector — bites even if Filament
        // changes the per-button class but keeps the toolbar wrapper.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.fi-fo-rich-editor-toolbar\s+button[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'mobile-touch.css MUST also floor any button inside '
            . '.fi-fo-rich-editor-toolbar (defensive — bites if '
            . 'Filament restructures the per-button class).'
        );
    }

    #[Test]
    public function cycle_170_inside_touch_media_query(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css');

        // Cycle-170 rules MUST sit inside an @media block that
        // matches mobile + touch (same media query as cycle-168/169).
        $anchorPos = strpos($src, 'cycle-170');
        $this->assertNotFalse($anchorPos, 'cycle-170 anchor must be present.');
        $mediaPos = strpos($src, '@media', $anchorPos);
        $this->assertNotFalse($mediaPos, 'cycle-170 rules MUST sit inside an @media.');
        $mediaLine = substr($src, $mediaPos, 100);
        $this->assertMatchesRegularExpression(
            '/max-width:\s*1023\.98px/',
            $mediaLine,
            'cycle-170 @media MUST include max-width: 1023.98px so the '
            . 'rule fires at admin-drawer-collapse breakpoint.'
        );
        $this->assertStringContainsString('pointer: coarse', $mediaLine,
            'cycle-170 @media MUST include (pointer: coarse) so real '
            . 'touch devices hit the floor regardless of width.');
    }

    #[Test]
    public function built_bundle_carries_rte_floor(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built filament-theme bundle missing.");
        }
        $built = file_get_contents($path);

        // Functional pin per cycle-142 lesson — load-bearing rule
        // MUST appear in the compiled bundle.
        $this->assertStringContainsString(
            '.fi-panel-admin .fi-fo-rich-editor-tool',
            $built,
            'Built bundle MUST contain the AI-228 admin RTE toolbar '
            . 'button floor rule.'
        );
        $this->assertMatchesRegularExpression(
            '/\.fi-panel-admin\s+\.fi-fo-rich-editor-tool[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $built,
            'Built bundle MUST contain min-height:44px !important on '
            . '.fi-panel-admin .fi-fo-rich-editor-tool.'
        );
    }
}
