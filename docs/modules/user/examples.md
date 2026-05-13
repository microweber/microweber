# User Module — Examples

## Recipe 1: Programmatically create an admin user from a script

```php
use MicroweberPackages\User\Models\User;

$admin = User::create([
    'username'          => 'super',
    'email'             => 'super@yoursite.com',
    'password'          => 'ChangeMeOnFirstLogin!',  // auto-hashed
    'first_name'        => 'Super',
    'last_name'         => 'Admin',
    'is_active'         => 1,
    'is_admin'          => 1,
    'email_verified_at' => now(),
]);

echo "Created admin {$admin->id} — login at /admin\n";
```

For an interactive command-line install: `php artisan microweber:install --admin-email=... --admin-password=...`.

## Recipe 2: Listen for new registrations and Slack-notify

```php
// In a ServiceProvider boot()
use MicroweberPackages\User\Events\UserWasCreated;
use Illuminate\Support\Facades\Http;

\Event::listen(UserWasCreated::class, function (UserWasCreated $event) {
    $user = $event->user;

    Http::post(config('services.slack.webhook'), [
        'text' => sprintf(
            "New user registered: *%s* (%s) — admin: %s",
            $user->displayName(),
            $user->email,
            $user->is_admin ? 'yes' : 'no'
        ),
    ]);
});
```

## Recipe 3: Bulk-deactivate stale users

```php
use MicroweberPackages\User\Models\User;

// Find users who haven't logged in for 12+ months
// (assumes you track login timestamps separately — adjust if you don't)
$stale = User::where('is_active', 1)
    ->where('last_login_at', '<', now()->subYear())
    ->where('is_admin', 0)
    ->get();

foreach ($stale as $user) {
    $user->update(['is_active' => 0]);
    \Log::info("Deactivated stale user #{$user->id} ({$user->email})");
}
```

For users who already have no `last_login_at` data, use `created_at` as a proxy.

## Recipe 4: Custom registration with extra validation

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\User\Models\User;

class CustomRegisterController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'first_name' => 'required|string|max:64',
            'last_name'  => 'required|string|max:64',
            'invite_code' => 'required|exists:invite_codes,code',
        ]);

        $user = User::create($data + ['is_active' => 1]);
        $user->sendEmailVerificationNotification();

        // Mark the invite as used
        \DB::table('invite_codes')->where('code', $data['invite_code'])
            ->update(['used_by' => $user->id, 'used_at' => now()]);

        auth()->login($user);
        return redirect('/welcome');
    }
}
```

## Recipe 5: Issue a CI-bot API token

```php
$bot = \MicroweberPackages\User\Models\User::firstOrCreate(
    ['email' => 'ci-bot@yoursite.com'],
    [
        'username' => 'ci-bot',
        'password' => bin2hex(random_bytes(32)),  // unguessable, never used directly
        'is_active' => 1,
        'is_admin' => 0,  // give specific abilities instead
    ]
);

$token = $bot->createToken('github-actions', ['content:write', 'media:write'])
    ->plainTextToken;

echo "GH Actions token: {$token}\n";
echo "Add as repo secret CI_API_TOKEN\n";
```

Use ability strings to scope what the token can do — the Sanctum middleware can gate routes by ability via `auth:sanctum,scope`.

## Recipe 6: Migrate existing legacy users into Microweber

```php
use MicroweberPackages\User\Models\User;

$legacy = \DB::connection('legacy')->table('old_users')->cursor();

foreach ($legacy as $row) {
    $existing = User::where('email', $row->email)->first();
    if ($existing) continue;

    $user = new User();
    $user->email = $row->email;
    $user->first_name = $row->fname;
    $user->last_name = $row->lname;
    $user->username = $row->handle ?: \Str::slug($row->fname . ' ' . $row->lname);

    // Don't pass through setPasswordAttribute — we already have a hash
    $user->forceFill([
        'password' => $row->password_hash,  // assumed bcrypt-compatible
        'is_active' => $row->status === 'active' ? 1 : 0,
        'created_at' => $row->created_at,
    ])->save();

    \Log::info("Migrated user {$row->email} → #{$user->id}");
}
```

If the legacy hashes are NOT bcrypt-compatible (md5, sha1, etc.), force the user to reset their password on first login by setting `password_must_change = 1` (custom column) and gating in middleware.

## Recipe 7: Force 2FA for all admins

```php
// In a ServiceProvider boot()
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

\Auth::extend('admin-2fa-required', function ($app, $name, array $config) {
    $guard = new \Illuminate\Auth\SessionGuard(
        $name,
        $app['auth']->createUserProvider($config['provider'] ?? null),
        $app['session.store']
    );

    $guard->setUser($app['request']->user());

    return $guard;
});

// Then on every admin route, check:
\Route::middleware(['auth', function ($request, $next) {
    $user = $request->user();
    if ($user->is_admin && ! $user->two_factor_confirmed_at) {
        return redirect('/admin/2fa-setup-required');
    }
    return $next($request);
}])->group(function () { /* admin routes */ });
```

The redirect page should walk admins through 2FA setup before letting them into the panel.

## Recipe 8: Audit log every admin action

```php
use MicroweberPackages\User\Events\UserWasUpdated;

// Listen for admin-flag changes specifically
\Event::listen(UserWasUpdated::class, function (UserWasUpdated $event) {
    $user = $event->user;

    if ($user->wasChanged('is_admin')) {
        \DB::table('audit_log')->insert([
            'event' => 'user.admin_changed',
            'user_id' => $user->id,
            'old_value' => $user->getOriginal('is_admin'),
            'new_value' => $user->is_admin,
            'changed_by' => auth()->id(),
            'created_at' => now(),
        ]);
    }
});
```

## Recipe 9: Export all users as CSV

```php
use MicroweberPackages\User\Models\User;

$csv = fopen('php://temp', 'w+');
fputcsv($csv, ['id', 'email', 'username', 'first_name', 'last_name', 'is_admin', 'is_active', 'created_at']);

User::orderBy('id')->chunkById(500, function ($users) use ($csv) {
    foreach ($users as $user) {
        fputcsv($csv, [
            $user->id, $user->email, $user->username,
            $user->first_name, $user->last_name,
            $user->is_admin, $user->is_active, $user->created_at,
        ]);
    }
});

rewind($csv);
file_put_contents(storage_path('app/users-export-' . now()->format('Y-m-d') . '.csv'), stream_get_contents($csv));
fclose($csv);
```

## Recipe 10: REST API — register from a curl script

```bash
curl -X POST https://yoursite.com/api/auth/register \
    -H "Content-Type: application/json" \
    -d '{
        "email": "alice@example.com",
        "password": "S3cur3P@ss",
        "password_confirmation": "S3cur3P@ss",
        "first_name": "Alice",
        "last_name": "Smith"
    }' | jq .

# Response includes the user + access_token
# Use the token for subsequent admin / user-scoped API calls:
curl -H "Authorization: Bearer ${TOKEN}" https://yoursite.com/api/user | jq .
```
