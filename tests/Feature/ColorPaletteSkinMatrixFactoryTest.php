<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteSkinMatrixFactory;
use Tests\TestCase;

/**
 * Non-browser coverage of {@see ColorPaletteSkinMatrixFactory}.
 *
 * Verifies the seed path (admin + Bootstrap template + one populated
 * page per target skin) without Chromedriver. The browser-only
 * per-skin palette-apply behavior is exercised by the Phase-7
 * `LiveEditColorPaletteSkinMatrixTest` that consumes this factory.
 */
class ColorPaletteSkinMatrixFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ColorPaletteSkinMatrixFactory::cleanupAll();
    }

    protected function tearDown(): void
    {
        ColorPaletteSkinMatrixFactory::cleanupAll();
        parent::tearDown();
    }

    #[Test]
    public function target_skins_matches_phase_7_plan_entry(): void
    {
        // Regression guard: the plan enumerates exactly these tags.
        // If a contributor reshuffles the list, the plan row in TODO.md
        // and the factory const must move together — this test fails
        // loudly when they diverge.
        $this->assertSame(
            [
                'jumbotron/skin-1',
                'jumbotron/skin-2',
                'features/skin-1',
                'features/skin-2',
                'pricing/skin-1',
                'pricing/skin-2',
                'pricing/skin-3',
                'titles/skin-1',
                'content/skin-1',
                'blog/skin-1',
                'ecommerce/skin-1',
                'footers/skin-1',
                'text-block/skin-1',
                'menus/skin-1',
            ],
            ColorPaletteSkinMatrixFactory::TARGET_SKINS,
            'TARGET_SKINS must mirror the Phase-7 plan entry verbatim'
        );
    }

    #[Test]
    public function available_skins_filters_to_on_disk_blade_files(): void
    {
        $available = ColorPaletteSkinMatrixFactory::availableSkins();
        $pending = ColorPaletteSkinMatrixFactory::pendingSkins();

        $this->assertNotEmpty(
            $available,
            'At least one target skin must resolve to a blade file on disk'
        );

        $this->assertSame(
            count(ColorPaletteSkinMatrixFactory::TARGET_SKINS),
            count($available) + count($pending),
            'availableSkins() + pendingSkins() must partition TARGET_SKINS'
        );

        foreach ($available as $skin) {
            $this->assertTrue(
                ColorPaletteSkinMatrixFactory::skinBladeExists($skin),
                "availableSkins() returned '{$skin}' but the blade file "
                . 'is missing'
            );
        }
        foreach ($pending as $skin) {
            $this->assertFalse(
                ColorPaletteSkinMatrixFactory::skinBladeExists($skin),
                "pendingSkins() listed '{$skin}' but the blade file "
                . 'actually exists — it should be in availableSkins()'
            );
        }

        $this->assertEmpty(
            array_intersect($available, $pending),
            'availableSkins() and pendingSkins() must be disjoint'
        );
    }

    #[Test]
    public function make_for_skin_seeds_admin_bootstrap_template_and_single_skin_body(): void
    {
        $skins = ColorPaletteSkinMatrixFactory::availableSkins();
        $this->assertNotEmpty($skins);
        $skin = $skins[0];

        $fixture = ColorPaletteSkinMatrixFactory::makeForSkin($skin);

        try {
            $admin = User::where(
                'email',
                ColorPaletteSkinMatrixFactory::ADMIN_EMAIL
            )->first();
            $this->assertNotNull($admin, 'Admin user must exist after makeForSkin()');
            $this->assertSame(1, (int)$admin->is_admin);
            $this->assertSame(1, (int)$admin->is_active);

            $option = DB::table('options')
                ->where('option_key', 'current_template')
                ->where('option_group', 'template')
                ->first();
            $this->assertNotNull($option);
            $this->assertSame('Bootstrap', $option->option_value);

            $page = DB::table('content')->where('id', $fixture->pageId)->first();
            $this->assertNotNull($page);
            $this->assertSame('page', $page->content_type);
            $this->assertSame('static', $page->subtype);
            $this->assertSame('Bootstrap', $page->active_site_template);
            $this->assertSame('clean.blade.php', $page->layout_file);
            $this->assertSame(1, (int)$page->is_active);
            $this->assertSame($fixture->slug, $page->url);
            $this->assertSame($skin, $fixture->skin);
            $this->assertStringStartsWith(
                ColorPaletteSkinMatrixFactory::SLUG_PREFIX,
                $fixture->slug
            );

            $body = (string)($page->content ?? '');
            $this->assertStringContainsString(
                'template="' . $skin . '"',
                $body,
                "Seeded content must embed exactly the '{$skin}' module tag"
            );
            $this->assertSame(
                1,
                preg_match_all(
                    '#<module\s+type="layouts"\s+template="[^"]+"#i',
                    $body
                ),
                'makeForSkin must embed exactly ONE layout module tag '
                . '— the whole point of the skin matrix is one-skin-per-page'
            );
        } finally {
            $fixture->cleanup();
        }
    }

    #[Test]
    public function make_for_skin_throws_for_missing_blade(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('no blade file');

        ColorPaletteSkinMatrixFactory::makeForSkin('nope/skin-999');
    }

    #[Test]
    public function make_all_produces_one_fixture_per_available_skin(): void
    {
        $fixtures = ColorPaletteSkinMatrixFactory::makeAll();

        try {
            $available = ColorPaletteSkinMatrixFactory::availableSkins();

            $this->assertCount(
                count($available),
                $fixtures,
                'makeAll() must return exactly one fixture per available skin'
            );

            foreach ($available as $skin) {
                $this->assertArrayHasKey(
                    $skin,
                    $fixtures,
                    "makeAll() missing fixture for available skin '{$skin}'"
                );
                $this->assertSame($skin, $fixtures[$skin]->skin);

                $page = DB::table('content')
                    ->where('id', $fixtures[$skin]->pageId)
                    ->first();
                $this->assertNotNull(
                    $page,
                    "Page for skin '{$skin}' must exist in DB"
                );
                $this->assertStringContainsString(
                    'template="' . $skin . '"',
                    (string)($page->content ?? ''),
                    "Page body for skin '{$skin}' must embed its own "
                    . 'layout module tag'
                );
            }

            // Unique ids and unique slugs — no fixture should collide
            // with another even though they all share the same
            // factory-wide prefix.
            $ids = array_map(
                static fn (ColorPaletteSkinMatrixFactory $f) => $f->pageId,
                $fixtures
            );
            $slugs = array_map(
                static fn (ColorPaletteSkinMatrixFactory $f) => $f->slug,
                $fixtures
            );
            $this->assertSame(
                count($ids),
                count(array_unique($ids)),
                'makeAll() must not produce duplicate page ids'
            );
            $this->assertSame(
                count($slugs),
                count(array_unique($slugs)),
                'makeAll() must not produce duplicate slugs'
            );
        } finally {
            foreach ($fixtures as $fixture) {
                $fixture->cleanup();
            }
        }
    }

    #[Test]
    public function cleanup_removes_the_fixture_page(): void
    {
        $skin = ColorPaletteSkinMatrixFactory::availableSkins()[0];
        $fixture = ColorPaletteSkinMatrixFactory::makeForSkin($skin);
        $id = $fixture->pageId;

        $this->assertNotNull(
            DB::table('content')->where('id', $id)->first(),
            'Sanity: page row must exist before cleanup'
        );

        $fixture->cleanup();

        $this->assertNull(
            DB::table('content')->where('id', $id)->first(),
            'Page row must be gone after cleanup()'
        );
    }

    #[Test]
    public function cleanup_all_sweeps_only_the_skin_matrix_prefix(): void
    {
        $skin = ColorPaletteSkinMatrixFactory::availableSkins()[0];
        $a = ColorPaletteSkinMatrixFactory::makeForSkin($skin);
        $b = ColorPaletteSkinMatrixFactory::makeForSkin($skin);

        $ids = [$a->pageId, $b->pageId];
        $this->assertSame(
            2,
            DB::table('content')->whereIn('id', $ids)->count(),
            'Sanity: both fixtures must exist before sweep'
        );

        $purged = ColorPaletteSkinMatrixFactory::cleanupAll();
        $this->assertGreaterThanOrEqual(
            2,
            count(array_intersect($purged, $ids)),
            'cleanupAll must return the ids it purged'
        );
        $this->assertSame(
            0,
            DB::table('content')->whereIn('id', $ids)->count(),
            'cleanupAll must remove every color-palette-skin-test-* page'
        );
    }
}
