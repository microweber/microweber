# Filament Modal Inert Fix

**Makes inline Filament action modals clickable by clearing the stray `inert` that traps them — in place, without moving the DOM.**

> Package id/namespace keep the historical `modal-teleport` name; the mechanism is `inert` neutralization (DOM teleport was tried and rejected — see below).

## The Problem

When a Filament modal opens, its focus trap marks the background content **`inert`** so nothing behind the modal is interactive or focusable. Normally Filament teleports the modal to the top of the panel first, so it sits **outside** the inerted region.

But **form / schema-action modals** (e.g. an "Apply template" action rendered from a page form) render **inline inside `.fi-main`**, and are *not* teleported. So `.fi-main` — an **ancestor of the modal** — receives `inert`, and `inert` propagates down **into the modal itself**.

**Result:** the modal is visible but completely non-interactive. Every click over it falls through to the nearest non-inert ancestor (`.fi-main-ctn`), so the modal looks dead — `elementFromPoint()` over the modal returns `.fi-main-ctn`, and even a real click never reaches the buttons.

## The Solution

Whenever an element that **contains an open modal** is marked `inert`, this package **clears the `inert`** on that element:

```js
document.querySelectorAll('[inert]').forEach((el) => {
    if (elementContainsAnOpenModal(el)) el.removeAttribute('inert');
});
```

- **In place — no DOM relocation.** The modal stays inside its Livewire component root, so `wire:model` / `wire:submit` keep working (verified end-to-end: the action actually runs).
- **Scoped.** Only elements that actually contain an open modal are touched; legitimate `inert` (loading overlays, the background sidebar, etc.) is left alone.
- **Self-healing.** A `MutationObserver` (filtered to the `inert` attribute) re-applies the fix if Filament re-adds `inert` on a later update.
- The modal's own Alpine focus-trap still keeps focus inside the modal, and its overlay still blocks background clicks, so only the modal itself is re-enabled.

## Why not the "obvious" fixes

Both were implemented and **rejected after in-browser verification**:

| Attempt | Result |
|---|---|
| **CSS stacking-context neutralization** (`opacity:1 / transform:none / filter:none / contain / z-index`) | **No effect.** `inert` is not a CSS property; the modal is `fixed z:40` in the root stacking context with *zero* stacking triggers on any ancestor, yet still un-hittable. |
| **DOM teleport** of `.fi-modal` out of `.fi-main` | Makes it *clickable*, **but** the Livewire component root (`.fi-page`) is nested *inside* `.fi-main-ctn`, so moving the modal out **severs its `wire:model`/`wire:submit`** binding — the action silently no-ops (verified: the template did not apply). |

## Installation

```php
// In your Filament PanelProvider:
use MicroweberPackages\FilamentModalTeleport\ModalTeleportPlugin;

$panel->plugin(ModalTeleportPlugin::make());
```

The plugin registers a `PanelsRenderHook::BODY_END` hook (in `register()`, so it is flushed to Filament's render-hook registry before the page renders) that injects the JS automatically.

## Supported scenarios

- ✅ Centered modals · SlideOver modals · nested / stacked modals (N levels)
- ✅ Filament v3 / v4 / v5 (`.fi-modal` / `.fi-main` and `inert` focus-trap are core)
- ✅ Any Filament project (not Microweber-specific — the fix is not hardcoded to `.fi-main`; it targets whatever inerted ancestor contains an open modal)

## License

MIT
