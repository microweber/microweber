# User Module

> **Slug:** `user`
> **Tier:** 1 (foundational — identity + auth + RBAC + API tokens)
> **Source:** `src/MicroweberPackages/User/`

The User module is Microweber's **identity foundation**. Operators rarely "use" this module directly — every other module sits on top of it. Authenticated requests, password resets, 2FA, API tokens, social login, customer-account integration — all of that flows through the `User` Eloquent model defined here.

## What this module does

- Owns the `users` table — the central identity store for the whole CMS
- Defines the `User` Eloquent model (extends Laravel's `Authenticatable`)
- Implements Filament's `FilamentUser` + `HasName` contracts (gates admin-panel access)
- Implements `MustVerifyEmail` for email-verification flow
- Implements `TwoFactorAuthenticatable` via Laravel Fortify
- Implements `HasApiTokens` via Laravel Passport for OAuth2 / token-based API auth
- Implements Socialite contracts for social login (Google / GitHub / Facebook / etc. — wired via `Modules/Multifactor` / Filament Socialite)
- Owns the `password_resets`, `personal_access_tokens`, `user_oauth_data` tables
- Provides auth controllers: login, register, logout, forgot-password, reset-password, verify-email
- Provides a Filament admin resource for user CRUD + role management
- Exposes user REST APIs at `/api/users` for admin operations

## Domain

User is at the **root of the identity domain**. Every authenticated action (a Page edit, a Product purchase, a Comment post) is attributed via `created_by` or a polymorphic `user_id`. The Customer module (`Modules/Customer/`) is a 1:1 satellite that carries shop-specific data (billing addresses, payment methods, order history) attached to a User.

Cross-references:

- **Filament admin** — the `canAccessPanel()` method gates which panel(s) a user can enter
- **Customer module** — `User::customer()` belongsTo relation; one Customer per User for shop flows
- **Order / Cart modules** — every Order has `user_id` linking back here
- **Sanctum + Passport** — token-based API auth; tokens are stored in `personal_access_tokens` and `oauth_access_tokens`
- **Fortify** — 2FA secret + recovery codes stored on the User row
- **Multifactor / Socialite** — third-party login providers map back to User via the `user_oauth_data` table
- **Admin model** (`Admin.php`) — a legacy thin wrapper for admin-only queries; new code should use `User::isAdmin()` instead

## Documentation map

| Page | Purpose |
|---|---|
| [`index.md`](./index.md) | This overview |
| [`installation.md`](./installation.md) | Schema, registration, auth-config setup, Passport/Fortify |
| [`usage.md`](./usage.md) | Registration / login / password reset / 2FA / roles / API tokens / social login |
| [`api.md`](./api.md) | REST endpoints + User Eloquent reference (accessors, contracts, helpers) |
| [`examples.md`](./examples.md) | End-to-end recipes |
| [`troubleshooting.md`](./troubleshooting.md) | Common auth + permission + token issues |

## Quick start

```php
use MicroweberPackages\User\Models\User;

// Create a user
$user = User::create([
    'username' => 'alice',
    'email'    => 'alice@example.com',
    'password' => 'plaintext-here',  // hashed by setPasswordAttribute()
    'first_name' => 'Alice',
    'last_name'  => 'Smith',
    'is_active'  => 1,
    'is_admin'   => 0,
]);

// Authenticate
auth()->attempt(['email' => 'alice@example.com', 'password' => 'plaintext-here']);

// Read the current user
$me = auth()->user();
echo $me->displayName();   // "Alice Smith" or username fallback
echo $me->avatarUrl();      // Gravatar URL
$me->isAdmin();             // bool

// Issue an API token
$token = $me->createToken('cli')->plainTextToken;
```

## Key files

- `src/MicroweberPackages/User/Models/User.php` — the parent Eloquent model
- `src/MicroweberPackages/User/Models/Admin.php` — legacy admin-only wrapper
- `src/MicroweberPackages/User/Models/PasswordReset.php` — password-reset tokens
- `src/MicroweberPackages/User/Models/PersonalAccessToken.php` — Sanctum/Passport token table model
- `src/MicroweberPackages/User/Models/UserOauthData.php` — Socialite provider data
- `src/MicroweberPackages/User/Http/Controllers/UserLoginController.php` — login flow
- `src/MicroweberPackages/User/Http/Controllers/UserRegisterController.php` — registration
- `src/MicroweberPackages/User/Http/Controllers/UserForgotPasswordController.php` — reset request
- `src/MicroweberPackages/User/Http/Controllers/UserVerifyController.php` — email verification
- `src/MicroweberPackages/User/Http/Controllers/Api/{AuthController,UserApiController,UsersApiController}.php` — REST surface
- `src/MicroweberPackages/User/Filament/` — admin user management
- `src/MicroweberPackages/User/Services/` — registration + login services
- `src/MicroweberPackages/User/Socialite/` — third-party provider integration
- `src/MicroweberPackages/User/Events/` — `UserIsCreating`, `UserWasCreated`, `UserWasUpdated`, etc.
- `src/MicroweberPackages/User/Notifications/` — password-reset + verify-email mailables

## Status

Production-stable, foundational. Every module that touches authenticated state depends on this. The auth flow has been stable for many releases — most "user bugs" trace to either config (session driver, mail driver for verification emails) or to per-application customization of the standard Laravel auth pipeline.
