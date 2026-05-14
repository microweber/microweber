# Microweber per-module docs

Two layers of documentation coexist in this repo:

1. **Tier-1 narrative docs** — VitePress-rendered, six-page-per-module sets covering nine core modules. These are the **canonical, hand-written guides** for the modules that ship Microweber's core surface. Use them for "how does this module work, end-to-end" and "how do I customise it from a sibling package".
2. **Per-module README snapshots** — auto-generated from filesystem on 2026-04-25, one per module, mostly machine-summarised. Use them for "what classes / events / columns exist right now". They lag the codebase by their generation date; treat as a reference rather than a guide.

When the two disagree on shape (e.g. a class was renamed after the auto-generation date), trust the narrative docs first, then read the actual source to confirm.

---

## Tier-1 narrative docs (the canonical guides)

Each tier-1 module has six pages: **Overview / Installation / Usage / API / Examples / Troubleshooting**. Grouped here by sub-cluster.

### E-commerce sub-cluster (data flow: Product → Cart → Checkout → Order)

| Module | Role | Docs |
|---|---|---|
| **Product** | data owner — three-table variant system, three-tier pricing, inventory triad | [📖 Product docs](./product/) |
| **Cart** | state manager — single-table line-item-per-row, server-canonical pricing | [📖 Cart docs](./cart/) |
| **Checkout** | conversion flow — five-step wizard, gateway round-trip, zero owned models | [📖 Checkout docs](./checkout/) |
| **Order** | persistence + fulfillment — `cart_orders` row, 7-state lifecycle, 8 events | [📖 Order docs](./order/) |

### Content-discovery sub-cluster

| Module | Role | Docs |
|---|---|---|
| **Search** | live `LIKE` search via 1 Livewire component over Content's `get_content()` | [📖 Search docs](./search/) |
| **Seo** | SeoMetadataService + 5 Blade directives; 13 columns on `content` via Content migration | [📖 Seo docs](./seo/) |
| **Sitemap** | 6 routes (sitemap-index + 5 sub-sitemaps); 3-hour disk cache; sitemap protocol 0.9 | [📖 Sitemap docs](./sitemap/) |

### Admin shell sub-cluster

| Module | Role | Docs |
|---|---|---|
| **Admin** | Filament v5 boot harness — panel provider, login, middleware, AdminSettingsPage abstract, MwColors | [📖 Admin docs](./admin/) |
| **LiveEdit** | two-surface in-place editor — admin frame + canvas iframe communicate via CustomEvent verbs | [📖 LiveEdit docs](./liveedit/) |

### Cross-cluster topics

