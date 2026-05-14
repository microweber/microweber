# Installation

The Admin package is part of Microweber core. There is **no separate install step** — it boots automatically with the framework. This page documents what auto-registers, where to override the admin URL prefix, what middleware is mounted, and which sibling packages the harness depends on.

---

## Auto-registration

`MicroweberPackages\Admin\Providers\AdminServiceProvider` is registered via the framework's package-discovery flow (no manual entry needed in `config/app.php`). It boots:

- `Filament\FilamentAdminPanelProvider` — the Filament v5 panel definition (panel id `admin`, default).
- `Providers\AdminRouteServiceProvider` — loads `routes/admin.php` (legacy paths) and `routes/api.php` (license endpoints).
- The Microweber SVG icon set via `BladeUI\Icons\Factory::add('mw', ...)` — makes `@svg('mw-add-page')` etc. available in Blade.
- The deprecated `AdminManager` facade alias (kept for backward compatibility; new code shouldn't use it).
- The Livewire components `admin-top-navigation-actions`, `admin-modal`, `admin-confirm-modal`.

---

## Admin URL prefix

The path that mounts the Filament panel is **dynamic**:

```php
->path(mw_admin_prefix_url())
```

`mw_admin_prefix_url()` resolves to whatever is configured via the project's URL-customisation system. Defaults to `admin` on a fresh install. Common overrides:

```php
// Programmatic override via option
\MicroweberPackages\Option\Models\Option::setValue('admin_url_prefix', 'backend', 'website');
```

After changing the prefix, **clear route caches**:

```bash
php artisan route:clear
php artisan view:clear
```

The legacy admin path (`{admin_url_legacy}/*`) lives in `routes/admin.php` and uses `mw_admin_prefix_url_legacy()` — kept for back-compat with installations that haven't migrated to the Filament-default admin yet.

---

## Middleware

The admin middleware is `MicroweberPackages\Admin\Http\Middleware\Admin`. It runs **before** Filament's own auth middleware on every admin request.

Behaviour:

1. Skip the check for routes named `admin.login.*` or `admin.reset.*` — those need to be reachable without being logged in.
2. Set the `isIframe` view variable based on the request's `Sec-Fetch-Dest: iframe` header (used by Live Edit's modal/iframe wrapper).
3. **First-time-setup escape hatch**: if no admin user exists yet (`User::where('is_admin', 1)->count() === 0`), allow the request through unauthenticated so the install wizard can run. Once an admin user exists this fallback closes.
4. Dispatch `Events\ServingAdmin` + legacy `event_trigger('mw.admin')` + legacy `event_trigger('mw_backend')`. Other packages can listen on any of the three to inject menus / scripts / custom tags.
5. Authorise the request: `Auth::user()?->is_admin === 1` OR no-admin-exists fallback. Otherwise redirect to login.

There is no Spatie / Fortify / fine-grained role check at this layer. RBAC inside the admin panel is per-resource — each Filament resource declares its own policy class against the User module's `is_admin` flag.

---

## Filament panel registration

`FilamentAdminPanelProvider::panel(Panel $panel)` does the heavy lifting. Three sections worth knowing:

### Pages, resources, clusters — plugin-driven

```php
->pages(\MicroweberPackages\Filament\FilamentRegistry::getPages())
->resources(\MicroweberPackages\Filament\FilamentRegistry::getResources())
->clusters(\MicroweberPackages\Filament\FilamentRegistry::getClusters())
```

You don't add a resource to this provider directly. Register it in your own package via:

```php
// In YourPackage\Providers\YourServiceProvider::boot()
\MicroweberPackages\Filament\FilamentRegistry::registerResource(YourResource::class);
```

The Admin package iterates the registry every boot.

### Render hooks

Three are wired by default:

```php
$panel->renderHook(
    name: PanelsRenderHook::TOPBAR_START,
    hook: fn() => Blade::render('@livewire("admin-top-navigation-actions")'),
);

$panel->renderHook(
    name: PanelsRenderHook::GLOBAL_SEARCH_AFTER,
    hook: fn() => view('admin::livewire.filament.top-navigation-go-live-edit')
                . view('admin::livewire.filament.search-quick-nav'),
);

$panel->renderHook(
    name: \Filament\Tables\View\TablesRenderHook::TOOLBAR_SEARCH_BEFORE,
    hook: fn() => view('modules.content::filament.admin.list-records-render-category-tree'),
    scopes: [
        \Modules\Content\Filament\Admin\ContentResource\Pages\ListContents::class,
        \Modules\Post\Filament\Admin\Resources\PostResource\Pages\ListPosts::class,
        \Modules\Product\Filament\Admin\Resources\ProductResource\Pages\ListProducts::class,
    ],
);
```

