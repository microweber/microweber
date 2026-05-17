<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\Content\Filament\Admin\ContentResource;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-6d65de / AI-776 — Posts admin Menus rail anti-leak filter.
 * Jira: https://microweber.atlassian.net/browse/AI-776
 *
 * Designer's Round-10 audit found dev/test menu fixtures surfacing in
 * the production Posts admin form's right-rail Menus checkbox list
 * (e.g. "Menu Test 6A030E7B650Aa (209 items)" + "Menu Test
 * 6A031938613B3 (209 items)"). Customer-trust P1.
 *
 * Recon on the audit DB: 595 menus total, 456 with null/empty title
 * (PHPUnit anonymous menu fixtures), 38 with obvious test-pattern
 * titles. The current rail was iterating ALL menus via get_menus().
 *
 * Fix (label-side only, no schema/migration changes): static helper
 * ContentResource::ai776MenuShouldRender(array $menu) filters menus
 * against a blocklist of empty/test-pattern titles before they reach
 * the dropdown's options array. Filter runs INSIDE the options
 * closure right after get_menus() — also short-circuits the
 * expensive per-menu item-count query for fixture menus.
 *
 * This test exercises the helper directly via PHPUnit data
 * providers — no DB needed, no Laravel boot. The helper is public
 * static specifically so it can be tested in isolation.
 */
class Admin6d65deAI776MenuFixtureLeakFilterContractTest extends TestCase
{
    // ─────────────────────────────────────────────────────────────────────
    // Group A — fixture-leak menus are excluded
    // ─────────────────────────────────────────────────────────────────────

    public static function fixtureLeakMenus(): array
    {
        return [
            'empty title (null)' => [['id' => 1, 'title' => null]],
            'empty title (empty string)' => [['id' => 2, 'title' => '']],
            'empty title (whitespace)' => [['id' => 3, 'title' => '   ']],
            'PHPUnit unique-name lowercase' => [['id' => 4, 'title' => 'menu test 6a030e7b650aa']],
            'PHPUnit unique-name uppercase' => [['id' => 5, 'title' => 'Menu Test 6A031938613B3']],
            'scenario label "test menu"' => [['id' => 6, 'title' => 'test menu']],
            'scenario label "Test Menu" mixed case' => [['id' => 7, 'title' => 'Test Menu']],
            'module-API integration test' => [['id' => 8, 'title' => 'Created via module API menu']],
            'lorem-ipsum cruft prefix' => [['id' => 9, 'title' => 'lorem ipsum dolor sit amet']],
            'before/after step label' => [['id' => 10, 'title' => 'After']],
            'pure-numeric title' => [['id' => 11, 'title' => '123213']],
        ];
    }

    #[Test]
    #[DataProvider('fixtureLeakMenus')]
    public function fixture_leak_menus_are_excluded(array $menu): void
    {
        $this->assertFalse(
            ContentResource::ai776MenuShouldRender($menu),
            sprintf('Menu fixture should be excluded: %s', json_encode($menu))
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — legitimate menus are kept
    // ─────────────────────────────────────────────────────────────────────

    public static function legitimateMenus(): array
    {
        return [
            'header_menu (existing primary)' => [['id' => 1, 'title' => 'header_menu']],
            'footer_menu (existing primary)' => [['id' => 3, 'title' => 'footer_menu']],
            'Main Navigation' => [['id' => 100, 'title' => 'Main Navigation']],
            'Sidebar Links' => [['id' => 101, 'title' => 'Sidebar Links']],
            'Customer support — multi-word natural name' => [['id' => 102, 'title' => 'Customer support']],
            'Slug-style identifier with valid words' => [['id' => 103, 'title' => 'shop_top_menu']],
        ];
    }

    #[Test]
    #[DataProvider('legitimateMenus')]
    public function legitimate_menus_are_kept(array $menu): void
    {
        $this->assertTrue(
            ContentResource::ai776MenuShouldRender($menu),
            sprintf('Legitimate menu must render in the admin dropdown: %s', json_encode($menu))
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — the menusSection options closure CALLS the filter
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function menus_section_source_calls_the_filter(): void
    {
        // Source-side anchor: the options closure must invoke the
        // helper. Strip block + line comments first so the docblock
        // referencing the helper doesn't false-pass on a removed call
        // (selector-self-match guard family).
        $source = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource.php'
        ));
        $stripped = preg_replace('!/\*.*?\*/!s', '', $source);
        $stripped = preg_replace('!//.*$!m', '', $stripped);
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*!\s*self::ai776MenuShouldRender\(\$menu\)\s*\)\s*\{[^}]*continue;/s',
            $stripped,
            'menusSection() options closure must call `self::ai776MenuShouldRender($menu)` inside the foreach loop and `continue` on false (after stripping comments).'
        );
    }

    #[Test]
    public function filter_runs_before_expensive_item_count_query(): void
    {
        // Performance guard: the per-menu item-count query
        // (`get_menu_items('count=1&parent_id=' . $menu['id'])`) must
        // happen AFTER the filter call — otherwise we waste DB
        // round-trips on fixtures we're going to discard anyway.
        $source = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource.php'
        ));
        $filterPos = strpos($source, 'ai776MenuShouldRender($menu)');
        $itemCountPos = strpos($source, "get_menu_items('count=1&parent_id=' . \$menu['id']");
        $this->assertNotFalse($filterPos);
        $this->assertNotFalse($itemCountPos);
        $this->assertLessThan(
            $itemCountPos,
            $filterPos,
            'ai776MenuShouldRender filter call must run BEFORE the per-menu item-count query so fixture-leak menus do not trigger expensive DB round-trips.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — markers + back-compat
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai776_markers_present(): void
    {
        $source = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource.php'
        ));
        $this->assertStringContainsString('task-2026-05-17-6d65de', $source);
        $this->assertStringContainsString('AI-776', $source);
    }
}
