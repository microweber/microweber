# Profile Module — Troubleshooting

## `/profile` returns 404

1. **`FilamentProfilePanelProvider` not registered** — verify `config/app.php` has the provider in the `providers` array (auto-discovered from `composer.json` `extra.laravel.providers` in most setups).
2. **Module disabled** — `php artisan module:enable Profile` (or check `Modules/Profile/module.json`'s `active: true` flag).
3. **Conflicting route** — another module registered `/profile` first. Check `php artisan route:list | grep profile` to spot conflicts.

## `/profile` redirects to `/admin/login` instead of `/profile/login`

The auth guard's intended-redirect resolved to the admin panel's login. Common when:

1. **The user hit `/admin` before** and the session has `url.intended = /admin/...` — clear via `session()->forget('url.intended')` or just have them log in via `/profile/login` directly.
2. **Filament panel `path()` mismatch** — the provider's `->path('profile')` must match the URL prefix. Verify in `FilamentProfilePanelProvider`.

## 2FA setup QR not rendering

1. **`bacon/bacon-qr-code` missing** — `composer require bacon/bacon-qr-code` (Fortify dependency).
2. **`two_factor_secret` empty** — call `EnableTwoFactorAuthentication` action first. The page should do this on first visit; if it doesn't, the secret stays null and `twoFactorQrCodeUrl()` returns empty.
3. **App not loading the secret correctly** — Microweber stores the secret encrypted. If `decrypt($user->two_factor_secret)` fails with `DecryptException`, the `APP_KEY` has changed since the secret was stored. Reset by calling `EnableTwoFactorAuthentication` again (regenerates with the current key).

## 2FA verify returns 429 too quickly

Rate limit kicked in. Defaults: 5 attempts per minute per `(ip, user_id)`. Check:

```php
config('twofactor.rate_limit.max_attempts')   // 5
config('twofactor.rate_limit.decay_minutes')  // 1
```

Bump in `Modules/Profile/config/twofactor.php` if needed. Aggressive throttling is protective — keep low unless legitimate users keep hitting it.

To clear a stuck rate limit during dev:

```bash
php artisan cache:clear
```

## Recovery codes don't work

1. **User typed it wrong** — codes are case-sensitive depending on Fortify config; typically 10-character alphanumeric. Copy-paste from the page they were shown after enabling 2FA.
2. **Code already used** — recovery codes are one-time. Once consumed, they're removed from the encrypted JSON in `two_factor_recovery_codes`. Generate new ones via `GenerateNewRecoveryCodes` action.
3. **2FA disabled** — recovery codes only apply when 2FA is active. If `two_factor_confirmed_at` is null, the codes can't be used.

## Order History page shows other users' orders

CRITICAL bug — the page query missing the `where('user_id', auth()->id())` clause. Verify the OrderHistory Filament page's `getTableQuery()` (or equivalent):

```php
protected function getTableQuery(): Builder
{
    return \Modules\Order\Models\Order::query()
        ->where('user_id', auth()->id())   // ← THIS IS REQUIRED
        ->orderByDesc('created_at');
}
```

Without that clause, ALL orders are visible to ALL users. File as P0 if observed in production.

## Profile photo upload silently fails

1. **`max_upload_mb` (Media option) too low** — defaults to `10` or `50` depending on install. Verify: `get_option('max_upload_mb', 'media')`.
2. **PHP `upload_max_filesize` too low** — `php -i | grep upload_max`. Bump to ≥ what the Media option allows.
3. **Userfiles disk not writable** — `chmod` checks on `public/userfiles/uploads/`.
4. **MIME blocked** — `get_option('allowed_extensions', 'media')` must include `jpg`, `png`, `webp` for avatar uploads.

## Email verification link in /profile/register never arrives

1. **Mail driver misconfigured** — check `MAIL_*` env vars. Test with `php artisan tinker; \Mail::raw('test', fn($m) => $m->to('me@example.com')->subject('Test'));`.
2. **Email queued but not dispatched** — check `php artisan queue:work` or the configured queue driver. Microweber defaults to sync (immediate) for verification emails.
3. **Spam folder** — Gmail in particular flags many transactional emails. Verify SPF + DKIM records on the sending domain.

## Customer satellite is null after registration

The Customer (1:1 satellite) is created lazily — on first shop interaction, not at registration time. To eagerly create:

```php
\Event::listen(\MicroweberPackages\User\Events\UserWasCreated::class, function ($event) {
    \Modules\Customer\Models\Customer::firstOrCreate(['user_id' => $event->user->id]);
});
```

Add this to a service provider's `boot()`. After deployment, run a backfill for existing users:

```php
\App\Models\User::doesntHave('customer')->each(function ($user) {
    \Modules\Customer\Models\Customer::create(['user_id' => $user->id]);
});
```

## Filament admin doesn't show Profile-panel users separately

Both panels share the same `users` table — this is by design. The Filament admin lists all users including customers. To filter the admin view to admins only:

```php
// Override UserResource::getEloquentQuery() in a subclass
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->where('is_admin', 1);
}
```

Or add a Filament filter for `is_admin` instead of hiding outright.

## Where to file bugs

- Profile module: `Modules/Profile/`. Tests in `Modules/Profile/Tests/`.
- Auth-pipeline bugs (login, register, password reset): file against the **User module** at `src/MicroweberPackages/User/` first. Profile is the UX shell; User owns the underlying flow.
- 2FA primitives: against Fortify upstream OR the User module. Profile module owns the panel UX but not the action classes.
- Order history bugs: against **Order module** (`Modules/Order/`).
- Saved addresses bugs: against **Customer module** (`Modules/Customer/`).
