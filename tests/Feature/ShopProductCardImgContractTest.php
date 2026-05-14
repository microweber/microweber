<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * audit-test 2026-05-08 PM TASK-017 / TICKET-AB regression coverage.
 *
 * Pins the cycle-52 <img> migration across the 4 PM-named files. Each test
 * reads the blade source and asserts:
 *   - a real <img> tag with src=
 *   - alt= attribute (alt="" allowed for decorative)
 *   - loading= attribute (lazy or eager)
 * AND that the prior `style="background-image: url('{{ ... }}')"` form is
 * gone (so a future regression that re-introduces the bg-image pattern
 * fails the test instead of silently shipping).
 *
 * Style after `LiveEditCodeEditorScriptContractTest` and
 * `LiveEditMobileRightRailCssContractTest` per PM brief acceptance #10.
 */
class ShopProductCardImgContractTest extends TestCase
{
    /** @return array<string,string> */
    private function loadFile(string $path): string
    {
        return file_get_contents(base_path($path));
    }

    /**
     * Return the file content with all `{{-- ... --}}` Blade comment blocks
     * stripped. Negative-shape assertions ("old form must be gone") use this
     * to avoid false matches on comment text that documents the prior shape.
     */
    private function loadFileWithoutComments(string $path): string
    {
        $content = file_get_contents(base_path($path));
        return preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $content) ?? $content;
    }

    private function assertHasImg(string $content, string $where): void
    {
        // Post cycle-55 / TICKET-CX update: the canonical image path is
        // `responsive_thumbnail($src, $w, $h, [...])` which renders a real
        // <img> with src+srcset+alt+loading+decoding at runtime (defaults
        // pinned by ResponsiveThumbnailHelperTest + EagerFirstImageContractTest).
        // A file that uses the helper passes the contract — match either
        // a literal `<img src=...>` OR a `responsive_thumbnail(` call.
        $stripped = preg_replace('/\\{\\{[^}]*\\}\\}/', '__', $content);

        $hasLiteralImg = (bool) preg_match('/<img\\s[^>]*src=/is', $stripped);
        $hasResponsiveHelper = str_contains($content, 'responsive_thumbnail(');

        $this->assertTrue(
            $hasLiteralImg || $hasResponsiveHelper,
            "{$where}: expected an <img src=...> element OR a responsive_thumbnail(...) call"
        );

        if ($hasLiteralImg) {
            $this->assertMatchesRegularExpression(
                '/<img\\s[^>]*\\salt=/is',
                $stripped,
                "{$where}: expected alt= on the <img>"
            );
            $this->assertMatchesRegularExpression(
                '/<img\\s[^>]*\\sloading=/is',
                $stripped,
                "{$where}: expected loading= on the <img>"
            );
        } else {
            // responsive_thumbnail() emits alt + loading by default; pin
            // that the call passes size args. Use [\s\S]*? (non-greedy
            // cross-line) instead of [^)]* so nested parens in the src
            // argument (e.g. $product->mediaUrl()) don't prematurely stop
            // the match before reaching the W,H arguments.
            $this->assertMatchesRegularExpression(
                "/responsive_thumbnail\\([\\s\\S]*?\\d+,\\s*\\d+/s",
                $content,
                "{$where}: expected responsive_thumbnail(\$src, W, H, [...]) with explicit width/height"
            );
        }
    }

    private function assertNoLiveBgImage(string $content, string $where): void
    {
        // Any live `background-image: url('{{ ...` outside Blade comments is a regression.
        $lines = preg_split('/\r?\n/', $content);
        foreach ($lines as $idx => $line) {
            if (! preg_match("/background-image:\\s*url\\('\\{\\{/", $line)) {
                continue;
            }
            // The Blade-comment form `{{-- ... --}}` is allowed (documentation).
            // Heuristic: if the line is part of a {{-- ... --}} block, skip.
            // We check by looking for the comment start on this line OR within
            // the preceding 30 lines without an intervening close.
            $isInBladeComment = false;
            for ($i = $idx; $i >= max(0, $idx - 30); $i--) {
                if (str_contains($lines[$i], '{{--')) {
                    $isInBladeComment = true;
                    break;
                }
                if (str_contains($lines[$i], '--}}')) {
                    break;
                }
            }
            // Also allow lines with backticks (markdown-doc-comment style inside @php).
            if (str_contains($line, '`background-image')) {
                $isInBladeComment = true;
            }
            $this->assertTrue(
                $isInBladeComment,
                "{$where}: live `background-image: url('{{` regression on line " . ($idx + 1) . ": {$line}"
            );
        }
    }

    #[Test]
    public function shop_product_card_uses_img_not_background_image(): void
    {
        $c = $this->loadFile('Modules/Shop/resources/views/livewire/shop/product-card.blade.php');
        $this->assertHasImg($c, 'Shop/product-card.blade.php');
        $this->assertNoLiveBgImage($c, 'Shop/product-card.blade.php');
    }

    #[Test]
    public function shop_product_card_skin_1_uses_img_not_background_image(): void
    {
        $c = $this->loadFile('Modules/Shop/resources/views/livewire/shop/product-card-skin-1.blade.php');
        $this->assertHasImg($c, 'Shop/product-card-skin-1.blade.php');
        $this->assertNoLiveBgImage($c, 'Shop/product-card-skin-1.blade.php');
    }

    #[Test]
    public function product_skin_7_uses_img_not_background_image(): void
    {
        $c = $this->loadFile('Modules/Product/resources/views/templates/skin-7.blade.php');
        $this->assertHasImg($c, 'Product/skin-7.blade.php');
        $this->assertNoLiveBgImage($c, 'Product/skin-7.blade.php');
    }

    #[Test]
    public function background_default_uses_img_not_background_image(): void
    {
        $c = $this->loadFile('Modules/Background/resources/views/templates/default.blade.php');
        $this->assertHasImg($c, 'Background/default.blade.php');
        $this->assertNoLiveBgImage($c, 'Background/default.blade.php');
    }

    #[Test]
    public function shop_product_card_thumbnails_use_smaller_dimensions(): void
    {
        $cardA = $this->loadFile('Modules/Shop/resources/views/livewire/shop/product-card.blade.php');
        $cardB = $this->loadFile('Modules/Shop/resources/views/livewire/shop/product-card-skin-1.blade.php');
        $skin7 = $this->loadFile('Modules/Product/resources/views/templates/skin-7.blade.php');

        // Acceptance #3 — dropped from 1000x1000 / 1250x1250 to 800x600.
        // Product-card Livewire views use responsive_thumbnail($product->mediaUrl(), 800, 600, [...]).
        // skin-7 uses responsive_thumbnail($item['image'], 800, 600, [...]).
        // Match either `responsive_thumbnail(` with 800, 600 args OR `->thumbnail(800, 600)`.
        $this->assertTrue(
            (bool) preg_match('/responsive_thumbnail\([\s\S]*?800,\s*600/s', $cardA) ||
            (bool) preg_match('/thumbnail\(800,\s*600\)/', $cardA),
            'Shop/product-card.blade.php: expected thumbnail at 800x600'
        );
        $this->assertTrue(
            (bool) preg_match('/responsive_thumbnail\([\s\S]*?800,\s*600/s', $cardB) ||
            (bool) preg_match('/thumbnail\(800,\s*600\)/', $cardB),
            'Shop/product-card-skin-1.blade.php: expected thumbnail at 800x600'
        );
        $this->assertMatchesRegularExpression(
            "/responsive_thumbnail\\(\\\$item\\['image'\\],\\s*800,\\s*600/s",
            $skin7,
            'Product/skin-7.blade.php: expected responsive_thumbnail($item[image], 800, 600, …)'
        );

        // The old sizes must be gone (excluding any blade-comment text).
        $cardANoCmt = $this->loadFileWithoutComments('Modules/Shop/resources/views/livewire/shop/product-card.blade.php');
        $cardBNoCmt = $this->loadFileWithoutComments('Modules/Shop/resources/views/livewire/shop/product-card-skin-1.blade.php');
        $skin7NoCmt = $this->loadFileWithoutComments('Modules/Product/resources/views/templates/skin-7.blade.php');
        $this->assertStringNotContainsString('thumbnail(1000,1000)', $cardANoCmt);
        $this->assertStringNotContainsString('thumbnail(1000,1000)', $cardBNoCmt);
        $this->assertStringNotContainsString('1250, 1250', $skin7NoCmt);
    }

    #[Test]
    public function price_range_inputs_are_numeric(): void
    {
        $c = $this->loadFile('Modules/Shop/resources/views/livewire/shop/filters/price_range/index.blade.php');
        // Acceptance #6 — type=number + inputmode + min + step.
        $this->assertStringContainsString('type="number"', $c);
        $this->assertStringContainsString('inputmode="decimal"', $c);
        $this->assertStringContainsString('min="0"', $c);
        $this->assertStringContainsString('step="any"', $c);
        // Old shape gone (excluding any blade-comment text).
        $cNoCmt = $this->loadFileWithoutComments('Modules/Shop/resources/views/livewire/shop/filters/price_range/index.blade.php');
        $this->assertStringNotContainsString('type="text" class="form-control" wire:model.live="priceFrom"', $cNoCmt);
        $this->assertStringNotContainsString('type="text" class="form-control" wire:model.live="priceTo"', $cNoCmt);
    }

    #[Test]
    public function shop_search_uses_debounced_wire_model(): void
    {
        $c = $this->loadFile('Modules/Shop/resources/views/livewire/shop/filters/top/index.blade.php');
        // Acceptance #7 — .debounce.500ms.
        $this->assertStringContainsString('wire:model.live.debounce.500ms="keywords"', $c);
        // Old un-debounced shape gone (excluding any blade-comment text).
        $cNoCmt = $this->loadFileWithoutComments('Modules/Shop/resources/views/livewire/shop/filters/top/index.blade.php');
        $this->assertStringNotContainsString('wire:model.live="keywords"', $cNoCmt);
    }

    #[Test]
    public function shop_grid_has_aria_live_and_busy(): void
    {
        $cDefault = $this->loadFile('Modules/Shop/resources/views/livewire/shop/default.blade.php');
        $cSkin1 = $this->loadFile('Modules/Shop/resources/views/livewire/shop/skin-1.blade.php');
        // Acceptance #8 — aria-live + wire:loading.attr=aria-busy.
        $this->assertStringContainsString('aria-live="polite"', $cDefault);
        $this->assertStringContainsString('wire:loading.attr="aria-busy"', $cDefault);
        $this->assertStringContainsString('aria-live="polite"', $cSkin1);
        $this->assertStringContainsString('wire:loading.attr="aria-busy"', $cSkin1);
    }

    #[Test]
    public function shop_product_card_tag_chip_uses_filter_tag_wire_click(): void
    {
        $cardA = $this->loadFile('Modules/Shop/resources/views/livewire/shop/product-card.blade.php');
        $cardB = $this->loadFile('Modules/Shop/resources/views/livewire/shop/product-card-skin-1.blade.php');
        // Acceptance #9 — wire:click="filterTag(...)" replaces the query-wiping <a href="?tags[]=">.
        $this->assertStringContainsString('wire:click="filterTag(', $cardA);
        $this->assertStringContainsString('wire:click="filterTag(', $cardB);
        // Old shape gone (excluding any blade-comment text).
        $cardANoCmt = $this->loadFileWithoutComments('Modules/Shop/resources/views/livewire/shop/product-card.blade.php');
        $cardBNoCmt = $this->loadFileWithoutComments('Modules/Shop/resources/views/livewire/shop/product-card-skin-1.blade.php');
        $this->assertStringNotContainsString('href="?tags[]=', $cardANoCmt);
        $this->assertStringNotContainsString('href="?tags[]=', $cardBNoCmt);
    }
}
