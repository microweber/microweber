<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Factories\ColorPaletteFactory;
use Tests\TestCase;

/**
 * Non-browser coverage of {@see ColorPaletteFactory}. Verifies the
 * seed path (admin + Bootstrap template + populated page) without
 * spinning up Chromedriver. The browser-only behavior (navigating
 * into live-edit) is exercised by the per-palette Dusk tests that
 * compose this factory under the Color Scheme Coverage Plan.
 */
class ColorPaletteFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ColorPaletteFactory::cleanupAll();
    }

    protected function tearDown(): void
    {
        ColorPaletteFactory::cleanupAll();
        parent::tearDown();
    }

    #[Test]
    public function make_seeds_admin_user_and_bootstrap_template_and_populated_page(): void
    {
        $fixture = ColorPaletteFactory::make('Palette fixture check');

        try {
            $admin = User::where('email', ColorPaletteFactory::ADMIN_EMAIL)->first();
            $this->assertNotNull($admin, 'Admin user must exist after make()');
            $this->assertSame(1, (int)$admin->is_admin, 'Seeded user must have is_admin=1');
            $this->assertSame(1, (int)$admin->is_active, 'Seeded user must have is_active=1');

            $option = DB::table('options')
                ->where('option_key', 'current_template')
                ->where('option_group', 'template')
                ->first();
            $this->assertNotNull($option, 'current_template option row must exist');
            $this->assertSame('Bootstrap', $option->option_value,
                'Active template must be Bootstrap after make()');

            $page = DB::table('content')->where('id', $fixture->pageId)->first();
            $this->assertNotNull($page, 'Page row must exist after make()');
            $this->assertSame('page', $page->content_type);
            $this->assertSame('static', $page->subtype);
            $this->assertSame('Bootstrap', $page->active_site_template);
            $this->assertSame('clean.blade.php', $page->layout_file);
            $this->assertSame(1, (int)$page->is_active);
            $this->assertSame($fixture->slug, $page->url);
            $this->assertStringStartsWith(
                ColorPaletteFactory::SLUG_PREFIX,
                $fixture->slug,
                'Fixture slug must use the color-palette-test- prefix'
            );
        } finally {
            $fixture->cleanup();
        }
    }

    #[Test]
    public function make_populates_content_with_the_four_layout_module_tags(): void
    {
        $fixture = ColorPaletteFactory::make('Palette content body check');

        try {
            $page = DB::table('content')->where('id', $fixture->pageId)->first();
            $this->assertNotNull($page);

            $body = (string)($page->content ?? '');
            $this->assertNotSame('', $body, 'content.content must be pre-populated by the factory');

            foreach (ColorPaletteFactory::layoutModules() as $template) {
                $this->assertStringContainsString(
                    'template="' . $template . '"',
                    $body,
                    "Seeded content must compose the {$template} layout module"
                );
            }

            // Microweber's save pipeline normalizes self-closing
            // `<module .../>` to paired `<module ...></module>`, so
            // compare on a tag-shape-insensitive canonical form.
            $canonical = static fn (string $html): string => preg_replace(
                '#<module\s+([^>]*?)\s*/?>(?:\s*</module>)?#i',
                '<module $1>',
                $html
            );

            $this->assertSame(
                $canonical(ColorPaletteFactory::buildContentBody()),
                $canonical($body),
                'Persisted content body must match the factory blueprint modulo tag-shape normalization'
            );
        } finally {
            $fixture->cleanup();
        }
    }

    #[Test]
    public function layout_modules_cover_every_palette_touchpoint(): void
    {
        // Jumbotron + Content + Pricing + Footer between them exercise
        // the CSS-custom-property "touchpoints" the Color Scheme plan
        // calls out: header, body, primary-button, section-background.
        // Regression guard: if someone drops one of these skins from
        // the factory, this test fails with the missing category.
        $required = ['jumbotron', 'content', 'pricing', 'footers'];

        $layouts = ColorPaletteFactory::layoutModules();
        foreach ($required as $category) {
            $match = array_filter(
                $layouts,
                static fn (string $tpl) => str_starts_with($tpl, $category . '/')
            );
            $this->assertNotEmpty(
                $match,
                "Factory must include a {$category}/* skin to cover palette touchpoints"
            );
        }
    }

    #[Test]
    public function cleanup_removes_the_fixture_page(): void
    {
        $fixture = ColorPaletteFactory::make('Palette cleanup check');
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
    public function cleanup_all_sweeps_every_color_palette_test_prefix_row(): void
    {
        $fixtures = [
            ColorPaletteFactory::make('sweep a'),
            ColorPaletteFactory::make('sweep b'),
            ColorPaletteFactory::make('sweep c'),
        ];

        $ids = array_map(static fn ($f) => $f->pageId, $fixtures);

        $before = DB::table('content')
            ->whereIn('id', $ids)
            ->count();
        $this->assertSame(3, $before, 'Sanity: all three fixtures must exist before sweep');

        $purged = ColorPaletteFactory::cleanupAll();
        $this->assertCount(3, array_intersect($purged, $ids),
            'cleanupAll must return at least the ids it purged');

        $after = DB::table('content')
            ->whereIn('id', $ids)
            ->count();
        $this->assertSame(0, $after, 'cleanupAll must remove every color-palette-test-* page');
    }

    #[Test]
    public function make_produces_unique_slugs_across_calls(): void
    {
        $a = ColorPaletteFactory::make('slug uniqueness a');
        $b = ColorPaletteFactory::make('slug uniqueness b');

        try {
            $this->assertNotSame(
                $a->slug,
                $b->slug,
                'Two consecutive make() calls must yield distinct slugs'
            );
            $this->assertNotSame($a->pageId, $b->pageId);
        } finally {
            $a->cleanup();
            $b->cleanup();
        }
    }
}
