# Installation

LiveEdit ships as part of Microweber core. There is **no separate install step** — the package's service providers boot automatically as part of the framework. This page documents what auto-registers, the routes that get loaded, and the sibling packages LiveEdit depends on.

---

## Service providers

Three providers auto-register via `module.json`:

| Provider | What it does |
|---|---|
| `Providers\LiveEditServiceProvider` (309 lines) | Boots Livewire components, registers Filament pages via `FilamentRegistry::registerPage()`, registers `live_edit` and `module_settings` middleware groups, initialises the top-right menu via `LiveEditManager` facade, registers the editor-tool URLs (presets, unlock-package, reset-content, code-editor, template-settings, style-editor, add-content-modal). |
| `Providers\LiveEditRouteServiceProvider` | Loads `routes/web.php`, `routes/api.php`, and `routes/live_edit.php`. |
| `Providers\VisualEditorServiceProvider` | Bootstraps the drag-drop block editor (`VisualEditorComponent`). |

The Filament pages registered into the admin panel:

- `AdminLiveEditPage` (slug `/admin/live-edit`)
- `AdminLiveEditSidebarTemplateSettingsPage`
- `AdminLiveEditSidebarElementStyleEditorPage`
- `ResetContentModuleSettingsPage`, `CodeEditorModuleSettingsPage`, `AddContentModalPage`, `ModulePresetsModuleSettingsPage`, `UnlockPackageModuleSettingsPage`
- `VisualEditorPage`

Plus the Livewire components: `ModuleSettingsComponent`, `ModuleSettingsFormComponent`, `VisualEditorComponent`, `ModuleSettingsItemsEditor*` (4 classes), `LiveEditSidebarAdmin*` (2 classes), `ModulePresetsManager`, `ModuleTemplateSelectComponent`.

---

## Routes

Three route files load:

### `routes/web.php`

| Method | URI | Action |
|---|---|---|
| GET | `/template/preview-layout` | renders a module + skin combination via `preview-layout.layout_render` (used by the layout-picker preview iframe) |
| GET | `/admin/setup-wizard` | the first-install setup wizard form |
| POST | `/admin/install-template` | installs the chosen template from the setup wizard |

### `routes/api.php`

| Method | URI | Action |
|---|---|---|
| GET | `/api/live-edit/get-top-right-menu` | serialised top-right menu JSON (deprecated path; the menu is now served via the LiveEditManager facade) |
| GET | `/api/live-edit/get-website-info` | website metadata for the setup wizard |

### `routes/live_edit.php`

Reserved for future Livewire-mounted endpoints. Currently empty / framework auto-discovery.

The `/admin/live-edit` URL itself is registered by Filament, not by these route files — `AdminLiveEditPage` is a Filament Page that lives on the admin panel.

---

## Middleware groups

LiveEdit registers two named middleware groups in `LiveEditServiceProvider`:

| Group | Members |
|---|---|
| `live_edit` | `admin` middleware + `DispatchServingLiveEdit` event-dispatcher (deprecated, still fires for back-compat) |
| `module_settings` | `admin` middleware + `DispatchServingModuleSettings` event-dispatcher |

Apply to custom routes that need the same admin gating + event hooks as the built-in LiveEdit surface:

```php
Route::middleware('live_edit')->group(function () {
    Route::get('/admin/my-custom-editor', MyEditorController::class);
});
```

---

## Required configuration

There is **no `config/live-edit.php`** at the package level. The visual-editor block-types and feature flags live in `config/visual-editor.php` (57 lines):

```php
return [
    'enabled' => env('VISUAL_EDITOR_ENABLED', true),
    'block_types' => ['heading', 'paragraph', 'image', ...],
    'ui' => [
        'sidebar_width' => 320,
        'block_handle_color' => '#0d6efd',
    ],
    'permissions' => [
        'can_add_blocks' => 'isAdmin',
        'can_reorder'    => 'isAdmin',
        'can_delete'     => 'isAdmin',
    ],
];
```

Override via your project's `config/` directory if you need different defaults.

---

## Dependencies on other packages

