# Search Module

The Search module is a **lightweight content-discovery surface** — a Livewire-powered search box that can be embedded into any page via the `<module type="search" />` tag. It does not own any data, any database table, or any HTTP route. It is a thin UI layer that delegates every query to the [Content module's](/modules/content/) `get_content()` helper.

> **TL;DR** — Search is a single Livewire component (`SearchComponent`) with two Blade templates (default form + autocomplete) and one Filament settings page. The search itself is a live SQL `LIKE` against the `content` table's `title`, `content`, and `description` columns, capped at 10 results, debounced 300 ms, with input keyword XSS-sanitized via `strip_tags()` + `mb_substr()` (defense-in-depth per AI-130 / SEC-05).

---

## What this module owns

| Concern | Storage / surface |
|---|---|
| Search input form + results UI | `SearchComponent` Livewire component |
| Two visual variants | `default.blade.php` (form + dropdown results), `autocomplete.blade.php` (typeahead) |
| Per-instance settings | `Filament\SearchSettings` (live-edit module settings) |
| Module-tag bridge | `Microweber\SearchModule::render()` (resolves template → mounts Livewire) |
| Keyword sanitization | `strip_tags()` + `mb_substr(0, 200)` on every query |
| Result-link generation | `content_link($result['id'])` from the Content module |

What this module does **NOT** own:

- The search backend — every query is a `get_content()` call into the [Content module](/modules/content/). Switching backends (e.g. to Elasticsearch, Meilisearch, Scout) means changing the Content module's underlying query implementation, not this module.
- An index table — search is purely query-time. No content is pre-indexed; no listeners fire on content save.
- A dedicated `/search` URL — the module is embedded in any page via the live-edit module tag.
- Multi-source aggregation (products + posts + categories + users in one list) — `get_content()` returns rows from the `content` table only.
- Search analytics — there is no `search_queries` table, no event fires when a query runs.

---

## Architectural fact: zero-persistence, zero-routes, all-Livewire

This module is intentionally **stateless and route-free**. You will not find:

- A migration directory (`SearchServiceProvider` calls `loadMigrationsFrom()` but the `database/` directory is absent).
- An active route file (`routes/web.php` and `routes/api.php` exist but every route is commented out — reserved for future extension).
- A `Services/` or `Repositories/` directory — the only logic is in `SearchComponent::search()` (5 lines of `get_content()` parameter assembly).

Adding routes, a Service class, or persistent index storage is a *future* refactor, not a current pattern. If your work touches Search, prefer Livewire + module-settings options over carving out new HTTP endpoints.

---

## The query pipeline

```
User types ≥ 3 characters
        ↓
Livewire `wire:model.live.debounce.300ms` fires after 300 ms idle
        ↓
SearchComponent::updatedSearchQuery() runs
        ↓
SearchComponent::search()
   ├─ strip_tags($searchQuery)             ← XSS guard
   ├─ mb_substr($searchQuery, 0, 200)      ← DoS guard (keyword length cap)
   └─ get_content([
        'search_in_fields' => 'title,content,description',
        'keyword'          => $sanitized,
        'limit'            => 10,
        'no_cache'         => true,
        'search_in'        => 'content',
        'parent'           => (int) $dataContentId,   // 0 = whole site
      ])
        ↓
Returns ≤ 10 rows from `content` table
        ↓
Livewire morph re-renders the `<ul>` results dropdown
   ├─ each result links to content_link($id)
   ├─ matches highlight via simple substring marker
   └─ "No results found" shown when count = 0
```

The 2-character minimum is enforced inside `updatedSearchQuery()`. Below 2 chars, `$searchResults` is cleared and no query runs.

---

## Per-instance settings

Every embedded `<module type="search" />` instance is independent. Live-edit settings are stored via the `Option` service keyed by `moduleId`. Available options:

| Option | Default | Effect |
|---|---|---|
| `options.placeholder` | `Search...` | input placeholder text |
| `options.data-content-id` | `0` | parent content id to scope results to (0 = whole site) |
| `options.searchPosition` | `center` | flex-align of the input within its container (`start` / `center` / `end`) |
| `options.searchWidth` | `500` (px) | input width |
| `options.searchHeight` | `100` (px) | results-list height (when shown) |
| `options.autocomplete` | `false` | when `true`, picks the `autocomplete.blade.php` template variant |
| `options.template` | `default` | explicit template override |

The Filament `SearchSettings` form exposes all seven; staff can also flip them inline via the live-edit module-settings panel.

---

## Two template variants

| Template | When to pick it | Layout |
|---|---|---|
| `default` | search results land on their own dropdown panel below the input | input + below-input dropdown with `.list-group-item` results |
| `autocomplete` | results appear as a typeahead overlay (clicking outside dismisses) | input with absolutely-positioned popover, narrower visual footprint |

Add your own template by dropping a Blade file into `Modules/Search/resources/views/templates/<your-slug>.blade.php` and selecting it in the Filament settings form's Design tab (the live-edit module-settings template registry auto-discovers them).

---

## Where to next

- [Installation](./installation.md) — service provider, settings storage, sibling-module dependencies, asset build.
- [Usage](./usage.md) — embedding the module tag, per-page scoping, autocomplete variant, URL-param hooks.
- [API](./api.md) — `SearchComponent` properties + methods, `SearchModule::render()`, `SearchSettings` Filament form, the `get_content()` parameter contract.
- [Examples](./examples.md) — basic page search, autocomplete variant, scoping to a sub-page, customising the result-row Blade.
- [Troubleshooting](./troubleshooting.md) — empty results, Livewire mount failures, search too slow on big sites, keyword too short.
