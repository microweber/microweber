# Content Module — Troubleshooting

Common issues and their fixes.

## Typed model returns rows of the wrong type

**Symptom:** `Page::query()->get()` returns rows where `content_type != 'page'` (posts/products mixed in).

Causes:

1. **`withoutGlobalScopes()` somewhere upstream** — search for it and remove from typed queries.
2. **Direct `Content::query()` usage** — Content itself has NO scope. Use the typed model.
3. **Stale composer autoload after class moves** — `composer dump-autoload`.
4. **A custom scope binding registered after the typed scope and overriding it** — check your AppServiceProvider boot order.

## ContentWasCreated event doesn't fire

**Symptom:** Listener registered, but the event never reaches it.

1. **Listener registered AFTER the model was used** — register listeners in a ServiceProvider's `boot()`, not lazily.
2. **Event class autoload missing** — `use Modules\Content\Events\ContentWasCreated` must resolve. Run `composer dump-autoload`.
3. **Bulk operations bypass events** — `Content::query()->update([...])`, `\DB::table('content')->insert([...])`, and `truncate()` all skip events. Use `Content::create()` / `$content->update()` / `$content->delete()` instead.
4. **Event listener throws** — exceptions in a listener can be swallowed depending on Laravel's queue config. Wrap your handler in try/catch and log.

## Soft-delete leaves orphan rows in joined tables

**Symptom:** After `delete_content(['id' => 5])`, the row in `categories_items` / `taggable` / `media` still has `rel_id = 5`.

Microweber's soft-delete only sets `content.is_deleted = 1` — it does NOT cascade to joined tables. The scopes (`active()`, the global PageScope, etc.) hide the deleted row from queries, so the orphans are functionally invisible. To clean them up:

```php
// Hard cleanup
\DB::table('categories_items')->where('rel_id', $id)->where('rel_type', 'content')->delete();
\DB::table('media')->where('rel_id', $id)->where('rel_type', 'content')->delete();
\DB::table('taggable')->where('taggable_id', $id)->delete();
```

Or hard-delete the content row entirely:

```php
\Modules\Content\Models\Content::find($id)?->forceDelete();
```

(That fires `ContentWasDestroyed`.)

## content_data writes succeed but reads return null

**Symptom:** `$content->setContentDataByFieldName('foo', 'bar')` runs without error but `$content->getContentDataByFieldName('foo')` returns `null`.

1. **Stale model instance** — the write didn't refresh `$content->contentData`. Call `$content->refresh()` or re-query.
2. **Wrong `rel_type`** — confirm the write inserted with `rel_type = 'content'`. Inspect: `\DB::table('content_data')->where('rel_id', $id)->get()`.
3. **Cache hit** — `getContentDataByFieldName` caches its result for the request lifecycle. Outside of one request the cache resets; within one request, mutations may not be visible to subsequent reads.

## REST API returns 401 on writes

1. The token must be a Sanctum bearer for an admin user.
2. The route must be inside the protected group in `Modules/Content/routes/api.php` — verify the request path matches.
3. CORS preflight failing — confirm `OPTIONS /api/content/{id}` returns the expected headers.

## Migration error: "column already exists"

**Symptom:** `php artisan migrate` fails complaining about duplicate columns on `content`.

Two modules tried to add the same column. Check `Modules/Content/database/migrations/` AND any typed module's migrations for duplicates. The Content module is authoritative for shared columns (SEO, OG, Twitter, etc.); typed modules should NOT add columns to `content` — they share the table.

## URL slug collision across content types

URLs are not globally unique by default. A Page and a Post can both have `url = 'about'`. The router resolves by type heuristics, but the result is unpredictable.

For uniqueness, add a save event:

```php
\Modules\Content\Models\Content::saving(function ($content) {
    $exists = \Modules\Content\Models\Content::query()
        ->where('url', $content->url)
        ->where('id', '!=', $content->id)
        ->where('is_deleted', 0)
        ->exists();
    if ($exists) {
        throw new \RuntimeException("URL '{$content->url}' is already in use");
    }
});
```

## Revisions consume too much disk

**Symptom:** `content_fields` table grows to gigabytes.

1. Set the option `max_revisions_per_content` (default 20) to a lower value — the next save prunes older revisions.
2. Manual prune:

    ```php
    \DB::table('content_fields')
        ->where('created_at', '<', now()->subMonths(6))
        ->delete();
    ```

3. Disable revisions entirely (loses rollback feature):

    ```php
    \DB::table('options')->updateOrInsert(
        ['option_key' => 'revision_history_enabled', 'option_group' => 'content'],
        ['option_value' => '0']
    );
    ```

## Translations don't apply when locale switches

**Symptom:** `app()->setLocale('es')` then read `$content->title` — still English.

1. **Translation row missing** — confirm with `\DB::table('content_translations')->where('rel_id', $id)->get()`.
2. **Model cache** — re-fetch the model after locale change: `$content = Content::find($id);`.
3. **Locale not in `config/app.php` supported list** — Microweber may fall back to default.

## Custom content type's scope returns 0 rows

**Symptom:** `Event::all()` returns empty even though `content` table has `content_type = 'event'` rows.

1. **Scope not registered** — confirm `Event::booted()` calls `static::addGlobalScope(new EventScope)` and the parent `booted()` is called first.
2. **`content_type` value mismatch** — confirm DB rows have EXACTLY `'event'`, not `'events'` or `'Event'`.
3. **`is_active = 0` AND `Event::active()->get()`** — the `active()` scope filters them out. Use `Event::query()->get()` for all.

## Where to file bugs / contribute fixes

- Content module: `Modules/Content/`. Tests live in `Modules/Content/Tests/`.
- Typed-content bugs that aren't shared-scope issues belong against Page / Post / Product.
- File on the main Microweber GitHub project. Include:
  - PHP version
  - Microweber commit SHA
  - Active template name + locale
  - A minimal `Content::create([...])` repro
  - Whether the issue reproduces after `composer dump-autoload && php artisan config:clear && php artisan view:clear`
