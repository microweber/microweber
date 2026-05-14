# Usage

How the Admin package is consumed: registering Filament pages and resources via `FilamentRegistry`, building an Admin Settings page with the `AdminSettingsPage` abstract, injecting scripts and styles, adding render hooks, customising the brand colors, and gating access to admin-only routes.

---

## Registering a Filament resource or page

You do **not** add resources to `FilamentAdminPanelProvider` directly. The panel provider iterates `FilamentRegistry::getResources()` / `getPages()` / `getClusters()` at boot — so register from your own package:

```php
// In YourPackage\Providers\YourServiceProvider::boot()

use MicroweberPackages\Filament\FilamentRegistry;

public function boot(): void
{
    FilamentRegistry::registerResource(\YourPackage\Filament\Admin\Resources\WidgetResource::class);
    FilamentRegistry::registerPage(\YourPackage\Filament\Admin\Pages\WidgetDashboardPage::class);
    FilamentRegistry::registerCluster(\YourPackage\Filament\Admin\Clusters\WidgetCluster::class);
    FilamentRegistry::registerPlugin(new \YourPackage\WidgetFilamentPlugin());
}
```

The next admin-page render picks up the new registrations automatically — no Admin-package edits needed.

For resources that need to appear in a specific navigation group, set the resource's `$navigationGroup` property. The available groups (in `FilamentAdminPanelProvider`):

- Dashboard *(uncollapsible)*
- Website *(collapsible — heroicon-o-globe-alt)*
- Shop *(collapsible — heroicon-o-shopping-bag)*
- Marketplace, Modules, Settings, Users

Plus the hidden settings sub-groups: Website Settings, Shop Settings, Email Settings, Customization Settings, System Settings, Language Settings (shown only when the user navigates into Settings).

---

## Building an Admin Settings page

Every package that needs admin-editable settings extends `MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage`. Two minimal examples:

### Plain settings page (one option group)

```php
namespace YourPackage\Filament\Admin\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class WidgetSettingsPage extends AdminSettingsPage
{
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Widget Settings';
    protected static ?string $title = 'Widget Settings';

    // Tells the abstract which option group(s) to load + save
    protected array $optionGroups = ['widget'];

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('widget_default_color')
                ->label('Default colour')
                ->default('#0d6efd'),

            Toggle::make('widget_show_thumbnail')
                ->label('Show thumbnail')
                ->default(true),

            TextInput::make('widget_max_items')
                ->label('Max items')
                ->numeric()
                ->default(10),
        ];
    }
}
```

Register the page via `FilamentRegistry::registerPage(...)` and it appears under "Settings". On render, the abstract:

1. Reads option values from `MicroweberPackages\Option\Models\Option` where `option_group` is one of `$optionGroups`.
2. Converts boolean fields' `'y'` / `'1'` database values to native PHP `true`/`false` for the form.
3. Caches the result for 5 minutes per request scope.

