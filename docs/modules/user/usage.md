# User Module — Usage

Day-to-day patterns for registration, login, password management, roles, tokens, and 2FA.

## Registration

```php
use MicroweberPackages\User\Models\User;

$user = User::create([
    'username'   => 'alice',
    'email'      => 'alice@example.com',
    'password'   => 'plaintext-here',  // hashed automatically
    'first_name' => 'Alice',
    'last_name'  => 'Smith',
    'is_active'  => 1,
    'is_admin'   => 0,
]);

// Trigger the email-verification notification
$user->sendEmailVerificationNotification();
```

The `User::setPasswordAttribute()` mutator hashes the plaintext on assignment — never store unhashed passwords.

## Login

```php
// Standard credentials
if (auth()->attempt(['email' => $email, 'password' => $password])) {
    // Authenticated; session started
    return redirect()->intended('/admin');
}

// With "remember me"
auth()->attempt(['email' => $email, 'password' => $password], $remember = true);

// Read the current user
$user = auth()->user();
$userId = auth()->id();

// Logout
auth()->logout();
$request->session()->invalidate();
$request->session()->regenerateToken();
```

## Password reset

```php
use Illuminate\Support\Facades\Password;

// Send the reset link
$status = Password::sendResetLink(['email' => $email]);

// Verify status
if ($status === Password::RESET_LINK_SENT) {
    // Email queued
}

// Reset (called from the link the user clicked)
$status = Password::reset(
    [
        'email'                 => $request->email,
        'password'              => $request->password,
        'password_confirmation' => $request->password_confirmation,
        'token'                 => $request->token,
    ],
    function ($user, $password) {
        $user->forceFill(['password' => $password])->save();
    }
);
```

Microweber's controllers wire this flow at `/forgot-password` and `/reset-password/{token}`.

## Role checks

```php
$user = auth()->user();

if ($user->isAdmin()) {
    // Show admin link
}

// Display name + avatar
echo $user->displayName();    // full name → username → email fallback
echo $user->avatarUrl();      // Gravatar URL
echo $user->avatar;           // accessor returning the Gravatar
echo $user->role_name;        // 'Admin' / 'User' label
```

For more granular RBAC (custom roles + permissions beyond is_admin), wire `spatie/laravel-permission` or a similar package and attach roles via its trait. Microweber's default is the simple two-tier `is_admin` flag.

## API tokens

```php
$user = auth()->user();

// Create
$token = $user->createToken('cli-tool')->plainTextToken;
// Returns "12|aBcDeFg..." — show once, can't be re-fetched

// List
$user->tokens()->get(['id', 'name', 'last_used_at', 'expires_at']);

// Revoke one
$user->tokens()->where('id', $tokenId)->delete();

// Revoke all
$user->tokens()->delete();
```

In API requests, callers attach `Authorization: Bearer <token>`. Sanctum middleware (`auth:sanctum`) validates against `personal_access_tokens` and resolves `auth()->user()`.

For OAuth2 (3rd-party clients, refresh tokens), use Passport directly — see [`api.md`](./api.md).

## Two-factor authentication

```php
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

// Enable 2FA for the current user
app(EnableTwoFactorAuthentication::class)($user);

// Returns the secret + recovery codes
$secret = decrypt($user->two_factor_secret);
$recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);

// Render the QR code for an authenticator app
echo $user->twoFactorQrCodeUrl();  // otpauth://totp/...

// Confirm — user enters the 6-digit code from their authenticator
// Fortify's controller handles this at /two-factor-challenge

// Disable
app(\Laravel\Fortify\Actions\DisableTwoFactorAuthentication::class)($user);
```

The user's 2FA state is in `two_factor_confirmed_at` — null means not yet confirmed.

## Social login

```php
// Redirect to provider
return \Socialite::driver('google')->redirect();

// Callback handler
$socialUser = \Socialite::driver('google')->user();

$user = User::firstOrCreate(
    ['email' => $socialUser->email],
    [
        'username' => $socialUser->nickname ?: \Str::slug($socialUser->name),
        'first_name' => $socialUser->user['given_name'] ?? '',
        'last_name'  => $socialUser->user['family_name'] ?? '',
        'oauth_provider' => 'google',
        'oauth_uid' => $socialUser->id,
        'is_active' => 1,
        'email_verified_at' => now(),  // provider already verified
    ]
);

auth()->login($user);
```

Filament Socialite wires this automatically when `dutchcodingcompany/filament-socialite` is installed and provider credentials are set.

## Querying users

```php
use MicroweberPackages\User\Models\User;

// All admins
$admins = User::where('is_admin', 1)->where('is_active', 1)->get();

// By email
$user = User::where('email', $email)->first();

// Recent registrations
$newUsers = User::where('created_at', '>=', now()->subWeek())
    ->orderByDesc('created_at')
    ->paginate(20);

// With customer (shop) relation eager-loaded
$user = User::with('customer')->find($id);
```

## Lifecycle events

```php
namespace MicroweberPackages\User\Events;
```

| Event | Fires when |
|---|---|
| `UserIsCreating` | Before insert |
| `UserWasCreated` | After insert |
| `UserWasUpdated` | After update |
| `UserWasDeleted` | After delete |
| `UserWasLoggedIn` | After successful login (custom Microweber event) |

Listeners register the standard way:

```php
\Event::listen(\MicroweberPackages\User\Events\UserWasCreated::class, function ($event) {
    \Log::info("New user: {$event->user->email}");
});
```

## Lifecycle in the Filament admin

`UserResource` (under `Modules/User/Filament/`) provides:

- Index with role / status / activity filters
- Create + edit form (username, email, password, name, role, active toggle)
- Bulk delete + bulk-activate + bulk-deactivate
- Per-row "send password reset" + "send verify email" actions

Operators can promote a user to admin via the role toggle, which writes `is_admin = 1`.

## Customer integration

```php
use MicroweberPackages\User\Models\User;
use Modules\Customer\Models\Customer;

$user = User::find($id);
$customer = $user->customer;  // 1:1 satellite — may be null if shop never used

// Create the satellite on first shop interaction
if (! $customer) {
    $customer = Customer::create(['user_id' => $user->id]);
}

// Now you can attach shop-specific data
$customer->update([
    'billing_address_line1' => '123 Main St',
    'billing_city' => 'Springfield',
]);
```

## Helpers

| Helper | Returns |
|---|---|
| `auth()->user()` | current `User` or null |
| `auth()->id()` | current user id or null |
| `auth()->check()` | bool |
| `auth()->guest()` | bool (inverse of `check()`) |
| `User::find($id)` | single User |
| `User::where('email', $email)->first()` | single User by email |

Microweber doesn't add its own user helper layer — Laravel's `auth()` facade is the canonical interface.
