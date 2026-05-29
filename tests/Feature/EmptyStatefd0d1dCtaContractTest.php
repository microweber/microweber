<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-fd0d1d — Add primary "Add X" CTA to every Filament
 * table empty state served by `Modules/Content/resources/views/filament/
 * admin/empty-state.blade.php`.
 *
 * Audit finding (user screenshot): admin → Pages with zero pages shows
 * the "You do not have any pages yet." heading + illustration but NO
 * action button. Users had no obvious "create the first one" path. The
 * same blade renders empty states for ~10 model branches (Pages, Posts,
 * Products, Orders, Customers, Invoices, Payment/Shipping Providers,
 * Taxes, generic Content), all of which previously had the same gap.
 *
 * Fix: a centered `<a class="mw-table-empty-cta">` is rendered between
 * the heading and the SVG illustration in every branch, linking to the
 * corresponding `filament.admin.resources.<plural>.create` route.
 *
 * Class name `mw-table-empty-cta` is intentionally distinct from the
 * pre-existing `mw-empty-state-cta` defined in
 * `packages/microweber-filament-theme/resources/assets/css/microweber-
 * theme-v3.scss` for the dashboard's separate empty-state widget — the
 * two surfaces serve different purposes and must not collide.
 */
class EmptyStatefd0d1dCtaContractTest extends TestCase
{
    private string $blade;
    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blade = (string) file_get_contents(
            base_path('Modules/Content/resources/views/filament/admin/empty-state.blade.php')
        );
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
    }

    public static function ctaBranches(): array
    {
        return [
            'pages'              => ['You do not have any pages yet.',              'filament.admin.resources.pages.create',              '+ Add page'],
            'posts'              => ['You do not have any posts yet.',              'filament.admin.resources.posts.create',              '+ Add post'],
            'products'           => ['You do not have any products yet.',           'filament.admin.resources.products.create',           '+ Add product'],
            'orders'             => ['You do not have any orders yet.',             'filament.admin.resources.orders.create',             '+ Add order'],
            'customers'          => ['You do not have any customers yet.',          'filament.admin.resources.customers.create',          '+ Add customer'],
            'invoices'           => ['You do not have any invoices yet.',           'filament.admin.resources.invoices.create',           '+ Add invoice'],
            'payment-providers'  => ['You do not have any payment providers yet.',  'filament.admin.resources.payment-providers.create',  '+ Add payment provider'],
            // AI-1099 / task-2026-05-28-2f5a6c — Payments are created by
            // transactions; the CTA points at payment-provider settings
            // (not a payments.create route, which doesn't exist).
            'payments'           => ['You do not have any payments yet.',           'filament.admin.resources.payment-providers.index',   '+ Configure payment providers'],
            'shipping-providers' => ['You do not have any shipping providers yet.', 'filament.admin.resources.shipping-providers.create', '+ Add shipping provider'],
            'taxes'              => ['You do not have any taxes yet.',              'filament.admin.resources.taxes.create',              '+ Add tax'],
            'contents'           => ['No content found.',                           'filament.admin.resources.contents.create',           '+ Add content'],
        ];
    }

    #[Test]
    #[DataProvider('ctaBranches')]
    public function each_empty_state_branch_renders_a_cta(string $heading, string $route, string $label): void
    {
        $this->assertStringContainsString($heading, $this->blade,
            "Branch heading '{$heading}' must remain present in the blade.");

        $this->assertStringContainsString("route('{$route}')", $this->blade,
            "CTA href for '{$route}' must be present in the blade.");

        $this->assertStringContainsString($label, $this->blade,
            "CTA label '{$label}' must be present in the blade.");
    }

    #[Test]
    public function cta_uses_mw_table_empty_cta_class_not_the_dashboard_widget_class(): void
    {
        // Name-collision guard — the dashboard's empty-state widget has its
        // own `.mw-empty-state-cta` rule in microweber-theme-v3.scss. Ours
        // is `.mw-table-empty-cta` and they must stay distinct.
        $this->assertStringContainsString('mw-table-empty-cta', $this->blade);
        $this->assertDoesNotMatchRegularExpression(
            '/class="[^"]*\bmw-empty-state-cta\b[^"]*"/',
            $this->blade,
            'Blade must not reuse the legacy .mw-empty-state-cta class.'
        );
    }

    #[Test]
    public function cta_css_rule_lives_in_general_styles(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-table-empty-cta\s*\{[^}]*background-color:\s*#0d6efd/',
            $this->css,
            '.mw-table-empty-cta must declare the primary blue background.'
        );

        $this->assertMatchesRegularExpression(
            '/\.mw-table-empty-cta\s*\{[^}]*min-height:\s*44px/',
            $this->css,
            '.mw-table-empty-cta must satisfy the 44px touch-target floor.'
        );

        $this->assertStringContainsString('.mw-table-empty-cta:hover', $this->css);
        $this->assertStringContainsString('.mw-table-empty-cta:focus-visible', $this->css);
    }

    #[Test]
    public function cta_blocks_carry_the_task_id_for_audit_traceability(): void
    {
        $count = substr_count($this->blade, 'task-2026-05-16-fd0d1d');
        $this->assertGreaterThanOrEqual(10, $count,
            'Each of the 10 original CTA blocks must carry the task id comment for audit grepping.');
    }

    #[Test]
    public function counts_match_the_known_branch_count(): void
    {
        // 11 CTA wrappers expected — one per branch in ctaBranches().
        // Original count was 10; AI-1099 / task-2026-05-28-2f5a6c added
        // the Payment branch (11th) pointing at payment-providers.index.
        $wrappers = substr_count($this->blade, 'mw-table-empty-cta-wrap');
        $this->assertSame(11, $wrappers,
            'Exactly 11 .mw-table-empty-cta-wrap div blocks must be present.');
    }
}
