# `Customer` module

> **Slug:** `customer`
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

Migrations under `Modules/Customer/database/migrations/`:

  - `database/migrations/2019_11_25_021944_create_customers_table.php`
  - `database/migrations/2026_03_22_000001_create_customer_tags_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Customer\Models\Customer` | `Models/Customer.php` |
| `Modules\Customer\Models\CustomerTag` | `Models/CustomerTag.php` |
| `Modules\Customer\Models\ModelFilters\CustomerFilter` | `Models/ModelFilters/CustomerFilter.php` |

## API endpoints

Route files:

  - `routes/api.php`
  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\Customer\Http\Controllers\Api\CustomersApiController`

## Service classes

  - `Modules\Customer\Services\CustomerSegmentationService`

## Events

  - `Modules\Customer\Listeners\CreateCustomerFromOrderListener`

## Filament admin

  - `Modules\Customer\Filament\CustomerResource`
  - `Modules\Customer\Filament\CustomerResource\Pages\CreateCustomer`
  - `Modules\Customer\Filament\CustomerResource\Pages\EditCustomer`
  - `Modules\Customer\Filament\CustomerResource\Pages\ListCustomers`
  - `Modules\Customer\Filament\CustomerResource\Pages\ManageCustomers`

## Tests

Run: `php vendor/bin/phpunit Modules/Customer/Tests`

Test files:

  - `Tests/Filament/CustomerResourceTest.php`
  - `Tests/Unit/CustomerCheckoutTest.php`
  - `Tests/Unit/CustomerFilterTest.php`
  - `Tests/Unit/CustomerModelTest.php`
  - `Tests/Unit/CustomerSegmentationTest.php`
  - `Tests/Unit/Filament/CustomerResourceTest.php`

## Service providers

  - `Modules\Customer\Providers\CustomerEventServiceProvider`
  - `Modules\Customer\Providers\CustomerServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
