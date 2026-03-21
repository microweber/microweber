# API Authentication with Laravel Sanctum

Microweber uses [Laravel Sanctum](https://laravel.com/docs/sanctum) for API authentication, providing a lightweight authentication system for SPAs (Single Page Applications), mobile applications, and simple token-based APIs.

## Overview

Sanctum provides two primary authentication methods:

1. **Token-based API Authentication** - Issue API tokens to users for stateless authentication
2. **SPA Authentication** - Cookie-based session authentication for SPAs (via CORS)

This guide focuses on token-based authentication for API endpoints.

## Prerequisites

Sanctum is already included in Microweber. The package is automatically registered via `UserServiceProvider`:

```php
// In src/MicroweberPackages/User/Providers/UserServiceProvider.php
$this->app->register(\Laravel\Sanctum\SanctumServiceProvider::class);
Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
```

The `User` model already includes the `HasApiTokens` trait:

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasName
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasApiTokens, ...
```

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# Sanctum Configuration
SANCTUM_TOKEN_PREFIX=mw_token_
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,localhost:8000,your-domain.com
SANCTUM_EXPIRATION=1440

# CORS Configuration (already configured in config/cors.php)
# Ensure API routes are included in CORS paths
CORS_ALLOWED_ORIGINS=http://localhost:3000,https://your-domain.com
```

### Configuration File

Publish Sanctum configuration (optional):

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

This creates `config/sanctum.php` with the following key options:

```php
return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1'
    )),
    
    'guard' => ['web'],
    
    'expiration' => env('SANCTUM_EXPIRATION', 1440), // Token expiration in minutes (24 hours)
    
    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),
    
    'middleware' => [
        'authenticate_session' => Laravel\Sanavel\Http\Middleware\AuthenticateSession::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
    ],
];
```

### Database Migration

Sanctum requires a `personal_access_tokens` table. The migration is already included:

```bash
# Run migrations
php artisan migrate
```

This creates the table with:
- `id` - Primary key
- `tokenable_id` & `tokenable_type` - Polymorphic relation to users
- `name` - Token identifier
- `token` - Hashed token value
- `abilities` - JSON array of token abilities/permissions
- `last_used_at` - Timestamp of last token usage
- `expires_at` - Token expiration timestamp

## API Authentication Flow

### 1. Token Creation

Tokens can be created programmatically or via the API authentication endpoint:

**Via API Endpoint:**

```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response:**

```json
{
    "token": "1|laravel_sanctum_token_here",
    "token_type": "Bearer",
    "user": {
        "id": 1,
        "email": "user@example.com",
        "first_name": "John",
        "last_name": "Doe",
        ...
    }
}
```

**Programmatic Token Creation:**

```php
use App\Models\User;

// Create a user instance
$user = User::find(1);

// Create a token
$token = $user->createToken('api-token');
$plainTextToken = $token->plainTextToken;

// Create token with specific abilities
$token = $user->createToken('orders-token', ['orders:read', 'orders:create']);

// Create token with expiration
$token = $user->createToken('temp-token', ['*'])->expiresAt(now()->addHours(2));
```

### 2. Protecting API Routes

Apply the `auth:sanctum` middleware to protect API routes:

```php
// routes/api.php
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Protected routes - require authentication
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    
    // Cart
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::delete('/cart/items/{item}', [CartController::class, 'removeItem']);
});

// Admin-only routes
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/settings', [SettingsController::class, 'index']);
});
```

### 3. Making Authenticated Requests

Include the token in the `Authorization` header:

```http
GET /api/user
Authorization: Bearer {your-token}
Accept: application/json
```

**Example with cURL:**

```bash
curl -X GET "https://your-domain.com/api/user" \
  -H "Authorization: Bearer 1|your-token-here" \
  -H "Accept: application/json"
```

**Example with JavaScript (fetch):**

```javascript
const token = localStorage.getItem('api_token');

