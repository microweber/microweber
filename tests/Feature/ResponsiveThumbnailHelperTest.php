<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * audit-test 2026-05-08 PM TASK-012 / TICKET-CX regression coverage.
 *
 * Pins acceptance #6 of the responsive_thumbnail helper:
 *   - <img> output contains src + srcset + sizes + alt + loading + decoding
 *   - sizes default is 100vw, overridable via $options
 *   - loading default is lazy, override to eager works
 *   - srcset emits at least 2 widths (1x + 2x DPR by default)
 *   - alt is htmlspecialchars-escaped (no `<` / `>` / `"` leaks)
 *   - class / itemprop / id / style options render when supplied
 *   - empty alt is allowed (decorative-image case)
 *
 * Helper lives at Modules/Media/Support/media_functions.php and is
 * registered globally via composer autoload (existing thumbnail()
 * helper is in the same file). No DB touch required.
 */
class ResponsiveThumbnailHelperTest extends TestCase
{
    #[Test]
    public function responsive_thumbnail_function_is_globally_registered(): void
    {
        $this->assertTrue(
            function_exists('responsive_thumbnail'),
            'responsive_thumbnail() must be globally available via composer autoload'
        );
    }

    #[Test]
    public function output_contains_required_attributes(): void
    {
        $out = responsive_thumbnail('userfiles/media/test.jpg', 800, 600, [
            'alt' => 'Test image',
        ]);

        $this->assertMatchesRegularExpression('/<img\b/', $out, 'must emit a <img> tag');
        $this->assertStringContainsString('src="', $out);
        $this->assertStringContainsString('srcset="', $out);
        $this->assertStringContainsString('sizes="', $out);
        $this->assertStringContainsString('alt="', $out);
        $this->assertStringContainsString('loading="', $out);
        $this->assertStringContainsString('decoding="async"', $out);
    }

    #[Test]
    public function default_sizes_is_100vw_and_default_loading_is_lazy(): void
    {
        // AI-115 / TICKET-CG (cycle-105 2026-05-09): the helper now
        // consults a request-scoped counter and emits `loading="eager"`
        // for the FIRST `eager_first_n` (default 2) calls of a
        // request, then falls back to "lazy". To pin the historical
        // "default is lazy" guarantee we explicitly pass
        // `eager_first_n => 0` here so the counter never gates
        // eager.
        $out = responsive_thumbnail('userfiles/media/test.jpg', 800, 600, [
            'eager_first_n' => 0,
        ]);

        $this->assertStringContainsString('sizes="100vw"', $out);
        $this->assertStringContainsString('loading="lazy"', $out);
    }

    #[Test]
    public function loading_eager_override_emits_eager(): void
    {
        $out = responsive_thumbnail('userfiles/media/test.jpg', 800, 600, [
            'loading' => 'eager',
        ]);
        $this->assertStringContainsString('loading="eager"', $out);
        $this->assertStringNotContainsString('loading="lazy"', $out);
    }

    #[Test]
    public function srcset_carries_multiple_widths(): void
    {
        $out = responsive_thumbnail('userfiles/media/test.jpg', 400, 300);

        // Default srcset is [width, width*2] -> 400w + 800w.
        $this->assertMatchesRegularExpression('/srcset="[^"]*\\b400w\\b[^"]*"/', $out);
        $this->assertMatchesRegularExpression('/srcset="[^"]*\\b800w\\b[^"]*"/', $out);

        // Custom srcset list.
        $custom = responsive_thumbnail('userfiles/media/test.jpg', 800, 600, [
            'srcset' => [400, 800, 1200],
        ]);
        $this->assertMatchesRegularExpression('/srcset="[^"]*\\b400w\\b[^"]*"/', $custom);
        $this->assertMatchesRegularExpression('/srcset="[^"]*\\b800w\\b[^"]*"/', $custom);
        $this->assertMatchesRegularExpression('/srcset="[^"]*\\b1200w\\b[^"]*"/', $custom);
    }

    #[Test]
    public function alt_is_htmlspecialchars_escaped(): void
    {
        $out = responsive_thumbnail('userfiles/media/test.jpg', 800, 600, [
            'alt' => '<script>alert(1)</script> "quote" \'apos\'',
        ]);

        // Must NOT contain unescaped `<script>` or unescaped `"` inside alt.
        $this->assertStringNotContainsString('<script>', $out);
        $this->assertStringContainsString('&lt;script&gt;', $out);
        $this->assertStringContainsString('&quot;quote&quot;', $out);
    }

    #[Test]
    public function omitted_alt_falls_back_to_filename_basename(): void
    {
        // TASK-012 addition 2026-05-08: empty alt is forbidden on
        // product/content imagery. The helper must emit a non-empty alt
        // — derived from the src filename basename when no alt is passed.
        $out = responsive_thumbnail('userfiles/media/sunset-beach.jpg', 800, 600);

        $this->assertStringNotContainsString('alt=""', $out);
        $this->assertStringContainsString('alt="sunset beach"', $out);
    }

    #[Test]
    public function explicit_empty_alt_still_falls_back_to_filename(): void
    {
        // Callers that pass `'alt' => ''` (e.g. via a `?? ''` fallback)
        // must still get a non-empty alt — the helper is the last-line
        // safety net that prevents alt="" from ever shipping.
        $out = responsive_thumbnail('userfiles/media/Product_Hero.jpg', 800, 600, [
            'alt' => '',
        ]);

        $this->assertStringNotContainsString('alt=""', $out);
        $this->assertStringContainsString('alt="Product Hero"', $out);
    }

    #[Test]
    public function unusable_filename_falls_back_to_generic_image(): void
    {
        // If the basename is empty (e.g. '' or '/') the helper falls back
        // to the literal 'Image' — never alt="".
        $out = responsive_thumbnail('', 800, 600);
        $this->assertStringNotContainsString('alt=""', $out);
        $this->assertStringContainsString('alt="Image"', $out);
    }

    #[Test]
    public function class_itemprop_id_style_options_render_when_supplied(): void
    {
        $out = responsive_thumbnail('userfiles/media/test.jpg', 800, 600, [
            'class' => 'img-fluid d-block',
            'itemprop' => 'image',
            'id' => 'product-thumb-1',
            'style' => 'object-fit: cover;',
        ]);

        $this->assertStringContainsString('class="img-fluid d-block"', $out);
        $this->assertStringContainsString('itemprop="image"', $out);
        $this->assertStringContainsString('id="product-thumb-1"', $out);
        $this->assertStringContainsString('style="object-fit: cover;"', $out);
    }

    #[Test]
    public function class_itemprop_id_style_options_omitted_when_not_supplied(): void
    {
        $out = responsive_thumbnail('userfiles/media/test.jpg', 800, 600);
        $this->assertStringNotContainsString('class=""', $out);
        $this->assertStringNotContainsString('itemprop=""', $out);
        $this->assertStringNotContainsString('id=""', $out);
        $this->assertStringNotContainsString('style=""', $out);
    }

    #[Test]
    public function helper_returns_a_string_not_an_object(): void
    {
        $out = responsive_thumbnail('userfiles/media/test.jpg', 800, 600);
        $this->assertIsString($out);
        // Sanity: not a leaked PHP object/array stringification.
        $this->assertStringStartsWith('<img', $out);
        $this->assertStringEndsWith('>', $out);
    }
}
