# `Rating` module

> **Slug:** `rating`
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

### `rating` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `rel_type` | `string` | — |
  | `rel_id` | `string` | — |
  | `rating` | `integer` | — |
  | `comment` | `text` | nullable |
  | `session_id` | `string` | nullable |
  | `created_by` | `unsignedBigInteger` | nullable |
  | `edited_by` | `unsignedBigInteger` | nullable |
  | `timestamps` | `timestamps` | — |
  | `rel_type` | `index` | — |
  | `rel_id` | `index` | — |

## Models

### `Modules\Rating\Models\Rating`

Source: `Models/Rating.php`. Table: `rating`. 

**Fillable:** `rel_type`, `rel_id`, `rating`, `comment`, `session_id`, `created_by`, `edited_by`

**Casts:**

  - `rating` → `integer`
  - `created_at` → `datetime`
  - `updated_at` → `datetime`

## API endpoints

### `routes/web.php`

  | Method | Path | Action |
  |--------|------|--------|
  | `POST` | `rating/Controller/save` | `RatingController::save` |

## Controllers

### `Modules\Rating\Http\Controllers\RatingController`

Source: `Http/Controllers/RatingController.php`.

  - `save(Request $request)`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Rating\Filament\RatingModuleSettings` | — | — |
  | `Modules\Rating\Filament\RatingTableList` | — | — |
  | `Modules\Rating\Filament\Resources\RatingModuleResource` | Website Settings | — |
  | `Modules\Rating\Filament\Resources\RatingModuleResource\Pages\CreateRating` | — | — |
  | `Modules\Rating\Filament\Resources\RatingModuleResource\Pages\EditRating` | — | — |
  | `Modules\Rating\Filament\Resources\RatingModuleResource\Pages\ListRatings` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Rating/Tests`

### `Tests/Filament/RatingResourceTest.php`

  - `it_resource_has_correct_model`

### `Tests/Unit/Filament/RatingModuleResourceTest.php`

  - `it_index_page_shows_all_records`
  - `it_rating_value_is_validated`

### `Tests/Unit/RatingTest.php`

  - `it_update_rating`
  - `it_average_rating`

## Service providers

  - `Modules\Rating\Providers\RatingServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
