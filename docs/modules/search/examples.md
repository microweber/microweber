# Examples

Four recipes for the most common Search-module integrations.

---

## 1. Basic site-wide search box in the header

Drop the module tag into your template's header partial:

```blade
{{-- Templates/<your-template>/header.blade.php --}}
<header>
    <a href="/" class="logo">Brand</a>
    <nav>{!! menu(['template' => 'inline']) !!}</nav>
    <module type="search" />
</header>
```

Out of the box, this gives you a 500px-wide input with `Search...` placeholder, no scope (whole-site search), and the default below-input dropdown variant.

Open the page in live-edit mode, click the search box, and tune from the settings panel:

- **Placeholder** → "What are you looking for?"
- **Width** → 300 (fits a tighter header)
- **Autocomplete** → on (gets you the typeahead variant)

Each search-tag instance has its own settings, so a search box in the footer can be configured independently.

---

## 2. Section-scoped search on a docs hub page

If you have a `/docs` parent page with child articles, scope a search box to only return docs results:

1. Embed the search tag on the `/docs` page:

   ```blade
   <module type="search" />
   ```

2. In the live-edit settings for this instance:
   - **Parent page** → select the `/docs` page from the dropdown
   - **Placeholder** → "Search the docs"
   - **Autocomplete** → on

That's it. The `parent` parameter on the underlying `get_content()` call is now set to the docs page's id; only descendants of `/docs` will appear in results.

Programmatic equivalent (e.g. when seeding the module instance):

```php
\option_set([
    'option_key'   => 'data-content-id',
    'option_value' => 42,                  // the /docs page id
    'option_group' => 'options',
    'module'       => $moduleId,
]);
\option_set([
    'option_key'   => 'placeholder',
    'option_value' => 'Search the docs',
    'option_group' => 'options',
    'module'       => $moduleId,
]);
\option_set([
    'option_key'   => 'autocomplete',
    'option_value' => true,
    'option_group' => 'options',
    'module'       => $moduleId,
]);
```

---

## 3. Custom result-row template with a thumbnail

The default `livewire/search/index.blade.php` renders results as plain text links. To show a thumbnail alongside each result, copy the view to your app-level Blade override path and edit the foreach body:

```bash
# Make a project-level override directory if you don't have one
mkdir -p resources/views/vendor/modules/search/livewire/search/

# Copy the shipped Blade
cp Modules/Search/resources/views/livewire/search/index.blade.php \
   resources/views/vendor/modules/search/livewire/search/index.blade.php
```

Edit the new copy to add thumbnails:

```blade
{{-- resources/views/vendor/modules/search/livewire/search/index.blade.php --}}
<div class="mw-search-wrap">
    <input
        type="text"
        class="form-control"
        wire:model.live.debounce.300ms="searchQuery"
        placeholder="{{ $placeholder }}"
    />

    @if ($isLoading)
        <div class="spinner-border spinner-border-sm" role="status"></div>
    @endif

    @if (count($searchResults))
        <ul class="list-group">
            @foreach ($searchResults as $result)
                <li class="list-group-item d-flex align-items-center gap-2">
                    @if (!empty($result['thumbnail']))
                        <img src="{{ $result['thumbnail'] }}"
                             alt=""
                             class="search-result-thumb"
                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;" />
                    @endif
                    <a href="{{ content_link($result['id']) }}" class="flex-grow-1">
                        <strong>{{ $result['title'] }}</strong>
                        @if (!empty($result['description']))
                            <small class="d-block text-muted">{{ \Str::limit(strip_tags($result['description']), 120) }}</small>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    @elseif (mb_strlen($searchQuery) >= 2)
        <div class="alert alert-info">{{ _e('No results found') }}</div>
    @endif
</div>
```

Laravel's view-override convention picks up your `resources/views/vendor/...` path automatically — no provider registration needed.

To get the `thumbnail` field populated, ensure `get_content()` returns it for your content type. Page/post content rows include `thumbnail` natively; product rows use `image` — adjust the field name in the foreach accordingly.

---

## 4. Deep-link from a marketing campaign into pre-searched results

The shipped `search.js` reads `?q=<keyword>` from the URL on page load and writes it into the input. Use this to send users into a pre-filtered view:

```html
<!-- In a marketing email or social post -->
<a href="https://your-site/blog?q=migration">
    See our migration guides
</a>
```

When the user arrives at `/blog`, the search input on that page (if you've embedded one with `<module type="search" />`) auto-populates with `migration` and live-runs the search after the 300ms debounce.

For maximum discoverability:

1. Embed the search tag on every section page (`/blog`, `/docs`, `/products`).
2. Scope each instance to its section via `data-content-id`.
3. Pre-build canonical deep-link URLs (`/blog?q=performance`, `/docs?q=auth`, etc.) for use in marketing.

The deep-link reaches the right scope automatically because each section's search instance has its own scope already configured — the URL just carries the keyword.

Note: the URL query param is **not** part of the Livewire component's `$query` whitelist, so URL-keyword updates only fire on the initial mount. Subsequent typing does NOT update the URL — that's a separate Livewire `$queryString` opt-in if you want it (uncommented in `SearchComponent` — add `'searchQuery' => ['as' => 'q']` to the `$queryString` property to enable). The shipped behaviour is one-way (URL → input), which is the lower-risk default.
