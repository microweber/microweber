# Microweber Passport

OAuth2 / API token management for Microweber and any Laravel app, built on top of **Laravel Passport**. Adds stateless JSON login/logout endpoints, automatic RSA key generation, token last-used auditing, configurable scopes and expirations, and a Filament admin page for managing API keys.

## Features

- **Stateless token API** — `POST /api/passport/login` issues a personal-access token for email/username + password; `POST /api/passport/logout` revokes the current bearer token.
- **Auto-generated RSA keys** — `oauth-public.key` / `oauth-private.key` (4096-bit, PKCS8) are created in `storage/` on boot if missing, so the app never fails with "key not found".
- **Token auditing** — the `token.audit` middleware stamps `oauth_access_tokens.last_used_at` / `last_used_ip`, throttled by a cache key to avoid a write per request.
- **Configurable scopes & expirations** — token / refresh-token / personal-access-token lifetimes and the full CMS scope catalog are set from config.
- **Scope middleware aliases** — `scope` and `scopes` are registered for route protection.
- **Filament admin page** — `ApiApplicationsPage` for managing personal access tokens and OAuth2 applications (auto-registered when the CMS `FilamentRegistry` is present).

## Installation

```bash
composer require microweber-packages/microweber-passport
```

Inside Microweber the service provider is registered automatically by the framework's package discovery. In a standalone Laravel 11+ app, register it manually and run migrations:

```php
// bootstrap/providers.php
return [
    // ...
    MicroweberPackages\Passport\Providers\MicroweberPassportServiceProvider::class,
];
```

```bash
php artisan vendor:publish --tag=microweber-passport-config
php artisan vendor:publish --tag=microweber-passport-migrations
php artisan migrate
```

The provider registers Laravel Passport for you, loads Passport's own migrations plus the Microweber additions (`last_used_at`, `last_used_ip` columns), and generates the RSA keys on first boot.

Your `User` model must use Passport's `HasApiTokens` trait and the auth guard `api` should use the `passport` driver (standard Laravel Passport setup).

## Usage

### Login / logout (JSON API)

```bash
# Issue a token
curl -X POST https://your-site.com/api/passport/login \
  -H "Accept: application/json" \
  -d "email=user@example.com&password=secret"
# => { "token": "<JWT>", "token_type": "Bearer", "user": { ... } }

# Authenticated request
curl https://your-site.com/api/passport/user \
  -H "Authorization: Bearer <JWT>" -H "Accept: application/json"

# Revoke the current token
curl -X POST https://your-site.com/api/passport/logout \
  -H "Authorization: Bearer <JWT>" -H "Accept: application/json"
```

The `login` route accepts `email` **or** `username` and is rate-limited (`throttle:60,1`). `logout` and `user` run behind the `auth:api` guard.

### Protecting routes with scopes

```php
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'scope:products:write'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
});

// Any of several scopes
Route::middleware(['auth:api', 'scopes:orders:read,orders:write'])
    ->get('/orders', [OrderController::class, 'index']);
```

### Auditing token usage

Attach the `token.audit` middleware to stamp `last_used_at` / `last_used_ip`:

```php
Route::middleware(['auth:api', 'token.audit'])
    ->get('/me', fn (Request $r) => $r->user());
```

### Generating RSA keys manually

```php
use MicroweberPackages\Passport\Services\RSAKeyManager;

RSAKeyManager::ensureKeys(storage_path()); // no-op if keys already exist
```

### Filament admin

When the CMS `FilamentRegistry` is available the `ApiApplicationsPage` (slug `api-applications`, nav group *System Settings*) is registered automatically. In a standalone Filament panel, register it manually:

```php
use MicroweberPackages\Passport\Filament\Pages\ApiApplicationsPage;

$panel->pages([ApiApplicationsPage::class]);
```

Access is granted to admins via `is_admin()` inside the CMS, or any authenticated user standalone (override `canAccess()` in a subclass to change this).

## Configuration

Config file: `config/microweber-passport.php` (published as `microweber-passport.php`).

| Key | Env | Default | Purpose |
|-----|-----|---------|---------|
| `tokens_expire_days` | `MW_PASSPORT_TOKEN_EXPIRE_DAYS` | `15` | Access-token lifetime |
| `refresh_tokens_expire_days` | `MW_PASSPORT_REFRESH_TOKEN_EXPIRE_DAYS` | `30` | Refresh-token lifetime |
| `personal_access_tokens_expire_days` | `MW_PASSPORT_PAT_EXPIRE_DAYS` | `365` | Personal-access-token lifetime |
| `default_scope` | — | `['*']` | Scopes granted when none requested |
| `scopes` | — | CMS scope catalog | Available `:read` / `:write` scopes per module |
| `token_usage_stamp_interval_seconds` | `MW_PASSPORT_STAMP_INTERVAL` | `60` | Min seconds between `last_used_at` writes per token |
| `per_token_rate_limit_per_minute` | `MW_PASSPORT_RATE_LIMIT` | `120` | Per-token rate limit |

The `scopes` array ships a full catalog of `module:read` / `module:write` pairs (content, pages, posts, media, products, orders, users, settings, …). Override it entirely in your app config to customise what tokens may request.

## Key classes

- `MicroweberPackages\Passport\Providers\MicroweberPassportServiceProvider`
- `MicroweberPackages\Passport\Http\Controllers\Api\AuthController` — `login()`, `logout()`
- `MicroweberPackages\Passport\Http\Middleware\StampTokenLastUsed` — `token.audit` middleware
- `MicroweberPackages\Passport\Services\RSAKeyManager` — `ensureKeys(string $storagePath)`
- `MicroweberPackages\Passport\Filament\Pages\ApiApplicationsPage`

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
