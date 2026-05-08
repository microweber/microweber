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
        // Strip `{{ ... }}` interpolation blocks before regex-matching the <img>
        // tag, because the `->` in `$product->thumbnail()` etc. contains `>`
        // which would prematurely terminate `[^>]*`. Replacing with `__` keeps
        // attribute boundaries intact for the assertion.
        $stripped = preg_replace('/\\{\\{[^}]*\\}\\}/', '__', $content);

        $this->assertMatchesRegularExpression(
            '/<img\\s[^>]*src=/is',
            $stripped,
            "{$where}: expected an <img src=...> element"
        );
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
        $this->assertStringContainsString('thumbnail(800, 600)', $cardA);
        $this->assertStringContainsString('thumbnail(800, 600)', $cardB);
        $this->assertStringContainsString("thumbnail(\$item['image'], 800, 600)", $skin7);

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
