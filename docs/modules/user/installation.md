# User Module — Installation

The User module is **foundational** — ships with Microweber, registered automatically, hard dependency of every other module that touches authenticated state.

## Prerequisites

- PHP ≥ 8.2
- Laravel 11 base
- Filament v5 — admin user resource
- `laravel/fortify` — 2FA support
- `laravel/passport` — OAuth2 token issuance for the API
- `laravel/socialite` + `dutchcodingcompany/filament-socialite` — social login (optional)
- Mail driver configured (`MAIL_MAILER` in `.env`) — required for verification + password-reset notifications

## Registration

Standard module pipeline:

1. **`composer.json`** PSR-4: `"MicroweberPackages\\User\\": "src/MicroweberPackages/User/"`
2. **`Providers/UserServiceProvider.php`** registers config, views, migrations, API routes, the Filament resource, the auth controllers, the event listeners
3. **`Providers/AuthServiceProvider.php`** wires the `User` model as the default Authenticatable for `config/auth.php`

`composer dump-autoload` after a fresh clone is sufficient — no special boot command needed.

## Database schema

### `users` table

| Column | Type | Notes |
|---|---|---|
| `id` | bigint primary | |
| `username` | varchar | Optional public handle (unique when set) |
| `email` | varchar | Email; unique |
| `email_verified_at` | timestamp | `null` until verified via the email-verification link |
| `password` | varchar | bcrypt hash — set via `setPasswordAttribute()` which hashes automatically |
| `first_name`, `last_name` | varchar | |
| `phone` | varchar | |
| `is_active` | tinyint | `0` disables login |
| `is_admin` | tinyint | `1` grants admin-panel access (also checked via `isAdmin()` method) |
| `is_verified` | tinyint | Manual verification flag distinct from email verification |
| `oauth_provider` | varchar | When set, user signed up via Socialite (`google`, `github`, etc.) |
| `oauth_uid` | varchar | The provider's user id |
| `two_factor_secret` | text | Fortify 2FA secret (encrypted) |
| `two_factor_recovery_codes` | text | Fortify recovery codes (encrypted) |
| `two_factor_confirmed_at` | timestamp | When the user finished 2FA setup |
| `remember_token` | varchar | Laravel "remember me" |
| `created_at`, `updated_at` | timestamp | |

### `password_resets` table

| Column | Type | Notes |
|---|---|---|
| `email` | varchar primary | |
| `token` | varchar | Hashed reset token |
| `created_at` | timestamp | |

### `personal_access_tokens` table

Sanctum + Passport unify here. Standard Laravel `personal_access_tokens` schema with `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`.

### `user_oauth_data` table

Social-login provider blob storage. Stores the raw OAuth response so Microweber can re-issue tokens without re-prompting the user for permission.

### Customer extension

The `Modules/Customer/` package adds a 1:1 satellite `customers` table linked by `user_id` for shop-specific fields (default billing address, default payment method, etc.). The User model's `customer()` belongsTo relation walks the link.

## Auth config

`config/auth.php` should set `User::class` as the default Authenticatable:

```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model'  => \MicroweberPackages\User\Models\User::class,
    ],
],
```

Microweber's install scripts set this automatically. Verify with `php artisan tinker`:

```php
config('auth.providers.users.model');
// Should print: MicroweberPackages\User\Models\User
```

## Mail driver

Email verification + password reset require a working mail driver:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@yourdomain
MAIL_PASSWORD=secret
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

The Settings module exposes these as admin-editable options (`option_group = 'email'`) — see [`docs/modules/settings/`](../settings/).

## Passport (API tokens)

Passport ships keys via `php artisan passport:install` on first install. Microweber's install scripts run this. Verify:

```bash
ls -la storage/oauth-*.key
# oauth-private.key + oauth-public.key should exist
```

If missing:

```bash
php artisan passport:install --force
```

Set the encryption key for the `oauth_personal_access_clients` table (Microweber's installer handles this; if running migrations by hand, follow [Passport's docs](https://laravel.com/docs/passport)).

## Fortify (2FA)

Fortify is enabled out of the box. To switch 2FA off site-wide:

```php
// config/fortify.php
'features' => [
    // Features::twoFactorAuthentication([...]),  // commented out
],
```

Per-user opt-in is the default — users enable 2FA from their profile page.

## Socialite (social login)

Configure provider credentials in `.env`:

```env
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT=https://yoursite.com/login/google/callback

GITHUB_CLIENT_ID=...
GITHUB_CLIENT_SECRET=...
GITHUB_REDIRECT=https://yoursite.com/login/github/callback
```

Then in `config/services.php`:

```php
'google' => [
    'client_id'     => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect'      => env('GOOGLE_REDIRECT'),
],
```

Microweber's `dutchcodingcompany/filament-socialite` integration auto-wires the matching login buttons in the admin and public login pages.

## What `microweber:install` does

1. Creates the `users` + ancillary tables
2. Creates an initial admin user (interactive prompt or via `--admin-email` / `--admin-password` flags)
3. Runs `php artisan passport:install`
4. Generates encryption keys
5. Seeds default option values for the admin panel (theme, language, etc.)

## Disabling / replacing

User cannot be disabled — every authenticated request would fail. To customize:

- Extend `User` and bind your subclass via `config/auth.php`'s `providers.users.model`
- Override Filament's `UserResource` from another module
- Hook into the lifecycle events (`UserIsCreating`, `UserWasCreated`, `UserWasUpdated`, `UserWasDeleted`) instead of overriding the model
- For custom registration flow, override `UserRegisterController` and re-register the `register` route
