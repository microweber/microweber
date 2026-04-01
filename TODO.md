## Done

- [x] 2026-04-01  feat: migrate old MW v2 admin design to Filament 5
  - Reconnaissance: captured screenshots and extracted CSS design tokens from demo.microweber.org
  - Created WelcomeWidget with "Welcome back, [username]" greeting matching MW v2 dashboard
  - Created DashboardQuickStatsWidget with colored icon cards (Emails, Comments, Sales, Orders)
  - Added dashboard widget CSS (welcome heading, 2x2 stat card grid with colored icons)
  - Updated Dashboard page to display welcome + stats widgets before analytics
  - Removed redundant "Dashboard" heading (replaced by welcome message)
  - Theme CSS (microweber-theme-v3.scss) already covers: sidebar, topbar, tables, forms, buttons, badges, tabs, breadcrumbs, pagination, modals, dark mode
  - Built and compiled theme CSS
  - Visual QA verified across: dashboard, pages list, orders, settings, create page

- [x] 2026-04-01  feat: migrate dashboard chart widget from Chart.js to ECharts
  - Created SiteStatsEchartsWidget replacing SiteStatsDashboardChart (Chart.js)
  - Built ECharts area chart with smooth line, gradient fill, matching MW v2 style
  - Added Statistics card UI: icon + title, online count, Daily/Weekly/Monthly period tabs
  - Footer with views/visitors counters and "Show more" link
  - Updated SiteStatsServiceProvider to register new ECharts widget
  - Added .mw-stats-card CSS with dark mode support to theme SCSS
  - Built and compiled theme CSS

