# `Attributes` module

> **Slug:** `attributes`
> **Tier:** 4
>
> Tier-4 module — pure presentation / template-side widget.
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

Migrations under `Modules/Attributes/database/migrations/`:

  - `database/migrations/2024_11_20_000001_create_attributes_table.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Attributes\Models\Attribute` | `Models/Attribute.php` |

## Tests

Run: `php vendor/bin/phpunit Modules/Attributes/Tests`

Test files:

  - `Tests/Unit/AttributesManagerTest.php`
  - `Tests/Unit/AttributesTest.php`

## Service providers

  - `Modules\Attributes\Providers\AttributesServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
