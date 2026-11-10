# Social Login

OAuth social login for Microweber CMS and standalone Laravel apps. A thin, config-driven wrapper around [Laravel Socialite](https://laravel.com/docs/socialite) that supports Facebook, Google, GitHub, Twitter, LinkedIn and Microweber's own `mwlogin.com` identity server.

## Features

- Single service (`SocialLoginService`) + `SocialLogin` facade — a greppable public API bound to the `SocialLoginServiceContract` interface (swap/decorate friendly).
- Reads everything from the `social-login` config array — no hard-coded option look-ups, so it works in any Laravel app.
- Normalises every provider's callback into a single `SocialUserResult` DTO (provider, id, email, name, nickname, avatar, first/last name).
- Ships a custom `microweber` Socialite driver (`MicroweberSocialiteProvider`) for login against `mwlogin.com`.
- Per-provider enable/credential gating via `enabledProviders()` / `isProviderEnabled()`.

## Installation

```bash
composer require microweber-packages/microweber-social-login
```

The `SocialLoginServiceProvider` is auto-discovered. It merges the package config and binds the service as a singleton on both `SocialLoginServiceContract::class` and `SocialLoginService::class`.

Publish the config to customise providers:

```bash
php artisan vendor:publish --tag=social-login-config
```

## Usage

### Redirect to a provider / handle the callback

```php
use MicroweberPackages\SocialLogin\Facades\SocialLogin;
use MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract;

// 1. Send the user to the provider's OAuth screen
return SocialLogin::redirect('google'); // Symfony RedirectResponse

// 2. On the callback route, exchange the code for the user
$socialUser = SocialLogin::handleCallback('google');

$socialUser->email;     // string|null
$socialUser->name;      // string|null
$socialUser->id;        // provider user id
$socialUser->avatar;    // avatar URL
$socialUser->nickname;  // string|null
$socialUser->firstName; // split from name
$socialUser->lastName;
```

`redirect()` automatically applies provider-specific tweaks (GitHub requests the `user:email` scope; Google/Azure add `prompt=select_account`). `handleCallback()` uses Socialite's stateless flow and returns a `SocialUserResult`.

### Resolve the service via the container or facade root

```php
// Facade root (as used inside Microweber's UserManager)
/** @var SocialLoginServiceContract $socialLogin */
$socialLogin = SocialLogin::getFacadeRoot();
return $socialLogin->redirect($provider);

// Or type-hint the contract in a constructor / method (DI)
public function __construct(private SocialLoginServiceContract $socialLogin) {}
```

### Discover which providers are configured

```php
SocialLogin::enabledProviders();          // ['google', 'github', ...] — enabled AND with credentials
SocialLogin::isProviderEnabled('github'); // bool
SocialLogin::callbackUrl('github');       // full OAuth callback URL for the provider
```

A provider counts as enabled only when its config has `enabled => true` **and** a non-empty `client_id` and `client_secret`.

### Microweber CMS integration

In the CMS this package is consumed by `microweber-user`'s `UserManager` (`social_login` / `social_login_process`) and by the Filament auth plugin — the default callback URL is `api/social_login_process?provider={provider}`.

## Configuration

`config/social-login.php`:

```php
return [
    // callable(string $provider): string — overrides the default callback URL.
    // When null: url('api/social_login_process?provider=' . $provider)
    'callback_url_builder' => null,

    'providers' => [
        'facebook'   => ['enabled' => false, 'client_id' => '', 'client_secret' => ''],
        'google'     => ['enabled' => false, 'client_id' => '', 'client_secret' => ''],
        'github'     => ['enabled' => false, 'client_id' => '', 'client_secret' => ''],
        'twitter'    => ['enabled' => false, 'client_id' => '', 'client_secret' => ''],
        'linkedin'   => ['enabled' => false, 'client_id' => '', 'client_secret' => ''],
        'microweber' => [
            'enabled' => false, 'client_id' => '', 'client_secret' => '',
            'server_url' => 'https://mwlogin.com',
        ],
    ],
];
```

The service pushes each provider's `client_id` / `client_secret` / callback into Laravel's `services.{provider}` config at request time, so Socialite picks them up. If your app populates the config late (e.g. from the DB during a provider boot), call `SocialLogin::getFacadeRoot()->refreshConfig()` to re-read it.

## Key classes

- `MicroweberPackages\SocialLogin\Facades\SocialLogin`
- `MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract`
- `MicroweberPackages\SocialLogin\Services\SocialLoginService`
- `MicroweberPackages\SocialLogin\Contracts\SocialUserResult`
- `MicroweberPackages\SocialLogin\Providers\MicroweberSocialiteProvider`
- `MicroweberPackages\SocialLogin\Providers\SocialLoginServiceProvider`

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
