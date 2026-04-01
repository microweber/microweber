# Implementation Plan — Filament 5 Admin Migration

> Roadmap for completing the Microweber CMS admin panel migration from MW v2 to Filament 5.
> Based on the product scope in `SCOPE.md` and a hands-on audit of every Filament resource.

---

## Current State Summary

| Resource | Completeness | Key Gaps |
|----------|-------------|----------|
| Dashboard | 100% | — |
| Login | 100% | Visual design doesn't match MW v2 branding |
| Pages | 80% | No hierarchy tree, no revisions, no template chooser |
| Posts | 60% | No excerpt, no scheduling, no author column |
| Products | 60% | No variants UI, no inventory, no SKU field |
| Orders | 85% | No timeline, no refunds, no tracking number |
| Settings hub | 70% | Individual sub-pages need completion |
| Categories | 100% | — |
| Tags | 100% | — |
| Comments | 100% | — |
| Users | 60% | Create/edit routes commented out |
| Roles | 100% | — |
| Permissions | 100% | — |
| Newsletter | 100% | — |
| Billing | 100% | — |
| Backup | 80% | Schedule/restore need verification |
| Media Library | 10% | Bare page wrapper, no functional UI |
| Menu mgmt | 10% | Settings page only, no drag-and-drop editor |

**Overall: ~70% functional. Remaining work in Phases 1–4 below.**

---

## Phase 1: Core Content & Commerce

The most-used admin pages. Every admin session touches these.

| # | Task | Size | Depends On |
|---|------|------|------------|
| 1.1 | feat: Pages — add parent-page tree selector in create/edit form | M | — |
| 1.2 | feat: Pages — add page template/layout chooser with preview thumbnails | M | — |
| 1.3 | feat: Pages — add bulk publish/unpublish action to list | S | — |
| 1.4 | feat: Posts — add excerpt field and featured-image showcase to form | S | — |
| 1.5 | feat: Posts — add publish/schedule date picker with draft vs published states | M | — |
| 1.6 | feat: Posts — add author display column and filter to list | S | — |
| 1.7 | feat: Posts — add bulk publish/unpublish action | S | — |
| 1.8 | feat: Products — add variant management UI (size/color/custom attributes) | L | — |
| 1.9 | feat: Products — add inventory tracking fields (stock qty, low-stock threshold) | M | — |
| 1.10 | feat: Products — add weight/dimensions fields for shipping calculation | S | — |
| 1.11 | feat: Products — add SKU/barcode field to main form | S | — |
| 1.12 | feat: Products — add stock status badge in list view | S | 1.9 |
| 1.13 | feat: Orders — add status-change timeline/activity log | M | — |
| 1.14 | feat: Orders — add shipping tracking number field | S | — |
| 1.15 | feat: Orders — add bulk status update action | S | — |
| 1.16 | feat: Orders — add refund processing UI (partial/full) | L | — |
| 1.17 | feat: Users — enable create/edit routes and verify form | S | — |
| 1.18 | feat: Users — add role assignment to create/edit form | S | 1.17 |
| 1.19 | style: Login page — match MW v2 visual design (branding, layout, colors) | M | — |

---

## Phase 2: Media & Navigation

Media browser and menus are used on every content-editing page.

| # | Task | Size | Depends On |
|---|------|------|------------|
| 2.1 | feat: Media Library — build grid/list view with thumbnails and search | L | — |
| 2.2 | feat: Media Library — add drag-and-drop upload with progress indicators | M | 2.1 |
| 2.3 | feat: Media Library — add bulk select, delete, and move-to-folder actions | M | 2.1 |
| 2.4 | feat: Media Library — add image metadata panel (dimensions, size, alt text) | S | 2.1 |
| 2.5 | feat: Menu management — build drag-and-drop menu editor with nested items | L | — |
| 2.6 | feat: Menu management — add item types (page link, custom URL, category) | M | 2.5 |
| 2.7 | feat: Menu management — add menu location assignment (header, footer, sidebar) | S | 2.5 |

---

## Phase 3: Settings Sub-Pages

Individual settings forms. Most follow a key-value pattern with `save_option()`.

