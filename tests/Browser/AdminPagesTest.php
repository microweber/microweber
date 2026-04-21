<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use Tests\Browser\Traits\AdminLoginTrait;

/**
 * Admin Pages Smoke Tests
 *
 * Tests that all admin pages load without errors
 * when logged in as an admin user.
 *
 * Prerequisites:
 *   - A running dev server at http://127.0.0.1:8000
 *   - An admin user with email admin@admin.com / password123
 *   - Login captcha disabled
 */
class AdminPagesTest extends DuskTestCase
{
    use AdminLoginTrait;

    protected function assertPreConditions(): void
    {
        // Skip parent — we rely on the already-running server's database
    }

    protected function assertPageLoads(Browser $browser, string $url, string $pageName): void
    {
        $browser->visit($url)->pause(800);

        $currentUrl = $browser->driver->getCurrentURL();

        if (str_contains($currentUrl, '/login')) {
            $this->loginAsAdmin($browser);
            $browser->visit($url)->pause(800);
            $currentUrl = $browser->driver->getCurrentURL();
        }

        $pageSource = $browser->driver->getPageSource();

        $this->assertStringNotContainsString(
            'Internal Server Error', $pageSource,
            "Page {$pageName} ({$url}) returned a 500 error"
        );

        $this->assertStringNotContainsString(
            'Database Server', $pageSource,
            "Page {$pageName} ({$url}) showed installer instead of admin page"
        );

        try {
            $logs = $browser->driver->manage()->getLog('browser');
            $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
            if (!empty($errors)) {
                fwrite(STDERR, "\nJS errors on {$pageName}: " . json_encode($errors) . "\n");
            }
        } catch (\Exception $e) {
            // ChromeDriver log API can be unstable
        }
    }

