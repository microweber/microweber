# `Profile` module

> **Slug:** `profile`
> **Tier:** 1
>
> Tier-1 module — owns its own data + exposes a public API.
>
> *(Auto-generated from filesystem survey on 2026-04-25;
> hand-edit to add operator-side context. The canonical
> shape lives in [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md);
> use `Modules/Settings/docs/README.md` as the
> hand-curated example.)*

## Domain

*Hand-edit this section to describe what the module does
operationally and which sibling modules it interacts
with.*

## Data model

Migrations under `Modules/Profile/database/migrations/`:

  - `database/migrations/2024_01_24_095154_add_two_factor_confirmed_at_to_users_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Profile\Models\User` | `Models/User.php` |

## API endpoints

Route files:

  - `routes/api.php`
  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Profile\Http\Controllers\Api\ProfileApiController`

## Filament admin

  - `Modules\Profile\Filament\Pages\ChangePassword`
  - `Modules\Profile\Filament\Pages\EditProfile`
  - `Modules\Profile\Filament\Pages\ForgotPassword`
  - `Modules\Profile\Filament\Pages\Login`
  - `Modules\Profile\Filament\Pages\OrderHistory`
  - `Modules\Profile\Filament\Pages\Register`
  - `Modules\Profile\Filament\Pages\SavedAddresses`
  - `Modules\Profile\Filament\Pages\TwoFactorAuth`

## Tests

Run: `php vendor/bin/phpunit Modules/Profile/Tests`

Test files:

  - `Tests/Feature/AuthenticationTest.php`
  - `Tests/Feature/OrderHistoryTest.php`
  - `Tests/Feature/ProfileManagementTest.php`
  - `Tests/Feature/SavedAddressesTest.php`
  - `Tests/Feature/TwoFactorAuthenticationTest.php`
  - `Tests/Unit/ProfileModuleTest.php`

## Service providers

  - `Modules\Profile\Providers\FilamentProfilePanelProvider`
  - `Modules\Profile\Providers\ProfileServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
