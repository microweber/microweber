# `Profile` module

> **Slug:** `profile`
> **Tier:** 1
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

### `users` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `two_factor_confirmed_at` | `timestamp` | nullable |
  | `two_factor_confirmed_at` | `dropColumn` | — |

## Models

### `Modules\Profile\Models\User`

Source: `Models/User.php`. 

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `ProfileApiController::show` |
  | `PUT` | `/` | `ProfileApiController::update` |
  | `PATCH` | `/` | `ProfileApiController::update` |
  | `POST` | `/change-password` | `ProfileApiController::changePassword` |

### `routes/web.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/enable` | `TwoFactorAuth::enableTwoFactorAuthentication` |
  | `POST` | `/confirm` | `TwoFactorAuth::confirmTwoFactorAuthentication` |
  | `POST` | `/disable` | `TwoFactorAuth::disableTwoFactorAuthentication` |
  | `GET` | `/recovery-codes` | `TwoFactorAuth::showRecoveryCodes` |
  | `POST` | `/regenerate-recovery-codes` | `TwoFactorAuth::regenerateRecoveryCodes` |

## Controllers

### `Modules\Profile\Http\Controllers\Api\ProfileApiController`

Source: `Http/Controllers/Api/ProfileApiController.php`.

  - `show(Request $request): JsonResponse`
  - `update(Request $request): JsonResponse`
  - `changePassword(Request $request): JsonResponse`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Profile\Filament\Pages\ChangePassword` | — | — |
  | `Modules\Profile\Filament\Pages\EditProfile` | — | — |
  | `Modules\Profile\Filament\Pages\ForgotPassword` | — | — |
  | `Modules\Profile\Filament\Pages\Login` | — | — |
  | `Modules\Profile\Filament\Pages\OrderHistory` | — | — |
  | `Modules\Profile\Filament\Pages\Register` | — | — |
  | `Modules\Profile\Filament\Pages\SavedAddresses` | — | — |
  | `Modules\Profile\Filament\Pages\TwoFactorAuth` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Profile/Tests`

### `Tests/Feature/OrderHistoryTest.php`

  - `it_displays_orders_for_logged_in_customer`

### `Tests/Feature/ProfileManagementTest.php`

  - `it_user_can_change_password`

### `Tests/Feature/SavedAddressesTest.php`

  - `it_displays_saved_addresses_for_customer`

### `Tests/Feature/TwoFactorAuthenticationTest.php`

  - `it_two_factor_recovery_codes`

### `Tests/Unit/ProfileModuleTest.php`

  - `it_usermodelhasfillablefields`

## Service providers

  - `Modules\Profile\Providers\FilamentProfilePanelProvider`
  - `Modules\Profile\Providers\ProfileServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