    /**
     * All admin page URLs to test grouped by section.
     */
    protected static function allAdminPages(): array
    {
        return [
            // ── Dashboard ──
            '/admin' => 'dashboard',

            // ── Content Resources ──
            '/admin/pages' => 'pages list',
            '/admin/posts' => 'posts list',
            '/admin/products' => 'products list',
            '/admin/categories' => 'categories list',
            '/admin/shop-categories' => 'shop categories',
            '/admin/content/create?type=page' => 'content create page',
            '/admin/content/create?type=post' => 'content create post',
            '/admin/content/create?type=product' => 'content create product',

            // ── Shop Resources ──
            '/admin/orders' => 'orders list',
            '/admin/customers' => 'customers list',
            '/admin/coupons' => 'coupons list',
            '/admin/tax-rates' => 'tax rates list',
            '/admin/taxes' => 'taxes list',
            '/admin/shipping-providers' => 'shipping providers',
            '/admin/payment-providers' => 'payment providers',
            '/admin/payments' => 'payments list',
            '/admin/invoices' => 'invoices list',
            '/admin/checkouts' => 'checkouts list',
            '/admin/offers' => 'offers list',
            '/admin/product-variant-attributes' => 'product variant attributes',
            '/admin/product-inventory' => 'product inventory',
            '/admin/product-pricing-rules' => 'product pricing rules',
            '/admin/currencies' => 'currencies list',
            '/admin/exchange-rates' => 'exchange rates list',

            // ── Billing ──
            '/admin/subscriptions' => 'subscriptions list',
            '/admin/subscription-plans' => 'subscription plans',
            '/admin/subscription-plan-groups' => 'subscription plan groups',
            '/admin/billing-users' => 'billing users',
            '/admin/billing-settings' => 'billing settings',

            // ── Users & Roles ──
            '/admin/users' => 'users list',
            '/admin/roles' => 'roles list',
            '/admin/permissions' => 'permissions list',
            '/admin/resource-permissions' => 'resource permissions',

            // ── Comments ──
            '/admin/comments' => 'comments list',

            // ── Media ──
            '/admin/media' => 'media list',
            '/admin/media-library' => 'media library page',

            // ── Tags ──
            '/admin/tags' => 'tags list',
            '/admin/tag-groups' => 'tag groups',
            '/admin/taggeds' => 'tagged items',

            // ── Newsletter ──
            '/admin/newsletter' => 'newsletter homepage',
            '/admin/newsletter/campaigns' => 'newsletter campaigns',
            '/admin/newsletter/campaigns/create' => 'newsletter create campaign',
            '/admin/newsletter/lists' => 'newsletter lists',
            '/admin/newsletter/subscribers' => 'newsletter subscribers',
            '/admin/newsletter/sender-accounts' => 'newsletter sender accounts',
            '/admin/newsletter/templates' => 'newsletter templates',
            '/admin/newsletter/workflows' => 'newsletter workflows',

            // ── Mail Templates ──
            '/admin/mail-templates' => 'mail templates list',

            // ── Modules & Marketplace ──
            '/admin/modules' => 'modules list',
            '/admin/templates' => 'templates list',
            '/admin/marketplace' => 'marketplace',
            '/admin/module-configurations' => 'module configurations',
            '/admin/module-dependencies' => 'module dependencies',

            // ── Backup ──
            '/admin/backup-history' => 'backup history',
            '/admin/backup-schedules' => 'backup schedules',

            // ── Error Tracking ──
            '/admin/error-tracking' => 'error tracking',

            // ── AI ──
            '/admin/ai/agent-chats' => 'ai agent chats',

            // ── FAQ ──
            '/admin/faqs' => 'faqs list',

            // ── Ratings ──
            '/admin/rating-modules' => 'ratings list',

            // ── Translations ──
            '/admin/translations' => 'translations list',

            // ── Contact Form ──
            '/admin/contact-form-entries' => 'contact form entries',

            // ── Site Stats ──
            '/admin/site-stats' => 'site stats',

            // ── Custom Fields ──
            '/admin/custom-fields' => 'custom fields',

            // ── Core Settings Pages ──
            '/admin/settings' => 'settings main',
            '/admin/admin-general' => 'settings general',
            '/admin/admin-language' => 'settings language',
            '/admin/admin-email' => 'settings email',
            '/admin/admin-seo' => 'settings seo',
            '/admin/admin-advanced' => 'settings advanced',
            '/admin/admin-custom-tags' => 'settings custom tags',
            '/admin/admin-experimental' => 'settings experimental',
            '/admin/admin-login-register' => 'settings login register',
            '/admin/admin-maintenance-mode' => 'settings maintenance mode',
            '/admin/admin-privacy-policy' => 'settings privacy policy',
            '/admin/admin-template' => 'settings template',
            '/admin/admin-template-customizer' => 'settings template customizer',
            '/admin/admin-google-analytics-settings' => 'settings google analytics',

            // ── Shop Settings Pages ──
            '/admin/admin-shop-general' => 'shop general settings',
            '/admin/admin-shop-other' => 'shop other settings',
            '/admin/admin-shop-auto-respond-email' => 'shop auto respond email',
            '/admin/admin-shop-invoices' => 'shop invoices settings',

            // ── Menus ──
            '/admin/admin-menus' => 'menus page',

            // ── Module Settings Pages ──
            '/admin/accordion-module-settings' => 'accordion module settings',
            '/admin/address-module-settings' => 'address module settings',
            '/admin/audio-module-settings' => 'audio module settings',
            '/admin/background-module-settings' => 'background module settings',
            '/admin/before-after-module-settings' => 'before after module settings',
            '/admin/blog-settings' => 'blog settings',
            '/admin/breadcrumb-module-settings' => 'breadcrumb module settings',
            '/admin/btn-module-settings' => 'btn module settings',
            '/admin/cart-add-module-settings' => 'cart add module settings',
            '/admin/category-module-settings' => 'category module settings',
            '/admin/checkout-module-settings' => 'checkout module settings',
            '/admin/comments-module-settings' => 'comments module settings',
            '/admin/company-module-settings' => 'company module settings',
            '/admin/contact-form-module-settings' => 'contact form module settings',
            '/admin/content-module-settings' => 'content module settings',
            '/admin/cookie-notice-module-settings-admin' => 'cookie notice module settings',
            '/admin/custom-fields-module-settings' => 'custom fields module settings',
            '/admin/elements-module-settings' => 'elements module settings',
            '/admin/embed-module-settings' => 'embed module settings',
            '/admin/export-module-settings' => 'export module settings',
            '/admin/facebook-like-module-settings' => 'facebook like module settings',
            '/admin/facebook-page-module-settings' => 'facebook page module settings',
            '/admin/file-manager-module-settings' => 'file manager module settings',
            '/admin/fonts-manager-module-settings' => 'fonts manager module settings',
            '/admin/google-maps-module-settings' => 'google maps module settings',
            '/admin/highlight-code-module-settings' => 'highlight code module settings',
            '/admin/image-rollover-module-settings' => 'image rollover module settings',
            '/admin/layout-content-module-settings' => 'layout content module settings',
            '/admin/layouts-module-settings' => 'layouts module settings',
            '/admin/log-module-settings' => 'log module settings',
            '/admin/logo-module-settings' => 'logo module settings',
            '/admin/marketplace-module-settings' => 'marketplace module settings',
            '/admin/marquee-module-settings' => 'marquee module settings',
            '/admin/media-module-settings' => 'media module settings',
            '/admin/menu-module-settings' => 'menu module settings',
            '/admin/module-presets-module-settings' => 'module presets module settings',
            '/admin/multilanguage-settings' => 'multilanguage settings',
            '/admin/newsletter-module-settings' => 'newsletter module settings',
            '/admin/offer-module-settings' => 'offer module settings',
            '/admin/page-module-settings' => 'page module settings',
            '/admin/pagination-module-settings' => 'pagination module settings',
            '/admin/pdf-module-settings' => 'pdf module settings',
            '/admin/pictures-module-settings' => 'pictures module settings',
            '/admin/post-module-settings' => 'post module settings',
            '/admin/products-module-settings' => 'products module settings',
            '/admin/rating-module-settings' => 'rating module settings',
            '/admin/reset-content-module-settings' => 'reset content module settings',
            '/admin/restore-module-settings' => 'restore module settings',
            '/admin/search-settings' => 'search settings',
            '/admin/settings-module-settings' => 'settings module settings',
            '/admin/sharer-module-settings' => 'sharer module settings',
            '/admin/shop-module-settings' => 'shop module settings',
            '/admin/site-stats-module-settings' => 'site stats module settings',
            '/admin/skills-module-settings' => 'skills module settings',
            '/admin/slider-module-settings' => 'slider module settings',
            '/admin/social-links-module-settings' => 'social links module settings',
            '/admin/spacer-module-settings' => 'spacer module settings',
            '/admin/tabs-module-settings' => 'tabs module settings',
            '/admin/tags-module-settings' => 'tags module settings',
            '/admin/teamcard-module-settings' => 'teamcard module settings',
            '/admin/template-editor' => 'template editor',
            '/admin/testimonials-module-settings' => 'testimonials module settings',
            '/admin/text-type-module-settings' => 'text type module settings',
            '/admin/tweet-embed-module-settings' => 'tweet embed module settings',
            '/admin/unlock-package-module-settings' => 'unlock package module settings',
            '/admin/updater' => 'updater page',
            '/admin/video-module-settings' => 'video module settings',
            '/admin/white-label-settings-admin' => 'white label settings',
            '/admin/ai-settings' => 'ai settings module',
            '/admin/ai-wizard-module-settings' => 'ai wizard module settings',

            // ── Legacy Admin Views ──
            '/admin/view:admin/index' => 'legacy admin index',
            '/admin/view:newsletter/index' => 'legacy newsletter index',
            '/admin/view:newsletter/subscribers' => 'legacy newsletter subscribers',
        ];
    }

    #[Test]
    public function all_admin_pages_load_without_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);

            $pages = static::allAdminPages();
            $failed = [];
            $passed = 0;

            foreach ($pages as $url => $pageName) {
                try {
                    $this->assertPageLoads($browser, $url, $pageName);
                    $passed++;
                } catch (\Exception $e) {
                    $failed[$pageName] = $e->getMessage();
                    // Take screenshot of failed page
                    $screenshotName = 'fail-' . str_replace(['/', '?', '=', ':', ' '], '-', $pageName);
                    try {
                        $browser->screenshot($screenshotName);
                    } catch (\Exception $ignored) {
                    }
                }
            }

            $total = count($pages);
            $failCount = count($failed);

            if (!empty($failed)) {
                $report = "Failed {$failCount}/{$total} admin pages:\n";
                foreach ($failed as $name => $error) {
                    $report .= "  - {$name}: {$error}\n";
                }
                $this->fail($report);
            }

            $this->addToAssertionCount($passed);
        });
    }
}