On save (via Livewire's `updated()` hook on each field):

1. Coerces booleans back to `'y'` / `'1'` for storage.
2. Calls `save_option($key, $value, $group)` per field.
3. Invalidates the 5-minute cache.

You don't write any "save" handler — the abstract does it field-by-field via Livewire's reactivity model.

### Translatable settings (per-locale values)

If your settings need per-locale storage (e.g. SEO defaults, welcome text), extend with `$moduleOptionScope`:

```php
class TranslatableSettingsPage extends AdminSettingsPage
{
    protected array $optionGroups = ['website'];
    protected string $moduleOptionScope = 'website';   // tells the abstract to use ModuleOption (translatable) instead of Option

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('website_welcome_text')->translatable(),
        ];
    }
}
```

The `MultilanguageFilamentPlugin` (registered by Admin) handles the locale-switcher UI; the abstract handles the per-locale read/write through `multilanguage_translations`.

---

## Injecting scripts and styles

The deprecated `AdminManager` facade still works for back-compat:

```php
use MicroweberPackages\Admin\Facades\AdminManager;

AdminManager::addScript('https://cdn.example.com/widget.js');
AdminManager::addStyle('https://cdn.example.com/widget.css');
AdminManager::addCustomHeadTag('<meta name="x-widget" content="enabled">');
```

For new code, prefer Filament's own render-hook pattern:

```php
// In YourPackage\Providers\YourServiceProvider::boot()

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

FilamentView::registerRenderHook(
    PanelsRenderHook::HEAD_END,
    fn() => '<script src="' . asset('vendor/yourpackage/widget.js') . '"></script>',
    scopes: [\YourPackage\Filament\Admin\Pages\WidgetDashboardPage::class],
);
```

The render-hook approach scopes to specific pages so you're not loading widget.js on every admin page.

---

## Adding a custom render hook

The Admin package wires three default hooks (TOPBAR_START, GLOBAL_SEARCH_AFTER, TABLES::TOOLBAR_SEARCH_BEFORE). To add more:

```php
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

FilamentView::registerRenderHook(
    PanelsRenderHook::SIDEBAR_NAV_END,
    fn() => view('yourpackage::admin.sidebar-footer-promo'),
);

FilamentView::registerRenderHook(
    PanelsRenderHook::PAGE_END,
    fn() => '<script>console.log("page loaded")</script>',
    scopes: [\YourPackage\Filament\Admin\Pages\WidgetDashboardPage::class],
);
```

The full list of available hooks is in `Filament\View\PanelsRenderHook` constants. Each hook fires from inside Filament's panel templates at a specific position.

`scopes:` constrains the hook to specific Pages/Resources. Omit `scopes:` to fire on every panel page.

---

## Customising brand colors + logo

Brand colors anchor to `MicroweberPackages\Admin\Filament\MwColors`. The primary blue is Bootstrap's `#0d6efd` at slot 500, with a full 50–950 ladder for darker/lighter variants. To replace the palette:

```php
// In YourPackage\Providers\YourServiceProvider::boot() — runs AFTER Admin's boot

\MicroweberPackages\Admin\Filament\MwColors::$Blue = [
    50  => '241, 245, 255',
    100 => '208, 220, 255',
    // ... your custom palette as RGB triplets
    500 => '255, 87, 51',    // hot orange instead of blue
    600 => '230, 75, 40',
    // ...
];
```

For a one-off logo override:

```php
\MicroweberPackages\Option\Models\Option::setValue('admin_logo', asset('img/your-logo.svg'), 'website');
```

For Tailwind-level theme overrides (custom fonts, custom CSS variables), edit the `microweber-filament-theme` package — that's the project's Tailwind config authority. See [Examples #3](./examples.md#3-replace-the-brand-logo-and-primary-color-palette) for the full flow.

---

## Gating routes with the `is_admin()` helper

Outside Filament (e.g. custom legacy admin routes, API endpoints, Livewire components rendered from public pages), use:

```php
// Pure check
if (! is_admin()) {
    abort(403);
}

// In Livewire components — extend AdminComponent for built-in authorisation
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;

class MyAdminLivewire extends AdminComponent
{
    public function mount(): void
    {
        // $this->authorize('isAdmin') already ran in the base constructor
        // do admin-only work here
    }
}
```

`AdminComponent` uses `Illuminate\Foundation\Auth\Access\AuthorizesRequests` so `$this->authorize('isAdmin')` works via Laravel's gate system — register the gate in `App\Providers\AuthServiceProvider`:

```php
Gate::define('isAdmin', fn($user) => $user->is_admin === 1);
```

The default project's `AuthServiceProvider` already registers this gate.

---

## Listening to admin boot events

Three events fire on every admin request, from the `Admin` middleware:

| Event | Type | Fired |
|---|---|---|
| `Modules\Admin\Events\ServingAdmin` *(typed)* | Laravel event | `event(new ServingAdmin())` |
| `mw.admin` | legacy string trigger | `event_trigger('mw.admin')` |
| `mw_backend` | legacy string trigger | `event_trigger('mw_backend')` |

Listen on whichever is convenient — they fire in the same place, so a listener on any of them is guaranteed to run once per admin request.

```php
// In App\Providers\EventServiceProvider::$listen

protected $listen = [
    \MicroweberPackages\Admin\Events\ServingAdmin::class => [
        \App\Listeners\InjectCustomAdminMenuItems::class,
    ],
];
```

Common uses: dynamically registering navigation items, lazy-loading scripts only on admin requests, instrumenting telemetry.

---

## Live Edit integration

The top-navigation has a "View Site / Live Edit" button injected via the `GLOBAL_SEARCH_AFTER` render hook. It targets `site_url('?editmode=y')` — the standard Microweber live-edit toggle.

If you want to suppress the button on a specific resource (e.g. a resource that has nothing to do with public-site content), narrow the render hook by re-registering with a custom scope. Or simpler — hide it via CSS scoped to the resource's page:

```css
body.fi-page-myresource-page #toolbar-go-live-edit { display: none; }
```

The button's id and class hooks are stable (preserved as project back-compat references — see the project's `feedback_always_build` memory note about toolbar back-compat hooks).
