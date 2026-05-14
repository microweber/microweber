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

---

## Template / live-edit mode switching

A staff user toggles between four distinct modes of viewing the same page:

| Mode | URL pattern | What it shows | Audience |
|---|---|---|---|
| **Public view** | `/{page-slug}` | the page as a public visitor sees it; no edit chrome, no toolbar | end visitors (default) |
| **Preview view** | `/{page-slug}?preview=y` | same DOM as public view + a "Return to admin" banner; useful for sharing draft content | staff sharing a preview link |
| **Live edit** | `/{page-slug}?editmode=y` | edit chrome + toolbar + drag handles + module slide-overs | staff editing inline |
| **Layout preview** | `/template/preview-layout?module=...&skin=...&template=...` | renders one module + one skin combination in isolation | staff picking a template/skin from the layout picker |

The `?editmode=y` toggle is a query-string signal the frontend recognises. When present, `iframe-page.blade.php` is the wrapping shell; without it, the public-page Blade renders directly. The Vue toolbar conditionally mounts based on the same signal:

```js
// In packages/frontend-assets/.../live-edit-page-scripts.js
if (location.search.includes('editmode=y')) {
    mountLiveEditToolbar();
    bootCanvasEditor();
}
```

The Admin package's top-nav "Live Edit" button targets `site_url('?editmode=y')` (`Admin\Filament\FilamentAdminPanelProvider` render hook). Direct navigation works too — bookmarking `/blog/some-post?editmode=y` jumps straight into editing that post.

The `preview` mode (`?preview=y`) is owned by the [Content module](/), not LiveEdit — it uses a different toggle so a marketing team can share preview URLs without invoking the editor chrome.

To programmatically detect the current mode inside a custom Vue component:

```js
const isLiveEdit = window.location.search.includes('editmode=y');
const isPreview  = window.location.search.includes('preview=y');
const isPublic   = !isLiveEdit && !isPreview;
```

If your custom module renders differently in edit mode (e.g. hiding pagination so a draft is fully visible), branch on `isLiveEdit`.

---

## Drag-and-drop event handlers (detailed)

The canvas-side drag-drop is built on **moveable.js** integrated by `liveeditmode.js` and `live-edit-page-scripts.js`. The library fires native browser events on the moveable element AND emits its own typed callbacks. Both are usable extension points.

### moveable.js callbacks (preferred)

The Microweber wrapper around moveable.js exposes these per-element callbacks (set on the wrapper, not the underlying moveable instance):

| Callback | When | Payload |
|---|---|---|
| `onDragStart(event)` | user begins dragging | `{ target, clientX, clientY }` |
| `onDrag(event)` | every mousemove during drag | `{ target, beforeTranslate, translate }` |
| `onDragEnd(event)` | user releases | `{ target, lastEvent }` |
| `onResizeStart(event)` | resize handle clicked | `{ target, direction }` |
| `onResize(event)` | every resize step | `{ target, width, height, drag }` |
| `onResizeEnd(event)` | resize completes | `{ target, lastEvent }` |
| `onRotateStart`/`onRotate`/`onRotateEnd` | rotation handle (currently unused on most elements; reserved) | similar shape |

These fire on the moveable wrapper, NOT on the underlying DOM element. Listen via:

```js
// Inside the canvas iframe
const wrapper = window.mw?.app?.moveable;
if (wrapper) {
    wrapper.on('drag', (event) => {
        console.log('dragging', event.target.id, event.translate);
    });
}
```

### Native browser drop events

For drag-from-outside-the-canvas (e.g. dragging a desktop file onto the editor), listen for native `drop` events on the canvas iframe's document:

```js
const canvasDoc = window.mw?.app?.canvas?.getDocument();
canvasDoc?.addEventListener('drop', (e) => {
    if (e.dataTransfer.files.length > 0) {
        // File drop — invoke the image-upload action
        window.parent.dispatchEvent(new CustomEvent('openAddContentAction'));
    }
});
```

The Microweber wrapper doesn't intercept native file drops by default — the +ADD picker's "Upload image" card is the canonical entry point for new media. The above pattern is for custom integrations.

### Sortable / reorder events

Block reordering (different from drag-positioning) uses **SortableJS** internally via the `VisualEditorComponent` Livewire (NOT moveable.js). The Vue toolbar's UNDO button captures a `body.innerHTML` snapshot before each reorder, so undo restores pre-reorder state.

For listening to block reorders specifically:

```php
// In a Livewire component embedded in the canvas
#[On('blockReordered')]
public function whenBlockReordered($oldIndex, $newIndex): void
{
    // Custom side-effect — e.g. log the move to an audit table
}
```

The `blockReordered` Livewire event is the canonical hook; the `VisualEditorComponent` fires it after every successful drag-to-reorder.

---

## Session persistence and auto-save

LiveEdit deliberately does **NOT auto-save**. The design choice: explicit SAVE button click → explicit dispatch via `liveEditSaveCallMountedAction` → explicit form submit. Three reasons:

