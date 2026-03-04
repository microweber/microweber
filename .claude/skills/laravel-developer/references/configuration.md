# Configuration Injection Guidelines

## Core Principle

Laravel provides a powerful `#[Config]` attribute that allows injecting configuration values directly into class constructors. This approach makes dependencies explicit and improves testability compared to using the global `config()` helper or `Config` facade.

## When to Use What

| Context        | Recommended Approach  | Reason                                                           |
| -------------- | --------------------- | ---------------------------------------------------------------- |
| **Services**   | `#[Config]` Attribute | Explicit dependencies, type safety, clear contract               |
| **Actions**    | `#[Config]` Attribute | Explicit dependencies, type safety                               |
| **Jobs**       | `#[Config]` Attribute | Serializable, explicit dependencies                              |
| **Middleware** | `#[Config]` Attribute | Config is static at runtime; explicit dependencies are preferred |
| **Views**      | Pass from Controller  | Views should receive all data explicitly from the controller     |

## Using the `#[Config]` Attribute

Use the attribute in the constructor of your class.

```php
use Illuminate\Support\Facades\Config;

class PaymentService
{
    public function __construct(
        #[Config('services.stripe.key')]
        private readonly string $stripeKey,

        #[Config('services.stripe.secret')]
        private readonly string $stripeSecret,
    ) {}

    public function charge(int $amount): void
    {
        // $this->stripeKey is available and typed
    }
}
```

## View Data Passing

Do not use the `config()` helper inside Blade views. Instead, pass the configuration value from the controller.

**Controller:**

```php
public function index()
{
    return view('welcome', [
        'appName' => config('app.name'),
        'supportEmail' => config('mail.from.address'),
    ]);
}
```

**View:**

```blade
<!-- ✅ Good: Variable passed from controller -->
<h1>Welcome to {{ $appName }}</h1>

<!-- ❌ Bad: Pulling config directly in view -->
<h1>Welcome to {{ config('app.name') }}</h1>
```

## Anti-Pattern to Avoid

Avoid using `config()` inside service methods. It hides dependencies inside the logic.

```php
// ❌ Bad: Hidden dependency
class PaymentService
{
    public function charge(): void
    {
        $key = config('services.stripe.key'); // Implicit dependency
    }
}
```
