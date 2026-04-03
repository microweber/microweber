# MW v2 → Filament 5 Admin Design Migration Plan

> **Source design:** https://demo.microweber.org/
> **Clone-website skill:** https://agents.tools.ooyes.net/skills/clone-website.yml
> **UI test workflow:** https://agents.tools.ooyes.net/workflows/dev-cycle/02-test-the-project-ui.yml

---

## Migration Approach

Each page migration follows this cycle:

1. **Capture reference** — Use the clone-website skill (Phase 1: Reconnaissance) to screenshot the MW v2 page at `https://demo.microweber.org/admin/<path>` and extract design tokens, layout patterns, spacing, colors
2. **Inspect current Filament page** — Open the local Filament admin, compare against the MW v2 reference
3. **Implement** — Update Filament Resource/Page forms, tables, Blade views, and CSS to match the MW v2 design language (section icons, card layouts, typography, colors, dark mode)
4. **Visual QA** — Use the UI test workflow to verify pixel-level match at desktop and mobile viewports, light and dark mode
5. **Commit** — One logical change per page/group

---

## Done (Phase 3 — completed 2026-04-03)

- [x] 2026-04-03  style: section.blade.php — add icon rendering support to custom section view
- [x] 2026-04-03  style: Tax pages — TaxResource, TaxRateResource section icons
- [x] 2026-04-03  style: Checkout — CheckoutResource section icons (7 sections)
- [x] 2026-04-03  style: Settings General — AdminGeneralPage section icons (5 sections)
- [x] 2026-04-03  style: Settings Email — AdminEmailPage section icons
- [x] 2026-04-03  style: Settings SEO — AdminSeoPage section icon
- [x] 2026-04-03  style: Settings Template — AdminTemplatePage section icon
- [x] 2026-04-03  style: Settings Advanced — AdminAdvancedPage section icons
- [x] 2026-04-03  style: Settings Login/Register — AdminLoginRegisterPage section icons
- [x] 2026-04-03  style: Settings Maintenance — AdminMaintenanceModePage section icon
- [x] 2026-04-03  style: Settings Privacy Policy — AdminPrivacyPolicyPage section icons (3 sections)
- [x] 2026-04-03  style: Settings Language — AdminLanguagePage section icons (3 sections)
- [x] 2026-04-03  style: Settings Custom Tags — AdminCustomTagsPage section icons (4 sections)
- [x] 2026-04-03  style: Shop General — AdminShopGeneralPage section icons (3 sections)
- [x] 2026-04-03  style: Shop Auto Respond — AdminShopAutoRespondEmailPage section icons (3 sections)
- [x] 2026-04-03  style: Comments settings — CommentsModuleSettingsAdmin section icons (4 sections)
- [x] 2026-04-03  style: Cookie Notice settings — CookieNoticeModuleSettingsAdmin section icon
- [x] 2026-04-03  style: White Label settings — WhiteLabelSettingsAdminSettingsPage section icons (7 sections)
- [x] 2026-04-03  style: Multilanguage settings — MultilanguageSettingsAdmin section icons (6 sections)
- [x] 2026-04-03  style: AI settings — AiSettingsPage section icons (11 sections)
- [x] 2026-04-03  style: Backup — BackupScheduleResource, BackupHistoryResource, ListBackups section icons
- [x] 2026-04-03  style: Mail Template — MailTemplateResource section icons (3 sections)
- [x] 2026-04-03  style: Error Tracking — ErrorTrackingResource section icons (3 sections)
- [x] 2026-04-03  style: Module Configuration — ModuleConfigurationResource section icons (2 sections)
- [x] 2026-04-03  style: Coupons — CouponResource section icons (8 sections)
- [x] 2026-04-03  style: Offers — OfferResource section icons
- [x] 2026-04-03  style: Invoices — InvoiceResource section icons
- [x] 2026-04-03  style: Payments — PaymentResource section icons
- [x] 2026-04-03  plan: enumerate all admin pages and create migration TODO

---

## Phase 4 — Dashboard & Top-Level Pages

