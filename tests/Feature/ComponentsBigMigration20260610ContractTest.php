<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract test for the mw_componenents_big.diff component migration
 * (task-2026-06-10 — Phase B/C of COMPONENTS_MIGRATION_PLAN).
 *
 * Pins, so the conversion can't silently regress:
 *   1. All 8 new components are registered in ComponentsServiceProvider.
 *   2. The 8 skins that consume a component directly still reference its
 *      `<x-…>` tag.
 *   3. The 4 Big "container" layout skins still embed the migrated module
 *      skin (`<module type="…" template="skin-N">`) that holds the component.
 *
 * DataProvider methods return RELATIVE paths only — Laravel helpers
 * (base_path()) run pre-boot inside providers and would fatal; the path is
 * resolved per-test via contents().
 */
class ComponentsBigMigration20260610ContractTest extends TestCase
{
    private function contents(string $relativePath): string
    {
        $full = base_path($relativePath);
        $this->assertFileExists($full, "Expected migrated file missing: {$relativePath}");

        return (string) file_get_contents($full);
    }

    public static function registrationProvider(): array
    {
        return [
            ['pricing-table', 'PricingTable'],
            ['pricing-row', 'PricingRow'],
            ['testimonial-card', 'TestimonialCard'],
            ['team-card', 'TeamCard'],
            ['content-card', 'ContentCard'],
            ['product-card', 'ProductCard'],
            ['post-card', 'PostCard'],
            ['media-card', 'MediaCard'],
        ];
    }

    #[Test]
    #[DataProvider('registrationProvider')]
    public function each_new_component_is_registered(string $tag, string $class): void
    {
        $src = $this->contents('Modules/Components/Providers/ComponentsServiceProvider.php');

        $this->assertMatchesRegularExpression(
            '/Blade::component\(\s*[\'"]' . preg_quote($tag, '/') . '[\'"]\s*,\s*' . $class . '::class\s*\)/',
            $src,
            "Component '{$tag}' is not registered as {$class}::class"
        );

        // class + view both exist
        $this->assertFileExists(base_path("Modules/Components/View/Components/{$class}.php"));
        $this->assertFileExists(base_path("Modules/Components/resources/views/components/{$tag}.blade.php"));
    }

    public static function directConsumerProvider(): array
    {
        return [
            // [skin path, expected <x- tags]
            'Big content/skin-90'        => ['Templates/Big/resources/views/modules/layouts/templates/content/skin-90.blade.php', ['<x-content-card']],
            'Big gallery/skin-29'        => ['Templates/Big/resources/views/modules/layouts/templates/gallery/skin-29.blade.php', ['<x-media-card']],
            'Big price_lists/skin-19'    => ['Templates/Big/resources/views/modules/layouts/templates/price_lists/skin-19.blade.php', ['<x-pricing-table', '<x-pricing-row']],
            'Bootstrap pricing/skin-1'   => ['Templates/Bootstrap/resources/views/modules/layouts/templates/pricing/skin-1.blade.php', ['<x-pricing-table', '<x-pricing-row']],
            'Post skin-26'               => ['Modules/Post/resources/views/templates/skin-26.blade.php', ['<x-post-card']],
            'Product skin-12'            => ['Modules/Product/resources/views/templates/skin-12.blade.php', ['<x-product-card']],
            'Teamcard skin-19'           => ['Modules/Teamcard/resources/views/templates/skin-19.blade.php', ['<x-team-card']],
            'Testimonials skin-23'       => ['Modules/Testimonials/resources/views/templates/skin-23.blade.php', ['<x-testimonial-card']],
        ];
    }

    #[Test]
    #[DataProvider('directConsumerProvider')]
    public function migrated_skin_references_its_component(string $path, array $tags): void
    {
        $src = $this->contents($path);

        foreach ($tags as $tag) {
            $this->assertStringContainsString($tag, $src, "{$path} no longer references {$tag}");
        }
    }

    public static function containerProvider(): array
    {
        return [
            // [Big layout skin, embedded module type, embedded template]
            'blog/skin-25 -> posts/skin-26'           => ['Templates/Big/resources/views/modules/layouts/templates/blog/skin-25.blade.php', 'posts', 'skin-26'],
            'team/skin-21 -> teamcard/skin-19'        => ['Templates/Big/resources/views/modules/layouts/templates/team/skin-21.blade.php', 'teamcard', 'skin-19'],
            'testimonials/skin-26 -> testimonials/skin-23' => ['Templates/Big/resources/views/modules/layouts/templates/testimonials/skin-26.blade.php', 'testimonials', 'skin-23'],
            'ecommerce/skin-15 -> shop/products/skin-12'   => ['Templates/Big/resources/views/modules/layouts/templates/ecommerce/skin-15.blade.php', 'shop/products', 'skin-12'],
        ];
    }

    #[Test]
    #[DataProvider('containerProvider')]
    public function container_layout_embeds_migrated_module_skin(string $path, string $type, string $template): void
    {
        $src = $this->contents($path);

        $this->assertMatchesRegularExpression(
            '/<module\s+type="' . preg_quote($type, '/') . '"\s+template="' . preg_quote($template, '/') . '"/',
            $src,
            "{$path} no longer embeds <module type=\"{$type}\" template=\"{$template}\">"
        );
    }
}
