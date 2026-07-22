<?php

namespace MicroweberPackages\Filament\GlobalSearch;

use MicroweberPackages\FilamentRegistry\FilamentRegistryManager;

/**
 * Registers all known admin settings pages and deep-link destinations
 * with the FilamentRegistry global search index.
 *
 * Called once at boot time. Each entry consists of a human-readable title,
 * a URL, and a bag of keywords that users might type when looking for
 * that settings surface.
 */
class GlobalSearchRegistrar
{
    public function __construct(
        protected FilamentRegistryManager $registry
    ) {}

    /**
     * Register all built-in searchable entries.
     */
    public function register(): void
    {
        $this->registerSettingsPages();
        $this->registerShopPages();
        $this->registerModulePages();
    }

    protected function registerSettingsPages(): void
    {
        // General Settings
        $this->entry(
            'General Settings',
            '/admin/settings/general',
            ['general', 'website name', 'website title', 'website description', 'website keywords',
             'seo', 'permalink', 'date format', 'time zone', 'posts per page', 'logo', 'favicon',
             'maintenance mode', 'under construction', 'online shop', 'shop disabled',
             'powered by', 'branding', 'items per page', 'meta tags', 'meta description'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // SEO Settings
        $this->entry(
            'SEO Settings',
            '/admin/settings/seo-page',
            ['seo', 'search engine', 'google analytics', 'google site verification',
             'facebook pixel', 'bing', 'yandex', 'pinterest', 'alexa',
             'meta tags', 'meta description', 'site verification', 'tracking code',
             'google tag manager', 'analytics id'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // Email Settings
        $this->entry(
            'Email Settings',
            '/admin/settings/email',
            ['email', 'smtp', 'mail', 'mail server', 'email notifications',
             'email configuration', 'mailer', 'mail driver', 'sendmail'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // Language Settings
        $this->entry(
            'Language Settings',
            '/admin/settings/language',
            ['language', 'locale', 'translation', 'translations', 'multilanguage',
             'multi-language', 'i18n', 'localization'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // Login & Register Settings
        $this->entry(
            'Login & Register Settings',
            '/admin/settings/login-register',
            ['login', 'register', 'registration', 'sign up', 'sign in',
             'authentication', 'user registration', 'login page', 'captcha'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // Privacy Policy
        $this->entry(
            'Privacy Policy Settings',
            '/admin/settings/privacy-policy',
            ['privacy', 'privacy policy', 'gdpr', 'data protection',
             'cookie policy', 'terms', 'consent'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // Advanced Settings
        $this->entry(
            'Advanced Settings',
            '/admin/settings/advanced',
            ['advanced', 'cache', 'debug', 'developer', 'api', 'cors',
             'performance', 'optimization', 'database', 'system'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // Custom Tags
        $this->entry(
            'Custom Tags Settings',
            '/admin/settings/custom-tags',
            ['custom tags', 'html head', 'html footer', 'custom code',
             'head tags', 'footer tags', 'tracking code', 'custom html',
             'script tags', 'css tags', 'header code', 'footer code'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // Experimental
        $this->entry(
            'Experimental Settings',
            '/admin/settings/experimental',
            ['experimental', 'beta', 'features', 'experimental features',
             'labs', 'new features'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // System / Maintenance Mode
        $this->entry(
            'System Settings',
            '/admin/settings/maintenance-mode',
            ['maintenance', 'maintenance mode', 'system', 'under construction',
             'site down', 'offline mode'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // Template
        $this->entry(
            'Template Settings',
            '/admin/settings/template',
            ['template', 'theme', 'design', 'appearance', 'skin',
             'layout', 'template settings', 'change template'],
            'Settings',
            ['Section' => 'Customization Settings']
        );

        // Updates
        $this->entry(
            'Updates',
            '/admin/settings/updates',
            ['updates', 'update', 'upgrade', 'version', 'latest version',
             'check for updates', 'system update'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // Comments Settings
        $this->entry(
            'Comments Settings',
            '/admin/settings/comments',
            ['comments', 'moderation', 'comment settings', 'user comments',
             'comment moderation', 'spam', 'comment approval'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // Cookie Notice
        $this->entry(
            'Cookie Notice Settings',
            '/admin/settings/cookie-notice',
            ['cookie', 'cookie notice', 'cookie consent', 'cookie banner',
             'gdpr cookie', 'cookie popup', 'cookie policy'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // Google Analytics
        $this->entry(
            'Google Analytics Settings',
            '/admin/settings/google-analytics',
            ['google analytics', 'analytics', 'ga4', 'tracking',
             'google tag', 'measurement id', 'website analytics'],
            'Settings',
            ['Section' => 'Website Settings']
        );

        // White Label / Branding
        $this->entry(
            'White Label / Branding Settings',
            '/admin/settings/white-label',
            ['white label', 'branding', 'brand name', 'powered by',
             'rebrand', 'custom branding', 'logo branding'],
            'Settings',
            ['Section' => 'Customization Settings']
        );

        // Multilanguage
        $this->entry(
            'Multilanguage Settings',
            '/admin/settings/multilanguage',
            ['multilanguage', 'multi language', 'language', 'translation',
             'locale', 'multilingual', 'i18n'],
            'Settings',
            ['Section' => 'Website Settings']
        );
    }

    protected function registerShopPages(): void
    {
        // Main Shop Settings
        $this->entry(
            'Main Shop Settings',
            '/admin/settings/shop-general',
            ['shop', 'store', 'ecommerce', 'e-commerce', 'currency', 'currency symbol',
             'terms and conditions', 'payment options', 'payment settings',
             'shipping settings', 'coupons', 'discount', 'discount prices',
             'shop settings', 'online store', 'usd', 'eur'],
            'Shop Settings',
            ['Section' => 'Shop Settings']
        );

        // Payment Providers
        $this->entry(
            'Payment Provider Settings',
            '/admin/payment-providers',
            ['payment', 'payment provider', 'payment gateway', 'paypal', 'stripe',
             'credit card', 'payment method', 'payment options', 'payment settings',
             'bank transfer', 'cash on delivery', 'cod'],
            'Shop Settings',
            ['Section' => 'Shop Settings']
        );

        // Shipping Providers
        $this->entry(
            'Shipping Provider Settings',
            '/admin/shipping-providers',
            ['shipping', 'shipping provider', 'shipping method', 'delivery',
             'shipping options', 'shipping settings', 'shipping rate',
             'free shipping', 'flat rate', 'pickup', 'shipping zone'],
            'Shop Settings',
            ['Section' => 'Shop Settings']
        );

        // Coupons
        $this->entry(
            'Coupons',
            '/admin/coupons',
            ['coupon', 'coupons', 'discount code', 'promo code', 'voucher',
             'promotional code', 'discount coupon', 'enable coupons'],
            'Shop Settings',
            ['Section' => 'Shop Settings']
        );

        // Tax Settings
        $this->entry(
            'Tax Settings',
            '/admin/taxes',
            ['tax', 'taxes', 'tax rate', 'vat', 'sales tax', 'tax settings',
             'tax class', 'tax zone', 'tax calculation'],
            'Shop Settings',
            ['Section' => 'Shop Settings']
        );

        // Shop Auto Respond Email
        $this->entry(
            'Shop Auto Respond Email Settings',
            '/admin/settings/shop-auto-respond-email',
            ['auto respond', 'auto reply', 'order confirmation email',
             'shop email', 'order email', 'notification email',
             'auto respond email', 'automated email'],
            'Shop Settings',
            ['Section' => 'Shop Settings']
        );

        // Offers / Discount Prices
        $this->entry(
            'Offers & Discount Prices',
            '/admin/offers',
            ['offer', 'offers', 'discount', 'discount price', 'sale price',
             'special offer', 'deal', 'promotion', 'price reduction'],
            'Shop Settings',
            ['Section' => 'Shop Settings']
        );

        // Invoice Settings
        $this->entry(
            'Invoice Settings',
            '/admin/settings/invoices',
            ['invoice', 'invoices', 'invoice settings', 'billing',
             'invoice template', 'invoice number'],
            'Shop Settings',
            ['Section' => 'Shop Settings']
        );

        // Currency
        $this->entry(
            'Currency Settings',
            '/admin/currencies',
            ['currency', 'exchange rate', 'currency conversion',
             'multi currency', 'currency settings'],
            'Shop Settings',
            ['Section' => 'Shop Settings']
        );
    }

    protected function registerModulePages(): void
    {
        // Menu
        $this->entry(
            'Menu Management',
            '/admin/settings/menus',
            ['menu', 'menus', 'navigation', 'nav', 'menu items',
             'header menu', 'footer menu', 'site menu'],
            'Admin Pages',
            ['Section' => 'Website']
        );

        // Media Library
        $this->entry(
            'Media Library',
            '/admin/media-library',
            ['media', 'media library', 'images', 'files', 'uploads',
             'file manager', 'image manager', 'documents'],
            'Admin Pages',
            ['Section' => 'Website']
        );

        // File Manager
        $this->entry(
            'File Manager',
            '/admin/settings/file-manager',
            ['file manager', 'files', 'file upload', 'file browser',
             'documents', 'download'],
            'Admin Pages',
            ['Section' => 'Website']
        );

        // Backup
        $this->entry(
            'Backup & Restore',
            '/admin/backups',
            ['backup', 'restore', 'backups', 'database backup',
             'site backup', 'export', 'import', 'data backup'],
            'Admin Pages',
            ['Section' => 'System']
        );

        // Mail Templates
        $this->entry(
            'Email Templates',
            '/admin/mail-templates',
            ['mail template', 'email template', 'notification template',
             'email design', 'mail templates', 'email templates'],
            'Admin Pages',
            ['Section' => 'Website Settings']
        );

        // AI Settings
        $this->entry(
            'AI Settings',
            '/admin/settings/ai',
            ['ai', 'artificial intelligence', 'ai settings', 'openai',
             'chatgpt', 'ai assistant', 'ai chat', 'machine learning'],
            'Admin Pages',
            ['Section' => 'Settings']
        );

        // Newsletter
        $this->entry(
            'Newsletter',
            '/admin/newsletter-campaigns',
            ['newsletter', 'email campaign', 'mailing list', 'subscribers',
             'email marketing', 'campaign', 'bulk email'],
            'Admin Pages',
            ['Section' => 'Marketing']
        );
    }

    /**
     * Helper to register a single entry.
     */
    protected function entry(string $title, string $url, array $keywords, string $group, array $details = []): void
    {
        $this->registry->registerGlobalSearchEntry(
            title: $title,
            url: $url,
            keywords: $keywords,
            group: $group,
            details: $details,
        );
    }
}