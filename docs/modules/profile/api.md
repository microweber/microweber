# Profile Module — API Reference

## REST API

Base URL: `/api/profile`

Routes registered in `Modules/Profile/routes/api.php`. All endpoints require Sanctum bearer authentication (the user reads/writes their OWN profile — no admin-on-behalf-of flow on this controller).

### `GET /api/profile`

Returns the auth'd user's profile via `ProfileResource`.

Response:

```json
{
    "data": {
        "id": 12,
        "username": "alice",
        "email": "alice@example.com",
        "first_name": "Alice",
        "last_name": "Smith",
        "phone": "+15550100",
        "avatar_url": "https://www.gravatar.com/avatar/...",
        "two_factor_enabled": true,
        "two_factor_confirmed_at": "2026-05-13T10:00:00Z",
        "email_verified_at": "2026-05-12T08:00:00Z",
        "created_at": "2026-05-01T12:00:00Z"
    }
}
```

Sensitive fields (password hash, 2FA secret, recovery codes) are NEVER included.

### `PUT /api/profile`

Update the current user's profile.

```json
{
    "first_name": "Alice",
    "last_name": "Smith",
    "phone": "+15550100"
}
```

Validation:

- `first_name`, `last_name` — string, max 64
- `phone` — string, max 32
- `username` — string, max 64, unique (cannot collide with another user)
- `email` — email, unique (changing email may trigger a re-verification flow)

Returns the updated `ProfileResource`.

NOTE: `is_admin`, `is_active`, `password` cannot be changed from this endpoint. Use the User module's admin API for `is_admin`/`is_active`; use `PUT /api/profile/password` for password.

### `PUT /api/profile/password`

Change password.

```json
{
    "current_password": "old",
    "password": "new-min-8-chars",
    "password_confirmation": "new-min-8-chars"
}
```

Validation:

- `current_password` — required, must match the user's hashed password
- `password` — required, min 8 chars, confirmed
- `password_confirmation` — required, must match

Returns 204 on success. Returns 422 with field errors on validation failure.

## Eloquent reference

### `Modules\Profile\Models\User`

Extends `App\Models\User` (which itself extends `MicroweberPackages\User\Models\User`). The Profile-specific subclass adds:

- A scope `forProfilePanel()` that's used by the panel-side queries
- A hook for the panel's "active session" check

The class is mostly a marker — most behavior is inherited from the parent User model. See [`docs/modules/user/api.md`](../user/api.md) for the parent's full surface (attributes, mutators, accessors, 14 methods).

The Profile model can be safely cast to/from `App\Models\User` — they share the same `users` table.

## Middleware

### `Modules\Profile\Http\Middleware\TwoFactorRateLimit`

Throttles 2FA verification attempts. Aliased as `2fa.rate_limit` (registered automatically by `ProfileServiceProvider`).

Rate-limit key: `2fa-attempts:<ip>:<user_id>`.

Config in `Modules/Profile/config/twofactor.php`:

| Key | Default | Notes |
|---|---|---|
| `rate_limit.max_attempts` | `5` | Attempts allowed per window |
| `rate_limit.decay_minutes` | `1` | Window length |

When the limit is hit, returns 429 with a `Retry-After` header.

## Trait

### `Modules\Profile\Traits\HasTwoFactorAuthentication`

Shared helper trait for 2FA setup + confirmation flows. Used by both the Filament page + the API controller to keep logic DRY.

Methods exposed:

| Method | Returns |
|---|---|
| `enable2fa(User $user): array` | secret + recovery codes for first-time setup |
| `confirm2fa(User $user, string $code): bool` | true when the user enters their first valid TOTP code; sets `two_factor_confirmed_at` |
| `disable2fa(User $user): void` | clears 2FA columns |
| `verify2faCode(User $user, string $code): bool` | challenge-time verification |
| `verifyRecoveryCode(User $user, string $code): bool` | consume a one-time recovery code |
| `regenerateRecoveryCodes(User $user): array` | new codes, old ones invalidated |

## Resource

### `Modules\Profile\Http\Resources\ProfileResource`

API resource that shapes the response for `GET /api/profile` and `PUT /api/profile`. Filters out sensitive fields, computes derived fields like `avatar_url` + `two_factor_enabled`.

```php
public function toArray($request): array
{
    return [
        'id' => $this->id,
        'username' => $this->username,
        'email' => $this->email,
        'first_name' => $this->first_name,
        'last_name' => $this->last_name,
        'phone' => $this->phone,
        'avatar_url' => $this->avatarUrl(),
        'two_factor_enabled' => ! is_null($this->two_factor_confirmed_at),
        'two_factor_confirmed_at' => $this->two_factor_confirmed_at?->toIso8601String(),
        'email_verified_at' => $this->email_verified_at?->toIso8601String(),
        'created_at' => $this->created_at->toIso8601String(),
    ];
}
```

## Filament panel

### `Modules\Profile\Providers\FilamentProfilePanelProvider`

Registers the standalone `/profile/*` panel. Distinct from the admin panel:

- `panel->id('profile')` — separate panel ID
- `panel->path('profile')` — mounted at `/profile`
- Auth uses the same `User` guard but different post-login redirect
- 8 pages registered (see [`usage.md`](./usage.md))

### Filament pages list

All under `Modules\Profile\Filament\Pages\`:

- `Login` — `/profile/login`
- `Register` — `/profile/register`
- `ForgotPassword` — `/profile/forgot-password`
- `EditProfile` — `/profile` (default landing)
- `ChangePassword` — `/profile/change-password`
- `TwoFactorAuth` — `/profile/two-factor-auth`
- `OrderHistory` — `/profile/order-history`
- `SavedAddresses` — `/profile/saved-addresses`

Each is a standard Filament `Page` class with form-binding via Livewire.

## Helpers

No Microweber-specific profile helpers. Use `auth()` for the current user and `$user->customer` for the shop satellite.

## Events

Profile fires no events of its own. Lifecycle events on the User model (`UserWasUpdated`, etc.) fire when users edit their profile — see [`docs/modules/user/api.md`](../user/api.md) for the event list.

## Testing

```bash
./vendor/bin/phpunit --filter=ProfileApiControllerTest
```

Coverage lives in `Modules/Profile/Tests/`.
