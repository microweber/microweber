# User Module — API Reference

## REST API

Routes registered in `src/MicroweberPackages/User/routes/`. The User module ships three API controllers:

| Controller | Endpoints | Purpose |
|---|---|---|
| `AuthController` | `/api/auth/{login,register,logout,refresh}` | Token issuance + revocation |
| `UserApiController` | `/api/user/*` | Current-user self-service |
| `UsersApiController` | `/api/users/*` | Admin user CRUD |

### Auth

#### `POST /api/auth/register`

```json
{
    "username": "alice",
    "email": "alice@example.com",
    "password": "plaintext",
    "password_confirmation": "plaintext",
    "first_name": "Alice",
    "last_name": "Smith"
}
```

Response `201` with the new user + an initial access token.

#### `POST /api/auth/login`

```json
{
    "email": "alice@example.com",
    "password": "plaintext"
}
```

Response: `{ "user": {...}, "access_token": "..." }`.

#### `POST /api/auth/logout`

Requires `Authorization: Bearer <token>`. Revokes the current token. `204` on success.

#### `POST /api/auth/refresh`

Issues a new access token from the current one.

### Current user

#### `GET /api/user`

Returns the authenticated user.

#### `PUT /api/user`

Update the current user's own profile fields (first_name, last_name, phone, etc.). Cannot change `email` or `is_admin` from this endpoint — those require admin scope.

#### `PUT /api/user/password`

```json
{
    "current_password": "old",
    "password": "new",
    "password_confirmation": "new"
}
```

#### `POST /api/user/email/verify-resend`

Re-sends the verification email.

### Admin user CRUD

All require Sanctum bearer with admin scope (`is_admin = 1`).

#### `GET /api/users` — list

Standard pagination + filter params: `search`, `is_admin`, `is_active`, `email_verified`, `created_after`, `created_before`.

#### `POST /api/users` — create

Same payload as `register`, plus optional `is_admin`, `is_active`.

#### `GET /api/users/{id}` — show

#### `PUT /api/users/{id}` — update

Full update including admin-only fields (`is_admin`, `is_active`, `email`).

#### `DELETE /api/users/{id}` — destroy

Hard-deletes (cascade-deletes related `personal_access_tokens`, `user_oauth_data`, `customers` rows). Returns `204`.

## Eloquent reference

### `MicroweberPackages\User\Models\User`

Extends Laravel's `Illuminate\Foundation\Auth\User as Authenticatable`. Implements `MustVerifyEmail`, `FilamentUser`, `HasName`, `FilamentSocialiteUserContract`, `OAuthenticatable`.

#### Traits

- `Notifiable` — sendable via `User::notify()`
- `HasFactory` — `User::factory()`
- `HasApiTokens` (Passport) — `createToken()`, `tokens()`
- `TwoFactorAuthenticatable` (Fortify) — 2FA secret + recovery codes
- `Filterable` — eloquent-filter
- `CanResetPassword` — password-reset notifications
- `HasSearchableTrait` — full-text search via the `Searchable` trait
- `CacheableQueryBuilderTrait` — Microweber's request-cache layer

#### Attributes

`id`, `username`, `email`, `email_verified_at`, `password`, `first_name`, `last_name`, `phone`, `is_active`, `is_admin`, `is_verified`, `oauth_provider`, `oauth_uid`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`.

#### Mutators

- `setPasswordAttribute($pass)` — auto-hashes plaintext on assignment

#### Accessors

- `avatar` — Gravatar URL
- `role_name` — `'Admin'` if `is_admin = 1`, else `'User'`
- `full_name` — `"first_name last_name"` joined

#### Methods

| Method | Returns |
|---|---|
| `isAdmin(): bool` | True when `is_admin = 1` |
| `displayName(): string` | full_name → username → email fallback |
| `avatarUrl(): string` | Gravatar URL |
| `canAccessPanel(Panel $panel): bool` | Filament gate — true when user can enter this panel |
| `getFilamentName(): string` | Display name in Filament admin |
| `twoFactorQrCodeUrl(): string` | otpauth:// URL for the user's authenticator app |
| `sendPasswordResetNotification($token): void` | Queue the reset-email notification |
| `customer(): BelongsTo` | Modules\Customer\Models\Customer relation |
| `findForPassport($username)` | Passport user-lookup callback |
| `setToken($token): void` | Socialite contract helper |
| `setRefreshToken($refreshToken): void` | Socialite contract helper |
| `setExpiresIn($expiresIn): void` | Socialite contract helper |
| `getUser(): Authenticatable` | Socialite contract helper |
| `validateAndFill(array $data): bool` | Validates input against the user-validation rules + fills the model |

#### Scopes

- `Filterable` provides `filter([...])` for declarative model-filter usage

## Auxiliary models

### `MicroweberPackages\User\Models\Admin`

Legacy thin wrapper. Newer code uses `User::isAdmin()` instead. Kept for backward compat.

### `MicroweberPackages\User\Models\PasswordReset`

`password_resets` table model. Used by the password-reset flow but rarely touched directly — the standard Laravel `Password` facade handles all interactions.

### `MicroweberPackages\User\Models\PersonalAccessToken`

Subclass of `Laravel\Sanctum\PersonalAccessToken` (and Passport's equivalent). Lets Microweber attach extra scoping / abilities to issued tokens.

### `MicroweberPackages\User\Models\UserOauthData`

Stores raw Socialite provider responses keyed by user_id + provider. Lets Microweber re-issue provider tokens without re-prompting.

## Events

| Event | Constructor signature |
|---|---|
| `UserIsCreating` | `(array $attrs)` |
| `UserWasCreated` | `(User $user)` |
| `UserWasUpdated` | `(User $user)` |
| `UserWasDeleted` | `(User $user)` |
| `UserWasLoggedIn` | `(User $user)` |

## Helpers

Laravel's `auth()` facade is the canonical interface. No Microweber-specific user helpers.

## Filament admin

`MicroweberPackages\User\Filament\Resources\UserResource` provides the admin UI:

- Index: search, role filter, active filter
- Create/Edit: username, email, password, profile fields, role toggle, active toggle
- Bulk actions: delete, activate, deactivate
- Per-row actions: send password reset, send email verification

## Testing

```bash
./vendor/bin/phpunit --filter=UserApiControllerTest
./vendor/bin/phpunit --filter=AuthControllerTest
```

Coverage lives in `src/MicroweberPackages/User/tests/`.