> Skill: https://agents.tools.ooyes.net/skills/clone-website.yml (Phase 1 recon + Phase 3 component spec)
> QA: https://agents.tools.ooyes.net/workflows/dev-cycle/02-test-the-project-ui.yml

- [x] 2026-04-03  migrate: Dashboard — `app/Filament/Admin/Pages/Dashboard.php` — ref: `https://demo.microweber.org/admin/dashboard`
- [ ] migrate: Main Settings hub page — `Modules/Settings/Filament/Pages/Settings.php` + `settings-main.blade.php` — ref: `https://demo.microweber.org/admin/settings`

---

## Phase 5 — Content Management Pages

- [ ] migrate: Pages list/create/edit — `Modules/Page/Filament/Resources/PageResource.php` — ref: `https://demo.microweber.org/admin/pages`
- [ ] migrate: Posts list/create/edit — `Modules/Post/Filament/Admin/Resources/PostResource.php` — ref: `https://demo.microweber.org/admin/posts`
- [ ] migrate: Categories list/create/edit — `Modules/Category/Filament/Admin/Resources/CategoryResource.php` — ref: `https://demo.microweber.org/admin/categories`
- [ ] migrate: Content resource — `Modules/Content/Filament/Admin/ContentResource.php` — ref: `https://demo.microweber.org/admin/content`
- [ ] migrate: Tags list/create/edit — `Modules/Tag/Filament/Resources/TagResource.php` — ref: `https://demo.microweber.org/admin/tags`
- [ ] migrate: Tag Groups — `Modules/Tag/Filament/Resources/TagGroupResource.php` — ref: `https://demo.microweber.org/admin/tag-groups`
- [ ] migrate: Comments list/create/edit — `Modules/Comments/Filament/Resources/CommentResource.php` — ref: `https://demo.microweber.org/admin/comments`
- [ ] migrate: Ratings list/create/edit — `Modules/Rating/Filament/Resources/RatingModuleResource.php` — ref: `https://demo.microweber.org/admin/ratings`
- [ ] migrate: FAQ list/create/edit — `Modules/Faq/Filament/Resources/FaqModuleResource.php` — ref: `https://demo.microweber.org/admin/faq`
- [ ] migrate: Media library — `Modules/MediaLibrary/Filament/Admin/Pages/MediaLibrary.php` + `Modules/Media/Filament/Resources/MediaResource.php` — ref: `https://demo.microweber.org/admin/media`
- [ ] migrate: Menus — `Modules/Menu/Filament/Admin/Pages/AdminMenusPage.php` — ref: `https://demo.microweber.org/admin/menus`

---

## Phase 6 — E-commerce Pages

