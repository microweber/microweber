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

---

## Building a custom dashboard widget

The admin dashboard is composed of Filament Widgets — small Livewire-backed cards that render in a grid on `/admin`. The Admin package ships one widget (`FilamentInfoWidget`) and registers it via `FilamentAdminPanelProvider`. Custom widgets live in any package and register themselves through the standard Filament pattern.

Three steps:

### Step 1 — Create the widget class

```php
namespace YourPackage\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class WidgetCountStatsWidget extends Widget
{
    protected string $view = 'yourpackage::filament.widgets.widget-count-stats';

    // Optional: sort order on the dashboard (-1 puts you above the
    // default stats row at 0; default is 0)
    protected static ?int $sort = 1;

    // Optional: column span (1, 2, 3 or 'full' for the full row)
    protected int|string|array $columnSpan = 1;

    // Optional: only show on the dashboard, not other pages
    public static function canView(): bool
    {
        return auth()->user()?->is_admin === 1;
    }

    public function getViewData(): array
    {
        return [
            'count' => \YourPackage\Models\Widget::count(),
            'recent' => \YourPackage\Models\Widget::latest()->take(5)->get(),
        ];
    }
}
```

For a stats-style widget with a number + trend indicator, extend `Filament\Widgets\StatsOverviewWidget` instead — gives you the standard card grid for free.

### Step 2 — Create the Blade view

```blade
{{-- resources/views/filament/widgets/widget-count-stats.blade.php --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Widget Counts</x-slot>

        <div class="text-2xl font-bold">
            {{ $count }} widgets
        </div>

        <ul class="mt-4 space-y-1 text-sm">
            @foreach ($recent as $widget)
                <li>{{ $widget->name }} <span class="text-gray-500">— {{ $widget->created_at->diffForHumans() }}</span></li>
            @endforeach
        </ul>
    </x-filament::section>
</x-filament-widgets::widget>
```

### Step 3 — Register via FilamentRegistry

```php
// YourPackage\Providers\YourServiceProvider::boot()

use MicroweberPackages\Filament\FilamentRegistry;

FilamentRegistry::registerWidget(\YourPackage\Filament\Admin\Widgets\WidgetCountStatsWidget::class);
```

The Admin panel provider iterates the registry on boot and includes every registered widget. No edits to the Admin package itself.

For conditional visibility (e.g. "only show on first-install" or "only show when content count is zero"), implement `canView(): bool` on the widget. The dashboard renders only widgets that return true. A real-world example: the `DashboardEmptyStateWidget` shipped earlier this session (commit `ce2e76bcdd`) returns `canView() === DB::table('content')->count() === 0` so it surfaces only on fresh installs.

---

## Listening to admin events / hooks

Beyond the typed `ServingAdmin` event the Admin middleware fires, there are three additional admin extension points:

### 1. Filament panel events

Filament v5 fires its own lifecycle events that you can listen to in any service provider:

```php
use Filament\Events\ServingFilament;
use Illuminate\Support\Facades\Event;

Event::listen(ServingFilament::class, function (ServingFilament $event) {
    // Filament is booting on this request — register dynamic resources,
    // adjust nav, etc.
});
```

`ServingFilament` fires on every Filament-panel request (admin, checkout, any future panels). Use `request()->routeIs('filament.admin.*')` inside the listener to scope to the admin panel only.

### 2. Livewire morph hooks

For DOM-mutation observability (e.g. instrumenting a custom analytics tracker when a Livewire component re-renders):

```js
// In your admin-side JS
document.addEventListener('livewire:initialized', () => {
    Livewire.hook('morph.added', ({ el, component }) => {
        // Element was added to the DOM
    });
    Livewire.hook('morph.removed', ({ el, component }) => {
        // Element was removed
    });
});
```

This is **not** an admin-package hook — it's a Livewire feature. But because the admin panel is heavy Livewire, listening here is the canonical way to react to component-level DOM activity.

### 3. Custom Filament action lifecycle hooks

Filament actions have their own lifecycle callbacks you can subscribe to without modifying the action class:

```php
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Event;

Event::listen('eloquent.created: Modules\Content\Models\Content', function ($content) {
    // Fired whenever a Content row is created — including from
    // Filament's "Create record" action. Useful for cross-cutting
    // concerns like audit logs.
});
```

Eloquent's wildcard event names (`eloquent.created:`, `eloquent.updated:`, `eloquent.deleted:`) work for any model and are model-agnostic — listeners don't need to know the resource class that triggered the save.

The **legacy event-trigger** hooks (`event_trigger('mw.admin')`, `event_trigger('mw_backend')`, `event_trigger('on_load')`) are still fired by the Admin middleware + controller for back-compat. New code should subscribe to the typed `ServingAdmin` event instead.

---

## Multilanguage support in admin

The `MultilanguageFilamentPlugin` registers automatically when the Multilanguage module is enabled (see [installation.md](./installation.md#filament-panel-registration)). It adds three concrete capabilities to the admin panel:

### 1. Per-locale option storage via `ModuleOption`

When an `AdminSettingsPage` declares `$moduleOptionScope`, the abstract uses `ModuleOption` (translatable) instead of `Option` (single-value). Each form field's value gets stored per active locale in the `multilanguage_translations` table.

```php
class WelcomeSettingsPage extends AdminSettingsPage
{
    protected array $optionGroups = ['website'];
    protected string $moduleOptionScope = 'website';   // ← activates per-locale storage

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('welcome_headline')->translatable(),  // ← field opts in
            TextInput::make('welcome_subhead')->translatable(),
        ];
    }
}
```

The plugin renders a locale switcher in the form header. Editing in EN, switching to DE, editing again, saving — both translations land in the right rows.

### 2. Translatable Filament resources

Microweber's modules with translatable content (Content, Product, Category) use the Content module's `HasMultilanguageTrait` on their Eloquent models. The `MultilanguageFilamentPlugin` automatically adds:

- A locale switcher in the resource's edit form.
- A "Locale" column option for list views.
- A "Locale filter" in the search bar.
- Per-locale validation so a missing-translation save shows the right field highlighted in the right locale.

To opt a field into translation in your custom resource:

```php
// In YourResource::form(Form $form): Form
TextInput::make('title')->translatable()->required();
```

The `translatable()` modifier is provided by the plugin — it wraps the field in a per-locale storage shim.

### 3. Admin UI locale (separate from content locale)

The locale of the admin UI itself (button labels, navigation, error messages) is independent of the content locale being edited. Switch the admin UI locale via:

```
GET /admin/lang/{locale}   (e.g. /admin/lang/de)
```

The route is registered by the Multilanguage module. It updates the session's `admin_locale` and redirects back. The admin language preference persists per-user (stored on `User::$admin_locale` if the column exists).

Translation files for the admin UI live at `lang/<locale>/admin.php` for project-level overrides; package-level admin translations are in each package's `lang/` directory and are picked up by `\Lang::addNamespace()` calls in the package providers.
