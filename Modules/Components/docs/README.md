# `Components` module

> **Slug:** `components`
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

This module owns no migrations of its own.

## Tests

Run: `php vendor/bin/phpunit Modules/Components/Tests`

Test files:

  - `Tests/Unit/Components/AlertTest.php`
  - `Tests/Unit/Components/ButtonTest.php`
  - `Tests/Unit/Components/CardTest.php`
  - `Tests/Unit/Components/CheckboxTest.php`
  - `Tests/Unit/Components/ColTest.php`
  - `Tests/Unit/Components/ContainerTest.php`
  - `Tests/Unit/Components/HeroTest.php`
  - `Tests/Unit/Components/InputTest.php`
  - `Tests/Unit/Components/NavItemTest.php`
  - `Tests/Unit/Components/NavbarTest.php`
  - `Tests/Unit/Components/RadioTest.php`
  - `Tests/Unit/Components/RowTest.php`
  - `Tests/Unit/Components/SectionTest.php`

## Service providers

  - `Modules\Components\Providers\ComponentsServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