- [ ] migrate: Products list/create/edit — `Modules/Product/Filament/Admin/Resources/ProductResource.php` — ref: `https://demo.microweber.org/admin/products`
- [ ] migrate: Product Inventory — `Modules/Product/Filament/Admin/Resources/ProductInventoryResource.php` — ref: `https://demo.microweber.org/admin/product-inventory`
- [ ] migrate: Product Pricing Rules — `Modules/Product/Filament/Admin/Resources/ProductPricingRuleResource.php` — ref: `https://demo.microweber.org/admin/product-pricing-rules`
- [ ] migrate: Product Variant Attributes — `Modules/Product/Filament/Admin/Resources/ProductVariantAttributeResource.php` — ref: `https://demo.microweber.org/admin/product-variant-attributes`
- [ ] migrate: Shop Categories — `Modules/Category/Filament/Admin/Resources/ShopCategoryResource.php` — ref: `https://demo.microweber.org/admin/shop-categories`
- [ ] migrate: Orders list/create/edit — `Modules/Order/Filament/Admin/Resources/OrderResource.php` — ref: `https://demo.microweber.org/admin/orders`
- [ ] migrate: Customers — `Modules/Customer/Filament/CustomerResource.php` — ref: `https://demo.microweber.org/admin/customers`
- [ ] migrate: Coupons list/create/edit — `Modules/Coupons/Filament/Resources/CouponResource.php` — ref: `https://demo.microweber.org/admin/coupons`
- [ ] migrate: Offers list/create/edit — `Modules/Offer/Filament/Admin/Resources/OfferResource.php` — ref: `https://demo.microweber.org/admin/offers`
- [ ] migrate: Invoices list/create/edit — `Modules/Invoice/Filament/Resources/InvoiceResource.php` — ref: `https://demo.microweber.org/admin/invoices`
- [ ] migrate: Payments list/create/edit — `Modules/Payment/Filament/Admin/Resources/PaymentResource.php` — ref: `https://demo.microweber.org/admin/payments`
- [ ] migrate: Payment Providers — `Modules/Payment/Filament/Admin/Resources/PaymentProviderResource.php` — ref: `https://demo.microweber.org/admin/payment-providers`
- [ ] migrate: Shipping Providers — `Modules/Shipping/Filament/Admin/Resources/ShippingProviderResource.php` — ref: `https://demo.microweber.org/admin/shipping-providers`
- [ ] migrate: Taxes list/create/edit — `Modules/Tax/Filament/Admin/Resources/TaxResource.php` — ref: `https://demo.microweber.org/admin/taxes`
- [ ] migrate: Tax Rates — `Modules/Tax/Filament/Admin/Resources/TaxRateResource.php` — ref: `https://demo.microweber.org/admin/tax-rates`
- [ ] migrate: Currencies — `Modules/Currency/Filament/Admin/Resources/CurrencyResource.php` — ref: `https://demo.microweber.org/admin/currencies`
- [ ] migrate: Exchange Rates — `Modules/Currency/Filament/Admin/Resources/ExchangeRateResource.php` — ref: `https://demo.microweber.org/admin/exchange-rates`
- [ ] migrate: Checkout flow — `Modules/Checkout/Filament/Resources/CheckoutResource.php` — ref: `https://demo.microweber.org/admin/checkout`

---

## Phase 7 — Settings Pages (deep design)

- [ ] migrate: General settings — `Modules/Settings/Filament/Pages/AdminGeneralPage.php` — ref: `https://demo.microweber.org/admin/settings/general`
- [ ] migrate: Email settings — `Modules/Settings/Filament/Pages/AdminEmailPage.php` — ref: `https://demo.microweber.org/admin/settings/email`
- [ ] migrate: SEO settings — `Modules/Settings/Filament/Pages/AdminSeoPage.php` — ref: `https://demo.microweber.org/admin/settings/seo`
- [ ] migrate: Template settings — `Modules/Settings/Filament/Pages/AdminTemplatePage.php` — ref: `https://demo.microweber.org/admin/settings/template`
- [ ] migrate: Template Customizer — `Modules/Settings/Filament/Pages/AdminTemplateCustomizerPage.php` — ref: `https://demo.microweber.org/admin/settings/template-customizer`
- [ ] migrate: Advanced settings — `Modules/Settings/Filament/Pages/AdminAdvancedPage.php` — ref: `https://demo.microweber.org/admin/settings/advanced`
- [ ] migrate: Experimental settings — `Modules/Settings/Filament/Pages/AdminExperimentalPage.php` — ref: `https://demo.microweber.org/admin/settings/experimental`
- [ ] migrate: Login/Register settings — `Modules/Settings/Filament/Pages/AdminLoginRegisterPage.php` — ref: `https://demo.microweber.org/admin/settings/login-register`
- [ ] migrate: Privacy Policy settings — `Modules/Settings/Filament/Pages/AdminPrivacyPolicyPage.php` — ref: `https://demo.microweber.org/admin/settings/privacy-policy`
- [ ] migrate: Maintenance Mode — `Modules/Settings/Filament/Pages/AdminMaintenanceModePage.php` — ref: `https://demo.microweber.org/admin/settings/maintenance-mode`
- [ ] migrate: Language settings — `Modules/Settings/Filament/Pages/AdminLanguagePage.php` — ref: `https://demo.microweber.org/admin/settings/language`
- [ ] migrate: Custom Tags — `Modules/Settings/Filament/Pages/AdminCustomTagsPage.php` — ref: `https://demo.microweber.org/admin/settings/custom-tags`
- [ ] migrate: Shop General — `Modules/Settings/Filament/Pages/AdminShopGeneralPage.php` — ref: `https://demo.microweber.org/admin/settings/shop-general`
- [ ] migrate: Shop Auto Respond Email — `Modules/Settings/Filament/Pages/AdminShopAutoRespondEmailPage.php` — ref: `https://demo.microweber.org/admin/settings/shop-auto-respond-email`
- [ ] migrate: Shop Other — `Modules/Settings/Filament/Pages/AdminShopOtherPage.php` — ref: `https://demo.microweber.org/admin/settings/shop-other`
- [ ] migrate: Updates — `Modules/Settings/Filament/Pages/AdminUpdatesPage.php` — ref: `https://demo.microweber.org/admin/settings/updates`

