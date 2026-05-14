# Usage

How the LiveEdit package is consumed in practice: entering live-edit mode, the +ADD picker, the save flow, module-settings slide-overs, drag-drop, undo/redo, image uploads, AI generation, and the cross-surface event verbs.

---

## Entering live-edit mode

A staff user clicks the top-nav "Live Edit" button (rendered into the admin panel by the [Admin package](/modules/admin/) via the `GLOBAL_SEARCH_AFTER` render hook). The button targets `site_url('?editmode=y')` — a query-string toggle that the front-end recognises and switches into edit mode.

In edit mode, the canvas iframe loads three things on top of the normal public page:

1. **The Vue toolbar** (from `packages/frontend-assets`) — pinned to the top, hosts SAVE / UNDO / VIEW / +ADD / user-menu.
2. **The element-selection chrome** — `.element-active` outline on click, `.moveit-hover` outline on hover, `.mw-sorthandle-parent-outline` on the containing block.
3. **The mw.app.editor / mw.app.canvas globals** — JS APIs the iframe scripts hook into for save / select / insert.

The admin frame around the iframe is `AdminLiveEditPage` at `/admin/live-edit?url=<canvas-url>`. The `?url=` parameter tells the admin frame which canvas page to host — Livewire persists it as `$this->liveEditUrl` so modals that need the current canvas context can resolve it.

---

## The +ADD picker workflow

Click +ADD on the toolbar. The Vue button fires a Livewire event that mounts the `addContentAction` modal in `AdminLiveEditPage`. The modal renders `add-content-modal.blade.php` — a card grid with one card per registered "add" action:

| Card | Action key | What it does |
|---|---|---|
| New page | `addPageAction` | compact create-record form for `content_type = page` |
| New post | `addPostAction` | compact create-record form for `content_type = post` |
| New category | `addCategoryAction` | compact category-create form |
| New product | `addProductAction` | compact create-record form for `content_type = product` |
| Upload image | `addImageAction` | Filament `FileUpload` drag-drop modal |
| Add a block to this page | `addToCurrentPageAction` | dispatches `liveEditInsertLayoutRequest` to the canvas — opens the layout picker without a server round-trip |

### Search + synonyms

The picker has an Alpine-only search input. Each card's haystack is `lowercase(title + description + synonyms)`. The synonym map (defined in `add-content-modal.blade.php` lines 130–137):

```js
const SYNONYMS = {
    addPageAction:           'about services contact landing static homepage',
    addPostAction:           'article news update story news blog entry',
    addCategoryAction:       'folder group section tag taxonomy',
    addProductAction:        'shop item store buy sell merchandise sku',
    addImageAction:          'photo picture banner graphic logo upload media gallery',
    addToCurrentPageAction:  'block layout text image button heading paragraph row column section insert',
};
```

A user typing "article" finds Post. Typing "photo" finds image upload. Typing "block" finds the layout inserter.

### Adding a custom +ADD card

