<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-69 / AI-74 / TICKET-H — srcset + lazy/eager sweep across Post
 * and Pictures public skins regression coverage.
 *
 * Pins:
 *   - 19 Post skins (skin-3, 7, 9, 12-22 except 11/24, 26, blog-pro,
 *     post-slider, pro_blog, related_posts) now route their listing
 *     image through `responsive_thumbnail()` instead of bare `<img>`.
 *   - 4 Pictures skins (blog_pro, skin-14, skin-18, skin-20) ditto.
 *   - The migration carries `class`, `style`, `itemprop`, `loading`
 *     options through to the helper so visual + a11y output is
 *     unchanged.
 *   - The cycle-55 helper invariants still hold (responsive_thumbnail
 *     emits src + srcset + sizes + alt + loading + decoding).
 *
 * Style after the cycle-52..68 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class PostPictureSrcsetSweepContractTest extends TestCase
{
    /**
     * Files migrated in cycle-69. Order matches the commit message;
     * each entry must satisfy the migration shape (helper present,
     * bare `<img src=` for the listing image gone).
     */
    // Updated 2026-06: skin-26 was rewritten to render through the
    // <x-post-card> Blade component, which owns the image markup itself
    // (the skin no longer emits an <img> nor calls responsive_thumbnail()
    // directly). It is therefore no longer part of this helper-call sweep.
    private const POST_SKINS = [
        'skin-3.blade.php', 'skin-7.blade.php', 'skin-9.blade.php',
        'skin-12.blade.php', 'skin-13.blade.php', 'skin-14.blade.php',
        'skin-15.blade.php', 'skin-16.blade.php', 'skin-17.blade.php',
        'skin-18.blade.php', 'skin-19.blade.php', 'skin-20.blade.php',
        'skin-21.blade.php', 'skin-22.blade.php',
        'blog-pro.blade.php', 'post-slider.blade.php',
        'pro_blog.blade.php', 'related_posts.blade.php',
    ];

    private const PICTURES_SKINS = [
        'blog_pro.blade.php', 'skin-14.blade.php',
        'skin-18.blade.php', 'skin-20.blade.php',
    ];

    #[Test]
    public function every_migrated_post_skin_calls_responsive_thumbnail(): void
    {
        foreach (self::POST_SKINS as $skin) {
            $path = base_path('Modules/Post/resources/views/templates/' . $skin);
            $this->assertFileExists($path, "Post skin {$skin} must exist");
            $src = file_get_contents($path);
            $this->assertStringContainsString(
                'responsive_thumbnail(',
                $src,
                "Post skin {$skin}: must route its listing image through responsive_thumbnail() (cycle-55 helper)"
            );
        }
    }

    #[Test]
    public function every_migrated_pictures_skin_calls_responsive_thumbnail(): void
    {
        foreach (self::PICTURES_SKINS as $skin) {
            $path = base_path('Modules/Pictures/resources/views/templates/' . $skin);
            $this->assertFileExists($path, "Pictures skin {$skin} must exist");
            $src = file_get_contents($path);
            $this->assertStringContainsString(
                'responsive_thumbnail(',
                $src,
                "Pictures skin {$skin}: must route its listing image through responsive_thumbnail()"
            );
        }
    }

    #[Test]
    public function migrated_post_skins_no_longer_emit_bare_img_for_item_image(): void
    {
        // The audit-target shape was `<img ... src="{{ $item['image'] }}" ...>`
        // — that exact substring must be gone after the migration. We do
        // NOT forbid all `<img>` in these files (some skins also render
        // author avatars, decorative SVG icons, etc.) — just the listing
        // image case the helper now covers.
        foreach (self::POST_SKINS as $skin) {
            $path = base_path('Modules/Post/resources/views/templates/' . $skin);
            $src = file_get_contents($path);
            $this->assertStringNotContainsString(
                'src="{{ $item[\'image\'] }}"',
                $src,
                "Post skin {$skin}: bare `<img src=\"{{ \$item['image'] }}\">` must be replaced with responsive_thumbnail()"
            );
        }
    }

    #[Test]
    public function migration_preserves_visual_attributes(): void
    {
        // Spot-check four skins where the cycle-69 migration was
        // expected to carry through specific visual-attribute classes.
        // If a future refactor strips one of these, the helper output
        // would visually diverge from the pre-migration markup.
        $cases = [
            // [path, fragment that MUST appear in the helper call]
            ['Modules/Post/resources/views/templates/skin-3.blade.php',
                "'class' => 'img-fluid'"],
            ['Modules/Post/resources/views/templates/skin-9.blade.php',
                "'style' => 'object-fit: cover;'"],
            ['Modules/Post/resources/views/templates/blog-pro.blade.php',
                "'class' => 'border img-fluid'"],
            ['Modules/Post/resources/views/templates/related_posts.blade.php',
                "'itemprop' => 'image'"],
        ];
        foreach ($cases as [$rel, $needle]) {
            $src = file_get_contents(base_path($rel));
            $this->assertStringContainsString(
                $needle,
                $src,
                "{$rel}: helper call must preserve `{$needle}` from the original <img> tag"
            );
        }
    }

    #[Test]
    public function pictures_skin_14_hero_is_eager_loaded(): void
    {
        // The hero image on skin-14 sits above the fold; the migration
        // must explicitly opt the helper into loading="eager" so the
        // LCP candidate isn't deferred by lazy-loading.
        $src = file_get_contents(base_path(
            'Modules/Pictures/resources/views/templates/skin-14.blade.php'
        ));
        // The eager hero call spans multiple lines and contains `)` from
        // the `?? ''` fallback, so `[^)]*` would stop too early. Pin
        // the eager option directly + verify it lives in the file's
        // FIRST helper call (the hero), not a later one.
        $firstCallPos = strpos($src, 'responsive_thumbnail(');
        $eagerPos = strpos($src, "'loading' => 'eager'");
        $this->assertNotFalse($firstCallPos, 'skin-14 must call responsive_thumbnail()');
        $this->assertNotFalse($eagerPos, 'skin-14 must declare loading=eager somewhere');
        $this->assertGreaterThan(
            $firstCallPos,
            $eagerPos,
            'skin-14: loading=eager must appear AFTER the first responsive_thumbnail() call (the hero)'
        );
        // Also pin that the eager option falls inside the hero block by
        // requiring no subsequent `responsive_thumbnail(` between the
        // hero call and the eager option.
        $betweenCallAndEager = substr(
            $src,
            $firstCallPos + strlen('responsive_thumbnail('),
            $eagerPos - $firstCallPos - strlen('responsive_thumbnail(')
        );
        $this->assertStringNotContainsString(
            'responsive_thumbnail(',
            $betweenCallAndEager,
            'skin-14: loading=eager must be inside the first (hero) call, not a later call'
        );
    }

    #[Test]
    public function helper_still_emits_canonical_responsive_attributes(): void
    {
        // Sanity: re-verify the cycle-55 helper invariants still hold.
        // If the helper ever stops emitting srcset, the entire AI-74
        // sweep value collapses — pin it again here.
        $out = responsive_thumbnail('userfiles/media/post-cover.jpg', 800, null, [
            'alt' => 'Test post',
        ]);
        $this->assertStringContainsString('srcset="', $out);
        $this->assertStringContainsString('sizes="', $out);
        $this->assertStringContainsString('loading="', $out);
        $this->assertStringContainsString('decoding="async"', $out);
    }
}
