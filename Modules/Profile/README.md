# Profile

User profile management module. Provides authentication, registration, profile editing, two-factor authentication, and a dedicated Filament frontend panel.

## Key Features

- User profile viewing and editing
- Two-factor authentication (2FA) with confirmation
- Rate limiting on 2FA attempts
- Dedicated Filament profile panel (separate from admin)
- Web and API route sets

## Key Classes

| Class | Purpose |
|---|---|
| `Models\User` | Extended user model |
| `Http\Controllers\ProfileController` | Profile API controller |
| `Http\Middleware\TwoFactorRateLimit` | 2FA rate limiting middleware |

## Database Tables

- Adds `two_factor_confirmed_at` column to the `users` table

## Panels

- **FilamentProfilePanelProvider** -- standalone Filament panel for user-facing profile management

## Routes

- `routes/web.php` -- web profile routes
- `routes/api.php` -- API profile routes (currently commented out, ready for activation)

## Configuration

```php
// config/config.php
'description' => 'User profile management module with authentication, registration, and profile editing capabilities.'
```

## Usage

```html
<module type="profile" />
```

Register the 2FA rate limit middleware:
```php
// Automatically aliased as '2fa.rate_limit'
Route::middleware('2fa.rate_limit')->group(function () {
    // 2FA verification routes
});
```