fetch('/api/user', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
})
.then(response => response.json())
.then(data => console.log(data));
```

**Example with axios:**

```javascript
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
axios.defaults.headers.common['Accept'] = 'application/json';

// All subsequent requests include the token
axios.get('/api/user')
  .then(response => console.log(response.data));
```

### 4. Token Abilities (Scopes)

Sanctum supports token abilities (similar to OAuth scopes) for fine-grained access control:

```php
// Create token with specific abilities
$token = $user->createToken('api-token', ['orders:read', 'profile:read']);

// Check ability in controller
public function update(Request $request, Order $order)
{
    if (!$request->user()->tokenCan('orders:update')) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    
    // Update order...
}
```

### 5. Token Management

**List User Tokens:**

```php
$user = Auth::user();
$tokens = $user->tokens;

foreach ($tokens as $token) {
    echo $token->name; // Token name
    echo $token->last_used_at; // Last usage timestamp
    echo $token->expires_at; // Expiration timestamp
}
```

**Revoke Specific Token:**

```php
// Revoke by token ID
$user->tokens()->where('id', $tokenId)->delete();

// Revoke current token
$request->user()->currentAccessToken()->delete();
```

**Revoke All Tokens:**

```php
// Revoke all tokens for user
$user->tokens()->delete();

// Revoke all tokens except current
$user->tokens()->where('id', '!=', $request->user()->currentAccessToken()->id)->delete();
```

## Token Expiration

Tokens can be configured to expire after a specified duration:

```php
// In config/sanctum.php
'expiration' => 1440, // 24 hours (in minutes)
```

When a token expires, API requests will return a `401 Unauthorized` response:

```json
{
    "message": "Unauthenticated."
}
```

The client should handle this by:
1. Redirecting to login page
2. Refreshing the token (if using refresh tokens)
3. Prompting user to re-authenticate

## API Authentication Controller

Here's a complete example controller:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Authenticate user and issue token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        // Create token with device name
        $token = $user->createToken($request->device_name ?? 'API Token');
        
        return response()->json([
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => now()->addMinutes(config('sanctum.expiration', 1440)),
            'user' => $user->only(['id', 'email', 'first_name', 'last_name']),
        ]);
    }
    
    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
            'token' => [
                'id' => $request->user()->currentAccessToken()->id,
                'name' => $request->user()->currentAccessToken()->name,
                'abilities' => $request->user()->currentAccessToken()->abilities,
                'last_used_at' => $request->user()->currentAccessToken()->last_used_at,
            ],
        ]);
    }
    
    /**
     * Revoke current token (logout)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
    
    /**
     * Revoke all tokens (logout from all devices)
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out from all devices',
        ]);
    }
    
    /**
     * List all user tokens
     */
    public function tokens(Request $request)
    {
        return response()->json([
            'tokens' => $request->user()->tokens->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'last_used_at' => $token->last_used_at,
                    'created_at' => $token->created_at,
                    'expires_at' => $token->expires_at,
                ];
            }),
        ]);
    }
}
```

## Routes Configuration

Add these routes to `routes/api.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\HealthCheckController;

// Health check (public)
Route::get('/health', [HealthCheckController::class, 'index']);

// Authentication
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('/auth/tokens', [AuthController::class, 'tokens']);
});
```

## Security Best Practices

### 1. Use HTTPS

Always use HTTPS in production to prevent token interception:

```env
APP_URL=https://your-domain.com
```

### 2. Token Storage

Store tokens securely:

- **Web Apps**: Use `httpOnly` cookies or secure localStorage with encryption
- **Mobile Apps**: Use platform secure storage (Keychain for iOS, Keystore for Android)
- **SPA**: Consider using Sanctum's cookie-based SPA authentication instead of tokens

### 3. Token Expiration

Set reasonable token expiration times:

```env
# Short expiration for security (24 hours)
SANCTUM_EXPIRATION=1440

# Or use longer for convenience (7 days)
SANCTUM_EXPIRATION=10080
```

### 4. Rate Limiting

Protect authentication endpoints from brute force attacks:

