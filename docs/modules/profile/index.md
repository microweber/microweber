# Profile Module

> **Slug:** `profile`
> **Tier:** 1 (customer-facing Filament panel over the User identity)
> **Source:** `Modules/Profile/`

The Profile module is Microweber's **customer-facing profile panel** — a separate Filament panel (NOT the admin panel) that gives logged-in users a place to edit their profile, change their password, manage 2FA, view their order history, and manage saved addresses. Where the User module owns identity + auth, Profile owns the **end-user portal UX** on top.

## What this module does

- Provides a dedicated Filament panel (`FilamentProfilePanelProvider`) reachable at `/profile/*` — distinct from the `/admin/*` panel
- Filament pages for: Login, Register, Forgot Password, Edit Profile, Change Password, Two-Factor Auth setup/confirm, Order History, Saved Addresses
- Extends the canonical `User` model via `Modules\Profile\Models\User` to add profile-specific behavior (the `Profile\Models\User` is a customer-facing wrapper, NOT a separate table)
- Adds the `two_factor_confirmed_at` column to the `users` table (migration: `2024_01_24_095154_add_two_factor_confirmed_at_to_users_table.php`)
- Provides the `TwoFactorRateLimit` middleware (`2fa.rate_limit` alias) to throttle 2FA verification attempts
- Provides the `HasTwoFactorAuthentication` trait for shared 2FA logic
- Provides the `ProfileApiController` (3 endpoints: show / update / changePassword) for programmatic profile access
- Renders the `<module type="profile" />` short-tag for embedding profile sections in public pages

## Domain

Profile sits at the **customer-portal layer** of Microweber. Where:

- The **User module** (`src/MicroweberPackages/User/`) owns identity (`users` table, auth, sessions, tokens, Fortify 2FA primitives, Socialite)
- The **Customer module** (`Modules/Customer/`) owns shop-specific 1:1 satellite data (billing addresses, payment methods)
- The **Order module** owns purchase history
- The **Profile module** is the **UX shell** that surfaces all of the above to the end user

Operators don't use this panel — they use `/admin`. Customers and admins-as-customers use `/profile`.

Cross-references:

- **User module** (`src/MicroweberPackages/User/`) — the parent identity. Profile's `User` model extends `\App\Models\User` which is the canonical user
- **Customer module** — saved addresses + order history are joined via `customers.user_id`
- **Order module** — `OrderHistory` page paginates orders where `orders.user_id = auth()->id()`
- **Multifactor / Socialite** — social login shows up as login options on the Profile panel's `/profile/login` page
- **Captcha module** — registration form includes captcha via `resources/views/components/captcha.blade.php`

## Documentation map

| Page | Purpose |
|---|---|
| [`index.md`](./index.md) | This overview |
| [`installation.md`](./installation.md) | Registration, Filament panel config, 2FA middleware, schema |
| [`usage.md`](./usage.md) | The 8 Filament pages, registration/login/2FA flows, profile editing, order history |
| [`api.md`](./api.md) | REST endpoints + Profile User model + middleware + trait reference |
| [`examples.md`](./examples.md) | End-to-end recipes |
| [`troubleshooting.md`](./troubleshooting.md) | Common issues |

## Quick start

End-user flow (no code):

1. Anonymous visitor lands on `/profile/login` → logs in or clicks "Register"
2. After login, lands on `/profile` (Edit Profile page is the default)
3. Sidebar links: Edit Profile, Change Password, Two-Factor Auth, Order History, Saved Addresses

Programmatic profile access:

```bash
curl -X GET https://yoursite.com/api/profile \
    -H "Authorization: Bearer ${TOKEN}" | jq .
```

```php
// Read auth'd user's full profile (server-side)
$user = auth()->user();
echo $user->avatarUrl();
echo $user->getFullNameAttribute();
$user->customer;  // Customer satellite (Modules/Customer/)
```

## Key files

- `Modules/Profile/Providers/FilamentProfilePanelProvider.php` — registers the standalone `/profile` panel
- `Modules/Profile/Providers/ProfileServiceProvider.php` — boots config, routes, middleware
- `Modules/Profile/Models/User.php` — subclass extending `App\Models\User` with profile-specific scopes
- `Modules/Profile/Http/Controllers/Api/ProfileApiController.php` — 3 REST endpoints (show / update / changePassword)
- `Modules/Profile/Http/Resources/ProfileResource.php` — API response shape
- `Modules/Profile/Http/Middleware/TwoFactorRateLimit.php` — throttles 2FA attempts (alias `2fa.rate_limit`)
- `Modules/Profile/Traits/HasTwoFactorAuthentication.php` — shared 2FA helper logic
- `Modules/Profile/Filament/Pages/{Login,Register,ForgotPassword,EditProfile,ChangePassword,TwoFactorAuth,OrderHistory,SavedAddresses}.php` — 8 panel pages
- `Modules/Profile/database/migrations/2024_01_24_095154_add_two_factor_confirmed_at_to_users_table.php` — schema extension
- `Modules/Profile/config/twofactor.php` — 2FA rate-limit + recovery code config

## Status

Production-stable. The Profile panel is the canonical customer portal for any Microweber site that has an authenticated user-facing flow (shop checkout post-purchase, blog comment authors, course participants, etc.). Changes to this module's pages affect every customer that logs in — coordinate with the Customer module + Order module when modifying the order-history / addresses surfaces.
