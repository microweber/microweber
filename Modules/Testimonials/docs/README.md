# `Testimonials` module

> **Slug:** `testimonials`
> **Tier:** 3
>
> Tier-3 module — admin tool / widget driven by a Filament page or resource.
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

Migrations under `Modules/Testimonials/database/migrations/`:

  - `database/migrations/2024_10_29_093606_create_testimonials_table.php`
  - `database/migrations/2024_10_29_093709_add_missing_columns_testimonials_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Testimonials\Models\Testimonial` | `Models/Testimonial.php` |

## Filament admin

  - `Modules\Testimonials\Filament\TestimonialsModuleSettings`
  - `Modules\Testimonials\Filament\TestimonialsTableList`

## Tests

Run: `php vendor/bin/phpunit Modules/Testimonials/Tests`

Test files:

  - `Tests/Unit/TestimonialsModuleFrontendTest.php`
  - `Tests/Unit/TestimonialsTableListFilamentTest.php`

## Service providers

  - `Modules\Testimonials\Providers\TestimonialsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
