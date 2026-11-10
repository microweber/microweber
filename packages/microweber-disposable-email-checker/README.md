# Microweber Disposable Email Checker

Disposable / temporary email address checker for Laravel apps — usable standalone or inside the Microweber CMS. Answers "is this email from a throwaway provider?" against a shipped (and extensible) domain list, and plugs into Laravel validation and route middleware.

Part of the [Microweber](https://microweber.com) CMS.

## Features

- **Service** — `isDisposable($email)` boolean check, backed by a newline-separated domain list (~ shipped under `resources/data/disposable_email_addresses.txt`).
- **Validation rule** — `not_disposable_email`, registered automatically via `Validator::extend`.
- **Route middleware** — `block_disposable_email`, aliased automatically; rejects requests whose email field is disposable.
- **Extensible list** — add your own domains at runtime with `addDomains()`.
- **Config + env toggle** — master `enabled` switch and a custom list path.

## Installation

```bash
composer require microweber-packages/microweber-disposable-email-checker
```

`DisposableEmailCheckerServiceProvider` singleton-binds the contract to the service, merges the config, registers the `not_disposable_email` validation rule and the `block_disposable_email` middleware alias.

## Usage

### Via the facade

```php
use MicroweberPackages\DisposableEmailChecker\Facades\DisposableEmailChecker;

if (DisposableEmailChecker::isDisposable('user@mailinator.com')) {
    // reject the signup
}

// Inspect or extend the blocked-domain list
$domains = DisposableEmailChecker::blockedDomains();
DisposableEmailChecker::addDomains(['my-temp-domain.com', 'another.tld']);
```

### Via dependency injection (the contract)

```php
use MicroweberPackages\DisposableEmailChecker\Contracts\DisposableEmailCheckerContract;

public function __construct(private DisposableEmailCheckerContract $checker) {}

$blocked = $this->checker->isDisposable($email);
```

### As a validation rule

```php
$request->validate([
    'email' => ['required', 'email', 'not_disposable_email'],
]);
```

### As route middleware

```php
// Defaults to the "email" request field
Route::post('/register', RegisterController::class)
    ->middleware('block_disposable_email');

// Or specify a different field
Route::post('/subscribe', SubscribeController::class)
    ->middleware('block_disposable_email:contact_email');
```

## Configuration

Publish the config:

```bash
php artisan vendor:publish --tag=disposable-email-checker-config
```

`config/disposable-email-checker.php`:

| Key | Default | Purpose |
| --- | --- | --- |
| `enabled` | `env('DISPOSABLE_EMAIL_CHECKER_ENABLED', true)` | Master switch. When `false`, the validation rule and middleware short-circuit and allow every email (the service itself is flag-agnostic and always answers from the list). |
| `list_path` | `env('DISPOSABLE_EMAIL_CHECKER_LIST', <shipped list>)` | Absolute path to a newline-separated list of disposable domains. |

## Key classes

- Facade: `MicroweberPackages\DisposableEmailChecker\Facades\DisposableEmailChecker`
- Contract: `MicroweberPackages\DisposableEmailChecker\Contracts\DisposableEmailCheckerContract`
- Service: `MicroweberPackages\DisposableEmailChecker\Services\DisposableEmailCheckerService`
- Validator: `MicroweberPackages\DisposableEmailChecker\Validators\NotDisposableEmailValidator`
- Middleware: `MicroweberPackages\DisposableEmailChecker\Http\Middleware\BlockDisposableEmail`
- Provider: `MicroweberPackages\DisposableEmailChecker\Providers\DisposableEmailCheckerServiceProvider`

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov
- Bozhidar Slaveykov

## License

MIT
