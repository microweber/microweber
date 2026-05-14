# Usage

How the Search module is consumed: embedding the module tag, configuring it via live-edit, scoping results to a sub-page, switching variants, and the URL-param hooks.

---

## Embedding the search box

The Search module is rendered exclusively via the Microweber module tag inside any Blade or template file:

```html
<module type="search" />
```

That single tag does five things:

1. Resolves `<module type="search">` to the Search module via `Microweber\Facades\Module`.
2. Reads per-instance options from the Option service (keyed by `moduleId`).
3. Calls `SearchModule::render()` to pick the right template (`default` or `autocomplete`).
4. Mounts the `SearchComponent` Livewire component with the resolved options.
5. Returns the rendered HTML to the page.

For multiple search boxes on the same page (e.g. header + sidebar + footer), embed the tag three times. Each instance gets its own `moduleId`, so settings (placeholder, scope, variant) are independent.

---

## Per-instance settings

Staff configure each Search instance from the live-edit panel — click the module → settings open in the Filament-powered `SearchSettings` form. Available fields:

| Field | Default | What it does |
|---|---|---|
| Placeholder | `Search...` | the input's `placeholder=""` attribute |
| Parent page | `All pages (0)` | scope results to a sub-page id; 0 = whole site |
| Position | `Center` | flex-align of the input in its container (`start` / `center` / `end`) |
| Width | `500` | input width in px |
| Height | `100` | results-list height in px |
| Autocomplete | `off` | when on, picks the `autocomplete.blade.php` template |
| Template (Design tab) | `default` | explicit override of the template selection |

Each field maps directly to the Option service key `options.<field>` under the module instance — so a programmatic write is:

```php
\option_set([
    'option_key'   => 'placeholder',
    'option_value' => 'Find an article…',
    'option_group' => 'options',
    'module'       => $moduleId,
]);
```

The Livewire component reads these on `mount($moduleId)` so changes take effect on next page load (or a Livewire re-mount).

---

## Scoping to a sub-page

The `Parent page` setting (`options.data-content-id`) restricts the result set to descendants of one content row. Common patterns:

- **Per-section search** — a "Blog" section page with `data-content-id` set to the blog parent id. Results stay within the blog.
- **Help center** — a "Docs" parent. Only docs articles match.
- **Whole site** — leave at `0` (default).

Internally, this becomes the `parent` parameter on `get_content()`:

```php
get_content([
    'search_in_fields' => 'title,content,description',
    'keyword'          => $keyword,
    'limit'            => 10,
    'no_cache'         => true,
    'search_in'        => 'content',
    'parent'           => (int) $dataContentId,
]);
```

`parent = 0` is "no parent constraint", which gets all matching content rows site-wide.

---

## Autocomplete vs default variant

The `Autocomplete` toggle switches the template:

| Variant | Template file | Visual behaviour |
|---|---|---|
| Default | `default.blade.php` | input + below-input dropdown panel; results stay visible until user clicks away |
| Autocomplete | `autocomplete.blade.php` | input with absolutely-positioned popover; clicking outside dismisses |

Both call the same `SearchComponent` — only the wrapping Blade differs. Custom variants drop in alongside as new files in `Modules/Search/resources/views/templates/<your-slug>.blade.php` and are automatically picked up by the live-edit template registry.

---

## URL-param hooks

The shipped `search.js` (in `resources/assets/js/`) reads a `?q=<keyword>` query parameter on page load and writes it into the search input. So this URL works:

```
https://your-site/about?q=team
```

The page loads with the search input pre-populated. Livewire's `wire:model.live.debounce.300ms` fires after the page settles, producing live results.

Two consequences:

- **Deep linking** — share a "search the docs for `migration`" URL by hand-crafting `/docs?q=migration`.
- **Internal links** — internal navigation that should land users on a pre-searched page can use `<a href="/blog?q=performance">Find articles about performance</a>` without any Search-specific routing.

---

## Programmatic search

If you need to run a search outside the embedded module tag (e.g. from a CLI command or a custom controller), call the underlying helper directly:

```php
$results = get_content([
    'search_in_fields' => 'title,content,description',
    'keyword'          => 'how to deploy',
    'limit'            => 20,
    'no_cache'         => true,
    'search_in'        => 'content',
    'parent'           => 0,
]);
```

This is the exact pattern `SearchComponent::search()` uses. You skip the Livewire wrapping and get the raw rows; sanitize the keyword yourself (the component does `strip_tags()` + `mb_substr(0, 200)` — copy the same calls).

---

## Reacting to search activity from custom code

There are **no Search-owned events** fired today. `OrderWasPaid` style listeners don't exist for "user searched". The Search module's `EventServiceProvider` is reserved for future analytics work.

If you need to track searches:

- **Client-side** — add a Livewire `dispatch('mw-search-performed', { keyword })` line inside `SearchComponent::search()` and listen for it in your custom JS.
- **Server-side** — wrap the `get_content()` call with your own logging service that fires whatever you need.

Both are app-level customisations, not Search-module modifications.

---

## Security notes

`SearchComponent::search()` runs two sanitization steps on every query (AI-130 / SEC-05):

1. `strip_tags($keyword)` — strips any HTML tags, defending against XSS if the search input is later rendered (the Livewire view does render the typed text in the input value, escaping via Blade's `&#123;&#123; }}` syntax — strip_tags is belt + braces).
2. `mb_substr($keyword, 0, 200)` — caps the keyword length at 200 characters, defending against DoS via gigantic `LIKE '%<200KB>%'` queries.

SQL injection is handled at the `get_content()` layer by parameterised queries — but the Search module's sanitization is defense-in-depth in case the helper is ever changed in a way that doesn't parameterise correctly.

If you fork `SearchComponent` or call `get_content()` directly elsewhere, **always run the same two sanitization steps**.

---

## Performance considerations

Because the underlying query is SQL `LIKE '%keyword%'`, performance degrades linearly with `content` table size:

- < 5,000 rows → sub-100ms typical
- 5,000–50,000 rows → 100–500ms typical
- > 50,000 rows → noticeable lag without FULLTEXT or external index

If your install has > 50,000 content rows and search is becoming sluggish:

1. **Add a FULLTEXT index** on `content.title`, `content.content`, `content.description`. (Content module migration; affects all content searches site-wide, not just this module.)
2. **Reduce the per-instance scope** — set `data-content-id` to a sub-tree so the `WHERE parent IN (...)` filters down the candidate rows first.
3. **Long-term: adopt Laravel Scout + Meilisearch/Algolia** at the Content module level. Search would inherit the upgrade automatically since it just delegates.

The Search module itself has no internal optimisation knobs — the path to speed is upstream in Content.