---

## Phase 8 — Module Settings Pages

- [ ] migrate: AI settings — `Modules/Ai/Filament/Pages/AiSettingsPage.php` — ref: `https://demo.microweber.org/admin/ai-settings`
- [ ] migrate: Comments settings — `Modules/Comments/Filament/Pages/CommentsModuleSettingsAdmin.php` — ref: `https://demo.microweber.org/admin/comments-settings`
- [ ] migrate: Cookie Notice settings — `Modules/CookieNotice/Filament/Pages/CookieNoticeModuleSettingsAdmin.php` — ref: `https://demo.microweber.org/admin/cookie-notice-settings`
- [ ] migrate: Google Analytics settings — `Modules/GoogleAnalytics/Filament/Pages/AdminGoogleAnalyticsSettingsPage.php` — ref: `https://demo.microweber.org/admin/google-analytics-settings`
- [ ] migrate: Multilanguage settings — `Modules/Multilanguage/Filament/Pages/MultilanguageSettingsAdmin.php` — ref: `https://demo.microweber.org/admin/multilanguage-settings`
- [ ] migrate: White Label settings — `Modules/WhiteLabel/Filament/Pages/WhiteLabelSettingsAdminSettingsPage.php` — ref: `https://demo.microweber.org/admin/white-label-settings`
- [ ] migrate: File Manager — `Modules/FileManager/Filament/Pages/FileManagerPageAdmin.php` — ref: `https://demo.microweber.org/admin/file-manager`

---

## Phase 9 — System & Tools Pages

- [ ] migrate: Users list/create/edit — `src/MicroweberPackages/User/Filament/Resources/UsersResource.php` — ref: `https://demo.microweber.org/admin/users`
- [ ] migrate: Roles — `src/MicroweberPackages/Role/Filament/Resources/RoleResource.php` — ref: `https://demo.microweber.org/admin/roles`
- [ ] migrate: Permissions — `src/MicroweberPackages/Role/Filament/Resources/PermissionResource.php` — ref: `https://demo.microweber.org/admin/permissions`
- [ ] migrate: Modules list — `src/MicroweberPackages/LaravelModules/Filament/Resources/ModuleResource/ModuleResource.php` — ref: `https://demo.microweber.org/admin/modules`
- [ ] migrate: Module Dependencies — `src/MicroweberPackages/LaravelModules/Filament/Resources/ModuleDependencyResource.php` — ref: `https://demo.microweber.org/admin/module-dependencies`
- [ ] migrate: Error Tracking — `src/MicroweberPackages/Monitoring/Filament/Resources/ErrorTrackingResource.php` — ref: `https://demo.microweber.org/admin/error-tracking`
- [ ] migrate: Backups — `Modules/Backup/Filament/Resources/BackupResource.php` — ref: `https://demo.microweber.org/admin/backups`
- [ ] migrate: Backup History — `Modules/Backup/Filament/Resources/BackupHistoryResource.php` — ref: `https://demo.microweber.org/admin/backup-history`
- [ ] migrate: Backup Schedules — `Modules/Backup/Filament/Resources/BackupScheduleResource.php` — ref: `https://demo.microweber.org/admin/backup-schedules`
- [ ] migrate: Mail Templates — `Modules/MailTemplate/Filament/Resources/MailTemplateResource.php` — ref: `https://demo.microweber.org/admin/mail-templates`
- [ ] migrate: Translations — `Modules/Settings/Filament/Resources/TranslationResource.php` — ref: `https://demo.microweber.org/admin/translations`
- [ ] migrate: Module Configuration — `Modules/Settings/Filament/Resources/ModuleConfigurationResource.php` — ref: `https://demo.microweber.org/admin/module-configuration`
- [ ] migrate: Updater — `Modules/Updater/Filament/Pages/UpdaterPage.php` — ref: `https://demo.microweber.org/admin/updater`
- [ ] migrate: Marketplace — `Modules/Marketplace/Filament/Admin/MarketplaceResource.php` — ref: `https://demo.microweber.org/admin/marketplace`

