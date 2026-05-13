# User Module — Troubleshooting

## "These credentials do not match our records" despite correct password

1. **User `is_active = 0`?** Disabled users can't log in. `User::where('email', $email)->update(['is_active' => 1])` to re-enable.
2. **Password hash type mismatch** — Laravel's `Hash::check()` requires bcrypt. If the user was migrated from a legacy system with md5/sha1 hashes, login fails silently. Either:
    - Reset the password via the password-reset flow (the new hash is bcrypt)
    - Provide a one-time migration path in `LoginController` that re-hashes on first successful login
3. **`email` casing mismatch** — Microweber casts email to lowercase (`StrToLowerTrimCast`) but if a legacy row has uppercase email, comparison fails. Normalize:

    ```sql
    UPDATE users SET email = LOWER(TRIM(email));
    ```

4. **Auth driver misconfigured** — `config('auth.providers.users.model')` should print `MicroweberPackages\User\Models\User`. If it prints the stock `App\Models\User`, the install didn't override correctly.

## Email verification link returns 404

1. **`MAIL_*` env vars unset** — verification email was queued but never sent. Check `storage/logs/laravel.log` for mail errors.
2. **Signed URL expired** — Laravel's signed verification URLs default to 60 minutes. Resend via `$user->sendEmailVerificationNotification()`.
3. **APP_URL mismatch** — the signed URL embeds `APP_URL`; if the request hits a different host (CDN, preview env), the signature won't verify.

## "Password reset link is invalid or has expired"

1. **Token in `password_resets` table mismatched** — `DB::table('password_resets')->where('email', $email)->get()`. Should have one row with a `token` hash + `created_at` within `config('auth.passwords.users.expire')` minutes (default 60).
2. **User clicked an old link after a newer one was issued** — only the most recent token is valid. Send a fresh reset email.
3. **Hashing mismatch** — Laravel hashes the token before storing; the link contains the plaintext. If you migrated the table, plaintext tokens won't validate. Force users to request fresh resets.

## Filament admin redirects to /login in a loop

The user passes auth but fails the `canAccessPanel(Panel $panel)` check.

```php
// src/MicroweberPackages/User/Models/User.php
public function canAccessPanel(Panel $panel): bool
{
    // Default: only admins can access admin panel
    return $this->isAdmin();
}
```

Verify the user's `is_admin = 1`. For non-admin panels (customer-facing portal, etc.), override `canAccessPanel` to gate on the specific panel id.

## 2FA setup QR code doesn't render

1. **`bacon/bacon-qr-code` missing** — `composer require bacon/bacon-qr-code` (or the equivalent QR package the project uses).
2. **`two_factor_secret` not set** — call `app(\Laravel\Fortify\Actions\EnableTwoFactorAuthentication::class)($user)` first.
3. **SVG renderer disabled** — Fortify supports SVG + PNG QR codes. Check `config('fortify.features')`.

## API token authentication returns 401

1. **Token expired** — `personal_access_tokens.expires_at` < now(). Issue a new token.
2. **Token's `tokenable_id` doesn't match the user** — should happen rarely; usually indicates a manual DB edit. Revoke + reissue.
3. **Missing `Authorization: Bearer ...` header** — verify the request actually sends it. Some HTTP clients strip auth headers on redirects.
4. **Sanctum config** — `config('sanctum.guard')` should be `'web'`. `config('sanctum.expiration')` is the default token TTL (null = no expiration).

## Passport keys missing

```bash
ls -la storage/oauth-*.key
# Should exist
```

If missing:

```bash
php artisan passport:install --force
```

If `--force` errors out:

```bash
rm -f storage/oauth-*.key
php artisan passport:keys
php artisan passport:client --personal
```

## Socialite callback returns "User already exists"

Multiple users registered with the same email via different providers (one via Google, one via the standard flow). The `User::firstOrCreate(['email' => ...])` lookup picks the first match.

Resolution:

```php
// Merge: prefer the existing user, attach the OAuth provider data
$existing = User::where('email', $socialUser->email)->first();
$existing->update([
    'oauth_provider' => 'google',
    'oauth_uid' => $socialUser->id,
]);
\Modules\Multifactor\Models\UserOauthData::updateOrCreate(
    ['user_id' => $existing->id, 'provider' => 'google'],
    ['data' => json_encode($socialUser->user)]
);
auth()->login($existing);
```

## User can't be deleted — foreign key constraint

```
SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: 
  a foreign key constraint fails (`microweber`.`orders`, CONSTRAINT `orders_user_id_foreign` ...)
```

The user has shop activity (orders, posts, etc.) referencing them. Options:

1. **Cascade-delete the related rows** (destructive):

    ```php
    $user->orders()->delete();
    $user->posts()->delete();
    // ...
    $user->delete();
    ```

2. **Soft-delete** the user (preserves orphans):

    ```sql
    ALTER TABLE users ADD COLUMN deleted_at TIMESTAMP NULL;
    ```

    Then `$user->delete()` (with SoftDeletes trait) leaves the row but excludes from queries.

3. **Reassign** related rows to a "deleted user" placeholder before deleting.

The right choice depends on legal / audit-trail requirements. For GDPR "right to be forgotten" requests, option 1 (full cascade) is typically required.

## "Too many login attempts" — rate limiter

Laravel's `RouteServiceProvider` rate-limits login attempts. Defaults: 5/min per (ip + email). On lockout:

1. Wait the configured cooldown (60s).
2. Or clear the rate limit cache: `\Cache::clear()` (broad) or target the specific key in `cache:storage`.
3. For production debugging, raise the limit in `RouteServiceProvider::configureRateLimiting()`.

## Newly-created user can't log in immediately

The default user has `email_verified_at = null` and `MustVerifyEmail` blocks login until verified. Workarounds:

1. **Skip verification for admin-created users**:

    ```php
    User::create([... 'email_verified_at' => now()]);
    ```

2. **Send a working verification email** (most setups break this — check mail driver).
3. **Disable MustVerifyEmail globally** (not recommended for production):

    ```php
    // Don't implement MustVerifyEmail on a subclass
    class User extends \MicroweberPackages\User\Models\User {
        // Remove the interface and the trait
    }
    ```

## Where to file bugs

- User module: `src/MicroweberPackages/User/`. Tests in `src/MicroweberPackages/User/tests/`.
- Filament-admin-specific bugs: same module path under `Filament/`.
- Customer module 1:1 issues: belong against `Modules/Customer/` first.
- Cross-cutting auth-pipeline bugs (Laravel core): upstream.
