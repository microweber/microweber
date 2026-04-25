# `RssFeed` module

> **Slug:** `rss-feed`
> **Tier:** 2
>
> Tier-2 module — service / API surface on top of shared infrastructure.
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

## API endpoints

Route files:

  - `routes/web.php`

*Hand-edit to inline the (Method / Path / Auth / Scope /
Controller) table for each route group.*

## Controllers

  - `Modules\RssFeed\Http\Controllers\RssController`

## Tests

Run: `php vendor/bin/phpunit Modules/RssFeed/Tests`

Test files:

  - `tests/RssControllerTest.php`

## Service providers

  - `Modules\RssFeed\Providers\RssFeedServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