---

## Phase 10 — Newsletter Module

- [ ] migrate: Newsletter Homepage/Dashboard — `Modules/Newsletter/Filament/Admin/Pages/Homepage.php` — ref: `https://demo.microweber.org/admin/newsletter`
- [ ] migrate: Campaigns — `Modules/Newsletter/Filament/Admin/Pages/Campaigns.php` — ref: `https://demo.microweber.org/admin/newsletter/campaigns`
- [ ] migrate: Campaign create/edit — `Modules/Newsletter/Filament/Admin/Pages/CreateCampaign.php` + `EditCampaign.php` — ref: `https://demo.microweber.org/admin/newsletter/campaigns/create`
- [ ] migrate: Newsletter Templates — `Modules/Newsletter/Filament/Admin/Pages/Templates.php` — ref: `https://demo.microweber.org/admin/newsletter/templates`
- [ ] migrate: Template Editor — `Modules/Newsletter/Filament/Admin/Pages/TemplateEditor.php` — ref: `https://demo.microweber.org/admin/newsletter/template-editor`
- [ ] migrate: Newsletter Lists — `Modules/Newsletter/Filament/Admin/Pages/Lists.php` — ref: `https://demo.microweber.org/admin/newsletter/lists`
- [ ] migrate: Newsletter Subscribers — `Modules/Newsletter/Filament/Admin/Pages/Subscribers.php` — ref: `https://demo.microweber.org/admin/newsletter/subscribers`
- [ ] migrate: Sender Accounts — `Modules/Newsletter/Filament/Admin/Pages/SenderAccounts.php` — ref: `https://demo.microweber.org/admin/newsletter/sender-accounts`
- [ ] migrate: Workflows — `Modules/Newsletter/Filament/Admin/Resources/WorkflowResource.php` — ref: `https://demo.microweber.org/admin/newsletter/workflows`

---

## Phase 11 — Billing Module

- [ ] migrate: Billing Dashboard — `Modules/Billing/Filament/Admin/Pages/Dashboard.php` — ref: `https://demo.microweber.org/admin/billing`
- [ ] migrate: Billing Settings — `Modules/Billing/Filament/Admin/Pages/Settings.php` — ref: `https://demo.microweber.org/admin/billing/settings`
- [ ] migrate: Subscriptions — `Modules/Billing/Filament/Admin/Resources/SubscriptionResource.php` — ref: `https://demo.microweber.org/admin/billing/subscriptions`
- [ ] migrate: Subscription Plans — `Modules/Billing/Filament/Admin/Resources/SubscriptionPlanResource.php` — ref: `https://demo.microweber.org/admin/billing/subscription-plans`
- [ ] migrate: Subscription Plan Groups — `Modules/Billing/Filament/Admin/Resources/SubscriptionPlanGroupsResource.php` — ref: `https://demo.microweber.org/admin/billing/subscription-plan-groups`
- [ ] migrate: Billing Users — `Modules/Billing/Filament/Admin/Resources/BillingUserResource.php` — ref: `https://demo.microweber.org/admin/billing/users`

---

## Phase 12 — AI & Wizard Pages

- [ ] migrate: Agent Chat — `Modules/Ai/Filament/Resources/AgentChatResource.php` — ref: `https://demo.microweber.org/admin/agent-chat`
- [ ] migrate: AI Wizard — `Modules/AiWizard/Filament/Admin/AiWizardResource.php` — ref: `https://demo.microweber.org/admin/ai-wizard`
- [ ] migrate: AI Wizard Page Design — `Modules/AiWizard/Filament/Admin/AiWizardResource/Pages/AiWizardPageDesign.php` — ref: `https://demo.microweber.org/admin/ai-wizard/design`

