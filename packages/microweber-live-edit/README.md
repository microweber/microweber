# Live Edit

The in-browser visual page editor for Microweber — the Filament/Livewire admin surface that powers drag-and-drop editing, module settings, element style editing, layout insertion and the Live Edit toolbar.

Part of the [Microweber](https://microweber.com) CMS.

## Features

- **`LiveEditManager` facade** — registers the scripts/styles and custom head tags that the Live Edit canvas needs, and builds the toolbar menus (`top_right_menu`).
- **Filament admin pages** — the Live Edit canvas page plus the editor-tool modals (add content, code editor, reset content, module presets, element style editor, template settings, unlock package), registered through `FilamentRegistry`.
- **Livewire components** — module template picker, module items editor (list / edit-item), Live Edit sidebar, module presets manager and the `filament-mw-dialog` modal.
- **Route middleware groups** — `live_edit` and `module_settings`, which dispatch the `ServingLiveEdit` / `ServingModuleSettings` events.
- **Preview + API routes** — universal layout-skin preview (`/template/preview-layout`), the setup wizard, and the `api/live-edit/*` endpoints used by the toolbar and page-settings UI.
- **Quick-settings kit** — a config-driven JS framework (`quick-settings-kit.js` + `quick-settings-configs.js`) served from `public/live-edit-quick-settings`.

## Installation

```bash
composer require microweber-packages/microweber-live-edit
```

The service provider (`MicroweberPackages\LiveEdit\Providers\LiveEditServiceProvider`) is registered by the Microweber core. It binds the `live_edit_manager` singleton, registers the Live Edit Livewire components and Filament pages, and loads the `web`, `api` and `live_edit` routes.

> This package is deeply coupled to the Microweber CMS (templates, modules, options, Filament admin panel). It is not a standalone drop-in for a generic Laravel app.

## Usage

### LiveEditManager facade

The manager owns the scripts, styles and custom head tags injected into the Live Edit canvas, plus the toolbar menus. It mixes in `HasScriptsAndStylesTrait` and `HasLiveEditMenus`.

```php
use MicroweberPackages\LiveEdit\Facades\LiveEditManager;

// Register assets for the Live Edit canvas
LiveEditManager::addScript('my-plugin', asset('js/my-plugin.js'));
LiveEditManager::addStyle('my-plugin-css', asset('css/my-plugin.css'));
LiveEditManager::addCustomHeadTag('<meta name="x-live-edit" content="1">');

// Render the combined head markup (styles + scripts + custom head tags)
echo LiveEditManager::headTags();

// Remove a previously registered asset
LiveEditManager::removeScript('my-plugin');
```

### Toolbar menus

The top-right toolbar menu is a `LiveEditMenu` (Knp Menu) instance keyed `top_right_menu`:

```php
use MicroweberPackages\LiveEdit\Facades\LiveEditManager;

$menu = LiveEditManager::getMenuInstance('top_right_menu');

$menu->addChild('My Tool', [
    'attributes' => [
        'id'   => 'js-my-tool-link',
        'href' => admin_url('my-tool'),
        'icon' => '<svg ...></svg>',
    ],
]);

// Items carry an orderNumber extra used for sorting
$menu->menuItems->getChild('My Tool')->setExtra('orderNumber', 20);

// Retrieve the sorted children for rendering
$items = LiveEditManager::getMenu('top_right_menu');
```

### Route middleware

The provider registers two middleware groups you can attach to routes that should boot the Live Edit / module-settings context:

```php
Route::middleware('live_edit')->group(/* ... */);       // dispatches ServingLiveEdit
Route::middleware('module_settings')->group(/* ... */); // dispatches ServingModuleSettings
```

### Layout / skin preview

Any layout skin can be previewed inside a template master without saving it to a page:

```
GET /template/preview-layout?module=layouts&skin=ecommerce/skin-1&template=Bootstrap
```

`skin` accepts both `/` and `.` separators; `template` is optional and falls back to the active template.

### API endpoints

Registered under `api/live-edit` (admin middleware) and consumed by the toolbar / page-settings UI:

| Method | Route | Name |
|--------|-------|------|
| GET | `api/live-edit/get-top-right-menu` | `api.live-edit.get-top-right-menu` |
| GET | `api/live-edit/get-website-info` | `api.live-edit.get-website-info` |
| GET | `api/live-edit/module-options` | `api.live-edit.module-options` |
| GET | `api/live-edit/content-settings` | `api.live-edit.content-settings` |
| POST | `api/live-edit/content-menu` | `api.live-edit.content-menu` |
| GET | `api/live-edit/template-layouts` | `api.live-edit.template-layouts` |
| GET | `api/live_edit/live_edit.module_settings` | `live_edit.module_settings` |

## Key classes

- `MicroweberPackages\LiveEdit\Facades\LiveEditManager` — facade for the manager singleton (`live_edit_manager`).
- `MicroweberPackages\LiveEdit\Services\LiveEditManagerService` — scripts/styles/head-tag registry + toolbar menus.
- `MicroweberPackages\LiveEdit\MenuBuilder\LiveEditMenu` — Knp-Menu based toolbar menu.
- `MicroweberPackages\LiveEdit\Filament\Admin\Pages\AdminLiveEditPage` — the Live Edit admin canvas page.
- `MicroweberPackages\LiveEdit\Providers\LiveEditServiceProvider` — package registration.
- `MicroweberPackages\LiveEdit\Traits\HasLiveEditMenus` · `MicroweberPackages\Template\Traits\HasScriptsAndStylesTrait` — the manager's mixins.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Alexander Raikov
- Bozhidar Slaveykov
- Nikolay Rachkov

## License

MIT
