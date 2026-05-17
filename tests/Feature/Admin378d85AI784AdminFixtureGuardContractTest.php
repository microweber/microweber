<?php

declare(strict_types=1);

namespace Tests\Feature;

use MicroweberPackages\Filament\Support\AdminFixtureGuard;
use Modules\Content\Filament\Admin\ContentResource;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-378d85 / AI-784 — systemic admin-form fixture-leak guard.
 * Jira: https://microweber.atlassian.net/browse/AI-784
 *
 * Designer's Round-10 audit identified a recurring defect family —
 * PHPUnit fixture data + Faker lorem-ipsum names + before/after
 * scenario labels surfacing in production admin form dropdowns.
 * AI-776 (Posts Menus rail) was the first per-resource fix; AI-784
 * lifts the blocklist into a shared
 * MicroweberPackages\Filament\Support\AdminFixtureGuard helper so
 * every admin form that surfaces fixture-prone data calls the same
 * canonical filter.
 *
 * This test exercises the helper directly (pure function, no DB) +
 * pins that ContentResource::ai776MenuShouldRender() now delegates
 * to the shared helper rather than carrying its own blocklist copy.
 *
 * Future callers: add a `shouldRenderItem(...)` (or
 * `filterByTitle(...)`) call inside the resource's option-closure +
 * extend this test with a new DataProvider row if the resource
 * surfaces a new fixture-leak pattern.
 */
class Admin378d85AI784AdminFixtureGuardContractTest extends TestCase
{
    // ─────────────────────────────────────────────────────────────────────
    // Group A — name-pattern blocklist excludes fixture-leak titles
    // ─────────────────────────────────────────────────────────────────────

    public static function fixtureLeakStrings(): array
    {
        return [
            'null title' => [null],
            'empty string title' => [''],
            'whitespace-only title' => ['   '],
            'PHPUnit unique-name menu fixture' => ['menu test 6a030e7b650aa'],
            'PHPUnit unique-name uppercase' => ['Menu Test 6A031938613B3'],
            'scenario label "test menu"' => ['test menu'],
            'scenario label "test page"' => ['Test Page'],
            'scenario label "test category"' => ['test category'],
            'module-API integration fixture' => ['Created via module API menu'],
            'lorem-ipsum prefix' => ['lorem ipsum dolor sit amet'],
            'before/after step label' => ['After'],
            'pure-numeric title' => ['12345'],
            // Faker lorem detection (AI-781 surface — caught Commodi Sunt / Reprehenderit Voluptate)
            'Faker 2-word lowercase' => ['commodi sunt'],
            'Faker 2-word Title Case' => ['Reprehenderit Voluptate'],
            'Faker 3-word title' => ['Asperiores Quia Voluptas'],
            'Faker 2-word with separator' => ['Aperiam-Placeat'],
        ];
    }

