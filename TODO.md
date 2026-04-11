# MW v2 → Filament 5 Admin Design Migration Plan

> **Source design:** https://demo.microweber.org/
> **Clone-website skill:** https://agents.tools.ooyes.net/skills/clone-website.yml
> **UI test workflow:** https://agents.tools.ooyes.net/workflows/dev-cycle/02-test-the-project-ui.yml

---

## Done

- [x] 2026-04-11  Make a todo to fix all mobile pages issues

## Mobile Pages Issues

> Audited at 390×844 viewport (iPhone 14 equivalent) on 2026-04-11.
> Screenshots saved under `screenshots/audit/mobile/`.

### Tables — column overflow / truncation

- [x] 2026-04-11  Products list: "PUBLISHED" badge text clipped on right edge — badge should wrap or abbreviate on mobile
- [x] 2026-04-11  Customers list: table columns overflow viewport ("LAST NA...", "Custo..." truncated) — hide low-priority columns (ID, Phone) on mobile or switch to card layout
- [x] 2026-04-11  Users list: "PHON..." column header truncated — same fix as customers, hide Phone column on small screens
- [x] 2026-04-11  Orders edit: Payments table headers overflow ("PAYMENT PROVIDER", "STATUS" clipped) — responsive table or hide columns on mobile

### Content list cards

- [ ] Pages list: long page titles overflow card width — add `text-overflow: ellipsis` with `max-width` on title text
- [ ] Categories tree: long category names overflow container — truncate with ellipsis

### Page headers / edit pages

- [ ] Page edit: page title in header bar overflows left edge ("ategoryJsonTreeAdminPageStatic0_69c...") — truncate with ellipsis and max-width on the header title element
- [ ] Page edit: header action buttons (trash, Live edit, SAVE) are cramped on mobile — stack vertically or use icon-only buttons on small screens
- [ ] Products list: duplicate "New product" button appears (one in page header, one in floating toolbar) — hide one on mobile

### Dashboard

- [ ] Dashboard chart: X-axis date labels slightly truncated on far left at 390px — increase left grid margin or reduce font size on mobile
- [ ] Dashboard stat cards: "Last comments" and "Recent Orders" card labels may truncate on very narrow screens — use shorter labels on mobile or allow text wrapping

### Tabs

- [ ] Order edit: third tab label ("P...") truncated — ensure tab labels are abbreviated or scrollable-visible on mobile
- [ ] Product edit forms with many tabs may overflow — verify horizontal scroll indicator is visible
