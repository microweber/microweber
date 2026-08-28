# Admin Design-System — component breakdown (design each THOROUGHLY, one at a time)

Work order is foundational → composite (later components reuse earlier tokens).
For **each** component design *all* of: default · hover · focus-visible · active ·
disabled · light + dark · RTL-safe · exact tokens (from `rgb(var(--primary-N))`,
never raw `var()`), then **compare to the v2 demo**, **verify on the running local
admin**, and **send before/after screenshots**.

> **Evaluate DEEPLY — not just color tokens.** Check LAYOUT & STRUCTURE too:
> container vs card-row, spacing/gaps, top-heavy headers, alignment, empty bands,
> overall composition vs the demo — not only bg/border/focus colors. (Lesson from
> the tables: the real gap was the table-in-card vs demo card-row layout.)


## Form controls (atomic)
- [x] 1. **Buttons** — primary (dark `#182433` / green save / blue table), soft-tinted (ADD `#E1EDF8` / EDIT `#E2F9E6`), danger `#D63939`, ghost, outline, icon-button, sizes sm/md/lg. All states, light+dark.
- [x] 2. **Text input / textarea** — border `#DADFE5`, radius, height 45, focus ring, invalid, disabled, dark.
- [x] 3. **Select / dropdown trigger** — chevron, height, focus, open state, dark.
- [x] 4. **Checkbox** — unchecked/checked/indeterminate/focus/disabled, dark.
- [x] 5. **Radio** — unchecked/checked/focus/disabled, dark.
- [x] 6. **Toggle switch** — off/on (`#182433`)/focus/disabled, dark.
- [x] 7. **Search field** (topbar + list) — compact + expanded, focus, dark.

## Display / feedback
- [x] 8. **Badges / status pills** — success/warning/info/danger/neutral tints, dropdown-toggle variant, count badge `#929DAB`, dark. **CORRECTED: title case ("Published"), not uppercase** (verified vs admin-content-list.png; the SAVE-button `.fi-color-success` uppercase was leaking onto the pill — overrode in `.fi-ta-content`, headers/SAVE keep uppercase).
- [x] 9. **Avatar** — circle, initials, sizes, dark.
- [x] 10. **Icon tiles / metric chips** — pastel set, **52px / radius 12px** unified across Statistics header + Sales hero + 4 metric cards; green icon fixed (was amber). Root cause: `general-styles.css` body-scoped `.mw-quick-stat-card-icon` to `var(--space-lg)`≈26px, outranking the plain-class v3 rule — fixed with matching-specificity `!important` in v3. CSS-only (no Blade change). Live-verified.
- [x] 11. **Tooltip** — Tippy.js retinted from generic `#333` to MW ink `#182433` + matching arrow (was `border-color:initial`) + subtle shadow, white 13px. Live-verified on theme-switcher tooltip.
- [x] 12. **Tag / chip** — bumped from 10px (inherited generic `.fi-badge` table size; old rule targeted pre-v5 `.fi-tags-input-tag`) to 12px / 24px height / roomier padding, targeting v5 `.fi-fo-tags-input .fi-badge`. Live-verified.

## Navigation
- [x] 13. **Sidebar nav item** — unified blue active idiom (done; refine per state).
- [x] 14. **Tabs** — CORRECTED: active was blue `#0d6efd`; the demo's in-form authoring tabs use dark ink `#182433` text + `#182433` underline (verified vs admin-product-form.png + tabs.md §4 — "never blue"). Fixed the winning rule (v3:6717; 3 conflicting rules total). Inactive grey/500, active ink/600 + ink underline.
- [x] 15. **Breadcrumbs** — ancestor `<a>` crumbs stay accent-blue links; current crumb (a non-link `<span>`) muted to `#718096` so it no longer reads as clickable. Fixed general-styles.css painting every label blue. Live-verified.
- [x] 16. **Pagination** — verified: "Showing X–Y of Z" + per-page select styled (inherits the input treatment); structure sound. (Few-record DB shows no page buttons to restyle.)
- [x] 17. **Dropdown menu** (user menu, row actions) — verified already clean: white panel, 4px radius, subtle `0 8px 24px -4px rgba(0,0,0,.12)` shadow, dividers, ink 14px items, theme-switcher blue selected ring. No change needed.

## Containers
- [ ] 18. **Card / section panel** — bg, radius 4, shadow, header, dark.
- [ ] 19. **Modal / dialog** — overlay, shell, header, footer buttons, focus-trap, dark.
- [x] 20. **Table** (card-rows for content lists, table-in-card for header-tables; scoped :not(:has(.fi-ta-header-cell))) — header, row, cell, hover, selected, zebra?, dark.
- [ ] 21. **Empty state** — illustration/heading/subtitle/CTA (unify the 5 variants).

## Chrome (refine)
- [x] 22. **Top bar** — a11y rings + dark done; refine per component.
- [ ] 23. **Page header / action bar** — title, back-arrow, action cluster, results footer.

> Tokens live in `packages/microweber-filament-theme/resources/assets/css/microweber-theme-v3.scss`
> (authoritative `!important` layer). Rebuild + verify live per §memory
> [[local-admin-dev-setup]] / [[filament-primary-var-triplet]].
