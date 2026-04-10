<?php

/**
 * Admin route inventory for HTTP no-500 smoke testing.
 *
 * Generated from `php artisan route:list --path=admin --json` then filtered:
 * - GET-only
 * - excludes livewire/filament internal endpoints, legacy routes, modals,
 *   export endpoints, backup mutation endpoints, preview-only routes, live-edit.
 *
 * Routes containing {record} are templated — the test substitutes a real
 * seeded record id at runtime. Routes WITHOUT {record} are tested as-is.
 *
 * Grouped by area for per-area data providers so failures point at the exact route.
 */

return [

    // =========================================================================
    // Dashboard
    // =========================================================================
    'dashboard' => [
        'admin',
    ],

    // =========================================================================
    // Content — Pages
    // =========================================================================
    'content_pages' => [
        'admin/pages',
        'admin/pages/create',
        'admin/pages/{record}/edit',  // record_source: content WHERE content_type='page'
    ],

    // =========================================================================
    // Content — Posts
    // =========================================================================
    'content_posts' => [
        'admin/posts',
        'admin/posts/create',
        'admin/posts/{record}/edit',  // record_source: content WHERE content_type='post'
    ],

    // =========================================================================
    // Content — Categories
    // =========================================================================
    'content_categories' => [
        'admin/categories',
        'admin/categories/create',
        'admin/categories/{record}/edit',  // record_source: categories
    ],

    // =========================================================================
    // Content — Generic contents
    // =========================================================================
    'contents' => [
        'admin/contents',
        'admin/contents/create',
        'admin/contents/{record}/edit',  // record_source: content
    ],

    // =========================================================================
    // Content — Comments
    // =========================================================================
    'comments' => [
        'admin/comments',
        'admin/comments/create',
        'admin/comments/{record}/edit',  // record_source: comments
    ],

    // =========================================================================
    // Content — Form Entries (emails)
    // =========================================================================
    'form_entries' => [
        'admin/form-entries',
    ],

    // =========================================================================
    // Content — Translations
    // =========================================================================
    'translations' => [
        'admin/translations',
        'admin/translations/create',
        'admin/translations/{record}/edit',  // record_source: translation_keys
    ],

    // =========================================================================
    // Content — FAQ
    // =========================================================================
    'faq' => [
        'admin/faq-modules',
        'admin/faq-modules/create',
    ],

    // =========================================================================
    // Shop — Products
    // =========================================================================
    'shop_products' => [
        'admin/products',
        'admin/products/create',
        'admin/products/{record}/edit',  // record_source: content WHERE content_type='product'
    ],

    // =========================================================================
    // Shop — Orders
    // =========================================================================
    'shop_orders' => [
        'admin/orders',
        'admin/orders/create',
        'admin/orders/{record}/edit',  // record_source: cart_orders
    ],

    // =========================================================================
    // Shop — Categories
    // =========================================================================
    'shop_categories' => [
        'admin/shop-categories',
        'admin/shop-categories/create',
        'admin/shop-categories/{record}/edit',  // record_source: categories
    ],

    // =========================================================================
    // Shop — Customers
    // =========================================================================
    'shop_customers' => [
        'admin/customers',
        'admin/customers/create',
        'admin/customers/{record}/edit',  // record_source: customers
    ],

    // =========================================================================
    // Shop — Coupons / Offers
    // =========================================================================
    'shop_coupons' => [
        'admin/coupons',
        'admin/coupons/create',
        'admin/coupons/{record}/edit',  // record_source: offers
        'admin/offers',
        'admin/offers/create',
        'admin/offers/{record}/edit',  // record_source: offers
    ],

    // =========================================================================
    // Shop — Invoices
    // =========================================================================
    'shop_invoices' => [
        'admin/invoices',
        'admin/invoices/create',
    ],

    // =========================================================================
    // Shop — Inventory
    // =========================================================================
    'shop_inventory' => [
        'admin/inventory',
        'admin/inventory/create',
    ],

    // =========================================================================
    // Shop — Payments
    // =========================================================================
    'shop_payments' => [
        'admin/payments',
        'admin/payments/create',
        'admin/payment-providers',
        'admin/payment-providers/create',
    ],

    // =========================================================================
    // Shop — Shipping
    // =========================================================================
    'shop_shipping' => [
        'admin/shipping-providers',
        'admin/shipping-providers/create',
    ],

    // =========================================================================
    // Shop — Tax
    // =========================================================================
    'shop_tax' => [
        'admin/tax-rates',
        'admin/tax-rates/create',
    ],

    // =========================================================================
    // Shop — Product Variant Attributes
    // =========================================================================
    'shop_variants' => [
        'admin/product-variant-attributes',
        'admin/product-variant-attributes/create',
    ],

    // =========================================================================
    // Shop — Tags
    // =========================================================================
    'tags' => [
        'admin/tags',
        'admin/tags/create',
        'admin/tag-groups',
        'admin/tag-groups/create',
        'admin/taggeds',
        'admin/taggeds/create',
    ],

    // =========================================================================
    // Users
    // =========================================================================
    'users' => [
        'admin/users',
        'admin/users/create',
        'admin/users/{record}/edit',  // record_source: users
    ],

    // =========================================================================
    // Mail Templates
    // =========================================================================
    'mail_templates' => [
        'admin/mail-templates',
        'admin/mail-templates/create',
        'admin/mail-templates/{record}/edit',  // record_source: mail_templates
    ],

    // =========================================================================
    // Settings
    // =========================================================================
    'settings' => [
        'admin/settings',
        'admin/settings/general',
        'admin/settings/seo-page',
        'admin/settings/google-analytics',
        'admin/settings/menus',
        'admin/admin-template-page',
        'admin/admin-login-register-page',
        'admin/admin-privacy-policy-page',
        'admin/admin-custom-tags-page',
        'admin/cookie-notice-module-settings-admin',
        'admin/admin-email-page',
        'admin/admin-shop-auto-respond-email-page',
        'admin/admin-shop-general-page',
        'admin/admin-shop-invoices-page',
        'admin/admin-shop-other-page',
        'admin/admin-advanced-page',
        'admin/admin-language-page',
        'admin/multilanguage-settings',
        'admin/multilanguage-settings-admin',
        'admin/white-label-settings-admin-settings-page',
        'admin/admin-experimental-page',
        'admin/admin-maintenance-mode-page',
        'admin/module-configuration',
        'admin/module-dependencies',
        'admin/module-dependencies/create',
        'admin/kitchen-sink',
        'admin/search-settings',
        'admin/ai-settings-page',
        'admin/template-customization',
    ],

    // =========================================================================
    // Marketplace
    // =========================================================================
    'marketplace' => [
        'admin/marketplace',
        'admin/marketplace/installed-item',
    ],

    // =========================================================================
    // Modules / Module settings pages
    // =========================================================================
    'module_settings' => [
        'admin/module-resource/modules',
        'admin/accordion-module-settings',
        'admin/audio-module-settings',
        'admin/background-module-settings',
        'admin/before-after-module-settings',
        'admin/blog-settings',
        'admin/breadcrumb-module-settings',
        'admin/btn-module-settings',
        'admin/cart-add-module-settings',
        'admin/category-module-settings',
        'admin/code-editor-module-settings-page',
        'admin/comments-module-settings',
        'admin/comments-module-settings-admin',
        'admin/contact-form-module-settings',
        'admin/content-module-settings',
        'admin/custom-fields-module-settings',
        'admin/embed-module-settings',
        'admin/facebook-like-module-settings',
        'admin/facebook-page-module-settings',
        'admin/google-maps-module-settings',
        'admin/highlight-code-module-settings',
        'admin/image-rollover-module-settings',
        'admin/layout-content-module-settings',
        'admin/layouts-module-settings',
        'admin/logo-module-settings',
        'admin/marquee-module-settings',
        'admin/menu-module-settings',
        'admin/module-presets-module-settings-page',
        'admin/newsletter-module-settings',
        'admin/page-module-settings',
        'admin/pagination-module-settings',
        'admin/pdf-module-settings',
        'admin/pictures-module-settings',
        'admin/post-module-settings',
        'admin/products-module-settings',
        'admin/rating-module-settings',
        'admin/reset-content-module-settings-page',
        'admin/sharer-module-settings',
        'admin/shop-module-settings',
        'admin/skills-module-settings',
        'admin/slider-module-settings',
        'admin/social-links-module-settings',
        'admin/spacer-module-settings',
        'admin/tabs-module-settings',
        'admin/tags-module-settings',
        'admin/teamcard-module-settings',
        'admin/testimonials-module-settings',
        'admin/text-type-module-settings',
        'admin/tweet-embed-module-settings',
        'admin/video-module-settings',
    ],

    // =========================================================================
    // Backups
    // =========================================================================
    'backups' => [
        'admin/backups',
        'admin/backup-histories',
        'admin/backup-schedules',
        'admin/backup-schedules/create',
    ],

    // =========================================================================
    // Billing
    // =========================================================================
    'billing' => [
        'admin/billing',
        'admin/billing/settings',
        'admin/billing/billing-users',
        'admin/billing/billing-users/create',
        'admin/billing/subscription-plan-groups',
        'admin/billing/subscription-plan-groups/create',
        'admin/billing/subscription-plans',
        'admin/billing/subscription-plans/create',
        'admin/billing/subscription-plans/groups',
        'admin/billing/subscriptions',
        'admin/billing/users',
    ],

    // =========================================================================
    // Newsletter
    // =========================================================================
    'newsletter' => [
        'admin/newsletter',
        'admin/newsletter/campaigns',
        'admin/newsletter/campaigns/create',
        'admin/newsletter/lists',
        'admin/newsletter/sender-accounts',
        'admin/newsletter/subscribers',
        'admin/newsletter/templates',
    ],

    // =========================================================================
    // AI
    // =========================================================================
    'ai' => [
        'admin/ai-wizards',
        'admin/ai-wizards/create',
        'admin/agent-chats',
        'admin/agent-chats/create',
    ],

    // =========================================================================
    // Other standalone pages
    // =========================================================================
    'other' => [
        'admin/file-manager-page-admin',
        'admin/media-library',
        'admin/updater',
        'admin/rating-modules',
        'admin/rating-modules/create',
        'admin/live-edit-template-settings-page',
    ],

    // =========================================================================
    // Auth (unauthenticated)
    // =========================================================================
    'auth' => [
        'admin/login',
    ],

    // =========================================================================
    // Record ID sources for {record} routes
    // Maps route prefix → [table, where_clause]
    // =========================================================================
    'record_sources' => [
        'admin/pages'           => ['content', "content_type = 'page'"],
        'admin/posts'           => ['content', "content_type = 'post'"],
        'admin/products'        => ['content', "content_type = 'product'"],
        'admin/contents'        => ['content', null],
        'admin/categories'      => ['categories', null],
        'admin/shop-categories' => ['categories', null],
        'admin/orders'          => ['cart_orders', null],
        'admin/customers'       => ['customers', null],
        'admin/users'           => ['users', null],
        'admin/comments'        => ['comments', null],
        'admin/coupons'         => ['offers', null],
        'admin/offers'          => ['offers', null],
        'admin/translations'    => ['translation_keys', null],
        'admin/mail-templates'  => ['mail_templates', null],
    ],
];
