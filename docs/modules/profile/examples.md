# Profile Module — Examples

## Recipe 1: Custom field on the Edit Profile form

Add a `bio` field to the user's profile.

```php
// 1. Migration — add the column to users
Schema::table('users', function (Blueprint $t) {
    $t->text('bio')->nullable()->after('phone');
});

// 2. Subclass the EditProfile Filament page
namespace App\Filament\Pages\Profile;

use Filament\Forms\Components\Textarea;

class EditProfile extends \Modules\Profile\Filament\Pages\EditProfile
{
    protected function getFormSchema(): array
    {
        return array_merge(parent::getFormSchema(), [
            Textarea::make('bio')
                ->label('About me')
                ->maxLength(500)
                ->rows(4),
        ]);
    }
}

// 3. Register the subclass with the panel in FilamentProfilePanelProvider
// (override the original registration with your subclass)
```

The form auto-saves `bio` to the `users.bio` column.

## Recipe 2: Force 2FA before viewing order history

```php
// Subclass OrderHistory page
namespace App\Filament\Pages\Profile;

class OrderHistory extends \Modules\Profile\Filament\Pages\OrderHistory
{
    public function mount(): void
    {
        $user = auth()->user();
        if (! $user->two_factor_confirmed_at) {
            \Filament\Notifications\Notification::make()
                ->title('2FA required to view order history')
                ->warning()
                ->send();
            redirect()->route('filament.profile.pages.two-factor-auth');
        }
        parent::mount();
    }
}
```

## Recipe 3: Avatar upload via the REST API

```bash
TOKEN="..."

# Read current profile to see if there's an existing avatar
curl -X GET https://yoursite.com/api/profile \
    -H "Authorization: Bearer $TOKEN" | jq .data.avatar_url

# Upload — note: the ProfileApiController doesn't ship a direct avatar
# upload route. Use the Media module's REST upload + then PUT /api/profile
# with the resulting media id, or use the Filament panel's UI.
curl -X POST https://yoursite.com/api/media \
    -H "Authorization: Bearer $TOKEN" \
    -F "file=@/tmp/avatar.jpg" \
    -F "rel_type=user" \
    -F "rel_id=$USER_ID" \
    -F "media_type=image"
```

## Recipe 4: Notify on every profile update

```php
// In a ServiceProvider boot()
use MicroweberPackages\User\Events\UserWasUpdated;

\Event::listen(UserWasUpdated::class, function (UserWasUpdated $event) {
    $user = $event->user;

    // Only fire when fields the user can change from /profile are touched
    $watched = ['first_name', 'last_name', 'email', 'phone', 'bio'];
    if (! collect($watched)->some(fn ($f) => $user->wasChanged($f))) {
        return;
    }

    \Mail::to($user->email)->queue(new \App\Mail\ProfileChanged($user));
});
```

## Recipe 5: Profile-completion progress widget

```php
// In Modules/Profile/Models/User.php subclass
public function profileCompletionPercent(): int
{
    $required = [
        'first_name', 'last_name', 'phone',
        'email_verified_at',
    ];
    $optional = ['bio', 'avatar'];

    $reqWeight = 70;
    $optWeight = 30;

    $reqFilled = collect($required)->filter(fn ($f) => filled($this->{$f}))->count();
    $optFilled = collect($optional)->filter(fn ($f) => filled($this->{$f}))->count();

    return (int) round(
        ($reqFilled / count($required)) * $reqWeight
        + ($optFilled / count($optional)) * $optWeight
    );
}
```

Render in a Filament widget on the EditProfile page header.

## Recipe 6: Public profile route

```php
// routes/web.php
\Route::get('/u/{username}', function ($username) {
    $user = \App\Models\User::where('username', $username)
        ->where('public_profile_enabled', 1)
        ->firstOrFail();

    return view('public-profile', [
        'user' => $user,
        'orderCount' => $user->orders()->where('order_completed', 1)->count(),
    ]);
});
```

```html
<!-- resources/views/public-profile.blade.php -->
<div class="public-profile">
    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->displayName() }}">
    <h1>{{ $user->displayName() }}</h1>
    @if($user->bio)
        <p class="bio">{{ $user->bio }}</p>
    @endif
    <p>Member since {{ $user->created_at->format('F Y') }}</p>
    <p>{{ $orderCount }} completed orders</p>
</div>
```

Requires adding the `public_profile_enabled` flag column + the EditProfile toggle.

## Recipe 7: Disable 2FA via REST as admin

```bash
# Only admins can do this (overriding a user's 2FA — emergency lockout recovery)
TOKEN_ADMIN="..."

curl -X DELETE "https://yoursite.com/api/users/${USER_ID}/2fa" \
    -H "Authorization: Bearer $TOKEN_ADMIN"
```

The corresponding controller method (in the User module's UsersApiController):

```php
public function disableTwoFactor(int $userId)
{
    abort_unless(auth()->user()->isAdmin(), 403);

    $user = \App\Models\User::findOrFail($userId);
    app(\Laravel\Fortify\Actions\DisableTwoFactorAuthentication::class)($user);

    return response()->noContent();
}
```

Useful when a user loses their authenticator app + their recovery codes.

## Recipe 8: Custom captcha provider on the Register page

```php
// Subclass Register page
namespace App\Filament\Pages\Profile;

class Register extends \Modules\Profile\Filament\Pages\Register
{
    protected function getFormSchema(): array
    {
        return array_merge(parent::getFormSchema(), [
            \Filament\Forms\Components\View::make('components.hcaptcha')
                ->viewData(['site_key' => env('HCAPTCHA_SITE_KEY')]),
        ]);
    }

    public function register()
    {
        // Verify hCaptcha before calling parent
        if (! $this->verifyCaptcha(request('h-captcha-response'))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'captcha' => 'Captcha verification failed',
            ]);
        }
        return parent::register();
    }
}
```

## Recipe 9: Per-user notification preferences

```php
// Add a preferences JSON column
Schema::table('users', function (Blueprint $t) {
    $t->json('notification_preferences')->nullable()->after('bio');
});

// Subclass User model
class User extends \Modules\Profile\Models\User
{
    protected $casts = [
        'notification_preferences' => 'array',
    ];

    public function wantsNotification(string $key): bool
    {
        return ($this->notification_preferences[$key] ?? true) === true;
    }
}

// Use before queueing a notification
$user = auth()->user();
if ($user->wantsNotification('order_shipped')) {
    $user->notify(new OrderShipped($order));
}
```

Add a "Notification Preferences" Filament page exposing each key as a toggle.

## Recipe 10: Saved-addresses bulk import via REST

```bash
TOKEN="..."

cat <<JSON | jq -c '.[]' | while read -r addr; do
    curl -X POST https://yoursite.com/api/profile/addresses \
        -H "Authorization: Bearer $TOKEN" \
        -H "Content-Type: application/json" \
        -d "$addr"
done <<JSON
[
    {"label":"Home", "line1":"123 Main St", "city":"Springfield", "country":"US", "is_default_billing":true},
    {"label":"Office", "line1":"456 Oak Ave", "city":"Springfield", "country":"US"}
]
JSON
```

The corresponding routes live on the Customer module (`Modules/Customer/`), not Profile. Profile renders the UI; Customer owns the data.
