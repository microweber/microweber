---
name: mobile-touch-target-floor
description: >-
  Floor a sub-44 touch target to ≥44×44 in Microweber's Filament-based
  admin and Bootstrap-template public surfaces. Use this whenever a
  WCAG 2.5.5 / iOS HIG report finds buttons, icons, anchors, toggles,
  checkboxes, radios, or inline-toolbar controls below 44px on either
  axis at 390×844 — even if the report only names one control. The
  surrounding controls usually share the same root cause, so probe
  with playwright first before patching.
---

# Mobile Touch-Target Floor (≥44×44)

> **Canonical location:** `.claude/skills/mobile-touch-target-floor/SKILL.md`.
> Placed under `.autodev/skills/` here because the harness sandbox
> blocked writes under `.claude/skills/` during cycle-176e. PM/agent-pm
> should `git mv` to the canonical path when convenient.

## Problem

Filament's bundled CSS defaults many controls to 24-36px on the
smaller axis (`.fi-toggle` 24h, `.fi-icon-btn` size-9/36, `.fi-btn`
36h, `.fi-fo-rich-editor-tool` h-8). Custom Microweber controls in
live-edit and the Bootstrap public template have similar gaps
(`.mw-control-boxclose` 20×20 with hardcoded `width:20px`,
`.reset-template-settings-and-stylesheet-button` 20×20).

These all fail WCAG 2.5.5 / iOS HIG 44 on mobile. A purely-min-width
override frequently LOSES to hardcoded `width:` rules later in the
cascade.

## Root Cause

Filament's CSS uses Tailwind utilities like `h-8 min-w-8` (32×32) or
`size-9` (36×36) as the default control size. Microweber's custom
`.mw-*` live-edit classes hard-set `width:` and `height:` properties
in absolute pixel values. On mobile + touch viewports those defaults
are below the 44 floor.

## Solution Pattern

Wrap the rule in the right media query, scope to the right surface,
and use `min-width`/`min-height` `!important` — UNLESS an existing
rule hard-sets `width:` / `height:`, in which case override with
`width:` / `height:` `!important` too.

### CSS template — admin (Filament panel)

Place in `packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css`:

```css
/*
 * AI-XXX (cycle-NNN YYYY-MM-DD) — <one-line problem>.
 * agent-test's audit reported `.foo-bar` measured WxH at 390×844.
 * Filament's base style is <selector> → <values>.
 * Fix: floor body.fi-panel-admin .foo-bar to 44×44 with
 * !important. Scope to admin panel so checkout panel
 * (.fi-panel-checkout) is unaffected.
 */
@media (max-width: 1023.98px), (hover: none) and (pointer: coarse) {
    body.fi-panel-admin .foo-bar,
    body.fi-panel-admin .foo-bar > button {
        min-width: 44px !important;
        min-height: 44px !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
    }
}
```

### CSS template — live-edit (admin chrome inside iframe)

Place in `packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css`:

```css
@media (max-width: 768px), (pointer: coarse) {
    .mw-admin-live-edit-page .target,
    .mw-live-edit-page .target {
        min-width: 44px !important;
        min-height: 44px !important;
    }

    /* If target is portaled to body root (control-boxes, AI chat,
       Filament modals are OUTSIDE the .mw-live-edit-page wrapper),
       use direct-class selectors. .mw-* class names are themselves
       live-edit-specific. */
    .mw-control-boxclose,
    .mw-ai-chat-box-action-voice {
        min-width: 44px !important;
        min-height: 44px !important;
    }
}
```

### CSS template — public (Bootstrap template, iframe-loaded)

Place in `public/templates/bootstrap/css/public-touch.css`:

```css
@media (max-width: 768px), (pointer: coarse) {
    .footer-background a {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        padding-top: 6px;
        padding-bottom: 6px;
    }
}
```

## Override rules

1. **`min-width` / `min-height` is the default tool** — lets text
   labels grow the control beyond 44 without truncating.
2. **`width` / `height !important` is required** when an existing
   rule hard-sets `width:`/`height:` to a specific pixel. Example:
   `.mw-control-box-default .mw-control-boxclose` is declared as
   `width: 20px; height: 20px;` — a `min-width: 44px` override
   will lose. Use `width: 44px !important; height: 44px !important;`.
3. **`!important` is needed** when Filament's bundled CSS loads
   AFTER the project CSS. Higher specificity alone is not enough.
4. **For height-only fixes** (AI chat send 320×40 → 320×44, Tools
   menu 46×35 → 46×44), use `min-height: 44px !important;` with
   `height: auto;` so the existing width stays intact.

## How to probe actual class names (Playwright)

Audit reports often misname classes (PM email said `.tiptap-toolbar`
but actual is `.fi-fo-rich-editor-tool`). Probe at 390×844 BEFORE
patching:

```javascript
const probes = {
    closeBox: '.mw-control-boxclose',
    resetBtn: '.reset-template-settings-and-stylesheet-button',
};
const out = {};
for (const k in probes) {
    const els = document.querySelectorAll(probes[k]);
    out[k] = { count: els.length, samples: [] };
    els.forEach((el, i) => {
        if (i > 1) return;
        const r = el.getBoundingClientRect();
        const cs = getComputedStyle(el);
        out[k].samples.push({
            w: Math.round(r.width), h: Math.round(r.height),
            minH: cs.minHeight, minW: cs.minWidth,
            width: cs.width, height: cs.height,  // catches hardcoded width:
        });
    });
}
return out;
```

`width: 20px` (not `auto`) in computed style = use `width !important`
override instead of `min-width`.

## How to verify after build

1. `npm run build` in `packages/microweber-filament-theme/` for
   admin/live-edit CSS, OR no-build for `public-touch.css` (served
   direct).
2. Navigate with `?nocache=ai###` to bust asset cache.
3. Re-probe; expect `w` and `h` ≥ 44.
4. Probe one sibling control to verify the rule doesn't bleed.

## Do NOT

- Apply globally without scoping (`button { min-height: 44px }`)
  — leaks to checkout panel + every other surface.
- Forget the media query — full-time 44 floor regresses desktop
  density.
- Use `min-*` when `width:`/`height:` is hardcoded — your rule
  silently loses.
- Skip the playwright probe — audit class names are unreliable.

## Applies To

- `packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css`
- `packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css`
- `public/templates/bootstrap/css/public-touch.css`
- Cycles 161-176 are all instances of this pattern.