Add your own from a custom provider — see [Examples #4](./examples.md#4-scope-a-render-hook-to-specific-resources) for the scoping pattern.

### Plugins

Bundled plugins registered by default:

| Plugin | What it adds |
|---|---|
| `MicroweberFilamentTheme` | Custom Tailwind theme + brand color CSS variables |
| `UsersFilamentPlugin` | User package's Filament UserResource and friends |
| `MultilanguageFilamentPlugin` | Per-locale option storage + translation switcher |
| `MicroweberFilamentSocialitePlugin` | OAuth login providers |
| `TableLayoutTogglePlugin` | Grid/list table toggle (default: grid) |

Plus everything `FilamentRegistry::getPlugins()` returns — auto-discovered from installed packages.

---

## Dependencies on other packages

| Package | Why Admin needs it |
|---|---|
| **Filament** v5 | the panel provider + Page + Resource + Widget base classes |
| **Livewire** v4 | the top-nav Livewire components |
| **User** | the User model (Admin reads `is_admin`); UsersFilamentPlugin (registers User Filament resource) |
| **`MicroweberPackages\Filament`** | `FilamentRegistry` (plugin-driven Page/Resource/Cluster discovery) |
| **MicroweberFilamentTheme** | brand-aligned Tailwind theme + the `body.fi-panel-admin` CSS scope |
| **[LiveEdit](/)** | `AdminLiveEditPage` (registered into the panel via FilamentRegistry); the top-nav button targets `site_url('?editmode=y')` |
| **Multilanguage** | MultilanguageFilamentPlugin (translatable option storage) |
| **Option** | `Option` + `ModuleOption` models (settings storage); `AdminSettingsPage` reads/writes through these |
| **Template** | `HasScriptsAndStylesTrait` (script/style injection); `template_manager->admin_head()` |
| **View** | `StringBlade` (used by legacy admin renderer) |

If any of these packages is disabled or missing, the admin panel will fail at boot — these are hard dependencies, not soft ones.

---

## Database

Admin owns **zero migrations** and **zero models**. The data Admin reads (the User row, the Option row) belongs to other packages. There is no Admin-specific migrate step.

---

## Configuration

There is **no `config/admin.php`**. All configurable behaviour is either compiled into the panel provider or stored in the Option table:

| Option key | Group | Default | Effect |
|---|---|---|---|
| `admin_url_prefix` | website | `admin` | the URL prefix where the panel mounts |
| `admin_logo` | website | (none) | logo image URL; falls back to login logo |
| `brand_name` | website | `Microweber` | site brand name shown in the panel header |

There are many more option keys (one per Admin Settings page) — but those are owned by the **package that registered the settings page**, not by the Admin package itself.

---

## Sanity check after install

```bash
# Admin panel route resolves
curl -I http://your-site/admin
# Expected: 302 redirect to /admin/login (when not logged in) or 200 (when logged in)

# Login page renders
curl -s http://your-site/admin/login | grep -c 'autocomplete="username"'
# Expected: 1 — the AI-281 autofill token landed correctly

# Filament panel provider resolves
php artisan tinker --execute='
    $p = app("filament")->getPanel("admin");
    echo $p->getId() . " path=/" . $p->getPath();
'
# Expected: admin path=/admin (or your custom prefix)

# FilamentRegistry returns the registered resources
php artisan tinker --execute='
    dd(\MicroweberPackages\Filament\FilamentRegistry::getResources());
'
# Expected: a non-empty array including Content, Product, etc.
```

If `/admin` 404s, confirm `AdminRouteServiceProvider` is loaded — `php artisan package:discover --ansi` should pick it up automatically; if it doesn't, add `\MicroweberPackages\Admin\Providers\AdminServiceProvider::class` to `config/app.php` providers list.

If `/admin` returns 500, check the Filament panel resolves cleanly via the tinker check above. Common causes: a malformed plugin registration in another package, or a Filament version mismatch between core + plugins.
