<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-265 product image optimization — bounded first slice contract test
 * (task-2026-05-13-de78ce).
 *
 * Pins the structural shape of the bounded slice we shipped on
 * filament-5: both shop product-card blade templates render their
 * image through `responsive_thumbnail($product->mediaUrl(), 800, 600, …)`
 * (gaining srcset + sizes + intelligent lazy/eager-first-N) and the
 * surrounding wrapper carries the `.mw-product-card-image-placeholder`
 * hook so the wrapper's background-color reserves layout space and
 * eliminates the white-flash while the network request resolves.
 *
 * The full WebP variant pipeline, LQIP blur-up, and 20%-LCP perf test
 * remain deferred to AI-265 follow-ups when the WebP variant generator
 * lands in MediaManager — this test only pins the slice that shipped
 * today.
 *
 * The legacy hand-rolled `<img src="{{ $product->thumbnail(800, 600) }}"
 * loading="lazy" decoding="async">` shape MUST be absent so a refactor
 * cannot silently regress the cards to non-srcset output.
 */
class AI265ProductImageContractTest extends TestCase
{
    private const PRODUCT_CARD_DEFAULT = __DIR__ . '/../../../Modules/Shop/resources/views/livewire/shop/product-card.blade.php';
    private const PRODUCT_CARD_SKIN_1 = __DIR__ . '/../../../Modules/Shop/resources/views/livewire/shop/product-card-skin-1.blade.php';
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const PUBLIC_TOUCH_CSS_SERVED = __DIR__ . '/../../../public/templates/bootstrap/css/public-touch.css';

    public static function productCardBladesProvider(): array
    {
        return [
            'default'   => [self::PRODUCT_CARD_DEFAULT],
            'skin-1'    => [self::PRODUCT_CARD_SKIN_1],
        ];
    }

    #[DataProvider('productCardBladesProvider')]
    #[Test]
    public function product_card_renders_image_via_responsive_thumbnail(string $path): void
    {
        $blade = $this->readFile($path);

        $this->assertMatchesRegularExpression(
            '/\{!!\s*responsive_thumbnail\(\s*\$product->mediaUrl\(\)\s*,\s*800\s*,\s*600\s*,\s*\[/s',
            $blade,
            basename($path) . ' must render its image via {!! responsive_thumbnail($product->mediaUrl(), 800, 600, [...]) !!}.'
        );
    }

    #[DataProvider('productCardBladesProvider')]
    #[Test]
    public function product_card_passes_sizes_attribute_for_grid_breakpoints(string $path): void
    {
        $blade = $this->readFile($path);

        $this->assertMatchesRegularExpression(
            "/'sizes'\\s*=>\\s*'\\(max-width: 575\\.98px\\) 100vw, \\(max-width: 991\\.98px\\) 50vw, 33vw'/",
            $blade,
            basename($path) . " must pass a sizes attribute matching the shop grid breakpoints (100vw < 575.98px, 50vw < 991.98px, 33vw beyond)."
        );
    }

    #[DataProvider('productCardBladesProvider')]
    #[Test]
    public function product_card_no_longer_emits_hand_rolled_img_with_thumbnail_helper(string $path): void
    {
        $blade = $this->readFile($path);

        $this->assertDoesNotMatchRegularExpression(
            '/<img\s+src="\{\{\s*\$product->thumbnail\(800,\s*600\)\s*\}\}"/',
            $blade,
            basename($path) . ' must NOT contain the legacy hand-rolled <img src="{{ $product->thumbnail(800, 600) }}"> — that shape skips srcset and was replaced by responsive_thumbnail().'
        );
    }

    #[DataProvider('productCardBladesProvider')]
    #[Test]
    public function product_card_wrapper_carries_placeholder_class(string $path): void
    {
        $blade = $this->readFile($path);

        $this->assertMatchesRegularExpression(
            '/class="[^"]*\bmw-product-card-image-placeholder\b[^"]*"/',
            $blade,
            basename($path) . ' wrapper must carry the .mw-product-card-image-placeholder hook so the CSS placeholder rule applies.'
        );
    }

    #[Test]
    public function public_touch_css_defines_the_placeholder_background_rule(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.mw-product-card-image-placeholder\s*\{[^}]*background-color:\s*var\(--color-surface-raised\)/s',
            $css,
            '.mw-product-card-image-placeholder must apply background-color: var(--color-surface-raised) in the canonical Templates/Bootstrap/.../public-touch.css.'
        );
    }

    #[Test]
    public function served_public_touch_css_mirrors_the_placeholder_rule(): void
    {
        if (!file_exists(self::PUBLIC_TOUCH_CSS_SERVED)) {
            $this->markTestSkipped('Served public-touch.css missing — Templates/Bootstrap has not been published to public/.');
        }

        $css = $this->readFile(self::PUBLIC_TOUCH_CSS_SERVED);

        $this->assertMatchesRegularExpression(
            '/\.mw-product-card-image-placeholder\s*\{[^}]*background-color:\s*var\(--color-surface-raised\)/s',
            $css,
            'Served public/templates/bootstrap/css/public-touch.css must mirror the placeholder rule from the canonical source.'
        );
    }

    private function readFile(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "File not found: {$path}");

        $contents = file_get_contents($real);
        $this->assertNotFalse($contents, "Could not read: {$path}");
        $this->assertNotEmpty($contents, "File is empty: {$path}");

        return $contents;
    }
}
