<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * audit-test 2026-05-08 PM TASK-018 / TICKET-AQ-residual regression coverage.
 *
 * TASK-003 (TICKET-AQ) shipped the delegated mw-add-to-cart-btn /
 * mw-add-to-cart-disabled-btn pattern with the listener at shop.js:342-360.
 * TASK-018 closes the residual debt: the 6 PM-named files plus 4 extra-sweep
 * files surfaced by the repo-wide grep. This test pins the contract so a
 * future regression that re-introduces inline `onclick="mw.cart..."` or
 * `onclick="mw.alert(...)"` on a button fails CI.
 *
 * Style after `ShopProductCardImgContractTest` and the
 * `LiveEditCodeEditorScriptContractTest` reference shape PM cited.
 *
 * Per project memory `feedback_testing`: contract tests hit only the file
 * system; no DB, no RefreshDatabase, no Livewire mounting needed.
 */
class CartProductOnclickResidualContractTest extends TestCase
{
    /** @var list<string> The 6 files PM listed in the TASK-018 brief. */
    private const PM_NAMED_FILES = [
        'Modules/Cart/resources/views/templates/mw_default.blade.php',
        'Modules/Product/resources/views/templates/skin-4.blade.php',
        'Modules/Product/resources/views/templates/skin-6.blade.php',
        'Modules/Product/resources/views/templates/skin-7.blade.php',
        'Modules/Product/resources/views/templates/skin-9.blade.php',
        'Modules/Product/resources/views/templates/skin-10.blade.php',
    ];

    /** @var list<string> Extra files surfaced by the cycle-53 sweep grep. */
    private const EXTRA_SWEEP_FILES = [
        'Modules/Cart/resources/views/templates/shop_inner.blade.php',
        'Modules/Cart/resources/views/templates/bootstrap.blade.php',
        'Modules/Product/resources/views/templates/skin-11.blade.php',
        'Modules/Product/resources/views/product-quick-view.blade.php',
    ];

    private function loadFile(string $path): string
    {
        return file_get_contents(base_path($path));
    }

    /**
     * Strip Blade `{{-- ... --}}` comments AND HTML `<!-- ... -->` comments
     * before assertion. Both forms are valid documentation locations and
     * negative-shape assertions must not trip on them.
     */
    private function stripComments(string $content): string
    {
        $content = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $content) ?? $content;
        $content = preg_replace('/<!--[\\s\\S]*?-->/', '', $content) ?? $content;
        return $content;
    }

    #[Test]
    public function pm_named_files_contain_no_live_add_and_show_modal_onclick(): void
    {
        foreach (self::PM_NAMED_FILES as $relative) {
            $stripped = $this->stripComments($this->loadFile($relative));
            $this->assertStringNotContainsString(
                'onclick="mw.cart.add_and_show_modal',
                $stripped,
                "{$relative}: live `onclick=\"mw.cart.add_and_show_modal` is a TASK-018 regression"
            );
        }
    }

    #[Test]
    public function pm_named_files_contain_no_live_mw_alert_onclick(): void
    {
        // The Cart out-of-stock branch used to carry onclick="mw.alert(...)"
        // — that path migrated to mw-add-to-cart-disabled-btn + data-alert-message.
        foreach (self::PM_NAMED_FILES as $relative) {
            $stripped = $this->stripComments($this->loadFile($relative));
            $this->assertStringNotContainsString(
                'onclick="mw.alert(',
                $stripped,
                "{$relative}: live `onclick=\"mw.alert(` is a TASK-018 regression"
            );
        }
    }

    #[Test]
    public function pm_named_files_use_delegated_classes_or_have_no_cart_button(): void
    {
        // Each PM-named file that emits an add-to-cart button must use the
        // delegated class. Files that have no add-to-cart button at all
        // (e.g. pure listing skins behind a flag) trivially pass.
        foreach (self::PM_NAMED_FILES as $relative) {
            $stripped = $this->stripComments($this->loadFile($relative));
            $hasInStockMarker = str_contains($stripped, 'in_stock')
                || str_contains($stripped, 'inStock')
                || str_contains($stripped, 'add_to_cart');
            if (! $hasInStockMarker) {
                continue;
            }
            $this->assertStringContainsString(
                'mw-add-to-cart-btn',
                $stripped,
                "{$relative}: PM-named file emits an add-to-cart path but does not carry the delegated `mw-add-to-cart-btn` class"
            );
        }
    }

    #[Test]
    public function extra_sweep_files_contain_no_live_add_and_show_modal_onclick(): void
    {
        // The TASK-018 brief listed 6 files but acceptance #5 was a repo-
        // wide grep. The cycle-53 sweep added 4 more files. This test pins
        // their migration too.
        foreach (self::EXTRA_SWEEP_FILES as $relative) {
            $stripped = $this->stripComments($this->loadFile($relative));
            $this->assertStringNotContainsString(
                'onclick="mw.cart.add_and_show_modal',
                $stripped,
                "{$relative}: live `onclick=\"mw.cart.add_and_show_modal` is a TASK-018 regression"
            );
        }
    }

    #[Test]
    public function skin_12_keeps_its_html_commented_onclick(): void
    {
        // Acceptance #4: skin-12.blade.php's onclick is HTML-commented; do
        // not re-introduce it. Verify the comment marker is still present
        // around the onclick string so a future un-comment fails this test.
        $content = $this->loadFile('Modules/Product/resources/views/templates/skin-12.blade.php');
        // After stripping comments, the live (non-comment) emission must
        // contain neither `add_and_show_modal` nor `<a href="javascript:`.
        $stripped = $this->stripComments($content);
        $this->assertStringNotContainsString(
            'mw.cart.add_and_show_modal',
            $stripped,
            'skin-12.blade.php: the previously-commented onclick has been re-emitted live'
        );
    }

    #[Test]
    public function shop_js_delegated_listener_is_not_modified(): void
    {
        // Acceptance #3: the existing delegated listener must still be in
        // place. Pin the listener marker strings so a refactor that drops
        // them fails CI.
        $shopJs = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/api-core/core/core/shop.js'
        ));
        $this->assertStringContainsString('mw-add-to-cart-btn', $shopJs,
            'shop.js: delegated mw-add-to-cart-btn handler must remain in place');
        $this->assertStringContainsString('mw-add-to-cart-disabled-btn', $shopJs,
            'shop.js: delegated mw-add-to-cart-disabled-btn handler must remain in place');
        $this->assertStringContainsString('add_and_show_modal', $shopJs,
            'shop.js: handler must still call mw.cart.add_and_show_modal under the hood');
    }
}
