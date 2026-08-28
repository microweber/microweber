# DESIGN.md — Microweber (legacy v2 demo) — deep design system

> A plain-text design-system reference an AI agent can read to generate UI that
> matches the **existing / old** Microweber product at `demo.microweber.org`
> (**v2.0.20**). Every value here was captured 2026-07-09 by driving a real
> browser and reading **live computed styles** (`getComputedStyle`), surface by
> surface, at desktop **1440px** and mobile **390px** — following the
> `clone-website` methodology (completeness over speed, real values not guesses,
> every state, appearance *and* behavior).
>
> **Status:** this documents the *old* design as a baseline to build the
> **Filament-5 rebuild** *from* — a snapshot to work against, not a spec to
> freeze. Where the old system is inconsistent (it is — see the two form
> families and the surface-dependent primary button), that's called out as a
> thing to *unify*, not copy.
>
> **Lineage:** Admin is **Tabler UI** (`--tblr-*`) skinned by Microweber admin
> (`--mw-*`); Live-Edit adds its own `mw-le-*` / `live-edit-*` layer. Body font
> is **Inter**; Tabler's default primary is blue `#206BC4`.
>
> **Surfaces covered:** admin shell & dashboard · lists/tables/grids · authoring
> forms · settings · design page · Live Edit (toolbar/canvas/RTE/selection) ·
> module-settings forms · Template Style Editor · Element Style Editor · modals ·
> commerce (orders/customers/contact) · marketplace item modal & module detail ·
> profile & auth screens · the public front-end template.
>
> **Backing research** (full measured specs + screenshots):
> [`admin-map.md`](docs/research/admin-map.md) ·
> [`modals.md`](docs/research/modals/modals.md) ·
> [`module-settings.md`](docs/research/modules/module-settings.md) ·
> [`settings.md`](docs/research/components/settings.md) ·
> [`tables-grids.md`](docs/research/components/tables-grids.md) ·
> [`forms.md`](docs/research/components/forms.md) ·
> [`orders-crm.md`](docs/research/components/orders-crm.md) ·
> [`marketplace-detail.md`](docs/research/components/marketplace-detail.md) ·
> [`frontend-template.md`](docs/research/components/frontend-template.md) ·
> [`profile-auth.md`](docs/research/components/profile-auth.md) ·
> [`sidebar.md`](docs/research/components/sidebar.md) ·
> [`tabs.md`](docs/research/components/tabs.md) ·
> [`live-edit-topbars.md`](docs/research/components/live-edit-topbars.md) ·
> [`inner-page-chrome.md`](docs/research/components/inner-page-chrome.md) ·
> [`AUDIT.md`](docs/research/AUDIT.md) ·
> screenshots in [`docs/design-references/`](docs/design-references/).

---

# Table of Contents