Override `AdminLiveEditPage::addContentAction()` (or extend it in your project provider) and add a card to the `actions` array. See [examples #1](./examples.md#1-add-a-custom-add-picker-card) for the full pattern.

---

## The save flow — what happens when SAVE is clicked

The Vue toolbar's SAVE button (in `packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue`) does NOT directly call a server endpoint. It dispatches:

```js
window.dispatchEvent(new CustomEvent('liveEditSaveCallMountedAction'));
```

The admin frame's `iframe-page.blade.php` (lines 349–494) listens. The handler:

1. Collects every `<form wire:submit.prevent="...">` from the parent document AND from same-origin iframes (e.g. a module-settings iframe that's currently open).
2. Ranks forms by **specificity**:
   - Forms inside iframes beat forms in the parent document (innermost intent wins).
   - Within the same scope, action type precedence is `callMountedTableBulkAction > callMountedTableAction > callMountedFormComponentAction > callMountedAction`.
3. Calls `requestSubmit()` on the most-specific form.
4. If the form has required fields with no value, fires inline-title validation styling.
5. After 450ms, reveals Filament's standard validation-error UI if any field rejected.

This complex routing exists because a typical edit session has **three concurrent forms** on screen: the outer module-settings slide-over, an inner table-row action form, and a nested form-component action. A naïve "submit the visible form" picks the wrong one. The specificity ranking guarantees the **innermost user-intent form** is the save target.

---

## Module-settings slide-overs

Clicking a module on the canvas opens its settings panel as a Filament slide-over (NOT a centered modal — the slide-over keeps the canvas visible behind it so the user can see their context).

The flow:

1. Canvas-side JS dispatches `openModuleSettingsAction` with a `detail: { component, icon, label, ... }` payload.
2. Admin frame listens (lines 213–225 of `iframe-page.blade.php`):
   ```js
   window.addEventListener('openModuleSettingsAction', (e) => {
       Livewire.dispatch('openModuleSettings', e.detail);
   });
   ```
3. `AdminLiveEditPage::openModuleSettingsAction()` resolves the `component` class:
   - If it's a Filament Page with a `getUrl()` method → renders in a same-origin iframe (cleanest sandboxing for legacy iframe-based settings).
   - Otherwise → embeds the Livewire component directly.
4. The slide-over mounts with class `mw-module-settings-live-edit-modal` (used by the draggable infrastructure to make it user-positionable).

The slide-over's SAVE button is the same toolbar SAVE — when clicked, the save-flow specificity ranker picks the slide-over's form, not the outer admin frame's.

---

## The compact create-record dialog (`generateAction`)

When the user picks "New page" / "New post" / etc. from the +ADD picker, `generateAction` mounts a Filament form with `ContentResource::formArrayCompact()` (a lean version of the full ContentResource form — title + body + published + parent + pricing-if-product, NO SEO / Tags / Menus tabs).

The modal:

- Is **centered** (`MaxWidth::ThreeExtraLarge`, 768px) — narrower than the standard 5xl but wider than a slide-over, keeps canvas visible.
- Has a green Save button (matches the toolbar SAVE colour).
- Has **sticky footer** (Save/Cancel stay visible when the form scrolls).
- **Cannot be closed by clicking the backdrop or pressing Escape** — `closeModalByClickingAway(false)` + `closeModalByEscaping(false)`. Stops accidental loss of typed input.
- Has a secondary footer action: **"Show all options"** — opens the full admin ContentResource form in a new tab, carrying the typed title forward via the URL query string (the `mw-open-in-admin-btn` JS hook).

On submit, the action closure:

1. Defaults the new content's `parent` to the current canvas page id (resolved via `resolveCurrentLiveEditPageId()`).
2. Creates the Content row.
3. **For new posts/pages with empty body**, injects placeholder HTML:
   ```html
   <p class="mw-novice-body-placeholder" ...>Click here to start writing...</p>
   ```
   so the freshly-created canvas is clickable instead of being a zero-height void. (NOVICE #11 from task-2026-05-13-899d57.)
4. Dispatches `liveEditAddContentSaved` with `{ url: <new-content-url> }`. The canvas listener navigates to that URL.
5. Surfaces a persistent toast with an "Edit details" action that links to the full admin form.

---

## Image upload (`addImageAction`)

Clicking the "Upload image" card opens a Filament `FileUpload` modal with:

- Drag-drop area + click-to-browse fallback
- Multi-file upload + reorder + image editor
- Stores files to `public/userfiles/media/` on the `public` disk
- Creates `Media` rows with `media_type = 'picture'` and `rel_id` / `rel_type` linking to the current content
- Success notification per file count
- Secondary footer action: **"Browse Media Library"** — opens in a new tab (preserves the upload-in-progress context per NOVICE #8)

The uploaded images are immediately available in the canvas via the standard Microweber image-picker once the modal closes.

---

## AI text generation

If the AI module is installed, the compact create-record form's Title and Body fields each gain a small "Generate" action button. Clicking it:

1. Reads any nearby context (existing typed text, parent page title).
2. Sends a prompt to the configured AI provider via the AI module.
3. Streams the response back into the field.
4. The user can refine before saving.

The AI module is optional — when not installed, the Generate action buttons are hidden (defensive feature-detection in the form schema). See the AI module's docs for provider config.

---

## Drag and drop

The canvas-side scripts (`live-edit-page-scripts.js`, `liveeditmode.js`) integrate **moveable.js** to make every editable element draggable, resizable, and reorderable. The CSS hooks:

- `.moveit-hover` — applied on mouseenter; 1px dashed gray outline (per AI-513 distinct-from-selected styling).
- `.element-active` — applied on click; 2px solid `#0d6efd` outline (Bootstrap primary blue, per AI-513).
- `.mw-sorthandle-parent-outline` — applied to the container of the selected element; 1px dashed translucent blue.
- `.element-active:focus-visible` — keyboard focus indicator with `outline-offset: 4px` (double-ring effect on top of the selection outline).

The drag handles emerge on hover/select; visible drag handles are `.mw-sorthandle-*` classes from `liveedit.css`. When the user drags an element to a new position, the drop indicator appears as a horizontal/vertical line (the `drop-indicator` SCSS partial).

To freeze drag (e.g. while editing inside a contenteditable element), the canvas adds `body:has(.element[contenteditable="true"])` styles in `liveedit.scss` that hide the moveable controls.

---

## Undo / Redo

The toolbar's UNDO and REDO buttons are Vue components (`packages/frontend-assets/resources/assets/ui/components/Toolbar/UndoRedo.vue`). They work by **snapshotting `canvas.body.innerHTML`** on each save:

1. Before save: snapshot the current DOM state.
2. On undo: restore the prior snapshot, re-save.
3. On redo: re-apply the snapshot that was active before the undo.

Implications:

- Undo is **DOM-level**, not action-level. Reverting "I added a paragraph then a heading" with one undo restores the state *before* the paragraph — not just before the heading.
- Snapshots are session-scoped (in-memory). Refreshing the page clears history.
- Cross-session undo (e.g. revert yesterday's publish) requires Content-module-level versioning, not LiveEdit's in-memory ring.

---

## Cross-surface event verb catalogue

The complete inventory of named CustomEvents between admin frame and canvas iframe:

### Parent dispatches → Canvas listens

| Verb | When | Payload |
|---|---|---|
| `liveEditSaveCallMountedAction` | toolbar SAVE click | (none) |
| `liveEditInsertLayoutRequest` | "Add a block" picker card click | (none — triggers in-canvas dialog) |

### Canvas dispatches → Parent listens

| Verb | When | Payload |
|---|---|---|
| `liveEditAddContentSaved` | after generateAction creates new content | `{ url: <new-content-permalink> }` |
| `liveEditModuleTableActionSaved` | after a table action inside a module-settings panel | `{ type: 'posts' | 'content' | 'shop/products' }` |

### Parent internal (Livewire event bus)

| Verb | When | Payload |
|---|---|---|
| `openAddContentAction` | Vue toolbar +ADD click | (none) |
| `openModuleSettingsAction` | canvas-side module click | `{ component, icon, label, ... }` |
| `closeFilamentSlideOver` | any "close" trigger | (none) |
| `liveEditMountedActionValidationFailed` | save-flow detected validation errors | (none — UI signal) |

Adding a new verb means editing **both** the dispatcher side and the listener side. Forgetting either yields a verb that fires into nothing — a silent failure mode that's easy to ship by accident.

---

## Setup wizard

On a fresh install (no admin user exists yet), navigating to `/admin/setup-wizard` shows the onboarding flow:

1. Pick a template from the gallery.
2. POST `/admin/install-template` installs it (downloads + extracts + activates).
3. Create the initial admin user.
4. Redirect to `/admin/live-edit` for the first-edit experience.

The wizard's metadata (template list, website-info defaults) comes from `/api/live-edit/get-website-info`. The wizard route is only reachable when no admin user exists — the Admin middleware's first-time-setup escape hatch (documented in the [Admin package](/modules/admin/installation#admin-url-prefix)) is what makes it accessible unauthenticated.
