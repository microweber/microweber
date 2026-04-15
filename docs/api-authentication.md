# API Authentication with Laravel Passport

Microweber uses [Laravel Passport](https://laravel.com/docs/passport) for API authentication, providing full OAuth2 server support with personal access tokens and OAuth application management.

## Overview

Passport provides multiple authentication methods:

1. **Personal Access Tokens** — Issue API tokens directly from the admin panel for stateless authentication
2. **OAuth2 Authorization Code** — Register OAuth applications for third-party integrations
3. **SPA Authentication** — Cookie-based session authentication for SPAs (via Sanctum, also included)

## Managing API Tokens

### Admin Panel

Navigate to **Settings > API Applications** (`/admin/api-applications`) to:

- **Create Personal Access Tokens** — Generate tokens for direct API access
- **Register OAuth Applications** — Create OAuth clients with redirect URIs
- **Revoke tokens and applications** — Disable access when no longer needed

### Creating a Personal Access Token

1. Go to `/admin/api-applications`
2. Enter a descriptive token name (e.g., "Mobile App", "CI/CD Pipeline")
3. Click **Create Token**
4. **Copy the token immediately** — it will not be shown again

### Programmatic Token Creation

```php
$user = \App\Models\User::find(1);

// Create a token with default scopes
$result = $user->createToken('My API Token');
$accessToken = $result->accessToken;

// Create a token with specific scopes
$result = $user->createToken('Limited Token', ['read-orders', 'read-products']);
```

## Making Authenticated Requests

Include the token in the `Authorization` header:

```http
GET /api/user
Authorization: Bearer {your-token}
Accept: application/json
```

### cURL Example

```bash
curl -X GET "https://your-domain.com/api/user" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### JavaScript Example

```javascript
const response = await fetch('/api/user', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
    }
});
const user = await response.json();
```

## OAuth2 Authorization Code Flow

For third-party applications:

### 1. Register an OAuth Application

In the admin panel at `/admin/api-applications`:
- Enter the application name
- Enter the redirect URI
- Click **Create Application**
- Copy the **Client ID** and **Client Secret**

### 2. Redirect Users to Authorize

```
GET /oauth/authorize?client_id=CLIENT_ID&redirect_uri=REDIRECT_URI&response_type=code&scope=*
```

### 3. Exchange Code for Token

```bash
POST /oauth/token
Content-Type: application/json

{
    "grant_type": "authorization_code",
    "client_id": "CLIENT_ID",
    "client_secret": "CLIENT_SECRET",
    "redirect_uri": "REDIRECT_URI",
    "code": "AUTHORIZATION_CODE"
}
```

### 4. Refresh an Access Token

```bash
POST /oauth/token
Content-Type: application/json

{
    "grant_type": "refresh_token",
    "client_id": "CLIENT_ID",
    "client_secret": "CLIENT_SECRET",
    "refresh_token": "REFRESH_TOKEN"
}
```

## Token Expiration

Default expiration settings (configured in `UserServiceProvider`):

| Token Type | Expiration |
|---|---|
| Access tokens | 15 days |
| Refresh tokens | 30 days |
| Personal access tokens | 1 year |

When a token expires, the API returns `401 Unauthenticated`.

## API Routes

### Protected Routes

```php
// routes/api.php
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
```

### OAuth Routes (auto-registered by Passport)

| Method | URI | Purpose |
|---|---|---|
| GET | `/oauth/authorize` | Authorization page |
| POST | `/oauth/authorize` | Approve authorization |
| DELETE | `/oauth/authorize` | Deny authorization |
| POST | `/oauth/token` | Issue access token |
| POST | `/oauth/token/refresh` | Refresh token |

## Configuration

### Environment Variables

```env
# Passport uses the RSA keys in storage/
# Keys are auto-generated on first boot by UserServiceProvider

# Optional: use env vars instead of key files
PASSPORT_PRIVATE_KEY=
PASSPORT_PUBLIC_KEY=

# Database connection for Passport tables
PASSPORT_CONNECTION=
```

### Config File

Published at `config/passport.php`:

```php
return [
    'guard' => 'web',
    'middleware' => [],
    'private_key' => env('PASSPORT_PRIVATE_KEY'),
    'public_key' => env('PASSPORT_PUBLIC_KEY'),
    'connection' => env('PASSPORT_CONNECTION'),
];
```

## Security Best Practices

1. **Use HTTPS** in production to prevent token interception
2. **Store tokens securely** — use httpOnly cookies or platform secure storage
3. **Set reasonable expiration** — shorter tokens for sensitive operations
4. **Revoke unused tokens** — regularly clean up from the admin panel
5. **Use scopes** — limit token abilities to only what's needed

## Database Tables

Passport creates these tables:

| Table | Purpose |
|---|---|
| `oauth_access_tokens` | Issued access tokens |
| `oauth_auth_codes` | Authorization codes |
| `oauth_clients` | Registered OAuth applications |
| `oauth_refresh_tokens` | Refresh tokens |
| `oauth_device_codes` | Device authorization codes |

## Further Reading

- [Laravel Passport Documentation](https://laravel.com/docs/passport)
- [OAuth2 Specification](https://oauth.net/2/)