    #[Test]
    #[DataProvider('fixtureLeakStrings')]
    public function fixture_leak_titles_are_excluded(?string $title): void
    {
        $this->assertFalse(
            AdminFixtureGuard::shouldRenderItem($title),
            sprintf('Title should be excluded: %s', json_encode($title))
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — legitimate titles pass through
    // ─────────────────────────────────────────────────────────────────────

    public static function legitimateStrings(): array
    {
        return [
            'header_menu' => ['header_menu'],
            'footer_menu' => ['footer_menu'],
            'Main Navigation' => ['Main Navigation'],
            'About Us (would be Faker false-positive if blocklist were too broad)' => ['About Us'],
            'Contact Us' => ['Contact Us'],
            'Customer Support' => ['Customer Support'],
            'Shop Top Menu' => ['Shop Top Menu'],
            'Product Categories' => ['Product Categories'],
            'Blog' => ['Blog'],
            'single Faker word IS allowed (could be a real product name)' => ['Voluptate'],
        ];
    }

    #[Test]
    #[DataProvider('legitimateStrings')]
    public function legitimate_titles_pass_through(string $title): void
    {
        $this->assertTrue(
            AdminFixtureGuard::shouldRenderItem($title),
            sprintf('Legitimate title must pass through: %s', $title)
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Faker-lorem detector edge cases
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function faker_lorem_detector_requires_two_long_words(): void
    {
        // Single Faker word alone does NOT trigger (could be a real
        // product name in a Roman-language store).
        $this->assertFalse(AdminFixtureGuard::looksLikeFakerLorem('commodi'));
        $this->assertFalse(AdminFixtureGuard::looksLikeFakerLorem('Voluptate'));
        // Two Faker words trigger.
        $this->assertTrue(AdminFixtureGuard::looksLikeFakerLorem('Commodi Sunt'));
        $this->assertTrue(AdminFixtureGuard::looksLikeFakerLorem('Reprehenderit Voluptate'));
    }

    #[Test]
    public function faker_lorem_detector_ignores_short_filler_words(): void
    {
        // Short words (≤3 char) are ignored as ambiguous — "Asperiores Et"
        // has only ONE ≥4-char word so doesn't match the multi-word
        // signature. The blocklist `^after$` pattern catches "After"
        // separately.
        $this->assertFalse(AdminFixtureGuard::looksLikeFakerLorem('Asperiores Et'));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — filterByTitle convenience method
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function filter_by_title_re_indexes_the_array(): void
    {
        $input = [
            ['id' => 1, 'title' => 'header_menu'],
            ['id' => 2, 'title' => 'menu test 6a030e7b650aa'],
            ['id' => 3, 'title' => 'footer_menu'],
            ['id' => 4, 'title' => null],
            ['id' => 5, 'title' => 'Commodi Sunt'],
        ];
        $filtered = AdminFixtureGuard::filterByTitle($input);
        $this->assertCount(2, $filtered);
        $this->assertSame([0, 1], array_keys($filtered));
        $this->assertSame(1, $filtered[0]['id']);
        $this->assertSame(3, $filtered[1]['id']);
    }

    #[Test]
    public function filter_by_title_supports_custom_key(): void
    {
        // Some helpers return `name` rather than `title` (categories
        // sometimes do). Custom key parameter handles that.
        $input = [
            ['id' => 1, 'name' => 'Real Category'],
            ['id' => 2, 'name' => 'test category'],
        ];
        $filtered = AdminFixtureGuard::filterByTitle($input, 'name');
        $this->assertCount(1, $filtered);
        $this->assertSame(1, $filtered[0]['id']);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — ContentResource delegates to the shared helper (AI-776 → AI-784 refactor)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function content_resource_ai776_method_delegates_to_shared_helper(): void
    {
        // Behaviour parity check: the public ai776MenuShouldRender
        // surface still works exactly as before AND now goes through
        // AdminFixtureGuard. Existing AI-776 contract test still
        // passes against this delegation.
        $this->assertFalse(ContentResource::ai776MenuShouldRender(['title' => null]));
        $this->assertFalse(ContentResource::ai776MenuShouldRender(['title' => 'menu test 6a030e7b650aa']));
        $this->assertFalse(ContentResource::ai776MenuShouldRender(['title' => 'Commodi Sunt']));
        $this->assertTrue(ContentResource::ai776MenuShouldRender(['title' => 'header_menu']));
    }

    #[Test]
    public function content_resource_source_imports_or_uses_admin_fixture_guard(): void
    {
        // Source-side pin: the AI-776 method must reference the
        // shared helper (either via FQN or import). After the AI-784
        // refactor, the bespoke const + helper body in ContentResource
        // is gone.
        $source = (string) file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource.php'
        ));
        $this->assertStringContainsString(
            'AdminFixtureGuard',
            $source,
            'ContentResource must reference AdminFixtureGuard after the AI-784 refactor.'
        );
        $this->assertStringContainsString(
            'AdminFixtureGuard::shouldRenderItem',
            $source,
            'ContentResource::ai776MenuShouldRender must delegate to AdminFixtureGuard::shouldRenderItem.'
        );
        // Old AI776_MENU_FIXTURE_LEAK_PATTERNS constant must be gone
        // (single source of truth lives in AdminFixtureGuard now).
        $this->assertStringNotContainsString(
            'AI776_MENU_FIXTURE_LEAK_PATTERNS',
            preg_replace('!/\*.*?\*/!s', '', $source),
            'Bespoke AI776_MENU_FIXTURE_LEAK_PATTERNS const must be gone after AI-784 refactor (lives in AdminFixtureGuard).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group F — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai784_markers_present_in_helper(): void
    {
        $source = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Filament/Support/AdminFixtureGuard.php'
        ));
        $this->assertStringContainsString('task-2026-05-17-378d85', $source);
        $this->assertStringContainsString('AI-784', $source);
    }
}
