# Skin Authoring Guide

> **Cycle-113 / AI-125 / TICKET-CQ (2026-05-09)** — patterns for
> authoring + consolidating module skins. Pairs with the
> MODULE_GUIDE.md (overall module architecture).

---

## What is a skin?

A "skin" is a Blade template that renders one of a module's
public-facing views. Each module has a skin pipeline:

```
Modules/<X>/resources/views/templates/
├── default.blade.php       # Fallback — always present
├── skin-1.blade.php
├── skin-2.blade.php
└── ...
```

The Live Edit picker auto-discovers every `<skin>.blade.php` and
exposes them in the per-module skin select. The chosen skin is
stored in the module's settings and rendered via the
`<module type="...">` tag in the page Blade.

---

## Authoring rules (post-cycle-89 / cycle-103)

### CSP-clean

Per cycle-87 + cycle-89 + cycle-103 (AI-113 grep-gate):

- **No inline `<style>` blocks.** Lift to a shared stylesheet
  loaded via `@once <link rel="stylesheet">`.
- **No inline `style="..."` with Blade interpolation.** The
  AI-113 grep-gate blocks
  `style="...background-image: url('&#123;&#123; thumbnail|asset...`.
  Use a real `<img>` via `responsive_thumbnail()` instead.
- **No inline `onclick="..."` with Blade interpolation.**
  Use `data-mw-action` + a delegated listener (see ADR-0003).

### a11y

- `alt=""` for decorative images; descriptive alt for content.
- `<h1>` for the primary heading on a content page; `<h2>+` for
  sections.
- `aria-current="page"` on the active link in nav menus.
- `loading="lazy"` for below-the-fold images;
  `loading="eager"` for the LCP candidate (cycle-105 helper does
  this automatically for the first 2 calls per request).

### Schema.org microdata

Wrap content blocks in `itemscope itemtype="..."`:
- `https://schema.org/BlogPosting` for posts.
- `https://schema.org/Product` for products.
- `https://schema.org/Person` for testimonials.

Don't duplicate `itemprop="url"` — Schema.org expects ONE
canonical URL per item (cycle-90 / AI-78 cleanup).

---

## Consolidation pattern (AI-125 PoC)

When a module has 10+ skins with 70-80% shared CSS, extract the
common rules into an SCSS base partial + have skins inherit via
`@extend`.

### Example: Post skins

Pre-cycle-89: 13 Post skin Blades each had 30-50 lines of inline
`<style>` with overlapping rules.

Cycle-89 (AI-77): lifted to a single `post-skins.css` bundle —
same rules but loaded once per page via `@once <link>`. Solved
CSP + dedupe.

Cycle-113 (AI-125 PoC): extracted the common rules to
`_post-skin-base.scss`:

```scss
// _post-skin-base.scss
.post-skin-base {
    .post-holder { ... }
    .thumbnail-image-holder { ... }
    .post-bottom-holder { ... }
    .mw-products-title { ... }
}

// Per-skin variants only declare the differences:
.post-skin-1 {
    @extend .post-skin-base;
    .post-holder:hover { ... }
}

.post-skin-15 {
    @extend .post-skin-base;
    .post-holder { border-radius: 12px; }
}
```

The runtime CSS bundle ends up roughly the same size (because
SCSS `@extend` inlines the selectors), but **maintenance** is
much easier:
- A change to `.post-holder` is one edit, not 13.
- New skins only need to declare what's different.
- Per-skin overrides are visible in one file, not buried in
  `<style>` blocks across 13 Blades.

### When to consolidate

| Signal | Action |
|---|---|
| 5+ skins with >50% shared CSS | Extract a base partial. |
| 1-2 skins | Don't pre-optimise — keep the CSS local. |
| Skins differ wildly | Don't force a base; keep them separate. |
| Existing skins use `@import` | Add new skins to the same base. |

---

## SCSS file layout

```
Modules/<X>/resources/assets/css/
├── _<module>-skin-base.scss   # Shared base partial
├── <module>-skins.scss        # Aggregator: @import base + per-skin
└── (built) <module>-skins.css # Output of vite build
```

The Blade skin loads the BUILT `.css`:

```blade
@once
    <link rel="stylesheet" href="{{ asset('modules/<x>/css/<module>-skins.css') }}">
@endonce
```

---

## Don'ts

- **Don't `@import` the base from a CDN.** Source dependencies
  must be local to enable offline + CI builds.
- **Don't override base rules in inline `style="..."`.** Defeats
  the purpose of the consolidation + violates CSP.
- **Don't rely on selector specificity tricks** (e.g. `!important`
  on a base rule to "win"). Refactor the base instead.

---

## See also

- ADR-0001 / 0002 / 0003 — security model that constrains what
  skins can do with user input.
- `MODULE_GUIDE.md` — overall module authoring (skin pipeline is
  one section).
- AI-77 / cycle-89 — the canonical "lift inline-style → shared
  bundle" cycle. Use it as the migration template.
- AI-79 / cycle-91 — same shape applied to the FAQ module.
- AI-80 / cycle-92 — same shape + BS5 markup migration for the
  Accordion module.