1. **Predictability** — users always know when their work has hit the database.
2. **Network resilience** — a failed auto-save mid-typing leaves the input in a confusing state (was it saved? was it lost?). The explicit-save model removes the ambiguity.
3. **Multi-edit scenarios** — when an admin has 5 changes typed across 3 modal slide-overs, auto-save would have to decide which to commit first; the user makes that decision via the save-flow specificity ranker (see [Overview → save flow](./#the-save-flow--most-specific-form-wins)).

What IS persisted across requests:

| State | Where | Lifetime |
|---|---|---|
| Current canvas URL | `?url=` query string on `/admin/live-edit?url=...` | URL-scoped (lost on refresh without the param) |
| `$liveEditUrl` Livewire property | the `AdminLiveEditPage` Livewire component state | Livewire-mount-scoped (rehydrated from `?url=` on refresh) |
| Admin session | Laravel session (cookie-backed) | session-lifetime |
| Vue toolbar settings (publish-confirmed flag, etc.) | localStorage keys prefixed `mw-` | until cleared |
| Undo/redo snapshots | in-memory in `UndoRedo.vue` | refresh clears them |

What is **NOT** persisted (and would be if auto-save existed):

- Typed-but-unsaved content in any open modal form.
- Drag-positions applied to the canvas DOM but not yet committed via SAVE.
- Module-settings slide-over field changes.

### Opting into auto-save (custom code)

If your project needs auto-save (e.g. a long-form editor where losing typing is catastrophic), wire a debounced auto-save in your custom Livewire component:

```php
namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Livewire\Component;

class MyAutoSaveEditor extends Component
{
    public string $title = '';
    public string $body  = '';

    public function updatedTitle(): void { $this->autoSave(); }
    public function updatedBody(): void  { $this->autoSave(); }

    // Debounced via wire:model.live.debounce.500ms on the form input
    public function autoSave(): void
    {
        \Modules\Content\Models\Content::updateOrCreate(
            ['id' => $this->contentId],
            ['title' => $this->title, 'content_body' => $this->body],
        );
    }
}
```

The `wire:model.live.debounce.500ms="title"` modifier in your Blade view triggers the `updatedTitle()` hook after 500ms of typing inactivity. The hook persists silently.

This pattern bypasses the standard SAVE button — your custom editor isn't part of the save-flow specificity ranker. Document this clearly for your users: "this surface auto-saves; you don't need to click Save".

### Recovering unsaved work after browser crash

Because LiveEdit doesn't auto-save, browser crashes lose unsaved input. Three mitigations:

1. **Browser-level form recovery** — most modern browsers (Chrome, Firefox, Safari) restore form input on tab restore. Test by killing + reopening the browser; usually 80% effective.
2. **Custom localStorage backup** — implement client-side periodic dumps of in-progress input. Not built into LiveEdit; would be a custom Vue plugin.
3. **Filament's optimistic-update model** — Filament writes through Livewire on every field blur. If your form uses `->live(onBlur: true)` on each field, each blur is effectively a checkpoint.

---

## Mobile LiveEdit considerations

The full drag-and-drop edit experience is **optimised for desktop / large tablet** (≥1024px viewport). On smaller viewports the experience degrades gracefully:

### Viewport breakpoints

| Width | Behaviour |
|---|---|
| ≥1024px | full edit chrome — drag handles, slide-overs, multi-column module settings |
| 768–1023px (tablet) | edit chrome present but compressed — slide-overs become full-screen modals; drag handles smaller but still touchable |
| <768px (phone) | edit chrome present but heavily compressed — many features hide or become single-column; touch interactions for drag/resize work but precision is harder |

The AI-515 safe-area-inset CSS (from the Phase 1 UX work — commit `54fdfeb713`) ensures Live Edit's toolbar respects iPhone notches and home-indicator gesture bars on iOS. See the [AI-515 ship report](/) for details.

### Touch event support

moveable.js's touch-event implementation covers single-finger drag and pinch-zoom. Two known limitations on mobile:

1. **Long-press to drag** — by default moveable.js treats every touchstart as a potential drag, which conflicts with scroll. The Microweber wrapper sets a 300ms long-press delay so the user can scroll-without-dragging by lifting before 300ms.
2. **Multi-select** — phone-touch doesn't support multi-element selection (no Shift+click equivalent). Multi-edit workflows are desktop-only.

### Performance on mobile

The canvas iframe loads the full public-page bundle PLUS the edit chrome on every Live Edit page-load. On a 4G phone, expect ~3-5s initial load. Mitigations:

- Pre-fetch from a CDN if available (Cloudflare, Fastly).
- Lazy-load non-critical Vue toolbar components — the `UndoRedo`, `AddContentButton`, user menu can defer until first interaction.
- Defer the moveable.js initialisation until the first `.element-active` click — drag handles aren't needed until the user selects something.

### Mobile-specific routes

There is **no separate mobile Live Edit route**. The desktop URL pattern works on mobile; the responsive CSS adapts.

For users who need a stripped-down mobile-only edit mode (e.g. quick-fix typos on the go), the recommended pattern is to expose the Content module's standard Filament Edit form (no canvas iframe) at `/admin/content/{id}/edit` — that surface is mobile-friendly by default and skips the heavy drag-drop chrome entirely.

### Known mobile gotchas

| Symptom | Cause | Fix |
|---|---|---|
| Toolbar hidden under notch | viewport-fit=cover not present | AI-515 ships the meta tag on the iframe; if missing, add it manually |
| Tap on toolbar button does nothing | iOS Safari blocking `pointer-events: none` ancestor | check parent element has `pointer-events: auto` |
| Drag handle jitters during scroll | touch-event conflict | rely on the 300ms long-press delay; if more time is needed, see the wrapper's `pressDelay` config |
| Modal close-X tap area too small | regression — should be 44×44px floor per AI-225 | check the WCAG 2.5.5 floor (44×44 px) is applied to the close-X CSS rule |
