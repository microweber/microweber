---
name: filament-admin-scope
description: >-
  Scope every Filament-related CSS or JS rule to the correct
  panel surface so a "fix the admin" change cannot regress
  the checkout panel, the live-edit panel, or any other Filament
  panel that shares the same base classes. Use this whenever you
  add a CSS rule that targets a `.fi-*` selector OR a `body.fi-*`
  selector OR a Microweber-bridged class that overlaps Filament
  (`.mw-control-*` portaled to body, `.mw-live-edit-page` wrapper,
  etc.). Wrong scope = silent regression in a panel you weren't
  even looking at.
---

# Filament Admin Scope Discipline

> **Canonical location:** `.claude/skills/filament-admin-scope/SKILL.md`.
> Placed under `.autodev/skills/` here because the harness sandbox
> blocked writes under `.claude/skills/` during cycle-176e.

## Problem

Filament v5 emits the same `.fi-*` class on every panel — admin
gets `.fi-panel-admin` on body, checkout gets `.fi-panel-checkout`,
live-edit uses its own custom wrapper. A bare rule like
`.fi-btn { min-height: 44px }` hits ALL panels at once. That's
how cycle-141 accidentally broke checkout's wizard density when
fixing the admin topbar.

The corollary: some Microweber live-edit elements are PORTALED
to `<body>` (not inside the `.mw-live-edit-page` wrapper). So
`.mw-live-edit-page .mw-control-boxclose` won't match — but the
unscoped `.mw-control-boxclose` selector is itself live-edit-only
(class name is unique to that surface), so direct-class selection
is safe.

## Root Cause

Filament panels share base CSS. Microweber's live-edit chrome
sometimes lives outside its own wrapper. Without explicit scoping
discipline, every "admin fix" risks the checkout panel and every
"live-edit fix" risks any other surface using `.mw-*` classes.

## Solution Pattern

Pick the right scope BEFORE writing the selector. Decision tree:

### 1. Is the target inside an admin Filament panel?

Use `body.fi-panel-admin`:

```css
body.fi-panel-admin .fi-topbar .fi-btn,
body.fi-panel-admin .fi-ta-row td a {
    min-height: 44px !important;
}
```

The checkout panel (`body.fi-panel-checkout`) and any future
panel (`.fi-panel-orders`, `.fi-panel-cms`, etc.) are untouched.

### 2. Is the target inside the live-edit admin chrome?

Use `.mw-admin-live-edit-page` and/or `.mw-live-edit-page`:

```css
.mw-admin-live-edit-page .live-edit-toolbar a,
.mw-live-edit-page .live-edit-toolbar a {
    min-height: 44px !important;
}
```

Note BOTH wrapper classes — `mw-admin-live-edit-page` is set on
the iframe body, `mw-live-edit-page` is set on the OUTER admin
shell. Some panels render in one, some in the other, some in
both.

### 3. Is the target PORTALED to body root from live-edit?

Use the DIRECT class name only (no wrapper prefix), but verify
the class is itself live-edit-specific:

```css
/* .mw-control-boxclose, .mw-ai-chat-box-action-voice, etc. are
   only ever rendered by live-edit JS — direct selector is safe. */
.mw-control-boxclose {
    min-width: 44px !important;
}
```

Verify with `grep -r "mw-control-boxclose" packages/ src/`:
the class name should appear ONLY inside live-edit-related
files. If it appears in non-live-edit code, do NOT use direct
selection — find a different scoping mechanism (e.g. a parent
`.mw-control-box` wrapper class).

### 4. Is the target a public-side element?

Scope to the Bootstrap template's existing parent classes:

```css
.footer-background a,
section.footer-background a {
    min-height: 44px;
}
```

Public-side CSS lives in `public/templates/bootstrap/css/public-touch.css`.
The `.mw-live-edit` body class is added when the page is loaded
INSIDE the live-edit iframe; use that to scope inside-iframe-only
rules:

```css
body.mw-live-edit .plain-text {
    min-height: 24px;
}
```

## Common scope mistakes

| Wrong | Right |
|---|---|
| `.fi-btn { min-height: 44px }` (leaks to checkout) | `body.fi-panel-admin .fi-topbar .fi-btn { ... }` |
| `.fi-toggle { ... }` (leaks to login + every panel) | `body.fi-panel-admin .fi-toggle { ... }` |
| `.mw-live-edit-page .mw-control-boxclose` (control-box is portaled OUTSIDE the wrapper — no match) | `.mw-control-boxclose { ... }` (direct selector — class is live-edit-unique) |
| `footer a { ... }` (any `<footer>` element anywhere) | `.footer-background a, section.footer-background a { ... }` |

## How to verify portal behaviour

Open the surface in Playwright at 390×844. Run:

```javascript
const target = document.querySelector('.mw-control-boxclose');
const wrapper = document.querySelector('.mw-live-edit-page');
return {
    targetFound: !!target,
    wrapperFound: !!wrapper,
    insideWrapper: wrapper?.contains(target),
    parentChain: (() => {
        if (!target) return null;
        let p = target.parentElement, chain = [];
        while (p && chain.length < 8) {
            chain.push({tag: p.tagName, cls: (p.className||'').slice(0,40)});
            p = p.parentElement;
        }
        return chain;
    })(),
};
```

If `insideWrapper === false` (and you see `BODY` near the top of
the chain), the element is portaled — use direct selectors only.

## How to verify scope doesn't bleed

After shipping, probe a related panel:

- Admin fix → probe `/checkout` at 390×844 and assert the
  Wizard Next button is still its expected height.
- Live-edit fix → probe `/admin` non-live-edit pages and assert
  their controls are unchanged.
- Public fix → probe `/admin/login` and assert no admin-side
  visual regression.

## Filament drawer-collapse breakpoint

At `<1024px`, Filament admin panels collapse the sidebar to an
off-screen drawer. This is INTENTIONAL UX (slide-out pattern,
not a bug). When audit reports flag "sidebar items extend past
screen edge" or "sidebar has no labels":

- Probe the closed-drawer state vs. open-drawer state.
- Confirm labels ARE visible in the open-drawer state.
- Scope decision: NO code change. Pin the existing cycle-N row
  floor in a regression-guard contract test.

This is the AI-220 / AI-231 rail-labels pattern — DON'T add
a new layout, the existing drawer pattern IS the mobile design.

## Do NOT

- Apply `.fi-*` rules globally — always check panel scope first.
- Use `.mw-live-edit-page` as a parent selector for elements that
  are portaled outside it (control-boxes, AI chat, Filament modals).
- Assume "the user only mentioned admin" means "scope to admin
  is fine" — if a class is shared with checkout, you MUST scope.
- Add layout changes to "fix" the Filament drawer-collapse — it's
  the intended mobile UX.

## Applies To

- All CSS in `packages/microweber-filament-theme/resources/assets/css/microweber/`
- All CSS in `public/templates/bootstrap/css/public-touch.css`
- Any JS that adds/removes classes that participate in Filament
  panel cascades.
- Cycles 161-176 of this project are all instances of correct
  scope discipline; AI-220 and AI-231 rail-labels are canonical
  examples of "no code change needed — drawer pattern is correct".
