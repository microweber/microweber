# API Reference

Class, method, route, event, and trait signatures for the Admin package.

---

## FilamentAdminPanelProvider

`MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider`. The Filament v5 `PanelProvider` that defines the admin panel. Auto-registered via `AdminServiceProvider`. Do not subclass — override behaviour via `FilamentRegistry` registrations or render-hook additions from your own provider.

### `panel(Panel $panel): Panel`

The complete panel configuration. Key sections:

| Setting | Value | Notes |
|---|---|---|
| `id` | `admin` | maps to `body.fi-panel-admin` CSS scope |
| `path` | `mw_admin_prefix_url()` | dynamic — defaults to `admin` |
| `default` | `true` | this panel is the default for `auth()->guard()` etc. |
| `font` | Inter | |
| `sidebarWidth` | 16rem | |
| `colors.primary` | `MwColors::Blue` | Bootstrap #0d6efd anchor |
| `colors.danger` | `Color::Rose` | |
| `colors.gray` | `Color::Neutral` | |
| `colors.info` | `Color::Sky` | |
| `colors.success` | `Color::Emerald` | |
| `colors.warning` | `Color::Amber` | |
| `brandLogo` | `app()->ui->admin_logo()` | falls back to login logo |
| `brandName` | `app()->ui->brand_name()` | |
| `paginationOptions` | `[10, 25, 50, 100, 250]` | default page size 25 |
| `pages` | `FilamentRegistry::getPages()` | plugin-driven |
| `resources` | `FilamentRegistry::getResources()` | plugin-driven |
| `clusters` | `FilamentRegistry::getClusters()` | plugin-driven |
| `widgets` | `[FilamentInfoWidget::class]` | other widgets self-register |
| `middleware` | `[VerifyCsrfToken, SubstituteBindings, DisableBladeIconComponents, DispatchServingFilamentEvent]` + Filament defaults | |
| `authMiddleware` | `[AuthenticateAdmin::class]` | |

---

## AdminSettingsPage (abstract)

`MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage`. Base class for every package's admin Settings page.

### Properties to override

| Property | Type | Purpose |
|---|---|---|
| `$optionGroups` | `array<string>` | which option groups to load + save (e.g. `['website']`, `['shop', 'shop_emails']`) |
| `$moduleOptionScope` | `string\|null` | if set, use `ModuleOption` (translatable) storage instead of plain `Option` |
| `$navigationGroup` | `string` | Filament navigation group (e.g. `'Settings'`) |
| `$navigationLabel` | `string` | sidebar link text |
| `$navigationIcon` | `string` | heroicon name |
| `$title` | `string` | page title |

### Method to implement

```php
abstract protected function getFormSchema(): array;
```

Return an array of Filament form components (`TextInput`, `Toggle`, `Select`, etc.). The abstract handles form construction, data binding, save lifecycle, and cache invalidation.

### Lifecycle hooks (already implemented — do not override)

| Hook | When | What |
|---|---|---|
| `mount()` | page load | loads option values for `$optionGroups` (and `$moduleOptionScope` if translatable); caches for 5 min |
| `updated($propertyName, $value)` | Livewire field change | coerces booleans, calls `save_option()`, invalidates cache |

### Boolean coercion

`Toggle` and `Checkbox` form fields store as PHP `true` / `false`. The abstract converts them to/from `'y'` / `'1'` strings on save and `'y'` / `'1'` / `true` / `1` to boolean on load. If a project legacy option stores `'1'` or `'y'`, both round-trip correctly.

---

## AdminManager (deprecated facade)

