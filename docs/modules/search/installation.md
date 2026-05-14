# Installation

Search ships as part of Microweber core. No `composer require` step. This page documents what the module pulls in, the (minimal) configuration surface, and the sibling-module dependencies.

---

## Service provider

`Modules\Search\Providers\SearchServiceProvider` is auto-registered via `module.json`. It:

- Registers the `SearchComponent` Livewire component as `module-search`.
- Loads Blade views under the `modules.search::` namespace.
- Registers the `SearchSettings` Filament form for use inside the live-edit module-settings panel.
- Calls `loadMigrationsFrom(module_path(..., 'database/migrations'))` even though the directory does not exist — harmless no-op, kept in case future versions add an index table.

`Modules\Search\Providers\EventServiceProvider` exists with an empty `$listen` array and `$shouldDiscoverEvents = true`. No Search-owned events fire today — the provider is reserved for future analytics integration (Search Performed, Result Clicked).

---

## No active routes

`routes/web.php` and `routes/api.php` ship with the module but every route inside them is commented out. The search interaction happens entirely via Livewire (websocket / fetch update over the `livewire/update` endpoint Livewire registers globally), so no Search-specific HTTP routes are needed.

If you add custom search routes in the future, the convention is `/api/module/search/*` (matching the Cart/Order/Checkout pattern).

---

## No config file

There is no `config/search.php`. All settings are **per-instance** and stored via the Option service keyed by the module instance's id. See the [Usage page](./usage.md#per-instance-settings) for the seven option keys.

This means:

- There is no global "min characters to search" config — it's hardcoded to 2 in `SearchComponent::updatedSearchQuery()`.
- There is no global "max results per query" config — it's hardcoded to 10 in `SearchComponent::search()`.
- There is no global "search enabled" toggle — embedding the module tag enables it for that page.

If you need to change those hardcoded values, edit `Modules/Search/Livewire/SearchComponent.php` directly. They're not surfaced as options because the module's design intent is "use the defaults; if they don't fit, fork and replace".

---

## Dependencies on other modules

| Module | Why Search needs it |
|---|---|
| **[Content](/modules/content/)** | every search query is `get_content([...])`. Search has no independent backend. |
| **Option** | Per-instance settings (placeholder, data-content-id, autocomplete toggle, template id) are stored via the Option facade. |
| **Filament** | The `SearchSettings` form runs inside Filament's live-edit module-settings panel. |
| **Livewire (v4)** | The entire search UI is a Livewire component (`wire:model.live.debounce.300ms`). |
| **Microweber (Module facade)** | `SearchModule::render()` is the bridge that resolves the embedded module tag to a Blade template and mounts the Livewire component. |

If the Content module is disabled, Search will still mount but every query will return an empty array — `get_content()` is unresolvable. The Livewire component does not throw on this case; the empty-state "No results found" message is shown instead.

---

## Database

Search owns **zero migrations**. It reads exclusively from the `content` table owned by the Content module via `get_content()`. Run the Content module's migrations as part of the normal Microweber install — there is no Search-specific migration step.

---

## Asset build

The module ships static CSS + JS at:

```
Modules/Search/resources/assets/css/search.css
Modules/Search/resources/assets/js/search.js
```

These are copied to `public/modules/search/` by the module-level `build.cjs` script (registered in `package.json`). The build runs as part of the project's overall `npm run build` orchestration via `run-build.js`.

For a one-off build of just the Search assets:

```bash
cd Modules/Search && npm run build
```

The CSS provides `.form-control` overrides + a `.list-group` styling pattern + a loading-spinner block. The JS hooks URL `?q=<keyword>` query params into the input on mount, so direct-link "search for X" URLs work.

---

## Sanity check after install

```bash
# Filament settings form loads (returns 200)
curl -I http://your-site/admin/livewire/modules/search/settings

# Module tag renders (embed it on a test page and load that page)
echo '<module type="search" />' > test.html

# Livewire component resolves
php artisan tinker --execute='dd(\Livewire\Livewire::getComponentClass("module-search"));'
# → Modules\Search\Livewire\SearchComponent
```

If the Livewire class doesn't resolve, the SearchServiceProvider didn't register the component. Confirm `SearchServiceProvider` is in `module.json → providers` and `php artisan config:clear` + `php artisan livewire:discover` (if your project uses it) ran during the install.

---

## Performance notes

Because `get_content()` performs a SQL `LIKE` query against `content.title`, `content.content`, and `content.description` with `%keyword%` wildcards, search performance scales linearly with the size of the `content` table. On installs with > ~50k content rows, expect noticeable latency.

Mitigations (in order of effort):

1. **Add a FULLTEXT index** on the searchable columns of the `content` table (Content module migration territory, not Search).
2. **Reduce the search scope** per Search instance via `options.data-content-id` so only a sub-page's children are queried.
3. **Adopt Laravel Scout** at the Content module level — Search would automatically inherit the upgrade since it just calls `get_content()`.

The "swap in Scout" path is the right long-term answer for large installs. The Search module does not need to change to support it; the change is upstream in Content.