**Foundations**
1. [Visual Theme & Atmosphere](#1-visual-theme--atmosphere)
2. [Color Palette & Roles](#2-color-palette--roles)
3. [Typography Rules](#3-typography-rules)
4. [Layout Principles](#4-layout-principles)
5. [Depth & Elevation](#5-depth--elevation)
6. [Motion](#6-motion)

**Systems (cross-cutting — read these first)**
7. [The Button System & the surface-dependent primary rule](#7-the-button-system)
8. [The Two Form-Control Families](#8-the-two-form-control-families)
9. [Status System — pills, tab-bars, badges](#9-status-system)
10. [Core Components](#10-core-components)

**Surfaces**
11. [Admin Shell & Dashboard](#11-admin-shell--dashboard)
12. [Lists, Tables & Grids](#12-lists-tables--grids)
13. [Authoring Forms (create / edit)](#13-authoring-forms)
14. [Settings (card hub, auto-save)](#14-settings)
15. [Design / Appearance page](#15-design--appearance-page)
16. [Live Edit (toolbar, canvas, RTE, selection)](#16-live-edit)
17. [Module Settings Forms](#17-module-settings-forms)
18. [Template Style Editor](#18-template-style-editor)
19. [Element Style Editor](#19-element-style-editor)
20. [Modals (the shell families)](#20-modals)
21. [Commerce — Orders, Customers, Contact](#21-commerce)
22. [Marketplace Item Modal & Module Detail](#22-marketplace-item-modal--module-detail)
23. [Profile & Auth Screens](#23-profile--auth-screens)
24. [Public Template Token Model](#24-public-template-token-model)

**Guidance**
25. [Responsive Behavior](#25-responsive-behavior)
26. [Do's and Don'ts](#26-dos-and-donts)
27. [Agent Prompt Guide](#27-agent-prompt-guide)
28. [Appendix: Raw Measured Values](#28-appendix-raw-measured-values)

---

## 1. Visual Theme & Atmosphere

- **Mood:** calm, clean, "productivity SaaS." Light, airy, low-contrast chrome
  with content floating on white cards over a faint blue-grey canvas `#F6F8FB`.
- **Density:** comfortable, not compact. Generous card padding, whitespace
  between widgets, a 240px left sidebar, and a centered content column.
- **Signature trait:** **soft-tinted action buttons** — pale pastel fills with
  dark ink text (pale-blue *ADD*, pale-green *EDIT*). Color is accent, not fill.
- **Two-layer product:** a **Tabler-based admin** (pages, tables, forms,
  settings) *and* a **Live-Edit overlay** (in-place website editing with its own
  toolbar, popovers, and two style editors). The two layers have overlapping but
  **not identical** design languages — the single most important thing to know
  about this codebase (see §7 and §8).
- **Personality:** friendly (rounded 4–5px corners, pastel chips, circular
  avatars) yet restrained (near-black ink, thin single-tier shadows, no
  gradients in chrome).

---

## 2. Color Palette & Roles

### Neutrals / surfaces
| Token | Hex | RGB | Role |
|---|---|---|---|
| Ink (text primary) | `#182433` | 24,36,51 | Headings, body, icons |
| Ink (live-edit chrome) | `#2B2B2B` | 43,43,43 | Toolbar & drawer text |
| Text muted | `#667382` | 102,115,130 | Help text, table `<th>`, sub-labels |
| Canvas / page bg | `#F6F8FB` | 246,248,251 | App background behind cards |
| Surface | `#FFFFFF` | 255,255,255 | Cards, drawers, admin inputs, top bar |
| Surface muted | `#F1F5F9` | 241,245,249 | Hovered rows, subtle fills |
| Fill (live-edit inputs) | `#F0F0F0` | 240,240,240 | Live-Edit filled inputs, VIEW btn, `--tblr-body-bg2` |
| Rail / admin gray | `#F5F5F5` | 245,245,245 | Link-picker rail, `--mw-admin-gray-color` |
| Input border (admin) | `#DADFE5` | 218,223,229 | Admin form-control borders |
| Hairline | `rgba(4,32,69,.14)` | — | Table cell borders, checkbox/radio borders |
| Currency prefix bg | `#FCFDFE` | 252,253,254 | `input-group-text` price prefix |

### Accents & semantics
| Token | Hex | Role |
|---|---|---|
| Primary / link | `#206BC4` | Links, Tabler primary, active status-tab wash, module reload |
| Save / commit (Live Edit + lists) | `#182433` | Live-Edit SAVE; list "New" on card-row pages; toggle ON |
| Save (authoring forms) | `#2FB344` | **Green** `btn-ghost-success` — form Save/Create |
| Success tint | `#EAF7EC` / text `#2FB344` | "Published" status pill |
| EDIT pastel (success-soft) | `#E2F9E6` | EDIT button; "saved/published" affordance |
| ADD pastel (info-soft) | `#E1EDF8` | ADD button |
| Element-type tag / selection | `#0078FF` | Live-Edit element tag; selection outline |
| Dropzone dashed | `#4592FF` | Media upload dropzone border |
| Premium / warning tint | `#FEF0E6` / text `#F76707` | Marketplace "Premium" badge |
| Count badge grey | `#929DAB` | Solid count badge inside status tabs |

### Second-pass semantics (commerce, marketplace, profile, auth)
| Token | Hex | Role |
|---|---|---|
| Danger / destructive | `#D63939` | Delete Account, Reinstall, `.btn-danger` |
| Info blue (light) | `#4299E5` | Alert-info text, Contact-form "No data" strip |
| Lime tint | `#F1F8E8` / text `#74B816` | Marketplace "Free/Installed" badge (≠ green pill) |
| Azure panel | `#ECF5FC` (`bg-azure-lt`) | Profile form panels sit on azure, not white |
| Login primary blue | `#206BC4` | Admin login submit (full-width) — *not* navy |

> **Front-end template colors are a separate system** (Arial, not Inter) — see §24.
> Key ones: section-bg `#8691A9`, primary `#000`, link green `#6AB340`, footer/accent
> orange `#F4A261`, front-auth border `#C3C3C3`.

### Pastel icon-tile palette (dashboard chips & settings-hub icons)
| Hex | Meaning |
|---|---|
| `#E1EDF9` | Info / analytics / settings-hub icon (blue) |
| `#E1F9E6` | Success / sales (green) |
| `#F9E1F3` | Comments / social (pink) |
| `#F9F3E1` | Orders / warning (yellow) |
| `#F6F8FB` | Neutral / default |

> Foreground on all tints stays **`#182433` ink** — tints are light enough for
> AA contrast without a colored foreground. (No exceptions: even the settings-hub
> card glyph is ink `#182433` on `#E1EDF9`, live-confirmed — an earlier "blue
> icon" note was wrong. Blue `#206BC4` on that card is only the *title/anchor*.)

---

## 3. Typography Rules

- **Family:** `Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif`
- **Color:** `#182433` for nearly all text; `#206BC4` for links; `#667382` for
  muted/help; `#2B2B2B` for live-edit chrome.

| Role | Size | Weight | Notes |
|---|---|---|---|
| Page hero title (dashboard) | 36px | 700 | line-height 32px |
| Page title (list/settings/form) | 20–24px | 600–700 | 20px lists · 24px settings/forms |
| Drawer / editor title | 20px | 600 | "Template Style Editor" |
| Section heading (in-form/settings) | 18px | 600 | `settings-title-inside` |
| Modal header title (Tabler dialog) | 18px | 700 | media/link picker |
| Stat number (KPI) | 30px | 600 | Big metric figures |
| Big title input (authoring) | 26px | 400 | Borderless headline field |
| Body / base | 14px | 400 | line-height 20px |
| Button label | 14px | 400–700 | 700 on green Save |
| Link | 16px | 500 | No underline at rest |
| Card title (list rows) | 16px | 600 | Title links, no underline |
| **Admin** field label (`.form-label`) | 16px | 500 | **Title-Case**, not uppercase |
| **Live-Edit** field label (`.live-edit-label`) | 9.75px | 500 | **UPPERCASE**, letter-spacing .75px |
| Table `<th>` | 10px | 600 | UPPERCASE, `#667382`, ls .4px |
| Help / meta / status pill | 12px | 400–600 | `#667382` help; 500 pills |

> **Two label conventions** mirror the two form families (§8): admin forms use
> **16px Title-Case** labels; Live-Edit forms use **9.75px UPPERCASE** labels.

---

## 4. Layout Principles

- **Admin shell:** fixed **240px left sidebar** (off-canvas under `md`) + a
  top action row + a centered content column on the `#F6F8FB` canvas. Content
  max-width is effectively the column (~1185px at 1440).
- **List pages:** title + top-right actions, then either a **stack of white
  card-rows** (Pages/Posts/Products) or a **Tabler `<table>`** (Users) — see §12.
- **Authoring forms:** **two columns** — `.col-md-8` main (title, RTE, media,
  price) + `.col-md-4` sidebar (visibility, categories, tags) — inside `card-sm`
  panels, with a flat in-form tab bar (§13).
- **Settings:** a **card hub** of ~19 group cards; each opens a full page at
  `?group=<key>`; sections are two-column (heading left, fields right) (§14).
- **Live Edit:** fixed 60px toolbar → full-bleed canvas iframe → right drawers
  / anchored popovers over the canvas (§16–19).
- **Spacing scale (~4px base):** `4 · 8 · 12 · 16 · 20 · 24 · 30 · 40px`. Card
  inner padding 16–24px; gap between card-rows 16px; section padding 30px.
- **Radius scale:** `0` (Live-Edit inputs, native modal, filled fields) · `2px`
  (small pills/toggles legacy) · `4px` (admin default — cards, buttons, inputs) ·
  `5px` (icon tiles, editor-area bottom) · `8px` (Tabler dialog) · `10px`
  (settings-hub icon tile) · `160px`/`32px` (pill buttons, toggle track).
- **Whitespace:** separate white cards with **shadow + gap, not borders**.

---

## 5. Depth & Elevation

| Level | Use | Shadow |
|---|---|---|
| 0 | Canvas, admin top bar, module-grid cards | none |
| 1 | Cards / widgets / list-rows / panels | `0 1px 4px rgba(0,0,0,.16)` |
| Toolbar (LE) | Live-Edit bar | `0 2px 4px rgba(24,36,51,.075)`, z-index 2 |
| Handle card (LE) | module hover handle | `0 4px 16px rgba(17,17,26,.1), 0 8px 32px rgba(17,17,26,.05)` |
| Module-settings shell | popover/modal | `0 3px 8px rgba(0,0,0,.24)` (no backdrop dim) |
| Tabler dialog | media/link modal | `0 3px 9px rgba(0,0,0,.15)`, overlay `#1D273B @24%` |
| Insert picker | LE-native modal | layered `0 54px 55px rgba(0,0,0,.25)` +4, overlay `rgba(0,0,0,.2)` |
| Button (tinted/dark) | ADD/EDIT/SAVE/New | `0 1px 0 rgba(24,36,51,.04)` + inset `0 -1px 0 rgba(24,36,51,.2)` |
| Button (green Save) | authoring Save | inset `0 3px 5px rgba(0,0,0,.125)` |

Elevation is **subtle and single-tier** for content. No glows, no gradients.
The only heavy shadow is the LE-native insert modal.

---

## 6. Motion

- **Drawers** slide in from the **right** over the canvas (Template / Element
  Style Editor); `X` closes, `← Back` returns from a sub-panel.
- **Module handles**: hovering a canvas module fades in a white handle card;
  clicking docks a compact dark quick-action toolbar. Keep ~120–300ms, gentle.
- **Module-grid cards** lift on hover (`.3s`). **Buttons** rely on the static
  inset bottom edge rather than large hover lifts.
- **Device toggle** reflows the canvas to a ~390px phone frame *without* reload.
- Motion is quiet and functional; this is not an animation-forward UI.

---

## 7. The Button System

> **The single most important rule: the primary button color is
> SURFACE-DEPENDENT, not global.** The old system uses seven different "primary"
> treatments depending on where you are. In the Filament-5 rebuild this should
> be **unified** — but to *match* the old look, apply the right one per surface.

| Context | Treatment | Tokens |
|---|---|---|
| Live-Edit **SAVE** | dark ink | bg `#182433`, white, radius 4px, pad 7×16, border 1px `#1F2E41`, inset bottom edge |
| List **"New"** (card-row pages) | dark ink | bg `#182433`, white, radius 4px, h47, inset bottom edge |
| Authoring-form **Save/Create** | **green** | `btn-ghost-success` bg `#2FB344`, white, radius 4px, h49, 14px/**700**, inset `0 3px 5px rgba(0,0,0,.125)` |
| Users **table** "New" | **blue** | Tabler `.btn-primary` `#206BC4`, white, radius 2px, h29 (sm) |
| Modal confirm (media/link) | **blue pill** | `.btn-pill.btn-primary` `#206BC4`, white, radius 160px, h45 |
| **Admin login** submit | **blue, full-width** | `.btn-primary` `#206BC4`, white, radius 4px, h45, `w-100` |
| **Profile** Save (per-panel) | dark ink | `.btn-dark` `#182433`, white, radius 4px, h45 |
| Settings | **none** | settings **auto-save**; no Save button exists |

**Destructive & state-dependent (second pass):**
- **Danger** (`.btn-danger`): bg `#D63939`, white — Delete Account (radius 4, h45).
- **Marketplace install is state-dependent**: **green** `.btn-success` `#2FB344`
  "Install" (not installed) → **red** `.btn-danger` `#D63939` "Reinstall"
  (installed); both radius **2px**, h29 (not pills). Template modals add a navy
  `.btn-dark` "Preview".
- **Front-end** CTAs are a different system entirely — **black pills** `#000`,
  radius 23px, h56 (see §24).

> Tally: the old system ships **~7 distinct "primary/commit" treatments** (LE
> dark, list dark, form green, table blue, modal blue-pill, login blue-full-width,
> profile navy) + danger red + state-dependent install. **Collapse to one primary
> + one danger token in the rebuild.**

**Soft-tinted secondary CTAs (the identity buttons):**
| Button | Fill | Text | Shape |
|---|---|---|---|
| ADD | `#E1EDF8` | `#182433` | radius 4px, h45/49, 1px `#F0F0F0`, leading `+` |
| EDIT | `#E2F9E6` | `#182433` | radius 4px, h45/49, leading eye |
| VIEW (LE) | `#F0F0F0` | `#182433` | radius 4px, 1px `#F0F0F0` |

**Other measured variants:**
- **Outline-primary** (e.g. "Upload logo", module Reload): transparent, `#206BC4`
  text+border, radius 4px, h45.
- **Bold dark-outline** (Design page CTAs — New Page / Preview / Customize):
  transparent, ink `#182433`, **border 2px solid `#000`**, radius 4px, 16px/700, h49.
- **Export**: outline `#182433`, radius 2px, h29.
- **Modal text buttons** (Tabler dialog footer): "Cancel"/"OK" plain text,
  `#182433`, 14px/600, transparent, no border.
- **Link button**: no bg, text `#206BC4`, 500.

---

## 8. The Two Form-Control Families

> Admin forms and Live-Edit module-settings forms use **different control
> vocabularies**. Never mix them within a surface. (Unifying these is a prime
> Filament-5 goal.)

### Family 1 — Admin (Settings, authoring forms, table filters)
Outlined-on-white. **This is the canonical admin form vocabulary.**
```
text  .form-control  : bg #FFFFFF · border 1px solid #DADFE5 · radius 4px · h45 · 14px · #182433 · pad ~10px 14px
textarea             : same border/bg/radius · auto-grow · min-h 36px
select .form-select  : bg #FFFFFF · border #DADFE5 · radius 4px · h~39 · 16px · chevron (appearance none)
label  .form-label   : 16px / 500 · Title-Case (NO transform) · #182433
help   .text-muted   : 12px / 400 · #667382 (may carry inline #206BC4 links)
toggle .form-switch  : 38×20 pill · radius 32px · ON bg+border #182433 · OFF #FFF + border rgba(4,32,69,.14)
radio  .form-check-input : 21×21 circle · border 1px rgba(4,32,69,.14) · white
checkbox (list/tree) : 21×21 · radius 4px · border 1px rgba(4,32,69,.14)   (bare native 13×13 in some spots)
```

### Family 2 — Live-Edit (module settings, style editors, link picker)
Filled-grey, borderless, square, tiny UPPERCASE labels.
```
text   .form-control-live-edit-input : bg #F0F0F0 · border none · radius 0 · h45 · 14px · #182433 · pad-left 10
select .form-select…live-edit        : bg #F0F0F0 · border none · radius 4px · h45 · 14px
label  .live-edit-label              : 9.75px / 500 · UPPERCASE · letter-spacing .75px · #182433
radio-card/segmented .form-selectgroup-item : item h36 · 14px · active = dark fill #182433 + white
radio  .form-check-input             : 21px · radius 50% · border 1px rgba(4,32,69,.14)
checkbox                             : ~20.8px
```

### Composite / rich controls (authoring forms — measured in §13)
Big title input (26px borderless) · Rich-text editor (`.mw-bar` + `.mw-editor-area`)
· Price `input-group` (currency **code** prefix, dynamic) · Category tree
(radio parent / checkbox children) · Tag pills (`btn-group` + add) · Media
dropzone (→ Media modal) · Skin card selector · Thumbnail-card grid.

### Reality check: it's actually **five** input styles, not two
The second-pass surfaces exposed more drift. Catalogue (all measured):
| # | Style | Where | Text-input tokens |
|---|---|---|---|
| 1 | Admin outlined | Settings, authoring, login, table filters | white · 1px `#DADFE5` · radius 4 · h45 |
| 2 | Live-Edit filled | Module settings, style editors, link picker | `#F0F0F0` · no border · radius 0 · h45 |
| 3 | **Profile mixed** | `/user/profile` | text inputs = filled `#F0F0F0`; selects = admin white `#DADFE5` **on the same page** |
| 4 | Front-end square | Contact form (public template) | white · 1px `#000` · radius 0 · h56 |
| 5 | Front-end users-module | Login/Register modals (public) | white · 1px `#C3C3C3` · radius 1 · h38 · `#505050` |

> **This is the headline debt.** The rebuild should collapse 1–3 into **one**
> admin field style, and 4–5 into **one** front-end field style.

---

## 9. Status System

### Status pill (badge, often a `dropdown-toggle`)
Tint family `bg-*-lt` (pale fill + saturated text/caret), `12px/500`, radius 4px,
pad 3×6, h28:
| Status | Fill | Text |
|---|---|---|
| Published / success | `#EAF7EC` | `#2FB344` |
| Premium / warning | `#FEF0E6` | `#F76707` |
| Info / azure | `~rgba(32,107,196,.04)` | `#206BC4` |

> Only "Published" was live on the demo; other statuses reuse the same `bg-*-lt`
> family. Keep foreground = the saturated hue, fill = its 4–8% tint.

### Status-tab bar (Comments; reused by Orders & Contact form)
Joined `.btn-group` (segmented), full-width, h45, 14px/400, pad 10.4×20:
- **Active tab:** bg `rgba(32,107,196,.04)` + **border 1px `#206BC4`** + ink `#182433`.
- **Inactive tab:** bg `#FFFFFF` + border 1px `#DADFE5`.
- Indicator is a **blue border + wash**, *not* an underline.
- **Count badge** inside a tab: solid grey `#929DAB`, white text, 12px/600,
  radius 4px, pad 3×6.
- A second `btn-group` (Newest/Oldest sort) reuses the identical active/inactive style.

### Tab taxonomy — **seven** idioms in the wild (do not conflate)
Deep spec + adversarial verification: [`tabs.md`](docs/research/components/tabs.md).
Three mechanism families: **A** text+underline, **B** segmented fill, **C** text/no-indicator.
| Idiom | Where | Active indicator |
|---|---|---|
| Underline (A) | Dashboard KPI (`Daily/Weekly/Monthly`) | 700 + **dark-navy `#182433` underline** (~2px; **not blue** — pixel-confirmed) |
| Blue segmented (B) `btn-group` | Comments status tabs | `#206BC4` border + pale wash |
| **Navy segmented** (B) `nav-pills btn-group` | Module detail dashboards (Newsletter) | **navy fill `#182433`** + white; inactive = page-tint `#F6F8FB` |
| Navy toggle (B) | List/Details view · Marketplace Templates/Modules | navy fill `#182433` + white (Marketplace = two *separate* rounded btns, not joined) |
| Flat text + underline (A) | Authoring forms | 600 + **`#182433` underline** + leading icon (earlier "no indicator" was wrong) |
| **Text links with `(count)`** (C) | Orders / Abandoned | **no visual delta** — current tab is URL-only, inline `(N)` count |
| Popover tabs (A) | Module-settings (Content/Design), Link picker | **`#182433` underline** (weight delta secondary) |

> **Active-underline rule (A-family) — RESOLVED (live, pixel-confirmed):** the
> active tab carries a **~2px `#182433` `::after` underline**, full text-width,
> **active-only** (pixel scan: solid `#182433` bar under active "Daily", absent
> under inactive "Weekly"). It is **dark-navy `#182433`, never blue**. Mechanism:
> a base `.mw-adm-liveedit-tabs::after` bar (confirmed by the sibling rule
> `.mw-adm-liveedit-tabs.text-danger::after { background-color: danger }`, which
> recolors the same bar red for danger tabs). The class-level active rule is just
> `font-weight: 700` (vs 600) — so **active = 700 weight + the `::after`
> underline**. (An earlier `getComputedStyle` read that reported "no ::after" had
> matched a hidden duplicate span — the adversarial screenshot check was right.)
> Navy-fill segments (B) invert ink to white on `#182433`; Comments is the only
> blue-border idiom.

### Empty states — **five** inconsistent treatments (pick ONE in rebuild)
| Copy | Surface | Treatment |
|---|---|---|
| Illustration + "No comments found" + subtitle | Comments | centered **illustration**, no CTA |
| "0 results found" | Orders | bare muted `#667382`, no art |
| "You don't have any orders yet" + sub | Abandoned | centered, friendly, no art |
| "No content found" + "…for this filters" | Customers | centered, no art |
| "No data found" (blue `#4299E5`, left-stripe strip) | Contact form | info-strip, no art |

> List CTA empty-states elsewhere use `.mw-table-empty-cta`. The five above have
> **no shared component** — a canonical empty-state is a clear rebuild win.

---

## 10. Core Components

### Cards / widgets / panels
```
bg #FFFFFF · radius 4px · border none · shadow 0 1px 4px rgba(0,0,0,.16) · body pad 16–24px
```
Exception: module-grid cards have **no shadow, no border** (rely on hover lift).

### Icon tile (metric chip / settings-hub icon)
```
dashboard metric tile : 60×60 · radius 5px · pastel bg · ink icon
settings-hub icon tile: 48×48 · radius 10px · bg #E1EDF9 · #182433 ink glyph
```

### Avatar (Users table)
`40×40` circle, `bg-light` with initials.

### Right-drawer (style editors)
`~263px`, docked right, white, slides over canvas; header 20px/600 title + `X`;
sub-panel = `← Back` + title + helper paragraph + control rows + footer action.

### Empty state
Centered illustration + heading 24px/600 `#182433` + subtitle 14px/400. (Comments'
"No comments found / Your queue is clear." has **no** CTA on that surface; list
empty-states elsewhere use `.mw-table-empty-cta`.)

### Page selector (Live-Edit)
Centered pill dropdown showing the current page ("Home") + chevron → searchable
content picker.

---

## 11. Admin Shell & Dashboard

**Ref:** [`mw-landing.png`](docs/design-references/mw-landing.png),
[`admin-mobile.png`](docs/design-references/admin-mobile.png),
[`admin-dashboard`](docs/design-references/).

- **Shell:** 240px left sidebar + a **transparent** top row (the ADD/EDIT bar
  sits directly on the `#F6F8FB` canvas — *not* a white bar; no border/shadow,
  ~73px tall).
- **Top bar:** left = hamburger + **ADD** (pastel-blue); right = **EDIT**
  (pastel-green → Live Edit at `/v2/admin/live-edit?editmode:y`).

### Left sidebar (deep spec: [`sidebar.md`](docs/research/components/sidebar.md))
`<aside class="navbar navbar-vertical navbar-expand-xl admin-dashboard-left-nav
ui-resizable">` — a fixed **240px white**, `position:sticky`, **drag-resizable**
flex column in three zones: **logo header (~90px)** · **scrollable nav** ·
**pinned footer** — off the `#F6F8FB` canvas by a 1px `#DADFE5` edge. Ink-on-white;
**no colored fills, no hover background.**
- **Logo:** MW SVG mark + wordmark in **`#4592FF`** (azure-blue — *not* `#206BC4`).
- **Nav (primary):** `.nav-link.fs-3` = **16px/400**, ink `#182433`, row **h54**,
  left-pad 15px, SVG Material-Symbols icon 24px. Items: Dashboard · Website▸ ·
  Shop▸ · Marketplace · Modules · Settings · Users. **(secondary, spacer-pushed):**
  Get Help (external) · Log out.
- **Parents** are Bootstrap `.dropdown-toggle`; **children** `.dropdown-item`
  (14px, indented ~2.75rem) with nested **Add/List** actions (12px/500) revealed on
  hover. Website→`Design/Pages/Categories/Posts`; Shop→`Products/Orders/
  Categories/Customers`.
- **Row rhythm:** top-level **54px**, sub-item **~36px**.
- **Active idioms differ by depth (unify target):** active **top-level** = a
  `::after` **`#EFEFEF` accent bar** under the label; active **sub-item** =
  **`#EFEFEF` highlight pill** (`--tblr-navbar-active-bg`). **Text stays ink even
  when active** (MW overrides Tabler's `#206BC4`). Hover = ink only, no fill.
- **Footer:** language `<select>` (white + `#DADFE5`) · theme toggle
  (`mw_admin_toggle_dark_theme()`) · user avatar (`#EDEFF3` "PA") → `/user/profile`.
- **Dark theme** (`html[data-bs-theme=dark]`): sidebar **`#182433`**, text
  `#BBC3CD`, links `#FFFFFF`, page `#151F2C`, logo unchanged. ⚠️ **the pastel
  ADD/EDIT buttons don't darken** — a dark-mode gap to fix.
- **Responsive:** `navbar-expand-xl` → **off-canvas below 1200px** (wider than the
  `md` used elsewhere), hamburger reveals it.
- **Hero:** `36px/700` greeting + **`24px/500`** subtitle (live-measured — *not*
  14px) + right-aligned range label.
- **Statistics widget** (full-width card): pastel icon tile + title + `30px/600`
  KPI + `Daily/Weekly/Monthly` tabs (active = bold + dark-navy `#182433`
  underline, *not* blue) + chart + footer metrics + blue "Show More".
- **Metric cards** (2-col grid): pastel tile (hue-coded) + label + `30px/600` +
  blue "View".
- **Footer:** "Create a website with Microweber · Version: 2.0.20".
- **Mobile:** single column; buttons wrap; hero wraps to 3 lines (size held);
  KPI tabs stack vertically; metric cards full-width.

### Inner-page chrome (shared scaffold)
Deep spec: [`inner-page-chrome.md`](docs/research/components/inner-page-chrome.md).
Everything right of the sidebar reuses **one chrome scaffold** over the
**transparent** top row (painted on `#F6F8FB` — *no* fixed white bar; `main`
transparent, pad ~10×16). Five slots:
- **A top-left:** back-arrow `←` **or** ADD — never both. Plain ~24px ink glyph
  (no pill). Back-arrow on **settings + form** pages; **lists omit it** (slot
  holds pastel ADD `#E1EDF8`; lists return via sidebar).
- **B top-right:** green **EDIT** pill (`#E2F9E6` → Live Edit) on list + settings;
  authoring-form swaps in a static `[Add <Type>] [Live Edit] [SAVE]` bar.
- **C title (H1):** left-aligned, ink, Inter, **no breadcrumb**.
- **D search/filter:** list-only, surface-dependent (see table).
- **E footer:** global centered tagline "Create a website with Microweber ·
  Version 2.0.20" (`#667382`); card-row lists prepend "N results found".

**Per-type variance** (the primary-button color is the headline — §7's
surface-dependence in one view):
| Dimension | List (card-row) | List (Users table) | Settings-group | Authoring-form |
|---|---|---|---|---|
| Slot A | ADD `#E1EDF8` | ADD `#E1EDF8` | back-arrow ← | back-arrow ← (inline) |
| Title | `20px/600` | `20px/600` | `24px/700` | `24px/700` |
| Primary | **navy `#182433`** New | **blue `#206BC4`** Add | **none** (auto-save) | **green `#2FB344`** Save |
| Search | flat-grey `#F0F0F0` r0 h45 | white `#DADFE5` r4 (in card) | none | none |
| Footer | "N results" + tagline | tagline | tagline | tagline |

> **List-page chrome live-confirmed** (Pages, 2026-07-10): New `#182433` radius
> **4px** h47 · title **20px/600** · keyword search `#F0F0F0` radius 0 h45 ·
> List/Details active navy `#182433` radius 2 · "N results found" `#667382` 14px ·
> ADD `#E1EDF8` / EDIT `#E2F9E6` radius 4 h49. Settings/form chrome verified from
> screenshots; a distinct **Products** list capture is still owed (tokens inferred
> from Pages).

---

## 12. Lists, Tables & Grids

**Ref:** [`admin-content-list.png`](docs/design-references/admin-content-list.png),
[`admin-users-table.png`](docs/design-references/admin-users-table.png),
[`admin-modules-grid.png`](docs/design-references/admin-modules-grid.png),
[`admin-marketplace-grid.png`](docs/design-references/admin-marketplace-grid.png),
[`admin-comments-tabs.png`](docs/design-references/admin-comments-tabs.png).
Full spec: [`tables-grids.md`](docs/research/components/tables-grids.md).

> **Two list renderers** that share only page chrome:

### A) Card-row list (Pages, Posts, Products)
No `<table>`. Each row is a white card, `radius 4px`, `shadow 0 1px 4px
rgba(0,0,0,.16)`, `16px` gap, **no header row, no dividers, no zebra**.
```
row body pad 16px 24px (Pages, h110) / 24px 30px (Products, h140), flex, wraps
col1 drag + checkbox (≤40px): checkbox 21×21 radius 4 border 1px rgba(4,32,69,.14)
col2 thumbnail 80×48 (Pages) / 104×83 (Products), radius 0, SVG placeholder
col3 title link 16px/600 #182433 (no underline) + "Updated: <date>" 14px muted
col4 quick-action icons  ·  col5 STATUS PILL  ·  col6 trailing ⋮ menu (Edit/View/Delete-danger)
price (Products): "$ 0.00" 12px/600 #182433 — currency from settings, never hardcoded
pagination: ?paginate=10 param; pager only when >1 page
```
Primary **"New"** here = **dark navy `#182433`** (white, radius 4, h47, inset edge).

### B) Tabler table (Users)
```
th : bg #FFF · 10px/600 · UPPERCASE · #667382 · ls .4px · pad 8px 12px 8px 24px · border-bottom 1px rgba(4,32,69,.14)
td : pad 12px 12px 12px 24px · 14px · #182433 · valign middle · same border-bottom
row height 73px · NO zebra · avatar 40×40 circle
```
Primary **"New"** here = **Tabler blue `#206BC4`** (radius 2, h29) + outline-blue
"Show Filters" + outline-green "Export all". List page chrome (both renderers):
title 20px/600, ADD/EDIT pastel pills (h49), keyword search = **flat grey**
`#F0F0F0` radius-0 h45 (Live-Edit-family field, not the white admin input),
List/Details segmented toggle, "N results found" 14px `#667382`.

### Card grids
- **Modules** (`/v2/admin/modules`): `row-cards` on `bg-azure-lt`; 4/2/1-up;
  card `#FFF` radius 4 **no shadow/border**, min-h 170, centered SVG icon 40×40 +
  title 16px/500 `#667382`; hover lift `.3s`; Reload = outline-blue.
- **Marketplace**: a **navy segmented toggle** for Templates/Modules (active =
  navy `#182433` fill + white, inactive = white outline); **Licenses is a
  separate text link** — not text-links/active-bold;
  cover card `~279×260` radius 4 shadow, cover `279×180` radius `3px 3px 0 0`,
  body pad 16, title 14px/600; "Premium" badge `bg-orange-lt`; whole card → item
  modal (install inside modal).

---

## 13. Authoring Forms

**Ref:** [`admin-product-form.png`](docs/design-references/admin-product-form.png),
[`admin-page-form.png`](docs/design-references/admin-page-form.png).
Full spec: [`forms.md`](docs/research/components/forms.md).

Product / Page / Post / Category all render one module (`module-content-edit`).
```
Top action bar .nav (STATIC, not sticky), right-aligned: [Add <type>] [Live Edit] [SAVE]
  Return = top-LEFT back-arrow (→ list).  Page title 24px/700.
In-form TAB bar (flat text links + leading icon; active 600 + ~2px #182433 underline / inactive 500; all #182433):
  Product : Product Details · Custom Fields · SEO · Advanced
  Page/Post: "<Type> Details" · Custom Fields · SEO · Advanced
BODY = .row in .card.card-sm > .card-body:
  LEFT  .col-md-8 (~737px): title · description(RTE) · media · price/inventory/shipping (or layout)
  RIGHT .col-md-4 (~368px): Visibility · Categories · Tags (+ Parent page / Add-to-Nav on pages)
Section panel .card.card-sm: #FFF, radius 4, shadow 0 1px 4px rgba(0,0,0,.16), body pad 16.
Field group: .form-label 16px/500 Title-Case + .text-muted 12px/400 #667382 + control.
```
**Save is GREEN** (`btn-ghost-success` `#2FB344`, white, radius 4, h49, 14px/700).
No Cancel button (back-arrow), no "Save & continue".

**New composite controls (measured here):**
- **Big title input**: borderless, white, 26px, h59 + slug/permalink chip (copy-link).
- **Rich-text editor**: toolbar `.mw-bar` (transparent, h43, above) + `.mw-editor-area`
  (white, radius `0 0 5px 5px`, min-h 250, pad 5, 14px). Buttons `.mw-ui-btn`
  transparent ~32px: Undo · Redo · Insert Image · Bold · Format · Paragraph ·
  Size · Link · Unlink · Remove Format · Edit source.
- **Price + currency prefix** `.input-group`: left `input-group-text` = currency
  **CODE** (e.g. `USD`, dynamic from shop settings — **never hardcoded `$`**),
  bg `#FCFDFE`, ink `#667382`, border `#DADFE5`; right price input radius `0 4px 4px 0`, h45.
- **Category tree** `.mw-tree-nav`: rows 12px `#212121`, **radio** for parent /
  **checkbox** for children; search `form-control-sm` white `#DADFE5` h45.
- **Tag pills**: `btn-group` of `btn-sm` (white, border `#DADFE5`, radius 2, h29)
  each with a trailing **trash/delete (🗑) button** (both tag *and* category
  pills); + input + "Add tag" (count badge); comma/Enter to add.
- **Media dropzone**: "Add media" + drag-drop zone + sortable thumbnail list →
  opens the shared Media picker modal (§20).

**Mobile:** two-col → single column (~294px each), **no horizontal overflow**
(adapts better than lists/settings); action bar static (Save scrolls off).

---

## 14. Settings

**Ref:** [`admin-settings-hub.png`](docs/design-references/admin-settings-hub.png),
[`admin-settings-general.png`](docs/design-references/admin-settings-general.png).
Full spec: [`settings.md`](docs/research/components/settings.md).

> **Card hub + auto-save. There is NO Save button anywhere in Settings.**

- **Hub** (`/v2/admin/settings`): two white panels — "Website Settings" (10) +
  "Shop Settings" (9) = ~19 groups. Card grid 1/2/3-up. Card = flex link
  (transparent, pad 40×20): **icon tile 48px `#E1EDF9` radius 10 `#182433`
  glyph** + title 16px/700 `#182433` + description `small.text-muted` **13.7px/500
  `#667382` (muted gray — *not* blue)**.
  Click → full page `?group=<key>`; return via top-left ← back-arrow.
- **Group page:** top bar ← back-arrow + green EDIT pill (→ Live Edit); title
  24px/700. Group panel `.card` (radius 4, shadow, body pad 16×24).
- **Section** = `.row` (pad 30), two columns: left `col-xl-3` heading
  `18px/600` + `.text-muted 12px/400 #667382`; right = stacked `.form-group.mb-4`.
  Under `xl`, columns **stack**.
- **Controls:** Family-1 admin vocabulary (§8) — white inputs, `#DADFE5`, radius
  4, h45; toggle 38×20 (ON `#182433`). File/logo upload = img preview +
  outline-primary "Upload logo" + "Clear logo". Read-only display = transparent,
  bold blue. Social-links composite = toggle + icon + name (+ URL when enabled).
- **Save:** none — inputs are `.mw-options-form-binded`, persist on change/blur.
- **Mobile:** hub cards → single column; section two-col → heading stacks above
  fields; **not fully optimized** (content ~442px > 375 → horizontal scroll; EDIT
  pill clips). Input height stays 45px.

---

## 15. Design / Appearance page

**Ref:** [`admin-design-page.png`](docs/design-references/admin-design-page.png).
Full spec: [`forms.md`](docs/research/components/forms.md).

> **NOT a template card grid.** A single current-template panel + live preview.

- Panel `.card` (`#FFF` radius 4 shadow, body pad 16×24) + **live preview
  iframe** `.preview_frame_small` (border 1px `#C0C0C0` silver, no radius,
  ~869×640) rendering the real page in the current template.
- `h3 "Design"` 24px/700; template name `h2` 20px/600 ("Big Template - FREE
  VERSION"); "Version : 18.8" is a **text link** (not a badge).
- Links: Site Details (→ settings general) · Template Store (→ Marketplace
  templates) — btn-link 16px/500.
- CTAs **New Page · Preview · Customize** (→ Live Edit) = **bold dark-outline**
  buttons (2px `#000` border, 16px/700, h49).
- A page selector (Home/Blog/Shop/Contact) + Prev/Next arrows drive the preview.
- Browse+activate of other templates is delegated to the Marketplace cover-card grid.

---

## 16. Live Edit

**Ref:** [`le-overview.png`](docs/design-references/le-overview.png),
[`le-mobile-preview.png`](docs/design-references/le-mobile-preview.png),
[`le-elem-selected.png`](docs/design-references/le-elem-selected.png).
Deep spec: [`live-edit-topbars.md`](docs/research/components/live-edit-topbars.md).

### Toolbar (`--toolbar-height: 60px`)
```
bg #FFFFFF · height 60px · color #2B2B2B · font Inter · shadow 0 2px 4px rgba(24,36,51,.075) · z-index 2
```
Left→right: `← ADMIN` · **undo/redo** (icon buttons on light-grey chips — undo
`#F0F0F0`, redo `#F8F8F8` when disabled) · **ADD** (pastel-blue `#E1EDF8`, ~88×36)
· **[ page selector "Home" ▾ ]** (centered — the **pill is grey `#F0F0F0`**, only
its container is white) · **device-toggle group** (`.mw-live-edit-resolutions-
wrapper`, bg `#F0F0F0`, radius 5, ~88×35, gap 2, pad 5; active device = white) ·
**palette** (→ Template Style Editor §18) · **contrast/droplet** (→ Element Style
Editor §19) · chevron ▾ · **VIEW** (`#F0F0F0`) · **SAVE** (dark `#182433`, white)
· hamburger. (`#F0F0F0` is the shared "inactive control" track — VIEW, page pill,
device group all match.)

### Canvas
Real site in an iframe below the toolbar. The **device toggle** reflows it to a
centered ~390px phone frame in place (no reload).

### Element selection & quick-action toolbar
Clicking an element draws a **bright-blue outline (`~#0078FF`, ~2px)** and floats
a **white rounded pill** (~203×41, radius ~7–8, no border, soft drop shadow),
**left-aligned above the element's top-left** (not centered): five dark (`#000`)
icons in a row — `⣿ drag · ⚙ settings · ＋ add · →|← width · ⋮ more`. Hovering a
*module* instead shows a white **handle card** (`.mw-handle-item-menus-holder`,
radius 7, shadow `0 4px 16px rgba(17,17,26,.1), 0 8px 32px rgba(17,17,26,.05)`,
pad 11): rows Settings / [Clone·Fav·Down·Up] / Delete (wide buttons: 1px `#EEEEEE`
border, radius 3, ink `#404040`). ⚙ opens the module settings surface (§17).

### Inline text editor (RTE, `.mw-small-editor`, on editable text)
Floating flex bar, radius 5, `z-index 1101` (pinned = fixed, centered, `top:20`);
buttons 35px h / 33px min-w / radius 3. **Light skin:** bg `#FFFFFF`, ink
`#2B2B2B`, active-control `#4592FF`/white. **Dark skin** (`.dark`): bg `#404040`,
white. `<800px` → full-width fixed `top:0`, radius 0, horizontal scroll. Commands
(25): `Insert · Delete · Insert Module · AI Text Generator · Line height · Font ·
Bold · Italic · Underline · Strike · Format(h1–h6) · Font Size · Align (l/c/r/j) ·
Insert Image · Insert link · Unlink · Text color · Text bg color · Text styles ·
Insert Table · Remove Format · Pin/Unpin · Close`.

> ⚠️ Live-Edit inserts & module settings **auto-persist to the content DB**.
> Drive pickers non-mutatingly; test inserts only on throwaway pages.

---

## 17. Module Settings Forms

**Ref:** [`le-elem-editor-open.png`](docs/design-references/le-elem-editor-open.png) (Content),
[`le-elem-design-tab.png`](docs/design-references/le-elem-design-tab.png) (Design),
[`modsettings-products.png`](docs/design-references/modsettings-products.png),
[`modsettings-posts.png`](docs/design-references/modsettings-posts.png).
Full spec: [`module-settings.md`](docs/research/modules/module-settings.md).

Opened from the ⚙ gear → `mw.app.moduleSettings.openSettingsModal` → an **iframe**
at `/v2/api/live_edit/live_edit.module_settings?...&type=<type>/admin`.

### Shell — one shell, three sizes, **no backdrop dim**
```
wrapper .mw-dialog (z 1200); overlay transparent
holder  .mw-dialog-holder : bg #FFFFFF · radius 0 · shadow 0 3px 8px rgba(0,0,0,.24) · pad 0 15px
  sizes: 370px popover (btn + simple modules)
         370px popover ANCHORED to right edge (layouts)
         930px CENTERED modal embedding a content-manager list (posts, products)
type tag .settings-title-inside : bg #0078FF · white · 10px/700 · pad 3×5 · top-left (= module title)
close : blue X, top-right
tabs (inside iframe): height 45, #182433, active 14px/700 + ~2px #182433 underline, inactive 14px/600
       (NOTE: current build shows an underline — likely drift from the v2.0.20 baseline; underline mechanism unresolved)
```
Controls use **Family-2** (§8): filled `#F0F0F0` inputs, `.live-edit-label`
9.75px UPPERCASE, radio-cards/segmented (active = dark fill), media-select, skin card.

### Per type (field inventories)
- **btn** (`btn/admin`, 370 popover, 2 tabs): **Content** [TEXT · LINK+picker ·
  ALIGN seg · open-in-new toggle] · **Design** [SKIN card · Button Icon · Icon
  Position seg · Button Style select · Button Size select].
- **layouts** (`layouts/admin`, 370 **anchored-right** popover, 3 tabs):
  **Background** [bg-type seg · media-select · IMAGE SIZE seg×4] · **Settings**
  [current-layout preview · module chips · Reset Layout (red link)] · **Change
  Layout** [~30 thumbnail cards + More layouts].
- **products** (`shop/products/admin`, 930 centered, 3 tabs): **Products**
  [embedded content-manager: product list, prices+sale, category chips, status
  pills] · **Settings** [6 fields: source · category filter · filter tags ·
  display-on-post radios · posts-per-page · order by] · **Design** [SKIN].
- **posts** (`posts/admin`, 930 centered, 3 tabs): **Posts** [content-manager] ·
  **Settings** [same 6 fields] · **Design** [SKIN + responsive column selects
  xs–xl (1–6) + Custom Classes].

The embedded content-manager (posts/products) reuses: "New" btn dark `#182433`
(h47), white search radius `4px 0 0 4px` h45, List/Details toggle (active
`#182433`/`#FCFDFE`), status pill green `#EAF7EC`/`#2FB344`.

> **Not mobile-adapted:** popovers stay 370px (clip off-left), the 930px modal
> overflows both edges.

---

## 18. Template Style Editor

**Ref:** [`le-style-editor.png`](docs/design-references/le-style-editor.png),
[`le-colors-panel.png`](docs/design-references/le-colors-panel.png).

Right drawer (~263px), title "Template Style Editor" (20px/600, `#2B2B2B`) + `X`.
Opened from the toolbar **palette** icon. Two grouped indexes (each row has a
**reset** icon):
- **Styles:** `Typography · Colors · Top Header · Header · Footer · Buttons · Forms`
- **Template Settings:** `Header Options · Shop Options · Footer Options · Other Options`

**Sub-panel pattern:** `← Back` → title → helper paragraph → control rows
(UPPERCASE label + **circular color swatch** / select / slider) → footer "Clear All".

**Colors tokens (template design-token schema — define these FIRST when building
a template):** `BACKGROUND · SECTION BACKGROUND · PRIMARY · BODY · HEADING ·
PARAGRAPH · TEXT ON DARK BACKGROUND · LINK · LINK COLOR HOVER`.

---

## 19. Element Style Editor

**Ref:** [`le-droplet.png`](docs/design-references/le-droplet.png),
[`le-elem-typography.png`](docs/design-references/le-elem-typography.png).

Right drawer opened from the toolbar **droplet/contrast** icon (distinct from the
palette). Title "Element Style Editor" (20px/600, `#2B2B2B`) + `X`.
- **Empty state:** blue "Please select an element to edit" info box.
- **Selected:** `SELECTED ELEMENT:` + element tag pill (e.g. `DIV`), then the
  category index (leading icons): `Typography · Background · Spacing · Border ·
  Rounded corners · Shadow · Classes (</>) · Section settings (⚙)`.

**Control vocabulary:** select dropdown (Font/Weight/Transform/Style) · 4-way
align **segmented** (active = dark fill `#182433` + white) · **color swatch +
hex + reset** · **slider + value + reset** (font-size/line-height/letter-spacing/
word-spacing) · inline "Load more fonts" link. Every numeric row has a **↺ reset**.

> **Two element editors, two jobs:** the **module popover** (§17) edits *what the
> module is* (module props, per type); the **Element Style Editor** edits *how the
> node looks* (raw CSS) + escape hatches (`Classes`, `Section settings`).

---

## 20. Modals

**Ref:** [`modal-insert-picker.png`](docs/design-references/modal-insert-picker.png),
[`modal-media-picker.png`](docs/design-references/modal-media-picker.png),
[`modal-link-picker.png`](docs/design-references/modal-link-picker.png).
Full spec: [`modals.md`](docs/research/modals/modals.md).

> **The full modal-shell census — five distinct shells** (yet another unify
> target). A/B below are the primary two; C–E were found in the second pass:
> - **A** Live-Edit-native `mw-le-*` — Insert picker · 500px · radius 0 · overlay `rgba(0,0,0,.2)`
> - **B** Tabler `mw-dialog` — Media/Link pickers · 860px · radius 8 · overlay `#1D273B@24%`
> - **C** Module-settings `mw-dialog` (simple) — §17 · 370/930px · radius 0 · **no dim**
> - **D** Livewire `.js-modal-livewire` — Marketplace item modal (§22) · 480px · radius 0 · overlay `rgba(0,0,0,.4)`
> - **E** Front-end auth modal — Login/Register (§23) · 500px · radius 4.8 · overlay `rgba(0,0,0,.376)`

### Family A — Live-Edit-native (`mw-le-*`) — Insert picker
```
overlay rgba(0,0,0,.2) (z 4) · shell #FFF · width 500 / max 95% · radius 0 · pad 0 · z 5
shadow layered 0 54px 55px rgba(0,0,0,.25) +4 soft layers · centered
search: white · 1px solid #90B5E2 · radius 0 · h60 · 14px · pad-left 40 (icon)
category heading h4 14px/600 #2B2B2B capitalize
tile .mw-modules-list-block-item : flex row · pad 8×16 · h36 · radius 0 · 2-col · icon 20 · label 14px · hover #F6F8FB
67 items / 12 categories · live client-side filter · no title bar, no footer (click = insert)
```

### Family B — Tabler admin dialog (`mw-dialog`) — Media & Link pickers
```
overlay rgba(29,39,59,.24) = #1D273B @24% (wrapper z 1200) · shell #FFF · width 860 / max 98% · radius 8 · z 2
shadow 0 3px 9px rgba(0,0,0,.15) · centered
header : title Inter 18px/700 #182433 · close .mw-dialog-close 32×28 border 1px #CFCFCF radius 0
footer : text buttons "Cancel" (left) / "OK" (right) — #182433 14px/600 transparent
primary: .btn-pill.btn-primary #206BC4 · radius 160 · h45 · white
dropzone: 2px dashed #4592FF
```
- **Media picker** ("Select image"): segmented tabs (My computer/URL/Uploaded/
  Media library) track `#F0F0F0` h45, active = white pill radius 5; body = dashed
  dropzone + `#206BC4` "Add file". *(Media library grid empty on demo.)*
- **Link picker** ("Link Settings"): two-column — left rail `ul.mw-ac-editor-nav`
  bg `#F5F5F5` pad 25 (URL/Pages/All content/File/Email/Page section; active 700)
  + right panel with **Family-2** filled fields; keeps two columns even at 390.

---

## 21. Commerce

**Ref:** [`admin-orders-list.png`](docs/design-references/admin-orders-list.png),
[`admin-customers-table.png`](docs/design-references/admin-customers-table.png),
[`admin-contact-submissions.png`](docs/design-references/admin-contact-submissions.png).
Full spec: [`orders-crm.md`](docs/research/components/orders-crm.md).

> ⚠️ **The demo has zero orders / customers / contacts**, so the **single
> order-detail view could not be captured** (the only path creates a record on
> the shared demo). Order-detail line-items/totals/status remain a **gap to
> measure on a seeded instance**. What follows is verified for the *list* level.

- **Orders list** (`/order`): standard **card-row** chrome (§12A); "New Order" =
  filled **navy `#182433`** (`btn-dark`, h47) → `mw_admin_add_order_popup()`;
  "Abandoned Carts" = bold dark-outline. Empty = bare "0 results found".
- **Abandoned** (`/order/abandoned`): white card panel with the **text-tab +
  count** idiom (§9): "Completed orders (N)" / "Abandoned carts (N)" — plain
  `btn-link`, 14px/500 `#182433`, no chrome. Empty = "You don't have any orders yet".
- **Customers** (`/customers`): stat header ("0 Customers") + centered "No content
  found" empty state; populated table unverified (empty demo).
- **Contact form** (`/contact-form`): 24px/700 title, admin white `select`
  (h46), Comments-family search (`form-control-sm`, radius 2, h45), Settings /
  Integrations as `btn-link`; empty = "No data found" in **blue `#4299E5`** on a
  left-stripe info strip.
- **Currency** in all commerce copy is dynamic from settings — never hardcode `$`.

> Confirms **filled navy `#182433` = the commerce primary** (matches list "New").

---

## 22. Marketplace Item Modal & Module Detail

**Ref:** [`marketplace-item-modal.png`](docs/design-references/marketplace-item-modal.png),
[`marketplace-template-modal.png`](docs/design-references/marketplace-template-modal.png),
[`module-detail.png`](docs/design-references/module-detail.png).
Full spec: [`marketplace-detail.md`](docs/research/components/marketplace-detail.md).

### Item modal — shell **D** (LE-native Livewire, not Tabler)
```
overlay .js-modal-livewire : fixed · z 1100 · rgba(0,0,0,.4) · pad-top 114px (clears ADD/EDIT bar)
panel   .js-modal-livewire-content : #FFFFFF · width 480px · radius 0 (SQUARE) · NO shadow · centered
header  .card-header h60 : title h3 24px/700 #182433 + Bootstrap × close (19×19) top-right
body    : cover 480×250 (tall page-shot, top-clipped) · "Latest Version: N" ·
          version select .form-select-sm (white #DADFE5 radius2 h29) · INSTALL CTA (+ Preview) ·
          meta table .card-table 12px (License / Homepage / Author / Released / Keywords)
```
Template modal & Module modal share this shell (Preview is template-only, → external
`templates.microweber.com`). **Install CTA is state-dependent** (see §7): green
Install → red Reinstall, both radius 2px / h29 (not pills). Grid tier badges:
Free/Installed = lime `#F1F8E8`/`#74B816`; Premium = orange `#FEF0E6`/`#F76707`.

### Module detail — a **full admin page** (no modal), two flavors
- **Bespoke dashboard** (e.g. Newsletter): 24px/600 title + a **navy segmented
  tab bar** (`nav-pills btn-group`, active = navy `#182433` fill, h51) + 4 KPI
  `card-sm` stat cards + `alert-info` (blue `#4299E5`, left stripe) + content card
  with outline-primary "+ Add new".
- **Generic options-form** (e.g. Cookie Notice): white card wrapping a Microweber
  options-form (`.mw_option_field`, **auto-save, no Save button**), 16px/500 labels.

---

## 23. Profile & Auth Screens

**Ref:** [`admin-profile.png`](docs/design-references/admin-profile.png),
[`frontend-login.png`](docs/design-references/frontend-login.png),
[`frontend-register.png`](docs/design-references/frontend-register.png),
[`admin-login.png`](docs/design-references/admin-login.png).
Full spec: [`profile-auth.md`](docs/research/components/profile-auth.md).

### Admin Profile (`/v2/admin/user/profile` — *not* `/admin/profile`)
Jetstream **stacked panels**, no tabs: 6 white `card.mb-7` (radius 4, shadow),
each a settings two-col row; the **form itself sits on a light-azure panel**
(`bg-azure-lt` `#ECF5FC`). Sections: Profile Info · Status & Role · Update
Password · 2FA · Browser Sessions · Delete Account.
- **Avatar:** 60×60 circle `#FCFDFE` + "Add photo" outline-primary-sm.
- **Fields = the "profile mixed" family (§8-#3):** text inputs filled `#F0F0F0`
  radius 0 h45 with **9.75px UPPERCASE** labels; but `select`s are admin white
  `#DADFE5` radius 4 — *on the same page*.
- **Save = per-panel** navy `.btn-dark #182433` (h45); **Delete Account** =
  danger `#D63939`.

### Front-end auth (public template — shell **E**)
Auth modal `.modal-content` bg `#F5F5F5`, radius 4.8, dialog 500px, overlay
`rgba(0,0,0,.376)`; triggered from the top-bar account dropdown.
- **Field (users-module, §8-#5):** white, 1px `#C3C3C3`, radius 1, h38, 16px
  Arial `#505050`.
- **Login:** username/email + password + remember-me + "Forgot password?";
  submit "Login" = **black pill** `#000` radius 23 h56; "CREATE NEW ACCOUNT →".
- **Register:** email + password + CAPTCHA + privacy note; submit "Register"
  black pill; "BACK TO LOGIN →". No social login on either.
- **Account/orders** (`users/orders`): `modal-lg` 800px, empty = "You have no
  orders" (Arial 44px).

### Admin login (`/v2/login`)
Centered Microweber logo (~250px) + `card-md` white (~373px). Fields use the
**admin white family** (`#DADFE5`, radius 4, h45). Submit "Login" = **blue
`#206BC4` full-width** (`w-100`, radius 4, h45) — note this is the *only* blue
full-width primary in the product. Links `#206BC4`; footer "Version 2.0.20".

---

## 24. Public Template Token Model

**Ref:** [`frontend-home.png`](docs/design-references/frontend-home.png),
[`frontend-shop.png`](docs/design-references/frontend-shop.png),
[`frontend-product.png`](docs/design-references/frontend-product.png),
[`frontend-contact.png`](docs/design-references/frontend-contact.png).
Full spec: [`frontend-template.md`](docs/research/components/frontend-template.md).

The public front end is a **Bootstrap-based Microweber template** whose tokens are
CSS custom properties (`--mw-*`); the demo overrides base defaults. **Front-end
font is `Arial`, not admin Inter.** Measured **applied** tokens (§18 schema):

| Token | Hex | | Token | Hex |
|---|---|---|---|---|
| BACKGROUND | `#FFFFFF` | | TEXT ON DARK | `#FFFFFF` |
| SECTION BACKGROUND | `#8691A9` (slate blue-grey) | | LINK | `#6AB340` (green) |
| PRIMARY | `#000000` (black) | | LINK/underline + FOOTER accent | `#F4A261` (orange) |
| BODY / HEADING | `#000000` | | `--mw-btn` (orange, defined, unused) | `#F4A261` → hover `#E76F51` |
| PARAGRAPH | `#EDE0E0` | | Bootstrap primary (unused by chrome) | `#0d6efd` |

**Type scale (Arial, weight 400, lh 1.5):** h1 64 · h2 52 · h3 44 · h4 32 · h5 24 ·
h6 16–20px; body 16/24; hero h1 **white 64px** (→32px at 390). Default `text-align:
center`.

**Anatomy:** pale-blue top bar `#ABCBED` **62px** (social + phone + white
"CONTACT US" pill + search/account/cart) → **black main nav `#000` 86px** (`Home ·
Blog · Shop · Contact us`, white links) → full-bleed hero image **800px**, white h1
64px + `#EDE0E0` subtitle + **black pill "Call to action"** (radius 23px, pad 15×48,
h56).

**Buttons = heavily-rounded pills.** Primary/CTA/add-to-cart/submit = **black
`#000`, radius 23px, h56** (`.btn-primary` = PRIMARY). **Form controls are square**
(white, 1px `#000`, radius 0, pad 15, h56) — square fields + pill buttons by design.

**Cards & forms:** product card has **no chrome** (transparent, radius 0, no
shadow), image 4:3 `object-fit:contain`; price black.

**Cart/Checkout:** mini-cart dropdown (white, 480px, radius 4) lists item + qty +
price + delete + total + white "Checkout" pill. The demo's **`/checkout` full page
and `/blog` list are premium-locked placeholders** ("part of the premium version").

**Footer:** white, black text, ~6 columns, orange `#F4A261` links (no underline),
"© All Rights Reserved. · Website Builder and CMS".

**Responsive (390):** nav → **hamburger**; hero h1 64→32; grids stack; footer stacks.

> **Chrome vs content contrast is intentional:** admin chrome = 4px radius,
> tinted, Inter; front-end content = pills + square fields, high-contrast, Arial.
> Two deliberate design languages — keep them distinct in the rebuild.

---

## 25. Responsive Behavior

- **Touch targets:** admin interactive controls ≥ **45px** (inputs, buttons);
  toolbar/table-sm run tighter (29–36px). Inputs use **16px** font (no iOS zoom).
- **Sidebar:** 240px → off-canvas hamburger under `md`; main goes full width.
- **Adapts well:** authoring forms (two-col → single, no overflow) and card-row
  lists (row body wraps, no overflow) — the best-behaved surfaces.
- **NOT mobile-optimized (known debt to fix in Filament-5):**
  - Settings group pages overflow (~442px > 375) → horizontal scroll; EDIT clips.
  - Status-tab bar stays `nowrap` → page overflows.
  - Users table → horizontal scroll (no card reflow).
  - Module-settings popovers/modals keep 370/930px → clip/overflow the viewport.
- **Breakpoints:** Tabler/Bootstrap — `sm 576 · md 768 · lg 992 · xl 1200 · xxl 1400`.

---

## 26. Do's and Don'ts

**Do**
- Float white cards on `#F6F8FB`; separate with **shadow + gap, not borders**.
- Use **soft-tinted** ADD/EDIT (pastel fill + `#182433` ink) as default CTAs.
- Pick the **right primary per surface** (§7): dark `#182433` on Live-Edit SAVE &
  list "New"; **green `#2FB344`** on authoring Save; blue `#206BC4` on Tabler
  tables & modal confirms.
- Use **Family-1** controls in admin forms (white + `#DADFE5`, 16px labels) and
  **Family-2** in Live-Edit (filled `#F0F0F0`, 9.75px UPPERCASE labels) — per §8.
- Status = `bg-*-lt` pill (saturated text on 4–8% tint); status-tab bar = blue
  border+wash active, grey `#929DAB` count badge.
- Currency = dynamic code/symbol from settings — **never hardcode `$`**.
- Define template **color tokens first** (§18) before building sections.

**Don't**
- Don't fill the page with solid saturated buttons — pastels are the identity.
- Don't add borders around cards; the shadow is the separation.
- Don't mix the two form families within one surface.
- Don't put a Save button in Settings — it auto-saves.
- Don't drop below **45px** on admin controls; don't apply the 4px admin radius
  to front-end content buttons (those are pills).
- Don't assume a global primary color — it's surface-dependent in the old system.
- Don't mutate content while inspecting Live Edit — inserts/settings auto-save.

**Fix-in-rebuild — the debt inventory (unify, don't copy).** The deep dive
quantified the drift; each of these collapses to one token/component:
- **5 input styles** (§8) → 1 admin + 1 front-end field.
- **~7 primary treatments + danger + state-install** (§7) → 1 primary + 1 danger.
- **7 tab idioms** (§9) → 1 tab component.
- **5 empty-state treatments** (§9) → 1 empty-state component.
- **5 modal shells** (§20) → 1 dialog + 1 popover.
- **radius sprawl** (0/1/2/4/4.8/5/8/10/23/160) → a small scale.
- **non-responsive** settings / tables / module modals / status-tabs (§25) → fix.
- **two ink tones** (`#182433` admin vs `#2B2B2B` LE chrome) → one.

**Known gap:** the single **order-detail** view (line-items/totals/status) is
unmeasured — the demo has no orders and creating one mutates it. Capture on a
seeded instance before finalizing commerce specs (§21).

---

## 27. Agent Prompt Guide

**Quick color reference**
```
ink #182433 · ink-chrome #2B2B2B · muted #667382 · canvas #F6F8FB · surface #FFFFFF
border #DADFE5 · hairline rgba(4,32,69,.14) · primary #206BC4 · save-green #2FB344
add #E1EDF8 · edit #E2F9E6 · view/fill #F0F0F0 · tag/select #0078FF
status: pub #EAF7EC/#2FB344 · premium #FEF0E6/#F76707 · badge #929DAB
tiles: blue #E1EDF9 · green #E1F9E6 · pink #F9E1F3 · yellow #F9F3E1
```

**Admin list page**
> Left 240px sidebar; `#F6F8FB` canvas. Title 20px/600 + pastel ADD/EDIT (h49).
> Rows = white cards (radius 4, `0 1px 4px rgba(0,0,0,.16)`, 16px gap): thumbnail,
> 16px/600 title link, `bg-*-lt` status pill, trailing ⋮. "New" button = dark
> navy `#182433`. Flat grey `#F0F0F0` keyword search.

**Admin authoring form**
> Two columns in `card-sm` panels (radius 4, shadow). Text tab bar w/ leading
> icons (active 600 + `#182433` underline). Left: 26px borderless title + slug
> chip, RTE (`.mw-bar` toolbar + white
> min-h 250 area), media dropzone, price `input-group` with dynamic currency-code
> prefix. Right: Visibility radios, category tree (radio parent/checkbox child),
> tag pills. **Save is green** `#2FB344` (h49, 14px/700). Fields = Family-1
> (white + `#DADFE5`, 16px Title-Case labels).

**Settings**
> Card hub: 48px `#E1EDF9` icon tile (`#182433` ink glyph) + 16px/700 title +
> 13.7px/500 **`#667382` muted-gray** desc; card → full page `?group=`. Sections two-col (18px/600 heading +
> muted help left, fields right). **No Save button — auto-save.** Toggle 38×20 ON
> `#182433`.

**Live-Edit chrome & editors**
> 60px white toolbar (`0 2px 4px rgba(24,36,51,.075)`, z 2): back+ADMIN, undo,
> redo, pastel ADD, centered page-selector pill, device toggle, palette, droplet,
> VIEW (`#F0F0F0`), dark SAVE (`#182433`). Selecting an element → blue `#0078FF`
> outline + quick-action pill (drag/⚙/＋/width/⋮). ⚙ → 370/930 white settings
> shell (blue `#0078FF` type tag, Content/Design tabs, Family-2 filled fields).
> Droplet → Element Style Editor drawer (Typography/Background/Spacing/Border/
> Rounded/Shadow; segmented active = dark fill, sliders + reset).

**Modals**
> Family A (LE insert): 500px, radius 0, overlay `rgba(0,0,0,.2)`, 60px search
> border `#90B5E2`, 2-col 36px tiles, no footer. Family B (Tabler media/link):
> 860px, radius 8, overlay `#1D273B@24%`, 18px/700 header, "Cancel/OK" text
> footer, `#206BC4` pill primary.

---

## 28. Appendix: Raw Measured Values

Verbatim highlights (demo v2.0.20, Inter). Full per-surface tables in the
[`docs/research/`](docs/research/) specs.

```
FOUNDATIONS
  body Inter 14px/20px #182433 · canvas #F6F8FB · sidebar 240px
  radius scale 0 / 2 / 4 / 5 / 8 / 10 / 160(px pill) · card shadow 0 1px 4px rgba(0,0,0,.16)

BUTTONS (surface-dependent primary)
  LE SAVE / list "New"  bg #182433 white radius4 border1px #1F2E41 inset bottom-edge (h47)
  form Save             #2FB344 white radius4 h49 14/700 inset 0 3px 5px rgba(0,0,0,.125)
  table "New"           #206BC4 white radius2 h29
  modal OK              #206BC4 pill radius160 h45  ·  Cancel/OK text #182433 14/600
  ADD #E1EDF8 · EDIT #E2F9E6 · VIEW #F0F0F0  (ink #182433, radius4, h45/49, inset edge)
  outline-primary transparent #206BC4 border radius4 h45
  bold dark-outline transparent #182433 border2px #000 radius4 16/700 h49

FORM FAMILY 1 (admin)     white .form-control border1px #DADFE5 radius4 h45 14px · label 16/500 Title-Case
                          select h~39 16px · toggle 38×20 ON #182433 OFF #FFF+rgba(4,32,69,.14)
FORM FAMILY 2 (live-edit) filled #F0F0F0 border-none h45 14px (text radius0 / select radius4)
                          label .live-edit-label 9.75/500 UPPERCASE ls.75 · segmented active dark #182433

TABLES/LISTS
  card-row: white card radius4 shadow0 1px4 rgba(0,0,0,.16) gap16 · title 16/600 · thumb 80×48/104×83
  table th 10/600 UPPERCASE #667382 ls.4 · td 14px #182433 border-bottom rgba(4,32,69,.14) · row h73 · avatar 40 circle
  status pill: #EAF7EC/#2FB344 12/500 radius4 h28  ·  status-tab active rgba(32,107,196,.04)+border #206BC4
  count badge #929DAB white 12/600 radius4

MODALS
  A LE insert  500px radius0 overlay rgba(0,0,0,.2) · search #90B5E2 h60 · tiles 36px 2-col hover #F6F8FB
  B Tabler     860px radius8 overlay #1D273B@24% · header 18/700 · close 32×28 border #CFCFCF · OK pill #206BC4
  module-settings shell 370/930 radius0 shadow0 3px8 rgba(0,0,0,.24) NO dim · type tag #0078FF 10/700

CSS VARS  --toolbar-height:60px · --mw-forms-min-height:45px · --mw-admin-gray-color:#f5f5f5
          --tblr-body-font-family:"Inter" · --tblr-body-bg2:#f0f0f0

TEMPLATE COLOR TOKENS  Background · Section Background · Primary · Body · Heading ·
                       Paragraph · Text on Dark Background · Link · Link Color Hover
ELEMENT STYLE CATEGORIES  Typography · Background · Spacing · Border · Rounded corners ·
                          Shadow · Classes · Section settings
```