| # | Task | Size | Depends On |
|---|------|------|------------|
| 3.1 | feat: General settings — site name, tagline, timezone, date format, logo upload | M | — |
| 3.2 | feat: Email settings — SMTP/Mailgun/SES config with test-send button | M | — |
| 3.3 | feat: Template settings — active template selector with live preview | M | — |
| 3.4 | feat: SEO settings — global meta tags, sitemap config, robots.txt editor | M | — |
| 3.5 | feat: Custom HTML tags — head/body script injection editor | S | — |
| 3.6 | feat: Template Customizer — color palette, font, layout CSS variable editor | L | 3.3 |
| 3.7 | feat: Auto-respond emails — order confirmation and shipping email templates | M | 3.2 |
| 3.8 | feat: Mail templates — CRUD for transactional email templates | M | 3.2 |
| 3.9 | feat: Privacy Policy — rich-text editor for policy page content | S | — |
| 3.10 | feat: Login & Register — social login toggles, registration options | S | — |
| 3.11 | feat: Advanced settings — cache clear, debug mode, maintenance toggle | M | — |
| 3.12 | feat: Cookie Notice — banner text, position, consent options | S | — |
| 3.13 | feat: File Manager — browse server filesystem with upload/delete | L | — |
| 3.14 | feat: Comments settings — moderation rules, anti-spam, notification prefs | S | — |
| 3.15 | test: Backup — verify schedule creation and restore workflow end-to-end | M | — |

---

## Phase 4: Cross-Cutting Design & Polish

Consistency and quality across all admin pages. Best done after content phases.

| # | Task | Size | Depends On |
|---|------|------|------------|
| 4.1 | style: Dark mode — full QA pass across all pages, fix contrast issues | M | Phase 1–3 |
| 4.2 | style: Mobile responsive — sidebar collapse, table stacking, touch targets | M | Phase 1–3 |
| 4.3 | style: Form layouts — consistent field spacing, labels, help text | M | Phase 1–3 |
| 4.4 | style: Table layouts — consistent column widths, row heights, status badges | M | Phase 1–3 |
| 4.5 | style: Modal dialogs — consistent sizing, padding, button placement | S | Phase 1–3 |
| 4.6 | style: Notifications/toasts — match MW v2 notification style | S | — |
| 4.7 | style: Empty states — consistent "no data" illustrations per resource | S | Phase 1–3 |
| 4.8 | style: Loading states — skeleton screens, spinners for slow operations | S | — |
| 4.9 | style: Breadcrumbs — consistent styling and hierarchy across all pages | S | Phase 1–3 |

---

## Complexity Legend

| Size | Effort | Description |
|------|--------|-------------|
| **S** | < 2 hours | Simple field additions, toggles, small UI tweaks |
| **M** | 2–6 hours | New form sections, filters, moderate UI components |
| **L** | 6–16 hours | Major features: variant UI, media library, menu editor |

---

## Resources Already Complete (No Migration Work Needed)

- Dashboard (welcome + quick stats + ECharts analytics)
- Categories + ShopCategories (full CRUD)
- Tags + TagGroups (full CRUD)
- Comments (full CRUD + moderation actions)
- Roles + Permissions (full CRUD + assignment UI)
- Newsletter (campaigns, subscribers, lists, templates, workflows, sender accounts)
- Billing (plans, groups, subscriptions, users, dashboard, settings)
- Login (custom auth with rate limiting via user_manager)
- Live Edit page

---

## Architecture Notes

1. **ContentResource inheritance** — Pages, Posts, and Products all extend a shared `ContentResource` base class. Changes to the base form/table schema affect all three. Always test all content types after modifying the base.

2. **Product variants** — DB tables (`product_variant_attributes`, `product_variants`) and models exist but have no Filament UI. Task 1.8 is the single largest task in the plan.

3. **Media Library** — Currently a bare `Widget` wrapper rendering a custom view. Building a full browser (2.1–2.4) can reuse the existing `MwMediaBrowser` Alpine.js component as a starting point.

4. **Menu editor** — Drag-and-drop nested list requires Alpine.js + Livewire interop. The existing `mw-tree-component.js` provides a tree UI pattern to build on.

5. **Settings pages** — Each extends `AdminSettingsPage` which provides a standard `save_option()`/`get_option()` pattern. Most settings forms are straightforward key-value pairs.

---

## References

- [SCOPE.md](SCOPE.md) — Product scope, target users, features, requirements
- [TODO.md](TODO.md) — Task list with completed and pending items

---

**Created**: 2026-04-01
**Last Updated**: 2026-04-01
