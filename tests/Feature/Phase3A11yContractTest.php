<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-60 / TASK-020 / TICKET-K + S + T / AI-38 — Phase 3 a11y/UX
 * sweep regression coverage.
 *
 * Pins the exact shapes from the agent-test 2026-05-08T16:59:31Z full
 * implementation brief:
 *
 *   K: row actions across 5 Filament resources (Content covers
 *      Posts/Pages; Product extends ContentResource; Order, Customer,
 *      Category direct) carry both `->label(fn ($record) => 'Edit
 *      "...title..."')` AND `->tooltip(fn ($record) => 'Edit
 *      "...title..."')`. Generic static `'Edit'` / `'Delete'` is
 *      forbidden on icon-only buttons.
 *   S: src/MicroweberPackages/Admin/resources/views/layouts/partials/
 *      topbar.blade.php Add New dropdown trigger carries
 *      aria-label="Add new content", aria-haspopup="listbox", AND
 *      retains its existing aria-expanded="false".
 *   T: packages/microweber-filament-theme/.../mobile-touch.css carries
 *      `.fi-breadcrumbs ol:has(> li:only-child) { display: none; }`.
 *
 * Style after the cycle-52..59 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class Phase3A11yContractTest extends TestCase
{
    private string $contentSrc;
    private string $orderSrc;
    private string $customerSrc;
    private string $categorySrc;
    private string $productSrc;
    private string $topbarBlade;
    private string $mobileTouchCss;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contentSrc = file_get_contents(base_path(
            'Modules/Content/Filament/Admin/ContentResource.php'
        ));
        $this->orderSrc = file_get_contents(base_path(
            'Modules/Order/Filament/Admin/Resources/OrderResource.php'
        ));
        $this->customerSrc = file_get_contents(base_path(
            'Modules/Customer/Filament/CustomerResource.php'
        ));
        $this->categorySrc = file_get_contents(base_path(
            'Modules/Category/Filament/Admin/Resources/CategoryResource.php'
        ));
        $this->productSrc = file_get_contents(base_path(
            'Modules/Product/Filament/Admin/Resources/ProductResource.php'
        ));
        $this->topbarBlade = file_get_contents(base_path(
            'src/MicroweberPackages/Admin/resources/views/layouts/partials/topbar.blade.php'
        ));
        $this->mobileTouchCss = file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css'
        ));
    }

    #[Test]
    public function ticket_k_content_resource_uses_label_fn_and_tooltip_fn(): void
    {
        // ContentResource is the base for Post/Page/Product resources;
        // pinning the brief's exact `->label(fn)` + `->tooltip(fn)`
        // shape here covers 3 of the 5 Phase-3 surfaces.
        $this->assertStringContainsString(
            'public static function contextualRowLabel',
            $this->contentSrc,
            'ContentResource: contextualRowLabel helper must exist'
        );
        $this->assertMatchesRegularExpression(
            "/->label\\(fn\\s*\\(Content\\s+\\\$record\\)[^)]*=>\\s*'Edit \"'\\s*\\.\\s*static::contextualRowLabel/",
            $this->contentSrc,
            'ContentResource: live_edit must use ->label(fn (Content $record) => \'Edit "\' . static::contextualRowLabel(...))'
        );
        $this->assertMatchesRegularExpression(
            "/->tooltip\\(fn\\s*\\(Content\\s+\\\$record\\)[^)]*=>\\s*'Edit \"'\\s*\\.\\s*static::contextualRowLabel/",
            $this->contentSrc,
            'ContentResource: live_edit must mirror its label string in ->tooltip(fn ...)'
        );
        // Settings + Delete actions follow the same pattern.
        $this->assertStringContainsString(
            "'Settings for \"'",
            $this->contentSrc,
            'ContentResource: edit (Settings) action must carry contextual "Settings for ..." label'
        );
        $this->assertStringContainsString(
            "'Delete \"'",
            $this->contentSrc,
            'ContentResource: delete action must carry contextual "Delete ..." label'
        );
    }

    #[Test]
    public function ticket_k_order_resource_uses_label_fn_and_tooltip_fn(): void
    {
        // Orders have no $title — anchor on the order id.
        $this->assertStringContainsString(
            "->label(fn (Order \$record): string => 'Edit \"Order #' . \$record->id . '\"')",
            $this->orderSrc,
            'OrderResource: EditAction must use ->label(fn) returning `Edit "Order #{id}"`'
        );
        $this->assertStringContainsString(
            "->tooltip(fn (Order \$record): string => 'Edit \"Order #' . \$record->id . '\"')",
            $this->orderSrc,
            'OrderResource: EditAction must mirror its label in ->tooltip(fn)'
        );
        $this->assertStringContainsString(
            "->label(fn (Order \$record): string => 'Delete \"Order #' . \$record->id . '\"')",
            $this->orderSrc,
            'OrderResource: DeleteAction must use ->label(fn) returning `Delete "Order #{id}"`'
        );
    }

    #[Test]
    public function ticket_k_customer_resource_uses_label_fn_and_tooltip_fn(): void
    {
        // Customers anchor on email (the canonical user-facing identifier),
        // with id-fallback for not-yet-saved rows.
        $this->assertStringContainsString(
            'public static function customerRowLabel',
            $this->customerSrc,
            'CustomerResource: customerRowLabel helper must exist'
        );
        $this->assertStringContainsString(
            "->label(fn (Model \$record): string => 'Edit \"' . static::customerRowLabel(\$record) . '\"')",
            $this->customerSrc,
            'CustomerResource: EditAction must use ->label(fn) wrapping customerRowLabel'
        );
        $this->assertStringContainsString(
            "->tooltip(fn (Model \$record): string => 'Edit \"' . static::customerRowLabel(\$record) . '\"')",
            $this->customerSrc,
            'CustomerResource: EditAction must mirror its label in ->tooltip(fn)'
        );
        $this->assertStringContainsString(
            "->label(fn (Model \$record): string => 'Delete \"' . static::customerRowLabel(\$record) . '\"')",
            $this->customerSrc,
            'CustomerResource: DeleteAction must use ->label(fn) wrapping customerRowLabel'
        );
    }

    #[Test]
    public function ticket_k_category_resource_uses_label_fn_and_tooltip_fn(): void
    {
        $this->assertMatchesRegularExpression(
            "/->label\\(fn\\s*\\(\\\$record\\):\\s*string\\s*=>\\s*'Edit \"'/",
            $this->categorySrc,
            'CategoryResource: EditAction must use ->label(fn ($record): string => \'Edit "\' . ...)'
        );
        $this->assertMatchesRegularExpression(
            "/->tooltip\\(fn\\s*\\(\\\$record\\):\\s*string\\s*=>\\s*'Edit \"'/",
            $this->categorySrc,
            'CategoryResource: EditAction must mirror its label in ->tooltip(fn ...)'
        );
    }

    #[Test]
    public function ticket_k_product_resource_inherits_via_parent_table_call(): void
    {
        // ProductResource overrides table() but does NOT override
        // ->actions([...]). It calls parent::table($table) which returns
        // the ContentResource action stack — so the TICKET-K labels on
        // ContentResource cover ProductResource transitively. Pin both
        // facts: parent::table is called AND ProductResource has no
        // local ->actions([ override.
        $this->assertStringContainsString(
            'parent::table($table)',
            $this->productSrc,
            'ProductResource: must call parent::table($table) so it inherits the contextual-label action stack'
        );
        $this->assertStringNotContainsString(
            '->actions([',
            $this->productSrc,
            'ProductResource: must NOT redefine its own ->actions([...]) — that would shadow ContentResource\'s contextual labels'
        );
    }

    #[Test]
    public function ticket_s_topbar_add_new_button_has_aria_attrs(): void
    {
        // The brief is exact about file path, attribute names, and values.
        $this->assertStringContainsString(
            "aria-label=\"<?php _e('Add new content'); ?>\"",
            $this->topbarBlade,
            'topbar.blade.php: Add New trigger must carry aria-label="<?php _e(\'Add new content\'); ?>"'
        );
        $this->assertStringContainsString(
            'aria-haspopup="listbox"',
            $this->topbarBlade,
            'topbar.blade.php: Add New trigger must carry aria-haspopup="listbox" (Bootstrap dropdown shape, NOT dialog)'
        );
        $this->assertStringContainsString(
            'aria-expanded="false"',
            $this->topbarBlade,
            'topbar.blade.php: existing aria-expanded="false" must be retained per AC3'
        );
    }

    #[Test]
    public function ticket_t_single_level_breadcrumb_hide_rule_is_present(): void
    {
        // The brief specifies the exact CSS rule shape.
        $this->assertStringContainsString(
            '.fi-breadcrumbs ol:has(> li:only-child)',
            $this->mobileTouchCss,
            'mobile-touch.css: must carry the brief\'s exact `.fi-breadcrumbs ol:has(> li:only-child)` selector'
        );
        $this->assertMatchesRegularExpression(
            '/\\.fi-breadcrumbs\\s+ol:has\\(\\s*>\\s*li:only-child\\s*\\)\\s*\\{\\s*display:\\s*none/',
            $this->mobileTouchCss,
            'mobile-touch.css: single-level breadcrumb hide rule must apply display: none'
        );

        // Negative: an UNSCOPED `.fi-breadcrumbs { display: none }`
        // would remove breadcrumbs everywhere — guard against that.
        $this->assertDoesNotMatchRegularExpression(
            '/^\\s*\\.fi-breadcrumbs\\s*\\{\\s*display:\\s*none/m',
            $this->mobileTouchCss,
            'mobile-touch.css: must NOT carry an unscoped `.fi-breadcrumbs { display: none }` — the :has() guard is mandatory'
        );
    }
}
