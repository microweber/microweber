# Troubleshooting

Common Admin package issues with diagnostic steps.

---

## Login autofill doesn't work on mobile

**Symptom.** Open admin login on iOS/Android. Password manager doesn't offer to fill, or fills the wrong field, or fills the email into the password input.

**Cause.** The shipped `Login` page (AI-281) deliberately emits `name="email"`, `autocomplete="username"`, `name="password"`, `autocomplete="current-password"` attributes — without these, mobile autofill silently fails because Filament's defaults don't match the WHATWG-spec attribute names password managers rely on.

If autofill is failing, the AI-281 customisation is being bypassed.

**Diagnosis.**

```bash
curl -s http://your-site/admin/login | grep -E 'autocomplete="username"|autocomplete="current-password"'
```

Expected: 2 matches. If 0:

- A project-level override has replaced the shipped `Login` page with Filament's stock one.
- Or `view:clear` and `route:clear` weren't run after a deploy.
- Or a CDN is serving a stale HTML cache.

Fix: confirm `\MicroweberPackages\Admin\Filament\Pages\Login` is what `FilamentAdminPanelProvider::login()` references. If it's been changed (e.g. to a custom subclass), make sure the subclass calls `parent::form()` so the AI-281 token attrs are preserved.

If you forked the file: re-add the four `extraInputAttributes(...)` calls per the [API reference](./api.md#login-page).

---

## Can't reach `/admin` — 404

**Symptom.** Visiting `/admin` (or whatever your custom prefix is) returns a 404.

**Cause.** Four most likely:

1. `AdminServiceProvider` didn't register (package discovery missed it).
2. The admin URL prefix in the Option store has been changed but caches weren't cleared.
3. A higher-precedence route catches `/admin` before Filament's panel-mounted routes resolve.
4. The Filament panel itself failed to boot (typically a plugin registration error in another package).

**Diagnosis.**

```bash
# Check the resolved prefix
php artisan tinker --execute='echo mw_admin_prefix_url();'

# List all admin-related routes
php artisan route:list | grep admin | head -30

# Check the panel resolves
php artisan tinker --execute='
    $p = app("filament")->getPanel("admin");
    echo $p->getId() . " path=" . $p->getPath() . "\n";
'
```

If `mw_admin_prefix_url()` returns something unexpected (e.g. `backend` instead of `admin`):

- Either change the option back (`Option::setValue('admin_url_prefix', 'admin', 'website')`),
- Or update your URL to match (`http://your-site/backend`).

After option changes:

```bash
php artisan optimize:clear
```

If `route:list` shows admin routes but the URL still 404s, a custom catch-all route in `routes/web.php` is shadowing Filament. Audit `Route::fallback()` and `Route::any('/{any}', ...)` definitions.

If the panel doesn't resolve at all (tinker errors with a binding or class-not-found), the Filament boot failed. Check the Laravel log (`storage/logs/laravel.log`) for the first stack trace — usually a typo in a plugin registration in another package.

---

## Middleware bypass on first install

**Symptom.** A fresh install lets unauthenticated users reach `/admin/*` URLs and edit content.

**Cause.** **By design.** The `Admin` middleware has a first-time-setup escape hatch: if no admin user exists yet (`User::where('is_admin', 1)->count() === 0`), the middleware allows the request through so the install wizard can run.

This is **deliberate** but creates a window of vulnerability on a fresh install if the wizard isn't completed promptly.

**Mitigation.** On a production deploy, run the admin-user seeder before the site goes public:

```bash
php artisan db:seed --class=CreateInitialAdminUserSeeder
```

Or programmatically:

```php
\MicroweberPackages\User\Models\User::create([
    'username' => 'admin',
    'email'    => 'admin@your-site.com',
    'password' => bcrypt(\Str::random(32)),
    'is_admin' => 1,
]);
```

Once any user has `is_admin = 1`, the first-time-setup escape closes and the middleware enforces normal auth.

**Verify the escape is closed:**

```php
echo \MicroweberPackages\User\Models\User::where('is_admin', 1)->count();
// Expected > 0 on a properly-set-up install
```

If this returns `0` on a production server, that's a security incident — fix immediately and audit logs for any unauthenticated admin activity.

---

## Filament resource doesn't appear in the navigation

**Symptom.** Registered a custom resource via `FilamentRegistry::registerResource(...)` but the sidebar doesn't show it.

**Cause.** Three common roots:

1. The resource's `$navigationGroup` doesn't match any of the registered groups in `FilamentAdminPanelProvider`.
2. The resource has `shouldRegisterNavigation()` returning false.
3. The current user fails `authorizeViewAny()` on the resource's policy.

**Diagnosis.**

```php
$resourceClass = \YourPackage\Filament\Admin\Resources\WidgetResource::class;

dd([
    'in_registry' => in_array($resourceClass, \MicroweberPackages\Filament\FilamentRegistry::getResources()),
    'navigation_group' => $resourceClass::getNavigationGroup(),
    'should_register'  => $resourceClass::shouldRegisterNavigation(),
    'can_view_any'     => auth()->user()?->can('viewAny', $resourceClass::getModel()),
]);
```

If `in_registry` is false → the registration didn't run. Confirm your provider's `boot()` is actually being called (`php artisan package:discover --ansi`).

If `navigation_group` returns a string that's not in the panel's `->navigationGroups([...])` list → Filament still shows it, but in an "Other" implicit group. Add the group to the panel provider OR change the resource's `$navigationGroup` to one that exists.

If `should_register` is false → the resource is intentionally hidden from navigation. Override the static to return true if you want it shown.

If `can_view_any` is false → the user's policy says no. Check `app/Policies/WidgetPolicy.php` for the `viewAny()` method.

---

## Top-nav "Live Edit" button is missing

**Symptom.** Other admin top-nav items render but the "View Site / Live Edit" button is gone.

**Cause.** The button is injected via `PanelsRenderHook::GLOBAL_SEARCH_AFTER` in `FilamentAdminPanelProvider`. If a project override registered a different hook at the same position, it can crowd out the original. Or the `top-navigation-go-live-edit.blade.php` view file was renamed/moved.

**Diagnosis.**

```php
// Check the hook still fires
$hooks = app('filament')->getPanel('admin')->getRenderHooks();
foreach ($hooks as $name => $callbacks) {
    if (str_contains($name, 'GLOBAL_SEARCH')) {
        dump($name, count($callbacks));
    }
}
```

Expected: at least one callback at `panels::global-search.after`. If zero, the hook never registered — confirm `AdminServiceProvider` ran. If many, multiple plugins are stacking on the hook — the first registered may be overwritten by later ones unless they coexist properly (Filament render hooks accumulate as a list, so this is rare but possible).

The view file should be at:

```
src/MicroweberPackages/Admin/resources/views/livewire/filament/top-navigation-go-live-edit.blade.php
```

If it's missing, restore from a prior commit — that file is the canonical source for the button.

---

## `AdminSettingsPage` save isn't persisting

**Symptom.** Edit a field on a settings page, see the success notification, refresh the page — the change is gone.

**Cause.** Most common: the `$optionGroups` property doesn't match what the page is actually reading/writing. The abstract's `mount()` reads from `Option::where('option_group', $group)`, and `updated()` writes to the same group. If they're misaligned, reads return defaults and writes go to the wrong group.

**Diagnosis.**

```php
$page = new \YourPackage\Filament\Admin\Pages\WidgetSettingsPage();
dd([
    'option_groups' => $page->getOptionGroups(),   // expects ['widget']
    'cached_values' => app('cache')->get('admin_settings_widget'),
    'raw_db_values' => \MicroweberPackages\Option\Models\Option::where('option_group', 'widget')->pluck('option_value', 'option_key')->all(),
]);
```

If `option_groups` is empty or wrong → fix the page's `$optionGroups` array.

If the cache value differs from the DB value → cache wasn't invalidated on save. The abstract invalidates on save automatically; if it's not, you may have overridden `updated()` and broken the invalidation. Don't override `updated()`; if you need a hook, override `updatedOptionKey()` (per-field hook the abstract calls into) or use a Livewire `updated*` shorthand.

5-minute cache TTL is `\Cache::remember(...)`. Force-clear:

```bash
php artisan cache:clear
```

---

## Render-hook content appears twice

**Symptom.** Your custom render-hook output renders correctly but shows up duplicated on every admin page.

**Cause.** Your provider's `boot()` is being called twice. Usually this is because the provider is registered both via `config/app.php` AND via package auto-discovery. Filament's `registerRenderHook()` accumulates callbacks, so a double-registration produces a double-render.

**Diagnosis.**

```php
$callbacks = app('filament')->getPanel('admin')
    ->getRenderHooks()['panels::sidebar-nav.end'] ?? [];
echo "Hook callbacks: " . count($callbacks);
```

Expected: 1 per registration. If 2+ where you only registered once, you have a duplicate provider load.

Fix: remove the manual `config/app.php` entry (or the `extra.laravel.providers` in `composer.json`) and let auto-discovery handle it. Or move the `registerRenderHook()` call from `boot()` to `register()` (registration runs once even if `boot()` runs twice, but is technically wrong — `register()` shouldn't reference services that aren't bound yet).

---

## Iframe detection returns wrong value

**Symptom.** `view()->shared('isIframe')` returns `false` even though the admin is loaded inside an iframe (e.g. Live Edit's wrapper).

**Cause.** The browser didn't send the `Sec-Fetch-Dest: iframe` header. Either:

1. The browser doesn't support `Sec-Fetch-*` headers (very old browsers).
2. The iframe is loaded with a `<frame>` element (deprecated) instead of `<iframe>` — different header behaviour.
3. A CORS proxy is stripping the header.

**Mitigation.** Check the parent context explicitly:

```js
const isIframe = (window !== window.parent) || (window.frameElement !== null);
```

Then pass an explicit query param or POST field through to the request so the Admin middleware can read it:

```html
<iframe src="/admin/some-page?is_iframe=1"></iframe>
```

```php
// In the Admin middleware (custom subclass)
$isIframe = $request->header('Sec-Fetch-Dest') === 'iframe'
         || $request->query('is_iframe') === '1';
View::share('isIframe', $isIframe);
```

The Microweber Live Edit module uses this dual-signal pattern (header AND query param) for exactly this reason.

---

## Custom MwColors palette has no effect

**Symptom.** Assigned `MwColors::$Blue = [...]` from your provider but the admin still renders the original Bootstrap blue.

**Cause.** Your provider booted BEFORE `AdminServiceProvider`, so when Admin reads `MwColors::$Blue` to configure Filament, it sees your override — but then a CSS layer (Tailwind compiled output in `microweber-filament-theme`) still renders the original blue because the actual rendered colors come from CSS variables baked into the built theme bundle.

**Fix.** Override CSS variables in the theme package (the canonical path):

```css
/* In your project's app-level CSS, AFTER theme.css loads */
body.fi-panel-admin {
    --primary-500: 255 87 51;   /* override Filament's primary */
}
```

OR rebuild the Filament theme with your palette:

```bash
cd packages/microweber-filament-theme
# edit tailwind.config.js to use your palette
npm run build
```

The PHP-level `MwColors` static is only consumed at panel-provider configuration time. The compiled CSS is what the browser actually reads. CSS-variable override is the working path; PHP-level override alone is not.

See [Examples #3](./examples.md#3-replace-the-brand-logo-and-primary-color-palette) for the full flow.
