# Troubleshooting

Common Search module issues with diagnostic steps.

---

## Search box doesn't appear on the page

**Symptom.** Embedded `<module type="search" />` but nothing renders.

**Cause.** Either the Microweber template engine isn't processing the tag, or the live-edit module-resolver can't find the Search module.

**Diagnosis.**

```bash
# Confirm the module is registered with Microweber
php artisan tinker --execute='dd(\MicroweberPackages\Module\Facades\Module::list("search"));'
```

If the result is empty:

- Check that `Modules/Search/module.json` exists and declares `"name": "search"`.
- Check that `Modules\Search\Providers\SearchServiceProvider` is loaded — `php artisan package:discover` should pick it up automatically; if it doesn't, add it to `config/app.php` providers.
- Check the page's content for the literal string `<module type="search" />` (not encoded entities like `&lt;module...`); the engine processes raw tags.

If the result is non-empty but the page still doesn't render the search box, check the page's HTML for a `<!-- module not found: search -->` HTML comment — the template engine emits that when it sees the tag but the module-render method throws.

---

## Search runs but no results appear

**Symptom.** Type a query, the spinner shows, but the results list stays empty (and the keyword definitely matches real content).

**Cause.** Most common: the keyword is being passed to `get_content()` but the Content module's underlying query is filtering by a content type / parent / status that excludes the matches.

**Diagnosis.**

```php
// Run the same query SearchComponent does, manually:
$results = get_content([
    'search_in_fields' => 'title,content,description',
    'keyword'          => 'your keyword',
    'limit'            => 10,
    'no_cache'         => true,
    'search_in'        => 'content',
    'parent'           => 0,
]);
dd(count($results), $results);
```

If `count($results) === 0`:

- Check the matching content row's `is_active` column — `get_content()` filters to `is_active = 1` by default.
- Check the matching row's `is_deleted` / `deleted_at` — soft-deleted rows are excluded.
- Confirm the keyword actually appears in `title`, `content`, or `description` (not just in `custom_field_data` — Search doesn't look there).

If the manual query returns results but the live search still shows zero, the per-instance `data-content-id` (parent scope) is filtering them out. Open the module settings, set Parent page to `All pages (0)`, and retest.

---

## "No results found" flashes briefly before the real results appear

**Symptom.** Empty-state message renders for ~300ms before the actual results replace it.

**Cause.** The 300ms debounce on `wire:model.live.debounce.300ms` plus the rendering-while-loading state. The component renders the empty state when `count($searchResults) === 0 && mb_strlen($searchQuery) >= 2`, but on first keystroke that condition briefly holds before `search()` runs.

**Fix.** Guard the empty-state render on the `$isLoading` flag too:

```blade
@elseif (mb_strlen($searchQuery) >= 2 && ! $isLoading)
    <div class="alert alert-info">{{ _e('No results found') }}</div>
@endif
```

This is in the shipped template — if you've forked it, propagate this guard from the upstream copy.

---

## Search is slow on a large site (> 50k content rows)

**Symptom.** Queries take > 500ms; spinner visible for noticeable time on every keystroke.

**Cause.** `get_content()` runs a SQL `LIKE '%keyword%'` against `content.title`, `content.content`, and `content.description`. Without a FULLTEXT index, this is a full table scan.

**Diagnosis.**

```sql
EXPLAIN SELECT id, title FROM content
WHERE (title LIKE '%hello%' OR content LIKE '%hello%' OR description LIKE '%hello%')
  AND is_active = 1
LIMIT 10;
```

If `EXPLAIN` shows `type: ALL` and `rows: <table_size>`, you have a full scan.

**Fixes, in order of effort:**

1. **Add a FULLTEXT index** on the searchable columns:

   ```sql
   ALTER TABLE content ADD FULLTEXT idx_content_search (title, content, description);
   ```

   Then upgrade `get_content()` to use `MATCH(...) AGAINST(...)` instead of `LIKE`. This is a Content module change, not a Search change.

2. **Scope every Search instance to a sub-tree** so the candidate row count is reduced before the LIKE runs.

3. **Adopt Laravel Scout + Meilisearch/Algolia** at the Content module level. Search auto-inherits the upgrade — its `get_content()` call doesn't know or care what backend is behind it.

The Search module itself has no internal tuning. The fix is always upstream in Content.

---

## Special characters in the search input break the query

**Symptom.** User searches for `O'Brien` or `<script>` and the search either returns nothing or throws an error in browser DevTools.

**Cause.** `SearchComponent::search()` runs `strip_tags()` on the keyword, which removes `<script>` entirely (working as intended). For apostrophes / quotes / unicode, the issue is usually downstream in `get_content()` parameter binding — which uses prepared statements and should handle them safely.

**Diagnosis.**

```php
// Confirm sanitization output
$kw = mb_substr(strip_tags("O'Brien"), 0, 200);
dd($kw);   // 'O\'Brien' (apostrophe preserved)

$results = get_content([
    'search_in_fields' => 'title,content,description',
    'keyword'          => $kw,
    'limit'            => 10,
    'no_cache'         => true,
    'search_in'        => 'content',
]);
dd($results);
```

If apostrophes are stripped from the post-sanitization output, it's a `strip_tags` regression (very rare — apostrophes aren't HTML).

If `get_content()` throws on the apostrophe, it's a SQL bind issue — file an Issue against the Content module; do not workaround in Search by manually escaping (defense-in-depth in the wrong place is harm).

For unicode (e.g. Chinese, Arabic, emoji): make sure your `content` table is `utf8mb4_unicode_ci` collation, not the legacy `utf8_general_ci`. The latter silently drops 4-byte chars in `LIKE` matches.

---

## Autocomplete popover stays open after clicking a result

**Symptom.** Variant: `autocomplete.blade.php`. User clicks a result, the page navigates, but on the new page the popover is still visible (briefly or persistently).

**Cause.** The autocomplete popover's "close on outside click" handler runs in the shipped `search.js` — if a custom template or another script is suppressing the document `click` event, the close fails.

**Diagnosis.**

```js
// In browser DevTools console, on the page with the autocomplete:
window.addEventListener('click', (e) => console.log('document click', e.target), true);
```

Click a result. If you don't see the log line, another script is calling `e.stopPropagation()` or `e.preventDefault()` higher up. Find the culprit (usually a navigation interceptor or analytics tracker) and adjust so it doesn't swallow the click.

If you do see the log line but the popover doesn't close, the `search.js` close handler isn't bound. Confirm the script is loaded:

```bash
curl -I http://your-site/modules/search/js/search.js
# Expect: 200 OK
```

If 404, the asset wasn't published — run `cd Modules/Search && npm run build`.

---

## Custom template not appearing in the Filament template selector

**Symptom.** Dropped a new template at `Modules/Search/resources/views/templates/<my-slug>.blade.php` but the Design tab dropdown still shows only `default` and `autocomplete`.

**Cause.** The template registry is built by `LiveEditModuleSettings::getTemplatesFormSchema()` (inherited base class). It scans the `templates/` directory but caches results. After adding a file, the cache may need a clear.

**Fix.**

```bash
php artisan cache:clear
php artisan view:clear
```

Reload the live-edit panel. Your new slug should appear.

If it still doesn't, confirm:

- The filename has no spaces or dots besides `.blade.php`.
- The file contains at least a renderable Blade body (an empty file is filtered out).
- The file is readable by the PHP process (`ls -l Modules/Search/resources/views/templates/`).
