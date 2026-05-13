import { defineConfig } from 'vitepress'

export default defineConfig({
  title: 'Microweber',
  lang: 'en-US',
  description: 'Documentation for Microweber CMS — drag-and-drop website builder with e-commerce',
  markdown: {
    lineNumbers: true
  },
  vite: {
    css: {
      postcss: {
        plugins: []
      }
    }
  },
  themeConfig: {
    logo: '/logo.svg',
    nav: [
      { text: 'Guide', link: '/installation' },
      { text: 'API', link: '/api-authentication' },
      { text: 'Modules', link: '/module-create' },
      { text: 'E-commerce', link: '/ECOMMERCE_API' },
      { text: 'Admin (Filament)', link: '/filament' }
    ],

    sidebar: [
      {
        text: 'Getting Started',
        collapsed: false,
        items: [
          { text: 'Installation', link: '/installation' },
          { text: 'Architecture Guide', link: '/architecture-guide' },
          { text: 'Architecture Decisions', link: '/ARCHITECTURE_DECISIONS' },
          { text: 'Database & Data Model', link: '/database' },
          { text: 'Data Model & Schema', link: '/data-model' },
          { text: 'User Manual', link: '/USER_MANUAL' }
        ]
      },
      {
        text: 'Admin Panel (Filament)',
        collapsed: false,
        items: [
          { text: 'Filament Overview', link: '/filament' },
          { text: 'Filament Migration', link: '/filament-migration' },
          {
            text: 'Features',
            collapsed: true,
            items: [
              { text: 'Media Library', link: '/features/media-library' },
              { text: 'Filament Resources', link: '/features/filament-resources' }
            ]
          }
        ]
      },
      {
        text: 'Module Development',
        collapsed: false,
        items: [
          { text: 'Creating a Module', link: '/module-create' },
          { text: 'Livewire Modules', link: '/module-create-livewire' },
          { text: 'Module Developer Guide', link: '/DEVELOPER_GUIDE_MODULES' },
          { text: 'Module Commands', link: '/commands' },
          { text: 'Livewire Registration', link: '/ticket-livewire-component-registration' },
          { text: 'Legacy Helpers (options/api/events)', link: '/legacy-helpers' }
        ]
      },
      {
        text: 'Module Reference',
        collapsed: false,
        items: [
          {
            text: 'Content Module',
            collapsed: true,
            items: [
              { text: 'Overview', link: '/modules/content/' },
              { text: 'Installation', link: '/modules/content/installation' },
              { text: 'Usage', link: '/modules/content/usage' },
              { text: 'API Reference', link: '/modules/content/api' },
              { text: 'Examples', link: '/modules/content/examples' },
              { text: 'Troubleshooting', link: '/modules/content/troubleshooting' }
            ]
          },
          {
            text: 'Page Module',
            collapsed: true,
            items: [
              { text: 'Overview', link: '/modules/page/' },
              { text: 'Installation', link: '/modules/page/installation' },
              { text: 'Usage', link: '/modules/page/usage' },
              { text: 'API Reference', link: '/modules/page/api' },
              { text: 'Examples', link: '/modules/page/examples' },
              { text: 'Troubleshooting', link: '/modules/page/troubleshooting' }
            ]
          },
          {
            text: 'Post Module',
            collapsed: true,
            items: [
              { text: 'Overview', link: '/modules/post/' },
              { text: 'Installation', link: '/modules/post/installation' },
              { text: 'Usage', link: '/modules/post/usage' },
              { text: 'API Reference', link: '/modules/post/api' },
              { text: 'Examples', link: '/modules/post/examples' },
              { text: 'Troubleshooting', link: '/modules/post/troubleshooting' }
            ]
          },
          {
            text: 'Category Module',
            collapsed: true,
            items: [
              { text: 'Overview', link: '/modules/category/' },
              { text: 'Installation', link: '/modules/category/installation' },
              { text: 'Usage', link: '/modules/category/usage' },
              { text: 'API Reference', link: '/modules/category/api' },
              { text: 'Examples', link: '/modules/category/examples' },
              { text: 'Troubleshooting', link: '/modules/category/troubleshooting' }
            ]
          },
          {
            text: 'Media Module',
            collapsed: true,
            items: [
              { text: 'Overview', link: '/modules/media/' },
              { text: 'Installation', link: '/modules/media/installation' },
              { text: 'Usage', link: '/modules/media/usage' },
              { text: 'API Reference', link: '/modules/media/api' },
              { text: 'Examples', link: '/modules/media/examples' },
              { text: 'Troubleshooting', link: '/modules/media/troubleshooting' }
            ]
          },
          {
            text: 'MediaLibrary Module',
            collapsed: true,
            items: [
              { text: 'Overview', link: '/modules/medialibrary/' },
              { text: 'Installation', link: '/modules/medialibrary/installation' },
              { text: 'Usage', link: '/modules/medialibrary/usage' },
              { text: 'API Reference', link: '/modules/medialibrary/api' },
              { text: 'Examples', link: '/modules/medialibrary/examples' },
              { text: 'Troubleshooting', link: '/modules/medialibrary/troubleshooting' }
            ]
          },
          {
            text: 'Menu Module',
            collapsed: true,
            items: [
              { text: 'Overview', link: '/modules/menu/' },
              { text: 'Installation', link: '/modules/menu/installation' },
              { text: 'Usage', link: '/modules/menu/usage' },
              { text: 'API Reference', link: '/modules/menu/api' },
              { text: 'Examples', link: '/modules/menu/examples' },
              { text: 'Troubleshooting', link: '/modules/menu/troubleshooting' }
            ]
          },
          {
            text: 'Settings Module',
            collapsed: true,
            items: [
              { text: 'Overview', link: '/modules/settings/' },
              { text: 'Installation', link: '/modules/settings/installation' },
              { text: 'Usage', link: '/modules/settings/usage' },
              { text: 'API Reference', link: '/modules/settings/api' },
              { text: 'Examples', link: '/modules/settings/examples' },
              { text: 'Troubleshooting', link: '/modules/settings/troubleshooting' }
            ]
          },
          {
            text: 'User Module',
            collapsed: true,
            items: [
              { text: 'Overview', link: '/modules/user/' },
              { text: 'Installation', link: '/modules/user/installation' },
              { text: 'Usage', link: '/modules/user/usage' },
              { text: 'API Reference', link: '/modules/user/api' },
              { text: 'Examples', link: '/modules/user/examples' },
              { text: 'Troubleshooting', link: '/modules/user/troubleshooting' }
            ]
          },
          {
            text: 'Profile Module',
            collapsed: true,
            items: [
              { text: 'Overview', link: '/modules/profile/' },
              { text: 'Installation', link: '/modules/profile/installation' },
              { text: 'Usage', link: '/modules/profile/usage' },
              { text: 'API Reference', link: '/modules/profile/api' },
              { text: 'Examples', link: '/modules/profile/examples' },
              { text: 'Troubleshooting', link: '/modules/profile/troubleshooting' }
            ]
          }
        ]
      },
      {
        text: 'Operations',
        collapsed: true,
        items: [
          { text: 'Multisite (per-domain config)', link: '/multisite' }
        ]
      },
      {
        text: 'API Reference',
        collapsed: false,
        items: [
          { text: 'Authentication', link: '/api-authentication' },
          { text: 'Content API', link: '/API_CONTENT' },
          { text: 'E-commerce API', link: '/ECOMMERCE_API' },
          { text: 'API Examples', link: '/api-examples' },
          { text: 'OpenAPI / Swagger', link: '/OPENAPI_DOCUMENTATION' }
        ]
      },
      {
        text: 'E-commerce',
        collapsed: true,
        items: [
          { text: 'Coupon / Discount System', link: '/COUPON_DISCOUNT_SYSTEM' },
          { text: 'Tax Calculation Engine', link: '/TAX_CALCULATION_ENGINE' },
          { text: 'Stripe Integration', link: '/STRIPE_PAYMENT_INTEGRATION' },
          { text: 'PayPal Integration', link: '/PAYPAL_PAYMENT_INTEGRATION' }
        ]
      },
      {
        text: 'Deployment & DevOps',
        collapsed: true,
        items: [
          { text: 'Deployment Guide', link: '/DEPLOYMENT_GUIDE' },
          { text: 'Staging Deployment', link: '/STAGING-DEPLOYMENT' },
          { text: 'CI/CD Pipeline', link: '/CI_CD_PIPELINE' },
          { text: 'Queue Workers', link: '/queue-workers' },
          { text: 'SSL/TLS Configuration', link: '/SSL_TLS_CONFIGURATION' }
        ]
      },
      {
        text: 'Security',
        collapsed: true,
        items: [
          { text: 'Security Audit', link: '/security-audit-2026-04-03' },
          { text: 'Penetration Test Report', link: '/SECURITY_PENETRATION_TEST_REPORT' },
          { text: 'File Upload Validation', link: '/FILE_UPLOAD_VALIDATION' },
          { text: 'NPM Security Status', link: '/NPM_SECURITY_STATUS' }
        ]
      },
      {
        text: 'Performance & Media',
        collapsed: true,
        items: [
          { text: 'Advanced Caching', link: '/ADVANCED_CACHING' },
          { text: 'Image Optimization', link: '/IMAGE_OPTIMIZATION' },
          { text: 'Cross-Browser Compatibility', link: '/CROSS_BROWSER_COMPATIBILITY' }
        ]
      },
      {
        text: 'Testing',
        collapsed: true,
        items: [
          { text: 'Testing Guide', link: '/testing' },
          { text: 'Module Testing Guide', link: '/testing/module-testing-guide' },
          { text: 'Test Suite Results', link: '/TEST_SUITE_RESULTS' }
        ]
      }
    ],

    editLink: {
      pattern: ({ filePath }) => {
        return `https://github.com/microweber/microweber/edit/filament-5/docs/${filePath}`
      }
    },

    socialLinks: [
      { icon: 'github', link: 'https://github.com/microweber/microweber' }
    ],

    search: {
      provider: 'local'
    },

    footer: {
      message: 'Released under the MIT License.',
      copyright: 'Copyright Microweber'
    }
  }
})
