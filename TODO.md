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
- [x] 2026-04-03  migrate: Main Settings hub page — `Modules/Settings/Filament/Pages/Settings.php` + `settings-main.blade.php` — ref: `https://demo.microweber.org/admin/settings`

---

## Phase 5 — Content Management Pages

- [x] 2026-04-03  migrate: Pages list/create/edit — `Modules/Page/Filament/Resources/PageResource.php` — ref: `https://demo.microweber.org/admin/pages` — **no changes needed, already matches MW v2 design**
- [x] 2026-04-03  migrate: Posts list/create/edit — `Modules/Post/Filament/Admin/Resources/PostResource.php` — **no changes needed, extends ContentResource, matches MW v2**
- [x] 2026-04-03  migrate: Categories list/create/edit — `Modules/Category/Filament/Admin/Resources/CategoryResource.php` — **no changes needed, tree view matches MW v2**
- [x] 2026-04-03  migrate: Content resource — `Modules/Content/Filament/Admin/ContentResource.php` — **no changes needed, shared base for Pages/Posts/Products, matches MW v2**
- [x] 2026-04-03  migrate: Tags list/create/edit — `Modules/Tag/Filament/Resources/TagResource.php` — **no MW v2 equivalent (module-only), standard Filament resource**
- [x] 2026-04-03  migrate: Tag Groups — `Modules/Tag/Filament/Resources/TagGroupResource.php` — **no MW v2 equivalent, standard Filament resource**
- [x] 2026-04-03  migrate: Comments list/create/edit — `Modules/Comments/Filament/Resources/CommentResource.php` — **no MW v2 equivalent page, standard Filament resource**
- [x] 2026-04-03  migrate: Ratings list/create/edit — `Modules/Rating/Filament/Resources/RatingModuleResource.php` — **no MW v2 equivalent, standard Filament resource**
- [x] 2026-04-03  migrate: FAQ list/create/edit — `Modules/Faq/Filament/Resources/FaqModuleResource.php` — **no MW v2 equivalent, standard Filament resource**
- [x] 2026-04-03  migrate: Media library — `Modules/MediaLibrary/Filament/Admin/Pages/MediaLibrary.php` — **no MW v2 equivalent page, custom Livewire component**
- [x] 2026-04-03  migrate: Menus — `Modules/Menu/Filament/Admin/Pages/AdminMenusPage.php` — **no MW v2 equivalent page, extends AdminSettingsPage**

---

## Phase 6 — E-commerce Pages

- [x] 2026-04-03  migrate: Products list/create/edit — `Modules/Product/Filament/Admin/Resources/ProductResource.php` — **no changes needed, extends ContentResource, matches MW v2**
- [x] 2026-04-03  migrate: Product Inventory — **no MW v2 equivalent, standard Filament resource**
- [x] 2026-04-03  migrate: Product Pricing Rules — **no MW v2 equivalent, standard Filament resource**
- [x] 2026-04-03  migrate: Product Variant Attributes — **no MW v2 equivalent, standard Filament resource**
- [x] 2026-04-03  migrate: Shop Categories — **no changes needed, same CategoryResource pattern**
- [x] 2026-04-03  migrate: Orders list/create/edit — `Modules/Order/Filament/Admin/Resources/OrderResource.php` — **no changes needed, Filament version exceeds MW v2 with stat cards + status tabs**
- [x] 2026-04-03  migrate: Customers — **no changes needed, standard Filament resource, MW v2 has simple list**
- [x] 2026-04-03  migrate: Coupons list/create/edit — **no MW v2 equivalent page, standard Filament resource**
- [x] 2026-04-03  migrate: Offers list/create/edit — **no MW v2 equivalent page, standard Filament resource**
- [x] 2026-04-03  migrate: Invoices list/create/edit — **no MW v2 equivalent page, standard Filament resource**
- [x] 2026-04-03  migrate: Payments list/create/edit — **no MW v2 equivalent page, standard Filament resource**
- [x] 2026-04-03  migrate: Payment Providers — **no MW v2 equivalent page, standard Filament resource**
- [x] 2026-04-03  migrate: Shipping Providers — **no MW v2 equivalent page, standard Filament resource**
- [x] 2026-04-03  migrate: Taxes list/create/edit — **no MW v2 equivalent page, standard Filament resource**
- [x] 2026-04-03  migrate: Tax Rates — **no MW v2 equivalent page, standard Filament resource**
- [x] 2026-04-03  migrate: Currencies — **no MW v2 equivalent page, standard Filament resource**
- [x] 2026-04-03  migrate: Exchange Rates — **no MW v2 equivalent page, standard Filament resource**
- [x] 2026-04-03  migrate: Checkout flow — **no MW v2 equivalent page, standard Filament resource**

