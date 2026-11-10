# Microweber Admin

The Microweber admin area — the Filament v5 **admin panel**, its routes, the admin Blade view namespace, and a handful of admin-only Livewire components and Blade directives. This is largely a wiring/glue package: it assembles the admin surface out of the other Microweber packages (template, user, multilanguage, live-edit, filament-theme, option, …).

## What it registers

- **`FilamentAdminPanelProvider`** — the Filament `admin` panel (id/path, default panel, custom `Login` page, brand, `MwColors`, discovered + explicitly-registered resources and pages). Registered by `microweber-filament`.
- **`AdminServiceProvider`** — registers `AdminRouteServiceProvider`, adds the `admin` Blade view namespace (`resources/views`), the `@dispatchGlobalBrowserEvents` Blade directive, and the `admin-top-navigation-actions` Livewire component. Registered by `microweber-app`'s `MicroweberServiceProvider`.
- **`AdminRouteServiceProvider`** — loads `routes/admin.php` (the legacy `/admin` dashboard + catch-all) and `routes/api.php`.
- **`AdminManager`** service — legacy admin menu/scripts helper. Mostly gutted / **deprecated** (the `AdminManager` facade is marked `@deprecated`); the one still-used method is `serving()`.

## Installation

```bash
composer require microweber-packages/microweber-admin
```

Within a full Microweber install the providers are wired automatically (`AdminServiceProvider` via `MicroweberServiceProvider`, `FilamentAdminPanelProvider` via `MicroweberFilamentServiceProvider`) — there is no `extra.laravel` auto-discovery.

## Usage

### Run code when the admin is served

```php
use MicroweberPackages\Admin\Services\AdminManager;

app(AdminManager::class)->serving(function () {
    // runs on the ServingAdmin event (admin area is being rendered)
});
```

`serving()` is a thin wrapper that registers a listener on `MicroweberPackages\Admin\Events\ServingAdmin`.

### Blade directive

```blade
{{-- Bridges a browser CustomEvent to Livewire inside the admin frame --}}
@dispatchGlobalBrowserEvents
```

Emits the script that forwards `dispatch-global-browser-event` browser events into `window.Livewire.dispatch(...)` (used to drive Livewire from the Live Edit iframe / top window).

### Admin views

Blade views published by this package resolve under the `admin::` namespace, e.g. `view('admin::...')`.

## Key classes

- `MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider` — the Filament admin panel.
- `MicroweberPackages\Admin\Providers\AdminServiceProvider` / `AdminRouteServiceProvider` — service + route wiring.
- `MicroweberPackages\Admin\Services\AdminManager` — legacy admin helper (deprecated).
- `MicroweberPackages\Admin\Events\ServingAdmin` — fired while the admin is served.
- `MicroweberPackages\Admin\Facades\AdminManager` — deprecated facade.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Nikolay Rachkov
- Alexander Raikov

## License

MIT
