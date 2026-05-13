# Page Module — Troubleshooting

Common issues and their fixes.

## Page returns 404 even though it exists in the database

**Symptom:** `/about` returns 404, but `Page::where('url', 'about')->first()` returns a row.

Checklist:

1. **`is_active = 1`?** Inactive pages are hidden from public routing. `Page::where('url', 'about')->update(['is_active' => 1])`.
2. **`is_deleted = 0`?** Soft-deleted pages stay in the DB but the public router skips them. `Page::where('url', 'about')->update(['is_deleted' => 0])`.
3. **URL slug collision?** Two rows with the same `url` cause the second to be unreachable. Run `Page::where('url', 'about')->count()` — if `> 1`, dedupe.
4. **Route cache stale?** After bulk URL changes, clear cached routes: `php artisan route:clear && php artisan config:clear`.
5. **Active template missing the layout file?** If `layout_file` references a path that doesn't exist under `Templates/<active_template>/`, the renderer may bail. Check `current_template` option vs `Templates/` directory contents.

## Homepage serves a different page than expected

**Symptom:** `/` renders the wrong page.

Two rows have `is_home = 1`:

```php
\Modules\Page\Models\Page::where('is_home', 1)->get(['id', 'title', 'url']);
```

Microweber serves whichever row the query returns first. Fix:

```php
\Modules\Page\Models\Page::where('is_home', 1)->update(['is_home' => 0]);

\Modules\Page\Models\Page::find($correctHomeId)->update(['is_home' => 1]);
```

## Live-edit link returns 404 or "page not found"

**Symptom:** Clicking the Live Edit button leads to an error.

Common causes:

1. **You're not authenticated as an admin** — Live Edit requires an admin session.
2. **The `current_template` option doesn't match an installed template** — set it to a valid directory under `Templates/`:

    ```sql
    SELECT option_value FROM options WHERE option_key = 'current_template' AND option_group = 'template';
    ```

3. **The page's `layout_file` references a file that doesn't exist** — set it to empty to fall back to the template default:

    ```php
    $page->update(['layout_file' => '']);
    ```

## Pages tree doesn't render children

**Symptom:** `pages_tree()` shows only top-level pages.

Children must have a valid `parent` ID pointing at an **active**, **non-deleted** page. If the parent was soft-deleted:

```php
$orphans = \Modules\Page\Models\Page::whereIn('parent', function ($q) {
    $q->select('id')->from('content')->where('is_deleted', 1);
})->get();
```

Re-parent the orphans:

```php
$orphans->each(fn ($p) => $p->update(['parent' => 0]));
```

## Filament admin shows posts/products mixed in with pages

**Symptom:** The Pages list in the Filament admin shows non-page rows.

The `PageScope` may not be applied because:

1. **You queried the parent `Content` model directly** — `Content::query()` returns everything. Use `Page::query()` instead.
2. **A `withoutGlobalScopes()` call leaked through a Filament Resource override** — search for `withoutGlobalScopes` in custom code and remove from page-specific queries.
3. **The scope class was renamed but the autoloader wasn't refreshed** — `composer dump-autoload`.

## REST API returns 401 on POST /api/pages

**Symptom:** Sanctum reject the write request.

1. The token must be issued for an admin user — verify the user's role.
2. The token must have the `*` ability or an explicit `pages.create` scope (project-specific).
3. CSRF: if calling from the same origin, include `X-XSRF-TOKEN` header. From a script with a bearer token, this is not required.

## Migration error: "column content_meta_description already exists"

**Symptom:** `php artisan migrate` fails on the Page module.

Page does NOT own migrations for the SEO columns — they live in the Content module. If a duplicate `add_seo_columns` migration exists in `Modules/Page/Database/migrations/`, delete it; the Content migration is authoritative.

## Test factory generates non-unique URLs

**Symptom:** `Page::factory()->count(10)->create()` fails with a unique-index violation on `url`.

The factory uses a faker word + a numeric suffix. If you need true uniqueness across a large batch:

```php
Page::factory()
    ->count(10)
    ->sequence(fn ($s) => ['url' => 'page-' . $s->index])
    ->create();
```

## Page `content_body` HTML is being escaped on render

**Symptom:** `<p>...</p>` shows up as text in the rendered page.

The template is using `{{ $page->content_body }}` (escaped). Use `{!! $page->content_body !!}` (raw) for HTML-typed body fields. Microweber's frontend already sanitizes content on save — raw output is safe for admin-authored content.

## Where to file bugs / contribute fixes

- The Page module is in `Modules/Page/`. Tests live in `Modules/Page/Tests/`.
- Cross-cutting issues (scopes, shared table columns) belong against the Content module.
- File issues against the main Microweber project on GitHub. Include:
  - PHP version
  - Microweber commit SHA
  - Active template name
  - A minimal `Page::create([...])` repro
  - Whether you can reproduce after `composer dump-autoload && php artisan config:clear`