`MicroweberPackages\Admin\Facades\AdminManager` → `MicroweberPackages\Admin\Services\AdminManager`. **Deprecated** — kept for backward compatibility. New code should use Filament render hooks (see [usage](./usage.md#adding-a-custom-render-hook)).

### Methods (via `HasScriptsAndStylesTrait`)

```php
AdminManager::addScript(string $url, ?string $key = null): void
AdminManager::addStyle(string $url, ?string $key = null): void
AdminManager::addCustomHeadTag(string $rawHtml): void

AdminManager::scripts(): string         // rendered <script> tags
AdminManager::styles(): string          // rendered <link> tags
AdminManager::customHeadTags(): string  // rendered raw HTML
AdminManager::headTags(): string        // all three combined
```

### Methods on `AdminManager` itself

```php
AdminManager::addDefaultScripts(): void    // no-op placeholder
AdminManager::addDefaultStyles(): void     // no-op placeholder
AdminManager::addDefaultCustomTags(): void // injects template_manager->admin_head() output
AdminManager::getMenu(string $menu): array // legacy menu; returns []
AdminManager::serving(Closure $callback): void // registers a callback to fire on ServingAdmin
```

`getMenu()` returns `[]` — kept for legacy code that called it expecting a menu array. Real menus come from Filament navigation now.

---

## Login page

`MicroweberPackages\Admin\Filament\Pages\Login`. Extends Filament's `\Filament\Pages\Auth\Login`. The only customisation is autofill tokens (see [installation](./installation.md#filament-panel-registration)). To customise further (e.g. add a "Remember me" extra field, embed a marketing image), subclass and override `form(Form $form)`.

---

## Admin middleware

`MicroweberPackages\Admin\Http\Middleware\Admin`. Registered as `admin` in the route middleware aliases.

### `handle(Request $request, Closure $next): mixed`

Pipeline:

1. Skip authorisation for `admin.login.*` / `admin.reset.*` named routes.
2. Set `isIframe` view shared variable based on `Sec-Fetch-Dest: iframe` header.
3. Dispatch `ServingAdmin` event + legacy `mw.admin` / `mw_backend` event triggers.
4. Authorise: `Auth::user()?->is_admin === 1`. First-time-setup escape: if no admin user exists at all, allow the request through (install wizard).
5. Otherwise redirect to the login page.

The middleware is project-wide — apply it to any admin-scoped route group, not just Filament:

```php
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/legacy-tool', AdminToolController::class);
});
```

---

## Events

### `MicroweberPackages\Admin\Events\ServingAdmin`

Fired by the `Admin` middleware on every admin request. Empty constructor. Listeners get a no-payload event — use it as a "we're inside an admin request" marker for menu registration, lazy script-loading, telemetry hooks.

```php
public function __construct() {}
```

### Legacy event triggers

`event_trigger('mw.admin')` and `event_trigger('mw_backend')` fire alongside the typed event. These are the older Microweber event-bus style — kept for back-compat. New listeners should subscribe to `ServingAdmin` instead.

### `event_trigger('on_load')`

Fired from `AdminController::render()` at template-render time. Pre-Filament admin pages depend on it for some legacy plugin hooks.

---

## Livewire components

### `AdminComponent`

`MicroweberPackages\Admin\Http\Livewire\AdminComponent`. Base class for any Livewire component that should be admin-only. Constructor calls `$this->authorize('isAdmin')` (via `AuthorizesRequests` trait), so any Livewire request to a subclass throws 403 if the user is not an admin.

```php
class MyAdminWidget extends AdminComponent
{
    public function render() { /* ... */ }
}
```

### `AdminModalComponent`

`MicroweberPackages\Admin\Http\Livewire\AdminModalComponent`. Extends `LivewireUI\Modal\ModalComponent`. Provides:

- Default modal settings (overlay on, click-outside-to-close on).
- `authorize('isAdmin')` in `mount()`.
- Project-style modal Blade wrapper.

Use for any admin-only modal Livewire component.

### `AdminConfirmModalComponent`

`MicroweberPackages\Admin\Http\Livewire\AdminConfirmModalComponent`. Specialisation of `AdminModalComponent` for confirmation dialogs. Public properties:

```php
public string $title;           // modal title text
public string $message;         // body copy
public string $confirmAction;   // Livewire event name to dispatch on confirm
public string $confirmLabel;    // confirm button text
public string $cancelLabel;     // cancel button text
public mixed  $data;            // arbitrary payload passed back with the confirm event
```

Render via `<livewire:admin-confirm-modal :title="..." :confirm-action="..." />`. On confirm, the named action dispatches on the parent component with `$data` as its argument.

### `TopNavigationActions`

`MicroweberPackages\Admin\Http\Livewire\TopNavigationActions`. The quick-add dropdown rendered into the panel topbar. Hardcoded actions: New Page, New Post, New Category, New Product. Each links to the relevant Filament Create page via `admin_url()`.

To add more quick-add items, subclass and override `getActions()`:

```php
class MyTopNav extends \MicroweberPackages\Admin\Http\Livewire\TopNavigationActions
{
    protected function getActions(): array
    {
        return array_merge(parent::getActions(), [
            ['label' => 'New Coupon', 'url' => admin_url('shop/coupons/create')],
        ]);
    }
}
```

Then re-register the alias via `Livewire::component('admin-top-navigation-actions', MyTopNav::class)` from your own provider.

---

## MwColors (brand color authority)

`MicroweberPackages\Admin\Filament\MwColors`. Static class exposing color palettes. The most important one:

```php
public static array $Blue = [
    50  => '231, 241, 255',
    100 => '...',
    // ...
    500 => '13, 110, 253',   // Bootstrap primary blue
    // ...
    950 => '...',
];
```

Filament consumes this via `colors(['primary' => MwColors::Blue])` in the panel provider. The shape (50→950 ladder of RGB triplets) is what Filament expects.

To change project-wide brand color, either:

- **Override the static property** from a provider that boots AFTER Admin (rare).
- **Re-bind in the panel provider** by subclassing `FilamentAdminPanelProvider` and replacing the `colors(...)` call (not recommended — fragile across upgrades).
- **Use the `microweber-filament-theme` package** to override Tailwind CSS variables — that's the canonical override path.

---

## HTTP routes

### `routes/admin.php` (legacy admin)

| Method | URI | Action |
|---|---|---|
| GET, POST | `{admin_url_legacy}` | `AdminController@dashboard` |
| GET, POST | `{admin_url_legacy}/{all}` | `AdminController@index` (catch-all) |
| GET, POST | `editor_tools{all}` | `AdminEditorToolsController@index` (deprecated) |

Middleware: `public.web` (allows both authenticated and unauthenticated access, with controller-level auth gating).

### `routes/api.php` (license management)

| Method | URI | Action |
|---|---|---|
| GET | `api/mw_delete_license` | delete license |
| POST | `api/mw_validate_licenses` | validate |
| POST | `api/mw_consume_license` | consume |
| POST | `api/mw_save_license` | persist |

All delegate to `mw('update')` service. Middleware: `api`, `admin`, `xss`.

### Filament-mounted routes

Everything under `/admin/*` (or whatever `mw_admin_prefix_url()` returns) — registered by Filament itself based on the Pages/Resources/Clusters in the panel.

---

## Microweber SVG icon set

40+ icons live in `resources/mw-svg/`. Registered via `BladeUI\Icons\Factory::add('mw', ['path' => ...])` in `AdminServiceProvider::boot()`. Use in Blade:

```blade
@svg('mw-add-page', 'h-5 w-5')
@svg('mw-shop', ['class' => 'h-6 w-6 text-primary-500'])
```

Key icons: `mw-dashboard`, `mw-shop`, `mw-product`, `mw-post`, `mw-category`, `mw-pages`, `mw-settings`, `mw-coupon`, `mw-payment`, `mw-shipping`, `mw-template`, `mw-language`, `mw-modules`, `mw-licenses`, `mw-privacy`, `mw-seo`, `mw-add-page`, `mw-add-post`, `mw-add-product`, `mw-add-category`, `mw-search`, etc.

Add custom icons by dropping SVG files into `resources/mw-svg/` — auto-discovered on next boot.

---

## Tests

`tests/`:

| File | Coverage |
|---|---|
| `AdminTest.php` | `AdminManager` facade: `addScript()`, `addStyle()`, `addCustomHeadTag()`, rendering helpers |
| `AdminLivewireComponentsTest.php` | base Livewire component authorisation behaviour |

Run:

```bash
./vendor/bin/phpunit src/MicroweberPackages/Admin/tests
```

There are no end-to-end Filament panel tests in this package — Filament-level coverage lives in each feature module's tests (`Modules/Content/Tests/Filament/`, `Modules/Order/Tests/Filament/`, etc.).
