<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * task-2026-05-21-3d7892 / AI-873
 * Jira: https://microweber.atlassian.net/browse/AI-873
 *
 * Two bugs in the Insert Layout modal:
 *
 * Bug 1 — Blank thumbnails: Bootstrap template has no static PNG screenshots
 *   at public/templates/bootstrap/img/screenshots/modules/layouts/templates/.
 *   ScanForBladeTemplates.php only sets screenshot_public_url when
 *   is_file($screen2) passes — so all 16 layout cards show blank white areas.
 *   Fix: Copy representative screenshots from Big2 as static proxies (named
 *   to match Bootstrap layout file paths, with "/" → "." convention).
 *
 * Bug 2 — "All Categories" duplicate: Group 1 tab text = "All categories";
 *   Group 3 section header was also "$lang('All categories')" per AI-716
 *   task-c4893b design. Visually confusing — two identical labels.
 *   Fix: Rename Group 3 header from "All categories" to "More categories".
 *   AI-716 desktop_rail_renders_group_header_for_other contract test updated
 *   in-place per pin-evolution discipline.
 */
class Layouts3d7892AI873ThumbnailsAndDupContractTest extends TestCase
{
    private string $vue;
    private string $vueExecutable; // comment-stripped
    private string $screenshotDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->vue = (string) file_get_contents(
            base_path('packages/frontend-assets/resources/assets/ui/components/Layouts/ListLayouts.vue')
        );
        // Strip JS comments for absence-assertions (selector-self-match guard)
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->vue) ?? $this->vue;
        $this->vueExecutable = preg_replace('~//[^\n]*~', '', $stripped) ?? $stripped;

        $this->screenshotDir = public_path('templates/bootstrap/img/screenshots/modules/layouts/templates');
    }

    // ─── Bug 1: Static screenshot thumbnails ────────────────────────────────

    #[Test]
    #[DataProvider('bootstrapLayoutScreenshotProvider')]
    public function bootstrap_layout_screenshot_file_exists(string $filename): void
    {
        $this->assertFileExists(
            $this->screenshotDir . '/' . $filename,
            "Bootstrap layout screenshot '{$filename}' must exist at " .
            "public/templates/bootstrap/img/screenshots/modules/layouts/templates/. " .
            "Missing screenshots cause blank white thumbnail areas in the Insert Layout modal."
        );
    }

    public static function bootstrapLayoutScreenshotProvider(): array
    {
        return [
            'default layout'              => ['default.png'],
            'skin-1 layout'               => ['skin-1.png'],
            'blog skin-1'                 => ['blog.skin-1.png'],
            'content skin-1'              => ['content.skin-1.png'],
            'ecommerce skin-1'            => ['ecommerce.skin-1.png'],
            'features skin-1'             => ['features.skin-1.png'],
            'features skin-2'             => ['features.skin-2.png'],
            'footers footer_cart'         => ['footers.footer_cart.png'],
            'footers skin-1'              => ['footers.skin-1.png'],
            'jumbotron skin-1'            => ['jumbotron.skin-1.png'],
            'menus skin-1'                => ['menus.skin-1.png'],
            'pricing skin-1'              => ['pricing.skin-1.png'],
            'pricing skin-2'              => ['pricing.skin-2.png'],
            'pricing skin-3'              => ['pricing.skin-3.png'],
            'text-block skin-1'           => ['text-block.skin-1.png'],
            'titles skin-1'               => ['titles.skin-1.png'],
        ];
    }

    #[Test]
    public function screenshot_directory_contains_expected_count(): void
    {
        $files = glob($this->screenshotDir . '/*.png') ?: [];
        $this->assertGreaterThanOrEqual(
            16,
            count($files),
            "Bootstrap layout screenshot directory must contain at least 16 PNG files."
        );
    }

    #[Test]
    public function screenshots_are_valid_png_files(): void
    {
        $defaultPng = $this->screenshotDir . '/default.png';
        if (!file_exists($defaultPng)) {
            $this->markTestSkipped('default.png does not exist — file-exists test will catch it.');
        }
        $info = getimagesize($defaultPng);
        $this->assertNotFalse($info, 'default.png must be a valid image file.');
        $this->assertSame(IMAGETYPE_PNG, $info[2], 'default.png must be a PNG file.');
        $this->assertGreaterThan(100, $info[0], 'default.png width must be > 100px.');
        $this->assertGreaterThan(50, $info[1], 'default.png height must be > 50px.');
    }

    // ─── Bug 2: "All Categories" duplicate eliminated ───────────────────────

    #[Test]
    public function group_1_tab_still_says_all_categories(): void
    {
        // Group 1 first-tab text must still be "All categories"
        $this->assertStringContainsString(
            "All categories",
            $this->vue,
            'Group 1 (All categories) tab must still say "All categories".'
        );
    }

    #[Test]
    public function group_3_desktop_header_says_more_categories(): void
    {
        // Desktop rail Group 3 section header is now "More categories" (not "All categories")
        // to eliminate the visual duplicate with the Group 1 tab.
        $this->assertMatchesRegularExpression(
            '/<li\s+v-if="otherCategories\.length"[\s\S]{0,400}class="mw-le-layouts-categories-group-header"[\s\S]{0,200}\$lang\([\'"]More categories[\'"]/',
            $this->vue,
            'Group 3 section header (desktop rail) must use $lang(\'More categories\') to avoid duplicating Group 1 tab text.'
        );
    }

    #[Test]
    public function group_3_mobile_header_says_more_categories(): void
    {
        // Mobile bottom-sheet Group 3 section header is also "More categories"
        $this->assertMatchesRegularExpression(
            '/mobile.*?mw-le-layouts-categories-group-header[\s\S]{0,200}\$lang\([\'"]More categories[\'"]|mw-le-layouts-categories-group-header[\s\S]{0,200}\$lang\([\'"]More categories[\'"][\s\S]{0,500}filterCategoryFromMobile/s',
            $this->vue,
            'Group 3 section header (mobile sheet) must use $lang(\'More categories\').'
        );
    }

    #[Test]
    public function group_3_header_does_not_duplicate_all_categories_text(): void
    {
        // The Group 3 header must NOT contain "All categories" any more.
        // We check by slicing from the otherCategories.length guard to the first
        // closing mw-le-layouts-categories-group-header </li> tag.
        $markerPos = strpos($this->vueExecutable, 'v-if="otherCategories.length"');
        $this->assertNotFalse($markerPos, 'otherCategories.length guard must be present.');

        $slice = substr($this->vueExecutable, $markerPos, 600);
        $firstHeaderClose = strpos($slice, '</li>');
        if ($firstHeaderClose !== false) {
            $headerBlock = substr($slice, 0, $firstHeaderClose + 5);
            $this->assertStringNotContainsString(
                '$lang(\'All categories\')',
                $headerBlock,
                'Group 3 section header must not use $lang(\'All categories\') — that duplicates the Group 1 tab text.'
            );
        }
    }

    #[Test]
    public function task_markers_present_in_vue(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-21-3d7892',
            $this->vue,
            'ListLayouts.vue must carry the AI-873 task-id marker.'
        );
    }
}
