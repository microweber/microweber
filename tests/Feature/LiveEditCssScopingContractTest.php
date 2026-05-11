<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Live-edit CSS scoping regression coverage.
 *
 * Pins:
 *   - The live-edit layout root carries a `mw-live-edit-page` class
 *     so live-edit-specific CSS rules can scope under it.
 *   - Every previously-bare top-level `.fi-*` selector in
 *     `live-edit-classes.css` is now wrapped under
 *     `.mw-live-edit-page` so the rule does not leak to non-live-edit
 *     Filament admin pages.
 *
 * Background: per the live-edit-css-must-be-scoped skill, any rule
 * matching a raw `.fi-*` selector in any `live-edit-*.css` file
 * leaks to every Filament admin page. Scoping uses `.mw-live-edit-page`
 * because the canonical hook is the layout root (added in
 * `src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit.blade.php`).
 *
 * Contract test style: file-system reads only, no DB touch.
 */
class LiveEditCssScopingContractTest extends TestCase
{
    private const LAYOUT = 'src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit.blade.php';
    private const CSS    = 'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function live_edit_layout_root_carries_mw_live_edit_page_class(): void
    {
        $src = $this->read(self::LAYOUT);

        $this->assertMatchesRegularExpression(
            '/<div\\s+class="fi-layout mw-live-edit-page/',
            $src,
            self::LAYOUT . ': layout root must carry the `mw-live-edit-page` scope class so live-edit CSS can scope under it'
        );
    }

    #[Test]
    public function no_bare_top_level_fi_selectors_remain_in_live_edit_classes(): void
    {
        $src = $this->read(self::CSS);

        // A "bare" top-level selector starts at column 0 with `.fi-`.
        // Every such selector must be prefixed by `.mw-live-edit-page`
        // (or be a comment / inside a block).
        $offenders = [];
        foreach (explode("\n", $src) as $i => $line) {
            if (preg_match('/^\\.fi-[a-z0-9_-]+/', $line)) {
                $offenders[] = sprintf('%d: %s', $i + 1, trim($line));
            }
        }

        $this->assertEmpty(
            $offenders,
            self::CSS . ' has bare top-level `.fi-*` selectors — every one must be scoped under `.mw-live-edit-page`:'
            . "\n  " . implode("\n  ", $offenders)
        );
    }

    #[Test]
    public function key_rules_explicitly_scoped_under_mw_live_edit_page(): void
    {
        $src = $this->read(self::CSS);

        // The 3 specific rules that must be scoped.
        $this->assertStringContainsString(
            '.mw-live-edit-page.fi-layout .fi-main-ctn',
            $src,
            self::CSS . ': transparent-bg rule must be scoped to `.mw-live-edit-page.fi-layout .fi-main-ctn`'
        );
        $this->assertStringContainsString(
            '.mw-live-edit-page .fi-section-header',
            $src,
            self::CSS . ': fi-section-header `!px-0` rule must be scoped to `.mw-live-edit-page`'
        );
        $this->assertStringContainsString(
            '.mw-live-edit-page #iframe-holder',
            $src,
            self::CSS . ': 56px toolbar-offset must be scoped to `.mw-live-edit-page #iframe-holder`'
        );
    }
}
