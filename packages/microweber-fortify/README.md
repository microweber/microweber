# Fortify

Two-Factor Authentication for Microweber CMS and standalone Laravel apps. A thin, opinionated wrapper around [Laravel Fortify](https://laravel.com/docs/fortify) that adds TOTP (Google Authenticator) 2FA with QR provisioning, encrypted recovery codes, a Livewire challenge/setup UI, a Filament settings page, and a "force 2FA" middleware.

## Features

- **TOTP two-factor auth** built on `pragmarx/google2fa` + `bacon/bacon-qr-code`.
- **Encrypted secrets & recovery codes** via the `HasTwoFactorAuthentication` trait.
- **Livewire v4 components** for the setup and challenge flows.
- **Filament v5 settings page** (`TwoFactorSettingsPage`) registered through a panel plugin.
- **`require-2fa` middleware** to force users (all, or admins only) to enrol before continuing.
- **Microweber-aware login** — authenticates against the Microweber `User` model, accepts email *or* username, and disables the login rate-limiter under `APP_ENV=testing`.

## Installation

```bash
composer require microweber-packages/microweber-fortify
```

The `FortifyServiceProvider` extends Microweber's package base (`spatie/laravel-package-tools`). It
registers Laravel Fortify automatically if it isn't already loaded, loads the package migrations,
views (`microweber-fortify::`), routes and the `require-2fa` middleware alias, and registers the
Livewire components `two-factor-setup` and `two-factor-challenge`.

Publish config / views / migrations if you want to customise them:

```bash
php artisan vendor:publish --tag=microweber-fortify-config
php artisan vendor:publish --tag=microweber-fortify-views
php artisan vendor:publish --tag=microweber-fortify-migrations
php artisan migrate
```

## Usage

### 1. Add the trait to your User model

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use MicroweberPackages\Fortify\Traits\HasTwoFactorAuthentication;
use MicroweberPackages\Fortify\Contracts\TwoFactorAuthenticatable;

class User extends Authenticatable implements TwoFactorAuthenticatable
{
    use HasTwoFactorAuthentication;
}
```

The trait expects the `two_factor_secret`, `two_factor_recovery_codes` and `two_factor_confirmed_at`
columns (provided by the package migrations) and exposes:

```php
$user->hasTwoFactorEnabled();          // bool — secret set AND confirmed
$user->twoFactorQrCodeSvg();           // QR code SVG for the authenticator app
$user->twoFactorProvisioningUrl();     // otpauth:// URI
$user->generateRecoveryCodes();        // (re)generate one-time recovery codes
$user->recoveryCodes();                // array of current recovery codes
$user->validateTwoFactorCode($code);   // verify a 6-digit TOTP code
$user->useRecoveryCode($code);         // consume a recovery code (marks it used)
$user->getDecryptedTwoFactorSecret();  // decrypted TOTP secret
```

### 2. Register the Filament settings page

```php
use Filament\Panel;
use MicroweberPackages\Fortify\Filament\MicroweberFortifyPlugin;

public function panel(Panel $panel): Panel
{
    return $panel->plugin(
        MicroweberFortifyPlugin::make()
            ->navigationHidden()   // optional: hide "Two-Factor Auth" from the sidebar
    );
}
```

This adds `TwoFactorSettingsPage` (navigation group **Security**) where the user can enable/confirm
2FA, view the QR code and manage recovery codes.

### 3. Force 2FA with middleware

The provider aliases `RequireTwoFactor` as `require-2fa`:

```php
Route::middleware(['auth', 'require-2fa'])->group(function () {
    // Users without confirmed 2FA are redirected to the setup route.
});
```

Whether enrolment is forced is driven by `microweber-fortify.require2fa_all` /
`require2fa_admin_only` (also readable via Microweber `get_option()`).

## Configuration

`config/microweber-fortify.php`:

| Key | Default | Purpose |
|-----|---------|---------|
| `user_model` | `null` | User model for auth/2FA (`null` = `auth.providers.users.model`). |
| `require2fa_all` | `false` | Force every user to set up 2FA on login. |
| `require2fa_admin_only` | `false` | Force only admins to set up 2FA. |
| `setup_route` | `/two-factor/setup` | Where `require-2fa` redirects unenrolled users. |
| `rate_limit.max_attempts` / `rate_limit.decay_minutes` | `5` / `15` | Login lockout window. |
| `recovery_codes_count` | `8` | Number of recovery codes generated. |
| `qr_code_size` | `200` | QR code dimension in px. |
| `issuer` | `null` | TOTP issuer label (`null` = `app.name`). |

The standard Laravel `config/fortify.php` is also merged and publishable.

## Key classes

- `MicroweberPackages\Fortify\FortifyServiceProvider` — package wiring (routes, views, middleware, Livewire, Fortify config & rate limiting).
- `MicroweberPackages\Fortify\Traits\HasTwoFactorAuthentication` — the 2FA behaviour for your User model.
- `MicroweberPackages\Fortify\Contracts\TwoFactorAuthenticatable` — marker contract.
- `MicroweberPackages\Fortify\Filament\MicroweberFortifyPlugin` — Filament panel plugin.
- `MicroweberPackages\Fortify\Filament\Pages\TwoFactorSettingsPage` — the settings UI.
- `MicroweberPackages\Fortify\Http\Middleware\RequireTwoFactor` — `require-2fa` alias.
- `MicroweberPackages\Fortify\Http\Livewire\{TwoFactorSetupComponent, TwoFactorChallengeComponent}` — setup/challenge components.
- `MicroweberPackages\Fortify\Actions\{CreateNewUser, UpdateUserProfileInformation, UpdateUserPassword, ResetUserPassword}` — Fortify action classes.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
