# LiveEdit Package

The LiveEdit package is the **in-place visual editor** — the canvas where admin users edit page content, drag and drop blocks, swap modules, manage module settings, and save back to the database without leaving the public-facing page. It lives under `src/MicroweberPackages/LiveEdit/` (a core package, not a `Modules/` feature module) because the entire admin editing experience flows through it.

> **TL;DR** — LiveEdit is a **two-surface system**. The **admin frame** is a Filament v5 + Livewire v4 page (`AdminLiveEditPage`) that hosts modals, slide-overs, validation, and the +ADD picker. The **canvas iframe** is the actual public page being edited, augmented with drag-drop handles, element-selection styling, and the `mw.app.editor` / `mw.app.canvas` global JS objects. The two surfaces communicate via **`window.dispatchEvent(new CustomEvent('verb', { detail }))`** — a one-way push model where each surface fires named verbs and the other side listens.

---

## What this package owns

| Concern | Where |
|---|---|
| `AdminLiveEditPage` Filament page (the admin frame) | `Filament/Admin/Pages/AdminLiveEditPage.php` (824 lines, 9 Filament actions) |
| The 2054-line `iframe-page.blade.php` admin frame Blade | `resources/views/iframe-page.blade.php` |
| The +ADD picker modal | `resources/views/add-content-modal.blade.php` (210 lines, Alpine search + synonym map) |
| The compact create-record form (`generateAction`) | inside `AdminLiveEditPage` |
| Module-settings slide-over orchestrator (`openModuleSettingsAction`) | inside `AdminLiveEditPage` |
| The save-flow JS that picks the right form across nested iframes | `iframe-page.blade.php` lines 349–494 |
| Draggable modal infrastructure (jQuery UI) | `iframe-page.blade.php` lines 525+ |
| The two-surface `CustomEvent` verb catalogue | dispatchers + listeners across `iframe-page.blade.php` |
| `LiveEditManager` facade for top-right menu + head tags | `Services/LiveEditManagerService.php` + facade |
| Module-settings Filament base class | `Filament/Admin/Pages/Abstract/LiveEditModuleSettings.php` |
| Visual editor block-extraction Livewire component | `Http/Livewire/VisualEditor/VisualEditorComponent.php` |
| Module items-editor Livewire components (4 classes) | `Http/Livewire/ItemsEditor/` |
| Module-presets manager | `Http/Livewire/Presets/ModulePresetsManager.php` |
| Setup wizard route + Blade | `routes/web.php` + `resources/views/setup-wizard.blade.php` |
| Layout-preview route | `routes/web.php` |
| API endpoints for menu + website info | `routes/api.php` |

What this package does **NOT** own:

- The Vue toolbar that hosts the SAVE / UNDO / VIEW / ADD buttons — owned by `packages/frontend-assets/resources/assets/ui/components/Toolbar/`. The toolbar communicates with LiveEdit via the `CustomEvent` verb catalogue.
- The Content model + per-content-type schema — owned by [Content module](/) (`ContentResource::formArrayCompact()` is the compact form spec LiveEdit re-uses).
- The Filament admin panel itself — owned by the [Admin package](/modules/admin/). LiveEdit's `AdminLiveEditPage` is *registered into* that panel via `FilamentRegistry::registerPage()`.
- The mw-modal Livewire generic modal — owned by the Livewire package; LiveEdit uses it for some legacy modals.
- The Media model + upload pipeline — owned by Media module; LiveEdit's `addImageAction` writes through `Media::create([...])`.
- AI text generation — provided by an optional AI module that `generateAction` integrates with.

---

## Architectural fact: two surfaces, one-way push verbs

The entire LiveEdit design hinges on this boundary:

```
┌──────────────────────────────────────────────────────────────────┐
│                       ADMIN FRAME (parent)                       │
│  Filament + Livewire panel @ /admin/live-edit                    │
│                                                                  │
│   Actions / modals / slide-overs / validation / Save flow        │
│                                                                  │
│   Dispatches to canvas:                                          │
│     • liveEditSaveCallMountedAction                              │
│     • liveEditInsertLayoutRequest                                │
│                                                                  │
│   Listens for canvas:                                            │
│     • liveEditAddContentSaved (detail: { url })                  │
│     • liveEditModuleTableActionSaved                             │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │             CANVAS IFRAME (child, same-origin)             │  │
│  │  The actual public page, edited in place                   │  │
│  │                                                            │  │
│  │  Globals: mw.app, mw.app.canvas, mw.app.editor             │  │
│  │  DOM:     .element-active, .moveit-hover,                  │  │
│  │           .mw-sorthandle-parent-outline                    │  │
│  │                                                            │  │
│  │  Dispatches to parent (window.top.dispatchEvent):          │  │
│  │     • liveEditAddContentSaved                              │  │
│  │     • liveEditModuleTableActionSaved                       │  │
│  │                                                            │  │
│  │  Listens for parent:                                       │  │
│  │     • liveEditSaveCallMountedAction                        │  │
│  │     • liveEditInsertLayoutRequest                          │  │
│  └────────────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────────┘
```