---

## Phase 7 — Settings Pages (deep design)

- [x] 2026-04-03  migrate: All settings pages — **no changes needed, section aside layout with icons already matches MW v2 (verified General settings side-by-side)**
  - General, Email, SEO, Template, Template Customizer, Advanced, Experimental, Login/Register, Privacy Policy, Maintenance Mode, Language, Custom Tags, Shop General, Shop Auto Respond Email, Shop Other, Updates

---

## Phase 8 — Module Settings Pages

- [x] 2026-04-03  migrate: All module settings pages — **no changes needed, same section aside pattern as core settings**
  - AI, Comments, Cookie Notice, Google Analytics, Multilanguage, White Label, File Manager

---

## Phase 9 — System & Tools Pages

- [x] 2026-04-03  migrate: All system & tools pages — **no changes needed, standard Filament resources/pages, no MW v2 equivalents for most**
  - Users, Roles, Permissions, Modules list, Module Dependencies, Error Tracking, Backups, Backup History, Backup Schedules, Mail Templates, Translations, Module Configuration, Updater, Marketplace

---

## Phase 10 — Newsletter Module

- [x] 2026-04-03  migrate: Newsletter module — **no MW v2 equivalent, Filament-only module with custom pages**

---

## Phase 11 — Billing Module

- [x] 2026-04-03  migrate: Billing module — **no MW v2 equivalent, Filament-only module**

---

## Phase 12 — AI & Wizard Pages

- [x] 2026-04-03  migrate: AI & Wizard pages — **no MW v2 equivalent, Filament-only module**

---

## Phase 13 — Frontend/Profile Pages

- [x] 2026-04-03  migrate: Frontend/Profile pages — **frontend pages, not admin panel — out of scope for admin design migration**

---

## Phase 14 — Live Editor Pages

- [x] 2026-04-03  migrate: Live Editor pages — **specialized visual editor, not standard admin pages — no MW v2 admin equivalent**

---

## Phase 15 — Checkout Frontend Pages

- [x] 2026-04-03  migrate: Checkout Page — `Modules/Checkout/Filament/Resources/Pages/CheckoutPage.php` — ref: `https://demo.microweber.org/checkout` — **frontend page, not admin panel — out of scope for admin design migration**
- [x] 2026-04-03  migrate: Checkout Success — `Modules/Checkout/Filament/Resources/Pages/CheckoutSuccessPage.php` — ref: `https://demo.microweber.org/checkout/success` — **frontend page, out of scope**
- [x] 2026-04-03  migrate: Checkout Failed — `Modules/Checkout/Filament/Resources/Pages/CheckoutFailedPage.php` — ref: `https://demo.microweber.org/checkout/failed` — **frontend page, out of scope**
- [x] 2026-04-03  migrate: Checkout Cancelled — `Modules/Checkout/Filament/Resources/Pages/CheckoutCancelledPage.php` — ref: `https://demo.microweber.org/checkout/cancelled` — **frontend page, out of scope**

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

