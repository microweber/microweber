# Profile Module — Usage

End-user-facing patterns for the `/profile` panel + programmatic access.

## The 8 Filament panel pages

| Page | URL | Purpose |
|---|---|---|
| `Login` | `/profile/login` | Email + password sign-in. Shows social-login buttons if Socialite providers are configured. |
| `Register` | `/profile/register` | Self-service account creation with captcha. |
| `ForgotPassword` | `/profile/forgot-password` | Request a reset email. |
| `EditProfile` | `/profile` (default) | First name, last name, email, phone, avatar. |
| `ChangePassword` | `/profile/change-password` | Current + new + confirm. |
| `TwoFactorAuth` | `/profile/two-factor-auth` | Enable / disable / recovery codes. |
| `OrderHistory` | `/profile/order-history` | Paginated list of the user's orders (via Order module). |
| `SavedAddresses` | `/profile/saved-addresses` | CRUD billing/shipping addresses (via Customer module). |

## End-user flow

A new visitor:

1. Lands on `/profile/login` (or hits `/profile/anything` while logged out → redirected here)
2. Clicks "Register" → fills the form → submits → receives a verification email
3. Verifies → redirected to `/profile` (the Edit Profile page)
4. Optional: opens "Two-Factor Auth" → scans QR with authenticator app → enters code → confirmed (`two_factor_confirmed_at` is set)

A returning visitor with 2FA enabled:

1. Lands on `/profile/login` → enters credentials
2. Redirected to `/profile/two-factor-challenge` → enters 6-digit code OR uses a recovery code
3. Lands on `/profile`

## Edit profile

```html
<!-- The Filament page renders a form with these fields by default -->
<form>
    <input name="first_name" />
    <input name="last_name" />
    <input name="email" />
    <input name="phone" />
    <input type="file" name="avatar" />
    <button>Save</button>
</form>
```

Programmatically:

```php
use App\Models\User;

$user = auth()->user();
$user->update([
    'first_name' => 'Alice',
    'last_name'  => 'Smith',
    'phone'      => '+1 555-0100',
]);

// Avatar upload — the panel uploads to userfiles disk via Filament's standard FileUpload
```

## Change password

```php
use Illuminate\Support\Facades\Hash;

$user = auth()->user();

if (! Hash::check($request->current_password, $user->password)) {
    return back()->withErrors(['current_password' => 'Wrong password']);
}

$user->update(['password' => $request->new_password]);  // User mutator auto-hashes
```

The ChangePassword page wraps this with form validation + a flash success message.

## Two-Factor Auth flow

```php
// Enable (called from TwoFactorAuth Filament page's "Enable" button)
app(\Laravel\Fortify\Actions\EnableTwoFactorAuthentication::class)($user);

// At this point:
// - users.two_factor_secret is set (encrypted)
// - users.two_factor_recovery_codes is set (encrypted JSON)
// - users.two_factor_confirmed_at is still null

// The page renders a QR code:
echo $user->twoFactorQrCodeUrl();

// User enters their first code (proves the secret matches their app)
// On success:
$user->update(['two_factor_confirmed_at' => now()]);

// From here, login requires the 2FA challenge
```

Disable:

```php
app(\Laravel\Fortify\Actions\DisableTwoFactorAuthentication::class)($user);
// Clears two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at
```

## Recovery codes

When 2FA is enabled, the user gets N (default 8) one-time recovery codes. Each can be used once in place of the authenticator code on the challenge page.

```php
// Read (decrypted automatically by Fortify's cast)
$codes = json_decode(decrypt($user->two_factor_recovery_codes), true);

// Regenerate
app(\Laravel\Fortify\Actions\GenerateNewRecoveryCodes::class)($user);
```

The TwoFactorAuth page shows the codes ONCE after enabling — user is expected to download/print them.

## Order history

The OrderHistory page paginates `orders.user_id = auth()->id()` orders, newest first. Click a row to see the line-item details (renders `pages/order-details.blade.php`).

```php
// Programmatic equivalent
$orders = \Modules\Order\Models\Order::where('user_id', auth()->id())
    ->orderByDesc('created_at')
    ->paginate(20);
```

## Saved addresses

The SavedAddresses page lists addresses from the Customer satellite. The Customer module owns the `addresses` table (or a similarly-named one — see `Modules/Customer/`).

```php
$customer = auth()->user()->customer;
if (! $customer) {
    $customer = \Modules\Customer\Models\Customer::create(['user_id' => auth()->id()]);
}

$customer->addresses()->create([
    'label' => 'Home',
    'line1' => '123 Main St',
    'city'  => 'Springfield',
    'country' => 'US',
    'is_default_billing' => 1,
]);
```

The page surfaces a CRUD table with add/edit/delete actions + a "Set as default" toggle per address.

## REST API

3 endpoints exposed via `ProfileApiController`:

```bash
# Read the auth'd user's profile
curl -X GET https://yoursite.com/api/profile \
    -H "Authorization: Bearer ${TOKEN}" | jq .

# Update profile fields
curl -X PUT https://yoursite.com/api/profile \
    -H "Authorization: Bearer ${TOKEN}" \
    -H "Content-Type: application/json" \
    -d '{"first_name": "Alice", "phone": "+1 555 0100"}'

# Change password
curl -X PUT https://yoursite.com/api/profile/password \
    -H "Authorization: Bearer ${TOKEN}" \
    -H "Content-Type: application/json" \
    -d '{
        "current_password": "old",
        "password": "new",
        "password_confirmation": "new"
    }'
```

See [`api.md`](./api.md) for the full reference.

## Public `<module type="profile" />` short-tag

Templates can embed profile sections into a custom layout via the standard module short-tag:

```html
<!-- Renders the auth'd user's profile section, or a login form if not authed -->
<module type="profile" />

<!-- Render only the avatar -->
<module type="profile/avatar" />
```

The render layer routes the short-tag to the matching blade view under `Modules/Profile/resources/views/`.

## Profile visibility

By default profiles are private (only the user themselves + admins can view). To expose a public profile page:

1. Add a `public_profile_enabled` column to `users` (custom migration)
2. Toggle via Edit Profile (subclass the page + add a Toggle field)
3. Create a public route + controller that gates on the flag:

```php
Route::get('/u/{username}', function ($username) {
    $user = \App\Models\User::where('username', $username)
        ->where('public_profile_enabled', 1)
        ->firstOrFail();
    return view('public-profile', compact('user'));
});
```

The shipped module doesn't include public profile pages out of the box — that's a per-site customization.

## Profile completion tracking

A common pattern is showing a "Your profile is 70% complete" widget. Implement via:

```php
class User extends \Modules\Profile\Models\User
{
    public function profileCompletion(): int
    {
        $required = ['first_name', 'last_name', 'phone', 'email_verified_at'];
        $optional = ['avatar', 'bio', 'website'];

        $filled = 0;
        foreach ($required as $field) {
            if ($this->$field) $filled += 70 / count($required);
        }
        foreach ($optional as $field) {
            if ($this->$field) $filled += 30 / count($optional);
        }
        return (int) round($filled);
    }
}
```

Surface in EditProfile by overriding the panel's view + injecting `$user->profileCompletion()`.
