# Profile Module — Installation

The Profile module is a **core module** — ships with Microweber, registered automatically.

## Prerequisites

- PHP ≥ 8.2
- Laravel 11 base
- Filament v5 — the Profile panel is a separate Filament Panel
- The **User module** (`src/MicroweberPackages/User/`) — Profile extends `\App\Models\User` which is wired by the User module
- The **Customer module** (`Modules/Customer/`) — for saved addresses + the shop satellite
- The **Order module** — for order history listing
- Laravel Fortify — the 2FA primitives Profile builds on top of

## Registration

Standard module pipeline:

1. **`Modules/Profile/module.json`** declares the module + providers
2. **`Modules/Profile/Providers/ProfileServiceProvider.php`** registers config, views, migrations, API routes, web routes, the `2fa.rate_limit` middleware alias
3. **`Modules/Profile/Providers/FilamentProfilePanelProvider.php`** registers the dedicated Profile panel at `/profile/*` with its own auth + branding
4. **`composer.json`** PSR-4: `"Modules\\Profile\\": "Modules/Profile/"`

After a fresh clone:

```bash
composer dump-autoload
php artisan migrate  # picks up the two_factor_confirmed_at column migration
```

## Database schema

Profile owns no tables of its own — it extends the User module's `users` table by ONE column.

### `users` table extension

Migration `2024_01_24_095154_add_two_factor_confirmed_at_to_users_table.php`:

```php
Schema::table('users', function (Blueprint $table) {
    $table->timestamp('two_factor_confirmed_at')->nullable();
});
```

This column is `null` until the user finishes their 2FA setup confirmation flow (entering the first valid code from their authenticator app). After confirmation, all future logins prompt for the 2FA code.

## Filament panel registration

The Profile panel is **separate** from the admin panel. It's registered by `FilamentProfilePanelProvider`:

```php
return $panel
    ->id('profile')
    ->path('profile')             // mounts at /profile/*
    ->login()                     // /profile/login
    ->registration()              // /profile/register
    ->passwordReset()             // /profile/forgot-password
    ->emailVerification()
    ->profile()                   // /profile = the user's panel home
    ->discoverPages(...)
    ->pages([
        Login::class,
        Register::class,
        ForgotPassword::class,
        EditProfile::class,
        ChangePassword::class,
        TwoFactorAuth::class,
        OrderHistory::class,
        SavedAddresses::class,
    ]);
```

Don't put admin-only pages here; that's what the `/admin` panel is for. Don't put customer pages in `/admin`; the role separation is meaningful.

## Middleware: `2fa.rate_limit`

`Modules\Profile\Http\Middleware\TwoFactorRateLimit` throttles 2FA verification attempts to prevent brute-force on the 6-digit code:

```php
// Default: 5 attempts per minute per (ip + user_id)
Route::middleware('2fa.rate_limit')->group(function () {
    Route::post('/profile/2fa/verify', [TwoFactorAuth::class, 'verify']);
});
```

The alias is auto-registered by `ProfileServiceProvider`. Config in `Modules/Profile/config/twofactor.php`:

```php
return [
    'rate_limit' => [
        'max_attempts' => 5,
        'decay_minutes' => 1,
    ],
    'recovery_codes_count' => 8,
];
```

## Configuration

`config/twofactor.php` (loaded by the service provider):

| Key | Default | Purpose |
|---|---|---|
| `rate_limit.max_attempts` | `5` | Max 2FA verify attempts before throttle |
| `rate_limit.decay_minutes` | `1` | Throttle window |
| `recovery_codes_count` | `8` | Number of one-time recovery codes generated |

`config/config.php` is the module's metadata for Microweber's module loader; rarely edited.

## What `microweber:install` does

- Runs the `add_two_factor_confirmed_at_to_users_table` migration
- Registers the Profile panel with the Filament discovery
- No initial seed data — the panel uses the existing `users` table

## Disabling / replacing

The Profile panel can be disabled (customers lose the `/profile` portal) but the underlying data layer (User module) keeps working. Customers without the panel can still:

- Log in via the admin login if they're admins
- Have their data accessed by admins via the admin panel's User resource
- Receive verification + password-reset emails directly

To customize:

- Replace any of the 8 Filament pages by subclassing + re-registering with the panel
- Add new pages by creating a class under `Modules/Profile/Filament/Pages/` and adding it to the `pages([...])` call in `FilamentProfilePanelProvider`
- Swap the 2FA rate-limit middleware for a custom implementation by re-binding the `2fa.rate_limit` alias
- For per-tenant branding, subclass `FilamentProfilePanelProvider` and add tenant-aware `->brandName($tenant->name)` calls in `panel()`

## Caching

Profile-page data (avatar URL, fullname, etc.) is read via `User` accessors — no caching beyond Laravel's standard request lifecycle. The Order History page paginates via the standard Filament table, which doesn't cache.

For the 2FA secret + recovery codes (encrypted at rest on the `users` row), no extra cache: they're read per-request when the user lands on the verify page.
