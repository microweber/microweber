# `Testimonials` module

> **Slug:** `testimonials`
> **Tier:** 3
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

### `testimonials` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `name` | `string` | nullable |
  | `content` | `longText` | nullable |
  | `client_company` | `string` | nullable |
  | `client_role` | `string` | nullable |
  | `client_website` | `string` | nullable |
  | `position` | `integer` | nullable |
  | `client_image` | `string` | nullable |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `settings` | `longText` | nullable |
  | `timestamps` | `timestamps` | — |
  | `client_image` | `string` | nullable |
  | `rel_type` | `string` | nullable |
  | `rel_id` | `string` | nullable |
  | `settings` | `longText` | nullable |
  | `updated_at` | `timestamp` | nullable |
  | `created_at` | `timestamp` | nullable |
  | `(unnamed)` | `dropColumn` | — |

## Models

### `Modules\Testimonials\Models\Testimonial`

Source: `Models/Testimonial.php`. Table: `testimonials`. 

**Fillable:** `name`, `content`, `client_image`, `client_company`, `client_role`, `client_website`, `position`, `rel_id`, `rel_type`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Testimonials\Filament\TestimonialsModuleSettings` | — | — |
  | `Modules\Testimonials\Filament\TestimonialsTableList` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Testimonials/Tests`

## Service providers

  - `Modules\Testimonials\Providers\TestimonialsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