- **Save flow** — the LiveEdit specificity ranker is documented in [LiveEdit usage → save flow](./liveedit/usage.md#the-save-flow--what-happens-when-save-is-clicked); for the resource-form save path see [Admin usage → AdminSettingsPage](./admin/usage.md#building-an-admin-settings-page).
- **Multilanguage** — Content owns the `HasMultilanguageTrait` and `multilanguage_translations` table; per-module translatable fields documented in each module's docs.
- **Color tokens** — anchored at `MicroweberPackages\Admin\Filament\MwColors` (primary blue `#0d6efd`); admin / checkout / public share the palette. See [Admin api → MwColors](./admin/api.md#mwcolors-brand-color-authority).
- **Mobile considerations** — AI-515 (commit `54fdfeb713`) ships safe-area-inset CSS across admin / checkout panels. Per-module mobile gotchas are in each module's troubleshooting page.

---

## Per-module README snapshots (auto-generated)

Each `Modules/<X>/docs/README.md` documents a single module's
data model, public API, service classes, events, and tests
according to the canonical
[`MODULE_DOCS_TEMPLATE.md`](./MODULE_DOCS_TEMPLATE.md). The
[`Modules/Settings/docs/README.md`](../../Modules/Settings/docs/README.md)
page is the hand-curated example; the rest were
auto-generated from a filesystem survey on 2026-04-25 and
marked **🤖 generated** in the index below — those need a
hand-edit pass to populate the operator-side Domain section
and inline the column lists / route tables.

## Tiers

  - **Tier 1** — owns its own data **and** exposes a public API.
  - **Tier 2** — service / API surface on top of shared infrastructure.
  - **Tier 3** — admin tool / widget driven by a Filament page or resource.
  - **Tier 4** — pure presentation / template-side widget.

## Index

| Tier | Module | Status | Docs |
|------|--------|--------|------|
| 1 | Ai | 🤖 generated | [`Modules/Ai/docs/README.md`](../../Modules/Ai/docs/README.md) |
| 1 | Backup | 🤖 generated | [`Modules/Backup/docs/README.md`](../../Modules/Backup/docs/README.md) |
| 1 | Billing | 🤖 generated | [`Modules/Billing/docs/README.md`](../../Modules/Billing/docs/README.md) |
| 1 | Cart | 🤖 generated | [`Modules/Cart/docs/README.md`](../../Modules/Cart/docs/README.md) |
| 1 | Category | 🤖 generated | [`Modules/Category/docs/README.md`](../../Modules/Category/docs/README.md) |
| 1 | Comments | 🤖 generated | [`Modules/Comments/docs/README.md`](../../Modules/Comments/docs/README.md) |
| 1 | ContactForm | 🤖 generated | [`Modules/ContactForm/docs/README.md`](../../Modules/ContactForm/docs/README.md) |
| 1 | Content | 🤖 generated | [`Modules/Content/docs/README.md`](../../Modules/Content/docs/README.md) |
| 1 | Coupons | 🤖 generated | [`Modules/Coupons/docs/README.md`](../../Modules/Coupons/docs/README.md) |
| 1 | Customer | 🤖 generated | [`Modules/Customer/docs/README.md`](../../Modules/Customer/docs/README.md) |
| 1 | Form | 🤖 generated | [`Modules/Form/docs/README.md`](../../Modules/Form/docs/README.md) |
| 1 | Invoice | 🤖 generated | [`Modules/Invoice/docs/README.md`](../../Modules/Invoice/docs/README.md) |
| 1 | Media | 🤖 generated | [`Modules/Media/docs/README.md`](../../Modules/Media/docs/README.md) |
| 1 | Menu | 🤖 generated | [`Modules/Menu/docs/README.md`](../../Modules/Menu/docs/README.md) |
| 1 | Newsletter | 🤖 generated | [`Modules/Newsletter/docs/README.md`](../../Modules/Newsletter/docs/README.md) |
| 1 | Offer | 🤖 generated | [`Modules/Offer/docs/README.md`](../../Modules/Offer/docs/README.md) |
| 1 | Order | 🤖 generated | [`Modules/Order/docs/README.md`](../../Modules/Order/docs/README.md) |
| 1 | Payment | 🤖 generated | [`Modules/Payment/docs/README.md`](../../Modules/Payment/docs/README.md) |
| 1 | Product | 🤖 generated | [`Modules/Product/docs/README.md`](../../Modules/Product/docs/README.md) |
| 1 | Profile | 🤖 generated | [`Modules/Profile/docs/README.md`](../../Modules/Profile/docs/README.md) |
| 1 | Rating | 🤖 generated | [`Modules/Rating/docs/README.md`](../../Modules/Rating/docs/README.md) |
| 1 | Shipping | 🤖 generated | [`Modules/Shipping/docs/README.md`](../../Modules/Shipping/docs/README.md) |
| 1 | SiteStats | 🤖 generated | [`Modules/SiteStats/docs/README.md`](../../Modules/SiteStats/docs/README.md) |
| 1 | Tag | 🤖 generated | [`Modules/Tag/docs/README.md`](../../Modules/Tag/docs/README.md) |
| 1 | Tax | 🤖 generated | [`Modules/Tax/docs/README.md`](../../Modules/Tax/docs/README.md) |
| 2 | Captcha | 🤖 generated | [`Modules/Captcha/docs/README.md`](../../Modules/Captcha/docs/README.md) |
| 2 | Checkout | 🤖 generated | [`Modules/Checkout/docs/README.md`](../../Modules/Checkout/docs/README.md) |
| 2 | CookieNotice | 🤖 generated | [`Modules/CookieNotice/docs/README.md`](../../Modules/CookieNotice/docs/README.md) |
| 2 | Currency | 🤖 generated | [`Modules/Currency/docs/README.md`](../../Modules/Currency/docs/README.md) |
| 2 | CustomFields | 🤖 generated | [`Modules/CustomFields/docs/README.md`](../../Modules/CustomFields/docs/README.md) |
| 2 | Export | 🤖 generated | [`Modules/Export/docs/README.md`](../../Modules/Export/docs/README.md) |
| 2 | FileManager | 🤖 generated | [`Modules/FileManager/docs/README.md`](../../Modules/FileManager/docs/README.md) |
| 2 | Log | 🤖 generated | [`Modules/Log/docs/README.md`](../../Modules/Log/docs/README.md) |
| 2 | MailTemplate | 🤖 generated | [`Modules/MailTemplate/docs/README.md`](../../Modules/MailTemplate/docs/README.md) |
| 2 | Multilanguage | 🤖 generated | [`Modules/Multilanguage/docs/README.md`](../../Modules/Multilanguage/docs/README.md) |
| 2 | Page | 🤖 generated | [`Modules/Page/docs/README.md`](../../Modules/Page/docs/README.md) |
| 2 | Post | 🤖 generated | [`Modules/Post/docs/README.md`](../../Modules/Post/docs/README.md) |
| 2 | RssFeed | 🤖 generated | [`Modules/RssFeed/docs/README.md`](../../Modules/RssFeed/docs/README.md) |
| 2 | Seo | 🤖 generated | [`Modules/Seo/docs/README.md`](../../Modules/Seo/docs/README.md) |
| 2 | Settings | ✅ documented | [`Modules/Settings/docs/README.md`](../../Modules/Settings/docs/README.md) |
| 2 | Shop | 🤖 generated | [`Modules/Shop/docs/README.md`](../../Modules/Shop/docs/README.md) |
| 2 | Sitemap | 🤖 generated | [`Modules/Sitemap/docs/README.md`](../../Modules/Sitemap/docs/README.md) |
| 2 | Updater | 🤖 generated | [`Modules/Updater/docs/README.md`](../../Modules/Updater/docs/README.md) |
| 2 | WhiteLabel | 🤖 generated | [`Modules/WhiteLabel/docs/README.md`](../../Modules/WhiteLabel/docs/README.md) |
| 2 | WordPressMigration | 🤖 generated | [`Modules/WordPressMigration/docs/README.md`](../../Modules/WordPressMigration/docs/README.md) |
| 3 | Accordion | 🤖 generated | [`Modules/Accordion/docs/README.md`](../../Modules/Accordion/docs/README.md) |
| 3 | AiWizard | 🤖 generated | [`Modules/AiWizard/docs/README.md`](../../Modules/AiWizard/docs/README.md) |
| 3 | Audio | 🤖 generated | [`Modules/Audio/docs/README.md`](../../Modules/Audio/docs/README.md) |
| 3 | Background | 🤖 generated | [`Modules/Background/docs/README.md`](../../Modules/Background/docs/README.md) |
| 3 | BeforeAfter | 🤖 generated | [`Modules/BeforeAfter/docs/README.md`](../../Modules/BeforeAfter/docs/README.md) |
| 3 | Blog | 🤖 generated | [`Modules/Blog/docs/README.md`](../../Modules/Blog/docs/README.md) |
| 3 | Breadcrumb | 🤖 generated | [`Modules/Breadcrumb/docs/README.md`](../../Modules/Breadcrumb/docs/README.md) |
| 3 | Btn | 🤖 generated | [`Modules/Btn/docs/README.md`](../../Modules/Btn/docs/README.md) |
| 3 | Embed | 🤖 generated | [`Modules/Embed/docs/README.md`](../../Modules/Embed/docs/README.md) |
| 3 | FacebookLike | 🤖 generated | [`Modules/FacebookLike/docs/README.md`](../../Modules/FacebookLike/docs/README.md) |
| 3 | FacebookPage | 🤖 generated | [`Modules/FacebookPage/docs/README.md`](../../Modules/FacebookPage/docs/README.md) |
| 3 | Faq | 🤖 generated | [`Modules/Faq/docs/README.md`](../../Modules/Faq/docs/README.md) |
| 3 | GoogleAnalytics | 🤖 generated | [`Modules/GoogleAnalytics/docs/README.md`](../../Modules/GoogleAnalytics/docs/README.md) |
| 3 | GoogleMaps | 🤖 generated | [`Modules/GoogleMaps/docs/README.md`](../../Modules/GoogleMaps/docs/README.md) |
| 3 | HighlightCode | 🤖 generated | [`Modules/HighlightCode/docs/README.md`](../../Modules/HighlightCode/docs/README.md) |
| 3 | ImageRollover | 🤖 generated | [`Modules/ImageRollover/docs/README.md`](../../Modules/ImageRollover/docs/README.md) |
| 3 | LayoutContent | 🤖 generated | [`Modules/LayoutContent/docs/README.md`](../../Modules/LayoutContent/docs/README.md) |
| 3 | Layouts | 🤖 generated | [`Modules/Layouts/docs/README.md`](../../Modules/Layouts/docs/README.md) |
| 3 | Logo | 🤖 generated | [`Modules/Logo/docs/README.md`](../../Modules/Logo/docs/README.md) |
| 3 | Marketplace | 🤖 generated | [`Modules/Marketplace/docs/README.md`](../../Modules/Marketplace/docs/README.md) |
| 3 | Marquee | 🤖 generated | [`Modules/Marquee/docs/README.md`](../../Modules/Marquee/docs/README.md) |
| 3 | MediaLibrary | 🤖 generated | [`Modules/MediaLibrary/docs/README.md`](../../Modules/MediaLibrary/docs/README.md) |
| 3 | Pagination | 🤖 generated | [`Modules/Pagination/docs/README.md`](../../Modules/Pagination/docs/README.md) |
| 3 | Pdf | 🤖 generated | [`Modules/Pdf/docs/README.md`](../../Modules/Pdf/docs/README.md) |
| 3 | Pictures | 🤖 generated | [`Modules/Pictures/docs/README.md`](../../Modules/Pictures/docs/README.md) |
| 3 | Search | 🤖 generated | [`Modules/Search/docs/README.md`](../../Modules/Search/docs/README.md) |
| 3 | Sharer | 🤖 generated | [`Modules/Sharer/docs/README.md`](../../Modules/Sharer/docs/README.md) |
| 3 | Skills | 🤖 generated | [`Modules/Skills/docs/README.md`](../../Modules/Skills/docs/README.md) |
| 3 | Slider | 🤖 generated | [`Modules/Slider/docs/README.md`](../../Modules/Slider/docs/README.md) |
| 3 | SocialLinks | 🤖 generated | [`Modules/SocialLinks/docs/README.md`](../../Modules/SocialLinks/docs/README.md) |
| 3 | Spacer | 🤖 generated | [`Modules/Spacer/docs/README.md`](../../Modules/Spacer/docs/README.md) |
| 3 | Tabs | 🤖 generated | [`Modules/Tabs/docs/README.md`](../../Modules/Tabs/docs/README.md) |
| 3 | Teamcard | 🤖 generated | [`Modules/Teamcard/docs/README.md`](../../Modules/Teamcard/docs/README.md) |
| 3 | Testimonials | 🤖 generated | [`Modules/Testimonials/docs/README.md`](../../Modules/Testimonials/docs/README.md) |
| 3 | TextType | 🤖 generated | [`Modules/TextType/docs/README.md`](../../Modules/TextType/docs/README.md) |
| 3 | TweetEmbed | 🤖 generated | [`Modules/TweetEmbed/docs/README.md`](../../Modules/TweetEmbed/docs/README.md) |
| 3 | Video | 🤖 generated | [`Modules/Video/docs/README.md`](../../Modules/Video/docs/README.md) |
| 4 | Address | 🤖 generated | [`Modules/Address/docs/README.md`](../../Modules/Address/docs/README.md) |
| 4 | Attributes | 🤖 generated | [`Modules/Attributes/docs/README.md`](../../Modules/Attributes/docs/README.md) |
| 4 | Cloudflare | 🤖 generated | [`Modules/Cloudflare/docs/README.md`](../../Modules/Cloudflare/docs/README.md) |
| 4 | Company | 🤖 generated | [`Modules/Company/docs/README.md`](../../Modules/Company/docs/README.md) |
| 4 | Components | 🤖 generated | [`Modules/Components/docs/README.md`](../../Modules/Components/docs/README.md) |
| 4 | ContentData | 🤖 generated | [`Modules/ContentData/docs/README.md`](../../Modules/ContentData/docs/README.md) |
| 4 | ContentDataVariant | 🤖 generated | [`Modules/ContentDataVariant/docs/README.md`](../../Modules/ContentDataVariant/docs/README.md) |
| 4 | ContentField | 🤖 generated | [`Modules/ContentField/docs/README.md`](../../Modules/ContentField/docs/README.md) |
| 4 | Country | 🤖 generated | [`Modules/Country/docs/README.md`](../../Modules/Country/docs/README.md) |
| 4 | Elements | 🤖 generated | [`Modules/Elements/docs/README.md`](../../Modules/Elements/docs/README.md) |
| 4 | HostingApi | 🤖 generated | [`Modules/HostingApi/docs/README.md`](../../Modules/HostingApi/docs/README.md) |
| 4 | OpenApi | 🤖 generated | [`Modules/OpenApi/docs/README.md`](../../Modules/OpenApi/docs/README.md) |
| 4 | Restore | 🤖 generated | [`Modules/Restore/docs/README.md`](../../Modules/Restore/docs/README.md) |

## Authoring

  1. Open the module's auto-generated `docs/README.md`.
  2. Replace the *Hand-edit this section* placeholders with
     real operator-side context (Domain, Data-model column
     lists, API endpoint tables, service-class contracts).
  3. Cross-link sibling modules and the relevant repo-level
     pages (e.g. `docs/mcp/README.md` for any module with
     MCP catalog entries).
  4. Update the **Status** column above from `🤖 generated`
     to `✅ documented`.
