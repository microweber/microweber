# Microweber User

User management for Microweber: authentication, registration, profiles, roles/admin flags, social login, password reset, verified email change, API tokens (Sanctum), and the Filament admin Users resource.

## Features

- **`UserManager` service** (`user_manager` singleton) — login, logout, register, save, delete, session helpers, social login, "login as", sensitive-action re-auth and verified email-change flow.
- **`User` Eloquent model** with roles (`spatie/laravel-permission`), `is_admin` / `is_active` flags and an `IsAdminScope`.
- **Global helper functions** (`user_login()`, `save_user()`, `is_logged()`, `user_id()`, `is_admin()`, `session_*()`, …) that delegate to `app()->user_manager`.
- **Auth routes & controllers** for web + API (login, register, forgot/reset password, email verification, logout, social login).
- **Filament** Users resource and admin profile redirect page; Livewire forms for the admin user editor and the public profile.
- **Sanctum** personal access tokens, **Socialite** social login, disposable-email registration guard.

## Installation

```bash
composer require microweber-packages/microweber-user
```

Inside the Microweber monorepo the provider is registered by the core
`MicroweberPackages\App\Providers\MicroweberServiceProvider`. In a standalone
Laravel app, register it manually (it pulls in its own Sanctum, social-login,
passport and disposable-email-checker providers):

```php
MicroweberPackages\User\Providers\UserServiceProvider::class,
```

The provider binds the `user_manager` singleton, loads `routes/web.php` +
`routes/api.php`, the migrations, the `user` / `admin` view namespaces, and
registers the Livewire components and the Filament profile-redirect page.

## Usage

### Via the global helpers (CMS style)

```php
// Login (accepts a query string or an array)
user_login('email=my@email.com&password=secret');
user_login(['username' => 'john', 'password' => 'secret']);

// Register (runs captcha/validation rules; XSS-cleaned)
user_register(['email' => 'new@user.com', 'password' => 'secret', 'password2' => 'secret']);

// Current-user checks
is_logged();        // bool
user_id();          // int|false
is_admin();         // bool
only_admin_access(); // exits with a message if not an admin

// Save / delete a user (security rules apply; see save() below)
save_user(['id' => 1, 'first_name' => 'John']);
delete_user(5);

// Profile URLs + CSRF
login_url(); logout_url(); register_url(); forgot_password_url(); profile_url();
csrf_form();          // hidden _token input
mw_csrf_token();

// Session helpers (wrap Laravel's Session, with a mw.user.session_set event)
session_set('key', 'value');
session_get('key');
session_append_array('cart', [...]);
session_del('key');
session_end();        // flush + regenerate

// Display helpers
user_name($id, 'full');     // full|first|last|username|email
user_picture($id, 80, 80);  // thumbnail
get_users('email=my@email.com');
get_user($id);
```

### Via the service (DI / facade-less)

```php
use MicroweberPackages\User\Services\UserManager;

/** @var UserManager $users */
$users = app('user_manager');          // the registered singleton

$users->login('email=a@b.com&password=x');
$users->register($params);
$users->is_admin();
$users->make_logged($userId);          // log a user in by id
$users->logout();
$users->reset_password_from_link($params);
$users->send_forgot_password($params);
$users->confirmEmailChange($userId, $token);
```

### `save()` security model

`UserManager::save()` (and the `save_user()` helper) enforces rules unless the
save is forced:

- A non-admin may only save their **own** row (`id` must match the logged-in user).
- Self email/password changes require a recent password confirmation **or** a
  matching `current_password` in the request (`guardSelfCredentialChange()`).
- Self email changes are parked in `pending_email` until confirmed via the
  emailed link, when verification is enabled (`maybeDeferSelfEmailChange()` →
  `confirmEmailChange()`); policy is gated by `allowUserEmailChangeEnabled()`.

To create a user or bypass the id/admin checks (e.g. in a trusted wrapper):

```php
define('MW_FORCE_USER_SAVE', true);     // or: mw_var('force_save_user', true);
save_user(['email' => 'new@user.com', 'password' => 'secret']);
```

### Social login

```php
user_social_login(['provider' => 'google']);  // redirect to the provider
social_login_process();                        // handle the OAuth callback
```

### Model

```php
use MicroweberPackages\User\Models\User;

$admins = User::where('is_admin', 1)->get();   // IsAdminScope also available
$user   = User::find(1);
$user->assignRole('Editor');                   // spatie/laravel-permission
```

## Configuration

This package reads Microweber **options** (via `get_option(key, 'users')`) and
env vars rather than publishing a config file:

- `register_email_enabled`, `new_user_registration_mail_template` (`users`) — registration email.
- `email_change_verify` (`users`) — toggle the verified email-change flow (default on).
- `allow_user_email_change` (`users`) / env `ALLOW_USER_EMAIL_CHANGE` — allow self-service email change (default off).
- `disable_registration_with_temporary_email` (`users`) — enables the disposable-email checker.
- `auth.password_timeout` (Laravel) — window for the sensitive-action re-auth guard.

## Key classes

- `MicroweberPackages\User\Services\UserManager` — the `user_manager` service.
- `MicroweberPackages\User\Models\User` — the user model (+ `Admin`, `PersonalAccessToken`, `UserOauthData`, `SocialiteUser`, `PasswordReset`).
- `MicroweberPackages\User\Scopes\IsAdminScope` — admin query scope.
- `MicroweberPackages\User\Providers\UserServiceProvider` — registration (+ `AuthServiceProvider`, `UserEventServiceProvider`, `UserSocialiteServiceProvider`).
- `MicroweberPackages\User\Filament\Resources\UsersResource` — admin Users CRUD.
- `src/helpers/helpers.php`, `src/helpers/api_user.php` — global helper functions.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov
- Nikolay Rachkov
- Petko Yovchevski

## License

MIT