```php
// In app/Providers/RouteServiceProvider.php
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});
```

Apply to routes:

```php
Route::post('/auth/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');
```

### 5. Token Rotation

Implement token rotation for enhanced security:

```php
public function refresh(Request $request)
{
    $user = $request->user();
    
    // Delete current token
    $request->user()->currentAccessToken()->delete();
    
    // Create new token
    $token = $user->createToken('refreshed-token');
    
    return response()->json([
        'token' => $token->plainTextToken,
        'token_type' => 'Bearer',
    ]);
}
```

### 6. Audit Token Usage

Monitor token usage for suspicious activity:

```php
// Log token usage
\Log::info('API token used', [
    'user_id' => $request->user()->id,
    'token_id' => $request->user()->currentAccessToken()->id,
    'endpoint' => $request->path(),
    'ip' => $request->ip(),
]);
```

## Frontend Integration

### React/Vue Example

```javascript
// api.js - API client configuration
import axios from 'axios';

const api = axios.create({
    baseURL: process.env.REACT_APP_API_URL || '/api',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
});

// Request interceptor to add token
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('api_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

// Response interceptor to handle token expiration
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem('api_token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export default api;

// auth.js - Authentication functions
export const login = async (email, password) => {
    const response = await api.post('/auth/login', {
        email,
        password,
        device_name: navigator.userAgent,
    });
    
    localStorage.setItem('api_token', response.data.token);
    return response.data;
};

export const logout = async () => {
    await api.post('/auth/logout');
    localStorage.removeItem('api_token');
};

export const fetchUser = async () => {
    const response = await api.get('/auth/user');
    return response.data;
};
```

## Troubleshooting

### "Unauthenticated" Response

**Issue:** API returns `401 Unauthenticated` even with valid token.

**Solutions:**

1. Check token hasn't expired:
   ```php
   $token = $request->user()->currentAccessToken();
   if ($token->expires_at && $token->expires_at->isPast()) {
       // Token expired
   }
   ```

2. Verify middleware is applied:
   ```php
   Route::middleware('auth:sanctum')->get('/user', ...);
   ```

3. Check token format:
   ```
   Authorization: Bearer 1|token_here
   ```

4. Ensure `Accept: application/json` header is sent

### CORS Issues

**Issue:** Cross-origin requests blocked.

**Solution:** Update `config/cors.php`:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['http://localhost:3000', 'https://your-domain.com'],
'allowed_headers' => ['*'],
```

### Token Not Created

**Issue:** `createToken()` returns error.

**Solutions:**

1. Ensure migrations have run:
   ```bash
   php artisan migrate --path=database/migrations
   ```

2. Verify `HasApiTokens` trait is on User model

3. Check database connection

## Testing

### Unit Tests

```php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);
        
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);
        
        $response->assertOk()
            ->assertJsonStructure(['token', 'token_type', 'user']);
        
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }
    
    public function test_protected_route_requires_token()
    {
        $response = $this->getJson('/api/user');
        
        $response->assertUnauthorized();
    }
    
    public function test_authenticated_user_can_access_protected_route()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/user');
        
        $response->assertOk()
            ->assertJson(['user' => ['id' => $user->id]]);
    }
    
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;
        
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/auth/logout');
        
        $response->assertOk();
        
        $this->assertDatabaseMissing('personal_access_tokens', [
            'name' => 'test-token',
        ]);
    }
}
```

## Further Reading

- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [API Authentication Best Practices](https://laravel.com/docs/authentication)
- [Laravel Security](https://laravel.com/docs/security)

## Summary

Microweber's Sanctum integration provides:

- **Token-based authentication** for API clients
- **Easy token management** with creation, revocation, and expiration
- **Fine-grained access control** via token abilities
- **Secure defaults** with token hashing and expiration
- **Simple integration** with existing User model and authentication system

For questions or issues, refer to the [Laravel Sanctum documentation](https://laravel.com/docs/sanctum) or check the Microweber source code in `src/MicroweberPackages/User/`.
