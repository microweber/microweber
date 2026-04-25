# `Customer` module

> **Slug:** `customer`
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

### `customers` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `bigIncrements` | — |
  | `user_id` | `integer` | nullable |
  | `company_id` | `integer` | nullable |
  | `currency_id` | `integer` | nullable |
  | `name` | `string` | nullable |
  | `first_name` | `string` | nullable |
  | `last_name` | `string` | nullable |
  | `email` | `string` | nullable |
  | `phone` | `string` | nullable |
  | `status` | `string` | nullable, has-default |
  | `stripe_id` | `string` | nullable |
  | `pm_type` | `string` | nullable |
  | `pm_last_four` | `string` | nullable |
  | `trial_ends_at` | `timestamp` | nullable |
  | `active` | `integer` | nullable |
  | `customer_data` | `json` | nullable |
  | `timestamps` | `timestamps` | — |

### `customer_tags` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `customer_id` | `unsignedBigInteger` | — |
  | `tag_id` | `unsignedInteger` | — |
  | `created_at` | `timestamp` | — |
  | `customer_id` | `foreign` | — |

## Models

### `Modules\Customer\Models\Customer`

Source: `Models/Customer.php`. Table: `customers`. 

**Casts:**

  - `customer_data` → `array`

### `Modules\Customer\Models\CustomerTag`

Source: `Models/CustomerTag.php`. Table: `customer_tags`. 

**Fillable:** `customer_id`, `tag_id`

**Casts:**

  - `customer_id` → `integer`
  - `tag_id` → `integer`
  - `created_at` → `datetime`

### `Modules\Customer\Models\ModelFilters\CustomerFilter`

Source: `Models/ModelFilters/CustomerFilter.php`. 

## API endpoints

### `routes/api.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `GET` | `/` | `CustomersApiController::index` |
  | `GET` | `/{customer}` | `CustomersApiController::show` |
  | `POST` | `/` | `CustomersApiController::store` |
  | `PUT` | `/{customer}` | `CustomersApiController::update` |
  | `PATCH` | `/{customer}` | `CustomersApiController::update` |
  | `DELETE` | `/{customer}` | `CustomersApiController::destroy` |

## Controllers

### `Modules\Customer\Http\Controllers\Api\CustomersApiController`

Source: `Http/Controllers/Api/CustomersApiController.php`.

  - `index(Request $request): AnonymousResourceCollection|JsonResponse`
  - `store(Request $request): JsonResponse`
  - `show(Request $request, int $id): JsonResponse`
  - `update(Request $request, int $id): JsonResponse`
  - `destroy(Request $request, int $id): JsonResponse`

## Service classes

### `Modules\Customer\Services\CustomerSegmentationService`

Source: `Services/CustomerSegmentationService.php`.

  - `getCustomersByTags($tags, bool $matchAll = false, array $additionalFilters = []): Collection`
  - `getCustomersWithoutTags(array $filters = []): Collection`
  - `getSegmentsGroupedByTag(): array`
  - `getTagAnalytics(): array`
  - `createSegment(string $name, array $criteria): array`
  - `exportSegment(array $segment, string $format = 'json'): string`
  - `getSimilarCustomers(int $customerId, int $limit = 10): Collection`
  - `bulkAssignTags(array $customerIds, array $tagIds): int`
  - `bulkRemoveTags(array $customerIds, array $tagIds): int`

## Events

  - `Modules\Customer\Listeners\CreateCustomerFromOrderListener`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Customer\Filament\CustomerResource` | Shop | — |
  | `Modules\Customer\Filament\CustomerResource\Pages\CreateCustomer` | — | — |
  | `Modules\Customer\Filament\CustomerResource\Pages\EditCustomer` | — | — |
  | `Modules\Customer\Filament\CustomerResource\Pages\ListCustomers` | — | — |
  | `Modules\Customer\Filament\CustomerResource\Pages\ManageCustomers` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Customer/Tests`

### `Tests/Filament/CustomerResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/Filament/CustomerResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_index_page_supports_search`
  - `it_create_page_validates_required_fields`

## Service providers

  - `Modules\Customer\Providers\CustomerEventServiceProvider`
  - `Modules\Customer\Providers\CustomerServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
