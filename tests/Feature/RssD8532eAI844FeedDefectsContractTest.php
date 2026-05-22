<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-d8532e / AI-844 HIGH — RSS feed four defect fixes.
 *
 * Four defects in /rss (RSS 2.0 via atom.blade.php):
 *
 * 1. <link>127.0.0.1</link> — hostname() returns bare host, no protocol.
 *    Fix: url('/') in all three controller methods.
 *
 * 2. <description></description> — empty channel description.
 *    Fix: 3-tier fallback: website_description → website_title → translated default.
 *
 * 3. Item <description> = URL repeated — useless for RSS consumers.
 *    Fix: strip_tags($item['description']) + Str::limit(280) in atom.blade.php.
 *
 * 4. Faker test data leaking — lorem-ipsum titles appear in public feed.
 *    Fix: AdminFixtureGuard::filterByTitle() applied to all three query results.
 *    Extends the AI-784 AdminFixtureGuard umbrella to the public RSS surface
 *    (Faker-leak family cross-surface, 3-recurrence: AI-776 + AI-781 + AI-844).
 */
class RssD8532eAI844FeedDefectsContractTest extends TestCase
{
    private string $controller;
    private string $controllerStripped;
    private string $atomTemplate;
    private string $atomStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $ctrlRaw = (string) file_get_contents(
            base_path('Modules/RssFeed/Http/Controllers/RssController.php')
        );
        $this->controller = $ctrlRaw;
        $this->controllerStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $ctrlRaw) ?? $ctrlRaw;
        $this->controllerStripped = preg_replace('~//[^\n]*~', '', $this->controllerStripped);

        $atomRaw = (string) file_get_contents(
            base_path('Modules/RssFeed/resources/views/atom.blade.php')
        );
        $this->atomTemplate = $atomRaw;
        $this->atomStripped = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $atomRaw) ?? $atomRaw;
    }

    // ─── Fix 1: siteUrl uses url('/') not hostname() ─────────────────────────

    #[Test]
    public function controller_uses_url_helper_not_hostname(): void
    {
        $this->assertStringContainsString(
            "url('/')",
            $this->controllerStripped,
            'Controller must use url(\'/\') for siteUrl — hostname() returns bare host with no protocol.'
        );
    }

    #[Test]
    public function hostname_method_no_longer_used_for_site_url(): void
    {
        // hostname() must not be used in any 'siteUrl' assignment
        $this->assertDoesNotMatchRegularExpression(
            "~'siteUrl'\s*=>\s*mw\(\)->url_manager->hostname\(\)~",
            $this->controllerStripped,
            'hostname() must not be used as siteUrl — it lacks the protocol prefix.'
        );
    }

    #[Test]
    public function all_three_methods_have_url_helper(): void
    {
        $count = substr_count($this->controllerStripped, "url('/')");
        $this->assertGreaterThanOrEqual(3, $count,
            'url(\'/\') must be used in all three controller methods (index, posts, products).'
        );
    }

    // ─── Fix 2: 3-tier siteDescription fallback ──────────────────────────────

    #[Test]
    public function site_description_has_fallback_chain(): void
    {
        // The fallback chain: website_description ?: website_title ?: __('Latest...')
        $this->assertMatchesRegularExpression(
            "~get\('website_description'[^)]*\)\s*\?:[^;]*get\('website_title'~s",
            $this->controllerStripped,
            'siteDescription must have 3-tier fallback: website_description ?: website_title ?: default.'
        );
    }

    #[Test]
    public function all_three_methods_have_description_fallback(): void
    {
        // Count occurrences of the fallback pattern across all three methods
        $count = preg_match_all(
            "~get\('website_description'[^)]*\)\s*\?:~",
            $this->controllerStripped
        );
        $this->assertGreaterThanOrEqual(3, $count,
            'All three methods (index, posts, products) must have the 3-tier description fallback.'
        );
    }

    // ─── Fix 3: item description uses excerpt, not URL ───────────────────────

    #[Test]
    public function item_description_no_longer_uses_url(): void
    {
        // The <description> element must not reference $item['url']
        $this->assertDoesNotMatchRegularExpression(
            '~<description>\s*\{\{\s*\$item\[.url.\]\s*\}\}\s*</description>~',
            $this->atomStripped,
            '<description> must not be the item URL — it should be a content excerpt.'
        );
    }

    #[Test]
    public function item_description_uses_strip_tags(): void
    {
        $this->assertStringContainsString(
            'strip_tags',
            $this->atomStripped,
            'atom.blade.php item <description> must use strip_tags() to remove HTML markup.'
        );
    }

    #[Test]
    public function item_description_is_limited_to_280_chars(): void
    {
        $this->assertStringContainsString(
            '280',
            $this->atomStripped,
            'atom.blade.php item <description> must be limited to 280 characters.'
        );
    }

    #[Test]
    public function item_description_uses_str_limit(): void
    {
        $this->assertMatchesRegularExpression(
            '~Str::limit\(strip_tags\(\$item\[.description.\]~s',
            $this->atomStripped,
            'Item description must use Str::limit(strip_tags($item[\'description\']...), 280).'
        );
    }

    // ─── Fix 4: AdminFixtureGuard faker filter ────────────────────────────────

    #[Test]
    public function controller_imports_admin_fixture_guard(): void
    {
        $this->assertStringContainsString(
            'use MicroweberPackages\Filament\Support\AdminFixtureGuard;',
            $this->controller,
            'RssController must import AdminFixtureGuard.'
        );
    }

    #[Test]
    public function index_method_applies_fixture_guard(): void
    {
        // Confirm filterByTitle is called inside index()
        $indexStart = strpos($this->controllerStripped, 'public function index(');
        $this->assertNotFalse($indexStart);
        $indexSlice = substr($this->controllerStripped, (int) $indexStart, 2000);
        $this->assertStringContainsString(
            'AdminFixtureGuard::filterByTitle',
            $indexSlice,
            'index() must apply AdminFixtureGuard::filterByTitle to filter faker content.'
        );
    }

    #[Test]
    public function posts_method_applies_fixture_guard(): void
    {
        $postsStart = strpos($this->controllerStripped, 'public function posts(');
        $this->assertNotFalse($postsStart);
        $postsSlice = substr($this->controllerStripped, (int) $postsStart, 2000);
        $this->assertStringContainsString(
            'AdminFixtureGuard::filterByTitle',
            $postsSlice,
            'posts() must apply AdminFixtureGuard::filterByTitle.'
        );
    }

    #[Test]
    public function products_method_applies_fixture_guard(): void
    {
        $prodStart = strpos($this->controllerStripped, 'public function products(');
        $this->assertNotFalse($prodStart);
        $prodSlice = substr($this->controllerStripped, (int) $prodStart, 2000);
        $this->assertStringContainsString(
            'AdminFixtureGuard::filterByTitle',
            $prodSlice,
            'products() must apply AdminFixtureGuard::filterByTitle.'
        );
    }

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-d8532e', $this->controller);
        $this->assertStringContainsString('task-2026-05-22-d8532e', $this->atomTemplate);
    }

    // ─── RSS 2.0 structural guards ────────────────────────────────────────────

    #[Test]
    public function atom_template_has_channel_link_tag(): void
    {
        $this->assertStringContainsString('<link>{{ $siteUrl }}</link>', $this->atomTemplate,
            'atom.blade.php must render <link> using $siteUrl (now set to url(\'/\')).'
        );
    }

    #[Test]
    public function atom_template_has_channel_description_tag(): void
    {
        $this->assertStringContainsString('<description>{{ $siteDescription }}</description>',
            $this->atomTemplate,
            'atom.blade.php must render <description> using $siteDescription.'
        );
    }
}