- [x] 2026-04-01  fix: sidebar inconsistencies between MW v2 and Filament 5
  - Fixed truncated sidebar text ("Variant Attri..." now shows full "Variant Attributes")
  - Removed white-space: nowrap from sidebar labels, allowing text to wrap naturally
  - Improved group header labels: darker color (#4a5568), slightly larger (0.7rem), better letter-spacing
  - Added subtle spacing (4px margin/padding) between navigation groups
  - Softened group separator border opacity (0.14 → 0.10)
  - Widened sidebar from 15rem to 16rem to accommodate longer labels
  - Fixed dark mode group separator border color (rgba white 6%)
  - Visual QA verified across: dashboard, pages list, settings

- [x] 2026-04-01  plan: full admin page mapping (old MW v2 → Filament 5)
  - Enumerated all old admin pages/routes and all Filament resources/pages
  - Created migration checklist below

- [ ] add the workflows to the todo and work on them one by one add them with [ ] points https://agents.tools.ooyes.net/workflows.yml

---

## Full Admin Migration Plan — Old MW v2 → Filament 5

### Legend
- **Old** = MW v2 admin page/section
- **New** = Filament 5 equivalent
- Status: `[x]` done, `[ ]` needs design work, `[~]` partially done

---

### 1. Core Pages

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 1.1 | Dashboard (Welcome, Stats, Chart) | `app/Filament/Admin/Pages/Dashboard.php` + WelcomeWidget, DashboardQuickStatsWidget, SiteStatsEchartsWidget | [x] |
| 1.2 | Live Edit (EDIT button in topbar) | `AdminLiveEditPage` (sidebar item) | [x] |
| 1.3 | Login page | Filament built-in login | [ ] |

### 2. Website Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 2.1 | Website > Pages (list) | `Modules/Page/Filament/Resources/PageResource.php` → ListPages | [~] |
| 2.2 | Website > Pages (create/edit) | PageResource → CreatePage, EditPage | [ ] |
| 2.3 | Website > Posts (list) | `Modules/Post/Filament/Admin/Resources/PostResource.php` → ListPosts | [~] |
| 2.4 | Website > Posts (create/edit) | PostResource → CreatePost, EditPost | [ ] |
| 2.5 | Website > Categories | `Modules/Category/Filament/Admin/Resources/CategoryResource.php` | [ ] |
| 2.6 | Media Library | `Modules/MediaLibrary/Filament/Admin/Pages/MediaLibrary.php` | [ ] |
| 2.7 | Menu management | `Modules/Menu/Filament/Admin/Pages/AdminMenusPage.php` | [ ] |
| 2.8 | Tags | `Modules/Tag/Filament/Resources/TagResource.php` | [ ] |
| 2.9 | Comments | `Modules/Comments/Filament/Resources/CommentResource.php` | [ ] |

### 3. Shop Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 3.1 | Shop > Products (list) | `Modules/Product/Filament/Admin/Resources/ProductResource.php` → ListProducts | [ ] |
| 3.2 | Shop > Products (create/edit) | ProductResource → CreateProduct, EditProduct | [ ] |
| 3.3 | Shop > Categories | `Modules/Category/Filament/Admin/Resources/ShopCategoryResource.php` | [ ] |
| 3.4 | Shop > Orders (list) | `Modules/Order/Filament/Admin/Resources/OrderResource.php` → ListOrders | [~] |
| 3.5 | Shop > Orders (create/edit) | OrderResource → CreateOrder, EditOrder | [ ] |
| 3.6 | Shop > Variant Attributes | `ProductVariantAttributeResource.php` | [ ] |
| 3.7 | Shop > Inventory | `ProductInventoryResource.php` | [ ] |
| 3.8 | Shop > Pricing Rules | `ProductPricingRuleResource.php` | [ ] |
| 3.9 | Shop > Coupons | `Modules/Coupons/Filament/Resources/CouponResource.php` | [ ] |
| 3.10 | Shop > Offers | `Modules/Offer/Filament/Admin/Resources/OfferResource.php` | [ ] |
| 3.11 | Shop > Invoices | `Modules/Invoice/Filament/Resources/InvoiceResource.php` | [ ] |
| 3.12 | Shop > Payments | `Modules/Payment/Filament/Admin/Resources/PaymentResource.php` | [ ] |
| 3.13 | Shop > Payment Providers | `PaymentProviderResource.php` | [ ] |
| 3.14 | Shop > Shipping Providers | `ShippingProviderResource.php` | [ ] |
| 3.15 | Shop > Taxes | `Modules/Tax/Filament/Admin/Resources/TaxResource.php` | [ ] |
| 3.16 | Shop > Tax Rates | `TaxRateResource.php` | [ ] |
| 3.17 | Checkout flow | `Modules/Checkout/Filament/Resources/CheckoutResource.php` | [ ] |

### 4. Settings Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 4.1 | Settings hub (card grid) | `Modules/Settings/Filament/Pages/Settings.php` | [~] |
| 4.2 | General settings | `AdminGeneralPage.php` | [ ] |
| 4.3 | Template settings | `AdminTemplatePage.php` | [ ] |
| 4.4 | SEO settings | `AdminSeoPage.php` | [ ] |
| 4.5 | Custom HTML tags | `AdminCustomTagsPage.php` | [ ] |
| 4.6 | Template Customizer | `AdminTemplateCustomizerPage.php` | [ ] |
| 4.7 | Email settings | `AdminEmailPage.php` | [ ] |
| 4.8 | Auto-respond emails | `AdminShopAutoRespondEmailPage.php` | [ ] |
| 4.9 | Mail templates | `MailTemplateResource.php` | [ ] |
| 4.10 | Privacy Policy | `AdminPrivacyPolicyPage.php` | [ ] |
| 4.11 | Login & Register | `AdminLoginRegisterPage.php` | [ ] |
| 4.12 | Advanced settings | `AdminAdvancedPage.php` | [ ] |
| 4.13 | Cookie Notice | `CookieNoticeModuleSettingsAdmin.php` | [ ] |
| 4.14 | File Manager | `FileManagerPageAdmin.php` | [ ] |
| 4.15 | Comments settings | `CommentsModuleSettingsAdmin.php` | [ ] |

### 5. System / Admin Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 5.1 | Modules list | `ModuleResource.php` (Customization Settings) | [ ] |
| 5.2 | Marketplace | `Modules/Marketplace/Filament/Admin/MarketplaceResource.php` | [ ] |
| 5.3 | Updates | `Modules/Updater/Filament/Pages/UpdaterPage.php` | [ ] |
| 5.4 | Maintenance mode | `AdminMaintenanceModePage.php` | [ ] |
| 5.5 | Backup & schedules | `BackupResource.php`, `BackupScheduleResource.php`, `BackupHistoryResource.php` | [ ] |
| 5.6 | Error tracking | `ErrorTrackingResource.php` | [ ] |
| 5.7 | AI settings | `AiSettingsPage.php` | [ ] |
| 5.8 | AI Wizard | `AiWizardResource.php` | [ ] |
| 5.9 | Experimental | `AdminExperimentalPage.php` | [ ] |
| 5.10 | White Label | `WhiteLabelSettingsAdminSettingsPage.php` | [ ] |

### 6. Users Section

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 6.1 | Users list | `UsersResource.php` | [ ] |
| 6.2 | User create/edit | UsersResource → CreateUsers, EditUsers | [ ] |
| 6.3 | Roles | `RoleResource.php` | [ ] |
| 6.4 | Permissions | `PermissionResource.php` | [ ] |

### 7. Multilanguage / Translations

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 7.1 | Language settings | `MultilanguageSettingsAdmin.php` | [ ] |
| 7.2 | Translations | `TranslationResource.php` | [ ] |

### 8. Email Marketing / Newsletter

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 8.1 | Newsletter dashboard | `Modules/Newsletter/Filament/Admin/Pages/Homepage.php` | [ ] |
| 8.2 | Campaigns | `CampaignResource.php` | [ ] |
| 8.3 | Subscribers | `SubscribersResource.php` | [ ] |
| 8.4 | Lists | `ListResource.php` | [ ] |
| 8.5 | Templates | `TemplatesResource.php` | [ ] |
| 8.6 | Template editor | `TemplateEditor.php` | [ ] |
| 8.7 | Sender accounts | `SenderAccountsResource.php` | [ ] |
| 8.8 | Workflows | `WorkflowResource.php` | [ ] |

### 9. Billing / Subscriptions (if enabled)

| # | Old MW v2 Page | Filament 5 Equivalent | Status |
|---|----------------|----------------------|--------|
| 9.1 | Billing dashboard | `Modules/Billing/Filament/Admin/Pages/Dashboard.php` | [ ] |
| 9.2 | Subscription plans | `SubscriptionPlanResource.php` | [ ] |
| 9.3 | Plan groups | `SubscriptionPlanGroupsResource.php` | [ ] |
| 9.4 | Subscriptions list | `SubscriptionResource.php` | [ ] |
| 9.5 | Billing users | `BillingUserResource.php` | [ ] |
| 9.6 | Billing settings | `Modules/Billing/Filament/Admin/Pages/Settings.php` | [ ] |

### 10. Cross-Cutting Design Tasks

- [ ] 10.1 Login page — match MW v2 login design
- [ ] 10.2 Dark mode — full QA pass across all pages
- [ ] 10.3 Mobile responsive — sidebar collapse, table stacking
- [ ] 10.4 Form layouts — consistent field spacing, labels, help text
- [ ] 10.5 Table layouts — consistent column widths, row heights, status badges
- [ ] 10.6 Modal dialogs — consistent sizing, padding, button placement
- [ ] 10.7 Notifications / toasts — match MW v2 notification style
- [ ] 10.8 Empty states — consistent "no data" illustrations
- [ ] 10.9 Loading states — skeleton screens, spinners
- [ ] 10.10 Breadcrumbs — consistent styling across all pages
