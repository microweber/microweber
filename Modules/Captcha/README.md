# Captcha

CAPTCHA verification for forms. Supports multiple captcha providers with a pluggable adapter architecture, custom Laravel validation rule, and Livewire confirmation modal.

## Key Features

- Multiple captcha adapters: Microweber built-in, Google reCAPTCHA v2, Google reCAPTCHA v3
- Custom `captcha` validation rule for Laravel forms
- CaptchaManager for programmatic captcha operations
- Livewire confirmation modal component
- Alpine.js frontend integration
- API and web routes for captcha generation and verification

## Captcha Adapters

| Adapter | Class |
|---|---|
| Microweber (built-in) | `Adapters\MicroweberCaptcha` |
| Google reCAPTCHA v2 | `Adapters\GoogleRecaptchaV2` |
| Google reCAPTCHA v3 | `Adapters\GoogleRecaptchaV3` |

## Key Classes

| Class | Purpose |
|---|---|
| `Services\CaptchaManager` | Captcha operations (`app('captcha_manager')`) |
| `Validators\CaptchaValidator` | Laravel validation rule (`captcha`) |
| `Livewire\CaptchaConfirmModalComponent` | Confirmation modal |
| `Microweber\CaptchaModule` | Module registration |

## Admin Panel (Filament)

- **CaptchaModuleSettings** -- select captcha provider and configure API keys

## Routes

- `routes/web.php` -- captcha image/challenge endpoints
- `routes/api.php` -- captcha verification API

## Usage

```html
<!-- Embed captcha in a template -->
<module type="captcha" />
```

```php
// Use in validation
$request->validate([
    'captcha' => 'required|captcha',
]);

// Programmatic access
$manager = app('captcha_manager');
```

The module auto-injects `captcha-alpine.js` into the page head for frontend captcha rendering.