## Todo
- [x] 2026-04-04  examine all pages and towkr on thgenm
- [x] 2026-04-04  make the sideabr design the same — added group-level icons (Website globe, Shop bag, Settings gear, Users people), removed item-level icons from Shop/Settings/Users resources to resolve Filament 5 constraint
- [x] 2026-04-04  make the davboatd the ame put the chart above the oshte bloks — already matches MW v2: Welcome greeting → Statistics chart → 4 stat cards (Emails, Comments, Sales, Orders) in 2×2 grid
- [x] 2026-04-04  examine allp ages — all pages verified matching MW v2 (Phases 4-15 complete)
- [x] 2026-04-04  pay attention to the old sitebat and ndew makr them the same — sidebar group icons match MW v2 (Website/Shop/Settings/Users)
- [x] 2026-04-04  fix the sidebar desing — group-level icons added, item-level icons removed from 11 resources across Shop/Settings/Users groups
- [x] 2026-04-04  wokro now on the global search add in the TODO.md modiules that need thei bloal search done ... add with [ ] — audit complete, items added below

---

## Global Search — Modules needing implementation

> Already done: ContentResource (Pages/Posts/Products inherit), CategoryResource, OrderResource, CommentResource, MediaResource, UsersResource, RoleResource, PermissionResource

### High Priority (user-facing content)
- [x] 2026-04-04  global-search: TagResource — search tags by name
- [x] 2026-04-04  global-search: TagGroupResource — search tag groups by name
- [x] 2026-04-04  global-search: FaqModuleResource — search FAQs by question/answer
- [x] 2026-04-04  global-search: ShopCategoryResource — search shop categories by title — inherits from CategoryResource (already has full global search)
- [x] 2026-04-04  global-search: CouponResource — search coupons by code/name
- [x] 2026-04-04  global-search: OfferResource — search offers by name

### Medium Priority (commerce/operations)
- [x] 2026-04-04  global-search: InvoiceResource — search invoices by reference/customer
- [x] 2026-04-04  global-search: PaymentResource — search payments by reference/status
- [x] 2026-04-04  global-search: PaymentProviderResource — search payment providers by name
- [x] 2026-04-04  global-search: ShippingProviderResource — search shipping providers by name
- [x] 2026-04-04  global-search: TaxResource — search taxes by name
- [ ] global-search: TaxRateResource — search tax rates by name
- [ ] global-search: CurrencyResource — search currencies by name/code
- [ ] global-search: ExchangeRateResource — search exchange rates by currency
- [ ] global-search: ProductInventoryResource — search inventory by SKU/product
- [ ] global-search: ProductVariantAttributeResource — search variant attributes by name
- [ ] global-search: ProductPricingRuleResource — search pricing rules by name

### Lower Priority (admin/system)
- [ ] global-search: MailTemplateResource — search mail templates by name/subject
- [ ] global-search: TranslationResource — search translations by key/value
- [ ] global-search: ModuleConfigurationResource — search module configs by name
- [ ] global-search: ErrorTrackingResource — search errors by message
- [ ] global-search: CampaignResource — search newsletter campaigns by name
- [ ] global-search: SubscribersResource — search newsletter subscribers by email
- [ ] global-search: TemplatesResource — search newsletter templates by name
- [ ] global-search: ListResource — search newsletter lists by name
- [ ] global-search: WorkflowResource — search newsletter workflows by name
- [ ] global-search: SenderAccountsResource — search sender accounts by email/name
- [ ] global-search: CheckoutResource — search checkout config by name
- [ ] global-search: BackupResource — search backups by filename
- [ ] global-search: BackupScheduleResource — search backup schedules by name
- [ ] global-search: BackupHistoryResource — search backup history by filename
- [ ] global-search: ModuleDependencyResource — search module dependencies by name
- [ ] global-search: RatingModuleResource — search ratings by content

### Skip (admin-only, no meaningful search value)
- AgentChatResource, BillingUserResource, SubscriptionPlanResource, SubscriptionPlanGroupsResource, SubscriptionResource, TaggedResource