Three rules follow:

1. **Adding a new verb requires editing both sides.** Each verb has exactly one dispatcher and one listener. Forget either and the verb fires into nothing — a class of bug that's invisible until QA exercises the workflow.
2. **No request-reply.** The push model means there's no `await dispatch(verb)` pattern. If you need a response, the receiving side has to dispatch a follow-up verb back.
3. **State is duplicated across surfaces.** The currently-selected element lives in the canvas's JS state. The currently-mounted action lives in Livewire's component state. Keeping them in sync is the developer's job — wrong state → wrong save target.

---

## The +ADD picker — Alpine search + synonym map

When the user clicks the toolbar's +ADD button, `AdminLiveEditPage::addContentAction()` mounts a Filament action that renders `add-content-modal.blade.php`. That modal is **plain Alpine + Blade** — no Livewire, no server round-trip on every keystroke:

- **Autofocused search input** with placeholder "What do you want to add? (page, post, image…)"
- **Synonym map** per card so users searching "article" find Post, "block" finds the layout inserter, "photo" finds image upload (lines 130–137 of `add-content-modal.blade.php`)
- **Keyboard navigation** (Arrow / Enter) cycles visible cards and activates the first match
- **Static empty-state copy** ("No content types found.") with inline `style="display: none;"` default — no `x-cloak` flash (the project has no global `[x-cloak]` rule)

Cards route in one of two ways:

- **Standard Filament action** — `wire:click="replaceMountedAction('addPageAction')"` swaps the picker for the next form (a compact create-record dialog).
- **Direct JS dispatch** — when the card has `js_dispatch`, the click fires `window.dispatchEvent(new CustomEvent(...))` and immediately `$wire.unmountAction()`s the picker. This is how the "Add a block to this page" card opens the canvas-side layout inserter without a server round-trip.

---

## The save flow — most-specific form wins

LiveEdit's biggest design subtlety. When the user clicks SAVE in the Vue toolbar, the toolbar fires `liveEditSaveCallMountedAction`. The handler (lines 349–494 of `iframe-page.blade.php`):

1. Collects every `<form wire:submit.prevent="...">` from the parent document AND from same-origin iframes.
2. Ranks them by specificity:
   - **Inner before outer**: iframe forms beat parent forms.
   - **Action type precedence**: `callMountedTableBulkAction` (4) > `callMountedTableAction` (3) > `callMountedFormComponentAction` (2) > `callMountedAction` (1).
3. Calls `requestSubmit()` on the highest-ranked form.

Why this complexity? In a typical edit session there can be **three concurrent forms** on screen:

- The outer module-settings modal form (e.g. "Edit Posts" → list of posts).
- An inner table-row action form (e.g. "Edit Post #42" opened from inside the list).
- A nested form-component action (e.g. a date picker's confirm dialog).

A naïve "submit the visible form" approach picks the wrong one and saves the wrong data. The specificity ranking guarantees the **innermost user-intent form** is the save target.

If the picked form has required fields with no value, the handler shows inline title-validation (lines 480–481) and reveals the validation-error UI 450ms after submit (lines 490–492) — so the user sees what failed.

---

## Surfaces

| Surface | Where | Audience |
|---|---|---|
| `/admin/live-edit` Filament page | `AdminLiveEditPage` | staff |
| Canvas iframe with edit chrome | `iframe-page.blade.php` | staff |
| +ADD picker modal | `add-content-modal.blade.php` | staff |
| Compact create-record dialog | `generateAction` in `AdminLiveEditPage` | staff |
| Module-settings slide-over | `openModuleSettingsAction` in `AdminLiveEditPage` | staff |
| Visual editor (drag-drop blocks) | `VisualEditorComponent` Livewire | staff |
| Module items editor (row tables) | `ModuleSettingsItemsEditorComponent` family | staff |
| Setup wizard | `setup-wizard.blade.php` | staff (first-install only) |
| Layout preview | `/template/preview-layout` route | staff |
| API endpoints | `/api/live-edit/get-top-right-menu`, `/get-website-info` | top-nav menu loader, setup wizard |

---

## Where to next

- [Installation](./installation.md) — provider boot, route + Livewire component registration, sibling-package dependencies.
- [Usage](./usage.md) — entering Live Edit mode, the +ADD picker, the save flow, module-settings slide-overs, drag-drop, undo/redo, AI generation.
- [API](./api.md) — `AdminLiveEditPage` (9 Filament actions), `LiveEditManager` facade, the `CustomEvent` verb catalogue, the LiveEditModuleSettings abstract, Livewire components.
- [Examples](./examples.md) — adding a custom +ADD picker card, custom module-settings page, listening for `liveEditAddContentSaved`, a fully bespoke save handler.
- [Troubleshooting](./troubleshooting.md) — save submits the wrong form, custom event fires into nothing (missing listener), modal close-on-backdrop loses unsaved input, drag-drop not working, AI generation returns nothing.
