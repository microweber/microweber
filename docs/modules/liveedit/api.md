# API Reference

Class, method, route, action, and `CustomEvent` verb signatures for the LiveEdit package.

---

## AdminLiveEditPage

`MicroweberPackages\LiveEdit\Filament\Admin\Pages\AdminLiveEditPage` (824 lines). The main Filament admin page. Slug `/admin/live-edit`. View `microweber-live-edit::iframe-page`. Layout `filament-panels::components.layout.live-edit`.

### Livewire properties

| Property | Type | Purpose |
|---|---|---|
| `$liveEditUrl` | `?string` | the URL of the canvas page currently being edited; captured from the `?url=` query string on mount; persisted across action mounts so modals can resolve the canvas context |

### `mount(): void`

Captures `request()->query('url')` into `$this->liveEditUrl`.

### `render(): \Illuminate\View\View`

Returns `view('microweber-live-edit::iframe-page')->layout('filament-panels::components.layout.live-edit')`.

### Filament actions

The page exposes 9 Filament actions:

#### `addContentAction(): \Filament\Actions\Action`

The +ADD picker entry point. Returns a `Filament\Actions\Action` configured with:

- Centered modal (`MaxWidth::TwoExtraLarge`).
- Class `mw-content-picker-modal`.
- Renders `add-content-modal.blade.php` with an `actions: array` of card definitions.
- Each card: `name`, `label`, `description`, `icon`, optional `js_dispatch` (for direct CustomEvent-based cards), and a `Pick this if` / `Skip this for now` decision-rule string (NOVICE #5).

The cards' `wire:click` targets `replaceMountedAction('actionName')` — Filament swaps out the picker for the named action's form when the user picks a card.

#### `addPageAction()` / `addPostAction()` / `addCategoryAction()` / `addProductAction()`

Each returns an Action that delegates to `generateAction('addPageAction', 'page')` etc. The second argument is the `content_type`.

#### `addImageAction(): \Filament\Actions\Action`

Returns an Action that opens a `FileUpload` modal. Configuration:

```php
FileUpload::make('files')
    ->multiple()
    ->image()
    ->imageEditor()
    ->reorderable()
    ->directory('userfiles/media')
    ->disk('public')
```

Action closure on submit:

```php
foreach ($data['files'] as $path) {
    Media::create([
        'filename'   => Storage::disk('public')->url($path),
        'media_type' => 'picture',
        'rel_id'     => $this->resolveCurrentLiveEditPageId(),
        'rel_type'   => 'content',
    ]);
}
```

Secondary footer action: **"Browse Media Library"** opens `admin/media` in a new tab (`openUrlInNewTab(true)`).

#### `addToCurrentPageAction(): \Filament\Actions\Action`

Returns an Action used **only as a picker-card spec** — the card's `js_dispatch: 'liveEditInsertLayoutRequest'` key short-circuits the Filament lifecycle. When clicked, the picker's Blade fires:

```js
window.dispatchEvent(new CustomEvent('liveEditInsertLayoutRequest'));
$wire.unmountAction();
```

The canvas listener (lines 239–246 of `iframe-page.blade.php`) catches the verb and dispatches `mw.app.editor.dispatch('insertLayoutRequest')` to open the in-canvas layout picker.

#### `openModuleSettingsAction(): \Filament\Actions\Action`

Mounts when the canvas dispatches `openModuleSettingsAction` with detail data. Resolves the `component` class from the payload:

- If a Filament Page with `getUrl()` → renders in a same-origin iframe at that URL.
- Otherwise → embeds the Livewire component directly via `Livewire::mount()`.

Slide-over layout (not centered). Class `mw-module-settings-live-edit-modal` enables draggable behaviour.

#### `generateAction(string $actionName, string $contentType): \Filament\Actions\Action`

The compact create-record dialog. Returns an Action configured with:

- `form([ContentResource::formArrayCompact()])` — title + body + published + parent + pricing-if-product
- `modalWidth(MaxWidth::ThreeExtraLarge)` (768px)
- `modalSubmitActionLabel('Save')` with `color('success')`
- `modalCancelActionLabel('Cancel')` *(not `modalCancelAction(false)` — that's a different Filament API per the AI-307 follow-up lesson)*
- `closeModalByClickingAway(false)` + `closeModalByEscaping(false)` — no accidental close
- `extraModalFooterActions([Action::make('showAllOptions')->openUrlInNewTab()])` — links to full admin form with title carry-forward

Action closure:

1. Defaults `parent` to `$this->resolveCurrentLiveEditPageId()` if not set.
2. Sets `content_type` on the new row.
3. Creates the Content model.
4. **NOVICE #11**: if `content_type` is `post` or `page` and `content_body` is empty, injects the `<p class="mw-novice-body-placeholder">` placeholder.
5. Dispatches `liveEditAddContentSaved` with `{ url: $newContent->link() }`.
6. Surfaces a Notification with "Edit details" action linking to the full admin form.

#### `resolveCurrentLiveEditPageId(): int`

Resolves the current canvas page's content id from `$this->liveEditUrl` via `app('content_manager')`. Falls back to `homepage()` if resolution fails. Returns `int` (never null — homepage is guaranteed to exist on any working install).

---

## LiveEditManager (facade)

`MicroweberPackages\LiveEdit\Facades\LiveEditManager` → `MicroweberPackages\LiveEdit\Services\LiveEditManagerService`. Bound as singleton in `LiveEditServiceProvider`.

### `getTopRightMenu(): array`

Returns the serialised top-right menu (used by the Vue toolbar's user menu). Composed from the `top_right_menu` array initialised in the service constructor + any `HasLiveEditMenus::registerTopRightMenuItem()` registrations.

### `headTags(): string`

Returns the concatenated `<style>` + `<script>` + custom-tag HTML to inject into the canvas iframe's `<head>`. Combines:

- Styles registered via `LiveEditManager::addStyle($url)`.
- Scripts registered via `LiveEditManager::addScript($url)`.
- Custom tags registered via `LiveEditManager::addCustomHeadTag($rawHtml)`.

(Inherits from `HasScriptsAndStylesTrait` — the same shape as `AdminManager`.)

### `addStyle(string $url, ?string $key = null): void`

Register a stylesheet for canvas-iframe `<head>` injection.

### `addScript(string $url, ?string $key = null): void`

Register a script for canvas-iframe `<head>` injection.

### `addCustomHeadTag(string $rawHtml): void`

Register arbitrary raw HTML for canvas-iframe `<head>` injection.

---

## LiveEditModuleSettings (abstract)

`MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings`. Base class for module-settings Filament pages. Subclasses ship in `Modules/<X>/Filament/Admin/Pages/<X>SettingsPage.php` to provide per-module configuration UI.

### Properties to override

| Property | Type | Purpose |
|---|---|---|
| `$moduleId` | string | passed from the canvas via the `openModuleSettingsAction` event detail; used to scope option storage |
| `$navigationGroup` | string | unused for module settings (they're not in the sidebar) |

### Methods to implement

```php
abstract protected function getFormSchema(): array;
```

Same shape as `AdminSettingsPage::getFormSchema()` — return Filament form components. The abstract handles load/save via the Option service scoped to `$moduleId`.

### Related abstract

`LiveEditModuleSettingsTable` — base for module-settings pages that need a table inside (e.g. "Edit Posts" with a list of posts). Provides table-action plumbing that integrates with the LiveEdit save-flow specificity ranker.

---

## Livewire components

### `VisualEditorComponent`

`Http\Livewire\VisualEditor\VisualEditorComponent`. Drag-drop block editor.

Public properties:

| Property | Purpose |
|---|---|
| `$blocks` | array of block descriptors parsed from the current content's HTML body |
| `$selectedBlockId` | which block is currently active |
| `$contentId` | the Content row being edited |

Public methods (Livewire listeners):

| Method | Listener | Effect |
|---|---|---|
| `blockReordered($oldIndex, $newIndex)` | `blockReordered` | reorders `$blocks` array; re-serialises to HTML on save |
| `blockSelected($blockId)` | `blockSelected` | sets `$selectedBlockId` |
| `blockContentUpdated($blockId, $newContent)` | `blockContentUpdated` | updates one block's HTML |
| `blockDeleted($blockId)` | `blockDeleted` | removes from `$blocks` |
| `blockDuplicated($blockId)` | `blockDuplicated` | clones a block |
| `dragStarted()` / `dragEnded()` | drag lifecycle | UI state flag |
| `refreshBlocks()` | `refreshBlocks` | re-parses from Content row |

Helper: `extractBlocksFromHtml(string $html): array` — parses the content body into a block array based on `config('visual-editor.block_types')`.

### `ModuleSettingsItemsEditorComponent`

`Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorComponent`. Row/item table editor used by modules that manage their own item list (e.g. Carousel slides, Accordion items, Tabs).

Four classes in this family:

- `AbstractModuleSettingsEditorComponent` — base
- `ModuleSettingsItemsEditorComponent` — main editor
- `ModuleSettingsItemsEditorListComponent` — list display
- `ModuleSettingsItemsEditorEditItemComponent` — single-row edit

### Sidebar admin components

`Http\Livewire\LiveEditSidebarAdmin\LiveEditSidebarAdminComponent` + `LiveEditSidebarAdminModulesListComponent`. Render the left sidebar inside Live Edit (modules list + admin shortcuts).

### `ModulePresetsManager`

`Http\Livewire\Presets\ModulePresetsManager`. Manages "preset" configurations per module — admins save a module's current config + skin as a named preset, recall it on other module instances later.

---

## HTTP routes

### `routes/web.php`

| Method | URI | Action | Notes |
|---|---|---|---|
| GET | `/template/preview-layout` | renders `preview-layout.layout_render` | module + skin + template preview iframe; used by layout picker |
| GET | `/admin/setup-wizard` | renders `setup-wizard.blade.php` | first-install onboarding |
| POST | `/admin/install-template` | installs chosen template | from setup wizard |

### `routes/api.php`

| Method | URI | Action | Notes |
|---|---|---|---|
| GET | `/api/live-edit/get-top-right-menu` | returns serialised menu JSON | **deprecated** — menu now served via `LiveEditManager::getTopRightMenu()` directly |
| GET | `/api/live-edit/get-website-info` | website metadata JSON | setup wizard data source |

### Filament-mounted routes

Every URL under `/admin/live-edit*` is registered by Filament based on the Pages registered into the admin panel. The primary one is `AdminLiveEditPage` at `/admin/live-edit`. Editor-tool URLs like `/admin/code-editor-module-settings` come from the `ModuleAdmin::registerLiveEditSettingsUrl()` calls in `LiveEditServiceProvider`.

---

## CustomEvent verb catalogue

The complete set of named verbs that flow between admin frame and canvas iframe.

### Admin → Canvas (parent → child)

| Verb | Dispatcher | Listener | Payload |
|---|---|---|---|
| `liveEditSaveCallMountedAction` | Vue toolbar SAVE button | `iframe-page.blade.php` (lines 349–494) | (none) |
| `liveEditInsertLayoutRequest` | +ADD picker "Add a block" card | `iframe-page.blade.php` (lines 239–246) | (none) |

### Canvas → Admin (child → parent)

| Verb | Dispatcher | Listener | Payload |
|---|---|---|---|
| `liveEditAddContentSaved` | `generateAction` after Content::create() | `iframe-page.blade.php` (lines 256–280) | `{ url: string }` |
| `liveEditModuleTableActionSaved` | inner module-settings table action after save | `iframe-page.blade.php` (lines 286–340) | `{ type: 'posts' \| 'content' \| 'shop/products' }` |

### Admin internal (Livewire event bus, not CustomEvent)

| Verb | Dispatcher | Listener | Payload |
|---|---|---|---|
| `openAddContentAction` | Vue toolbar +ADD click | `AdminLiveEditPage` | (none) |
| `openModuleSettingsAction` | canvas-side module click | `AdminLiveEditPage` | `{ component, icon, label, ... }` |
| `closeFilamentSlideOver` | any "close" trigger | `AdminLiveEditPage` | (none) |
| `liveEditMountedActionValidationFailed` | save-flow detected validation errors | UI signal — any visual handler | (none) |

**Rule of thumb**: adding a new verb means editing **both** the dispatcher side and the listener side. Forgetting either is a silent failure mode.

---

## Events (Laravel-level)

| Event | Status | Notes |
|---|---|---|
| `Events\ServingLiveEdit` | **deprecated, empty class** | Dispatched by `DispatchServingLiveEdit` middleware. Listeners can subscribe but the constructor takes no payload — use as a "we're inside a LiveEdit request" marker. |
| `Events\ServingModuleSettings` | active | Dispatched by `DispatchServingModuleSettings` middleware. Same pattern as ServingLiveEdit but scoped to module-settings requests. |

Neither event is widely listened to today. Use as future-extension hooks.

---

## DOM contract for canvas-side scripts

External scripts that integrate with the canvas iframe must respect:

| Class | Meaning |
|---|---|
| `.element-active` | element is currently selected — outlined in 2px solid #0d6efd (AI-513) |
| `.moveit-hover` | element is hovered — 1px dashed #6b7280 |
| `.mw-sorthandle-parent-outline` | the parent container of the selected element — 1px dashed translucent blue |
| `.mw-novice-body-placeholder` | NOVICE #11 placeholder injected into empty new-post/page bodies — `<p>Click here to start writing…</p>` |
| `.mw-content-form-modal` | applied to the compact create-record modal — drag-handle CSS selector |
| `.mw-module-settings-live-edit-modal` | applied to module-settings slide-overs — drag-handle CSS selector |
| `.mw-content-picker-modal` | applied to the +ADD picker — drag-handle CSS selector |
| `.mw-open-in-admin-btn` | JS-hook class on the "Show all options" footer button — triggers title carry-forward |

---

## Tests

`src/MicroweberPackages/LiveEdit/tests/`:

| File | Coverage |
|---|---|
| `LiveEditLivewireComponentsAccessTest.php` | mount + render tests for each Livewire component |
| `LiveEditTopRightMenuTest.php` | menu serialization via the legacy API endpoint |
| `LiveEditSaveContentApiTest.php` | end-to-end save/publish flow |
| `ModuleSettingsItemsEditorTest.php` | items editor CRUD operations |
| `VisualEditor/VisualEditorComponentTest.php` | block extraction from HTML |

Run with:

```bash
./vendor/bin/phpunit src/MicroweberPackages/LiveEdit/tests
```

Coverage is significant for backend logic but **does NOT cover the CustomEvent verb catalogue** — those are JS-level integrations that need browser-based E2E tests (Cypress, Playwright). The shipped E2E coverage is in the project's separate `tests/Browser/` directory if present.