---

## Phase 13 — Frontend/Profile Pages

- [ ] migrate: Login page — `Modules/Profile/Filament/Pages/Login.php` — ref: `https://demo.microweber.org/login`
- [ ] migrate: Register page — `Modules/Profile/Filament/Pages/Register.php` — ref: `https://demo.microweber.org/register`
- [ ] migrate: Forgot Password — `Modules/Profile/Filament/Pages/ForgotPassword.php` — ref: `https://demo.microweber.org/forgot-password`
- [ ] migrate: Edit Profile — `Modules/Profile/Filament/Pages/EditProfile.php` — ref: `https://demo.microweber.org/profile`
- [ ] migrate: Change Password — `Modules/Profile/Filament/Pages/ChangePassword.php` — ref: `https://demo.microweber.org/profile/change-password`
- [ ] migrate: Order History — `Modules/Profile/Filament/Pages/OrderHistory.php` — ref: `https://demo.microweber.org/profile/orders`
- [ ] migrate: Saved Addresses — `Modules/Profile/Filament/Pages/SavedAddresses.php` — ref: `https://demo.microweber.org/profile/addresses`
- [ ] migrate: Two Factor Auth — `Modules/Profile/Filament/Pages/TwoFactorAuth.php` — ref: `https://demo.microweber.org/profile/2fa`

---

## Phase 14 — Live Editor Pages

- [ ] migrate: Live Edit — `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditPage.php` — ref: `https://demo.microweber.org/admin/live-edit`
- [ ] migrate: Visual Editor — `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/VisualEditorPage.php` — ref: `https://demo.microweber.org/admin/visual-editor`
- [ ] migrate: Live Edit Sidebar Style Editor — `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditSidebarElementStyleEditorPage.php`
- [ ] migrate: Live Edit Sidebar Template Settings — `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/AdminLiveEditSidebarTemplateSettingsPage.php`
- [ ] migrate: Fonts Manager — `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/EditorTools/FontsManagerModuleSettingsPage.php`
- [ ] migrate: Code Editor Module — `src/MicroweberPackages/LiveEdit/Filament/Admin/Pages/EditorTools/CodeEditorModuleSettingsPage.php`

---

## Phase 15 — Checkout Frontend Pages

- [ ] migrate: Checkout Page — `Modules/Checkout/Filament/Resources/Pages/CheckoutPage.php` — ref: `https://demo.microweber.org/checkout`
- [ ] migrate: Checkout Success — `Modules/Checkout/Filament/Resources/Pages/CheckoutSuccessPage.php` — ref: `https://demo.microweber.org/checkout/success`
- [ ] migrate: Checkout Failed — `Modules/Checkout/Filament/Resources/Pages/CheckoutFailedPage.php` — ref: `https://demo.microweber.org/checkout/failed`
- [ ] migrate: Checkout Cancelled — `Modules/Checkout/Filament/Resources/Pages/CheckoutCancelledPage.php` — ref: `https://demo.microweber.org/checkout/cancelled`

---

## Notes

### How to use the clone-website skill for each page

For each `migrate:` task above:

```
1. Navigate to the MW v2 reference URL
2. Apply clone-website.yml Phase 1 (Reconnaissance):
   - Full-page screenshot at 1440px and 768px
   - Extract design tokens (colors, fonts, spacing, shadows, border-radius)
   - Catalog all interactive states (hover, focus, active, disabled)
3. Apply clone-website.yml Phase 3 (Component Spec):
   - For each section: extract DOM structure, computed CSS, content
   - Write spec file with exact measurements
4. Implement changes in the Filament Resource/Page PHP file
5. Apply 02-test-the-project-ui.yml:
   - Screenshot the local Filament page
   - Side-by-side comparison with MW v2 reference
   - Verify responsive behavior and dark mode
   - Log any visual discrepancies
```

### Priority order

Work through phases 4→15 in order. Within each phase, prioritize pages that are most user-facing (list views before create/edit, main pages before sub-pages).

### Total pages to migrate: ~115 across 12 phases
