<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\EnsuresAdminUser;

/**
 * Settings pages smoke tests
 *
 * Loads every page linked from /admin/settings (and the create forms of the
 * editable resources) and asserts none returns a server error (500 /
 * SQLSTATE / Whoops). This guards against regressions like a missing
 * migration or a broken form taking a settings page down.
 *
 * Read-only: it only visits pages, it never submits or deletes data.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user admin@admin.com / admin (provisioned by EnsuresAdminUser)
 *   - Login captcha disabled
 */
class SettingsPagesSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use EnsuresAdminUser;

    protected function assertPreConditions(): void
    {
        // Rely on the already-running server's database.
    }

    /** Every page reachable from the Settings hub (label => URL). */
    private function settingsViewPages(): array
    {
        return [
            'General' => '/admin/settings/general',
            'Template' => '/admin/admin-template-page',
            'SEO settings' => '/admin/settings/seo-page',
            'Main Shop Settings' => '/admin/admin-shop-general-page',
            'Payment Providers' => '/admin/payment-providers',
            'Email' => '/admin/admin-email-page',
            'Privacy Policy' => '/admin/admin-privacy-policy-page',
            'Custom Tags' => '/admin/admin-custom-tags-page',
            'Cookie Notice' => '/admin/cookie-notice-module-settings-admin',
            'Files' => '/admin/file-manager-page-admin',
            'Comments' => '/admin/comments',
            'Faqs' => '/admin/faq-modules',
            'Ratings' => '/admin/rating-modules',
            'Menu' => '/admin/settings/menus',
            'Template Customization' => '/admin/template-customization',
            'Media Library' => '/admin/media',
            'CDN Sync Settings' => '/admin/cdn-sync-settings',
            'Image Optimization' => '/admin/image-optimization-settings',
            'Mail Sender' => '/admin/mail-sender-settings',
            'Asset Minifier' => '/admin/minifier-settings',
            'Fonts' => '/admin/template-fonts',
            'Font Settings' => '/admin/template-fonts-settings',
            'Content Types' => '/admin/content-types',
            'Invoice Settings' => '/admin/admin-shop-invoices-page',
            'Variant Attributes' => '/admin/product-variant-attributes',
            'Taxes' => '/admin/taxes',
            'Offers' => '/admin/offers',
            'Tax Rates' => '/admin/tax-rates',
            'Currencies' => '/admin/currencies',
            'Inventory' => '/admin/inventory',
            'Exchange Rates' => '/admin/exchange-rates',
            'Coupons' => '/admin/coupons',
            'Shipping Providers' => '/admin/shipping-providers',
            'Categories' => '/admin/categories',
            'Tags' => '/admin/tags',
            'Auto Respond Email' => '/admin/admin-shop-auto-respond-email-page',
            'Mail Templates' => '/admin/mail-templates',
            'Advanced' => '/admin/admin-advanced-page',
            'Updater' => '/admin/updater',
            'Server & Environment' => '/admin/admin-maintenance-mode-page',
            'Countries' => '/admin/countries',
            'Logs' => '/admin/logs',
            'Queue Jobs' => '/admin/queue-jobs',
            'Failed Jobs' => '/admin/failed-jobs',
            'Import from WordPress' => '/admin/word-press-migration-resource',
            'Agent Chats' => '/admin/agent-chats',
            'API Applications' => '/admin/api-applications',
            'AI Settings' => '/admin/ai-settings-page',
            'Mcp Clients' => '/admin/mcp-clients',
            'Language' => '/admin/admin-language-page',
            'Multilanguage' => '/admin/multilanguage-settings-admin',
            'Login & Register' => '/admin/admin-login-register-page',
            'Two-Factor Auth' => '/admin/two-factor-settings',
            'Backup Schedules' => '/admin/backup-schedules',
            'Backup History' => '/admin/backup-histories',
            'Backup' => '/admin/backup',
            'Restore' => '/admin/restore',
            'Module Configuration' => '/admin/module-configuration',
            'White Label' => '/admin/white-label-settings-admin-settings-page',
            'Modules' => '/admin/module-resource/modules',
        ];
    }

    /** Create forms of the editable settings resources (label => URL). */
    private function settingsCreatePages(): array
    {
        return [
            'Payment Providers (create)' => '/admin/payment-providers/create',
            'Taxes (create)' => '/admin/taxes/create',
            'Tax Rates (create)' => '/admin/tax-rates/create',
            'Currencies (create)' => '/admin/currencies/create',
            'Offers (create)' => '/admin/offers/create',
            'Coupons (create)' => '/admin/coupons/create',
            'Exchange Rates (create)' => '/admin/exchange-rates/create',
            'Countries (create)' => '/admin/countries/create',
            'Variant Attributes (create)' => '/admin/product-variant-attributes/create',
            'API Applications (create)' => '/admin/api-applications/create',
            'Mcp Clients (create)' => '/admin/mcp-clients/create',
            'Mail Templates (create)' => '/admin/mail-templates/create',
            'Categories (create)' => '/admin/categories/create',
            'Tags (create)' => '/admin/tags/create',
            'Shipping Providers (create)' => '/admin/shipping-providers/create',
            'Fonts (create)' => '/admin/template-fonts/create',
        ];
    }

    private function assertPagesHaveNoServerError(Browser $browser, array $pages): void
    {
        $failures = [];

        foreach ($pages as $label => $url) {
            $browser->visit($url)->pause(2500);
            $this->ensureLoggedIn($browser);

            $source = $browser->driver->getPageSource();
            if (str_contains($source, 'Internal Server Error')
                || str_contains($source, 'SQLSTATE')
                || str_contains($source, 'Whoops, something went wrong')) {
                $failures[] = $label . ' (' . $url . ')';
            }
        }

        $this->assertEmpty(
            $failures,
            'These settings pages returned a server error: ' . implode('; ', $failures)
        );
    }

    #[Test]
    public function all_settings_pages_load_without_errors(): void
    {
        $this->ensureAdminUser();

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->assertPagesHaveNoServerError($browser, $this->settingsViewPages());
        });
    }

    #[Test]
    public function all_settings_create_forms_load_without_errors(): void
    {
        $this->ensureAdminUser();

        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            $this->assertPagesHaveNoServerError($browser, $this->settingsCreatePages());
        });
    }
}