| Package | Why LiveEdit needs it |
|---|---|
| **[Admin](/modules/admin/)** | the Filament admin panel that hosts `AdminLiveEditPage`; the top-nav "Live Edit" button render hook; the `is_admin()` gate via the Admin middleware |
| **[Content](/modules/content/)** | the `Content` model + `ContentResource::formArrayCompact()` (used by `generateAction` for the lean create-record form); `content_manager` facade for current-page resolution |
| **Filament** v5 | Pages, Actions, Modals, Forms, Schemas |
| **Livewire** v4 | Mounted-action lifecycle, form submission, the `wire:click` / `wire:submit.prevent` glue |
| **Module** | `ModuleAdmin::registerLiveEditSettingsUrl()` for editor-tool URL registration |
| **Template** | `TemplateManager` for layout-preview rendering, template install in setup wizard |
| **[Media](/)** | the `Media` model for `addImageAction` uploads (writes to `userfiles/media/` disk) |
| **Category** | `CategoryResource` for the +ADD picker's category creation card |
| **Frontend-assets** *(external package)* | the Vue toolbar that dispatches `liveEditSaveCallMountedAction`, the UndoRedo component, the AddContentButton |

If any of these are disabled or missing, LiveEdit fails at boot — these are hard dependencies, not soft ones.

---

## Database

LiveEdit owns **zero migrations** and **zero Eloquent models** of its own. The one model it ships, `Models\ModuleItemSushi`, uses the Sushi memory-backed ORM (no database table). The data that the editor reads/writes lives in the Content, Media, Module, and Option tables owned by sibling packages.

---

## Sanity check after install

```bash
# AdminLiveEditPage route resolves
curl -I http://your-site/admin/live-edit
# Expected: 302 redirect to login (when not authenticated) or 200 (when admin)

# LiveEditManager facade resolves
php artisan tinker --execute='
    echo get_class(\MicroweberPackages\LiveEdit\Facades\LiveEditManager::getFacadeRoot());
'
# Expected: MicroweberPackages\LiveEdit\Services\LiveEditManagerService

# Setup wizard route resolves
curl -I http://your-site/admin/setup-wizard
# Expected: 200 on a fresh install (no admin user); 302 to login otherwise

# Visual-editor config loads
php artisan tinker --execute='dd(config("visual-editor"));'
# Expected: associative array with 'enabled', 'block_types', 'ui', 'permissions'

# Custom events catalogue is reachable in the canvas iframe
# (browser DevTools console, on /admin/live-edit)
# > window.addEventListener('liveEditSaveCallMountedAction', () => console.log('save fired'))
# > // click the toolbar SAVE button
# Expected: 'save fired' logged
```

If `/admin/live-edit` 404s, confirm the `LiveEditServiceProvider` is loaded — `php artisan package:discover --ansi` should pick it up automatically. If `AdminLiveEditPage` shows but the canvas iframe is blank, check the browser DevTools console for JS errors — usually the Vue toolbar's bundle path is misconfigured (see [troubleshooting](./troubleshooting.md#canvas-iframe-loads-but-no-toolbar-buttons-respond)).

---

## Asset build

LiveEdit ships static CSS + JS that the canvas iframe loads. The build is part of the `frontend-assets` package (Vite). Trigger a rebuild after any edit to:

- `packages/frontend-assets/resources/assets/css/scss/liveedit.scss` (and its `@use`-d partials: `drop-indicator`, `handles`, `draggable`, `dialog`, `resizer`, `editor`, `spinner`, `tooltip`)
- `packages/frontend-assets/resources/assets/ui/components/Toolbar/*.vue`
- `packages/frontend-assets/resources/assets/live-edit/live-edit-page-scripts.js`

```bash
cd packages/frontend-assets && npm run build
```

The build copies output to `public/vendor/microweber-packages/frontend-assets/build/`. The Filament panel loads from that path.

There is also a **legacy** liveedit.css source at `packages/frontend-assets-libs/resources/local-libs/css/liveedit.css` — served via `frontend-assets-libs/build.mjs` to `public/vendor/microweber-packages/frontend-assets-libs/css/`. The two source copies are kept in sync by convention (see the AI-513 ship note about the parallel-copy pattern).
