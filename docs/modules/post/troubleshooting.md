# Post Module — Troubleshooting

Common issues and their fixes.

## Post is published but doesn't appear on the blog index

Checklist:

1. **`is_active = 1`?** Inactive posts are hidden from public queries. `Post::where('id', $id)->update(['is_active' => 1])`.
2. **`is_deleted = 0`?** Soft-deleted posts stay in the DB but the active() scope skips them.
3. **`posted_at <= now()`?** If your blog index gates on `posted_at`, future-dated posts stay hidden until their time arrives. Check: `Post::find($id)->posted_at`.
4. **Correct `parent` page ID?** Posts attached to a different blog page won't show on the one you're viewing. `Post::where('id', $id)->value('parent')` should match the blog page's id.
5. **Pagination?** The post may be on a later page. Append `?page=2` and check.

## Categories aren't attaching

**Symptom:** `$post->setCategories([3, 7])` runs without error but `$post->categoriesIds()` returns empty.

1. **Category IDs must exist** — `setCategories` silently skips IDs that don't resolve in the `categories` table.
2. **Verify after save:** `$post->save()` then re-query — some related fields are flushed only on save.
3. **Inspect the join table directly:**

    ```php
    \DB::table('categories_items')
        ->where('rel_type', \Modules\Content\Models\Content::class)
        ->where('rel_id', $post->id)
        ->get();
    ```

## Tags lost after a save

**Symptom:** You called `$post->tag(['a', 'b'])`, then `$post->save()`, and the tags are gone.

`tag()` is additive but writes the attachments **immediately**, not on save. Subsequent `$post->save()` calls do not re-write tags. If tags vanish, look for a `retag()` call elsewhere (e.g. in a save event that resets tags from a form field).

## Global PostScope returns pages/products

**Symptom:** `Post::query()->get()` includes rows where `content_type !== 'post'`.

Usually caused by:

1. **`withoutGlobalScopes()` somewhere in a chain** — search the codebase, remove from post-specific queries.
2. **A direct `Modules\Content\Models\Content::query()` call** instead of `Post::query()` — Content has no scope, returns everything.
3. **Composer autoload stale** after class moves — `composer dump-autoload`.

## REST API list returns 0 results despite posts in the database

1. **All posts are `is_active = 0`?** The public index filter excludes them. Authenticate with an admin token to bypass.
2. **`parent` filter is set in the request?** Removing the param widens the result set.
3. **`posted_at` filter on the controller?** Check `PostApiController::index` for any `where('posted_at', '<=', now())` gate.

## Author archive shows posts from deleted users

The `created_by` FK is not enforced — when a user is deleted, the post's `created_by` stays pointing at the missing id. The template should null-coalesce:

```php
$author = \App\Models\User::find($post->created_by);
if (! $author) {
    // Show "Author removed" or hide the byline
}
```

## Featured image not displaying

1. **Media row attached?** `\DB::table('media')->where(['rel_type' => 'content', 'rel_id' => $post->id])->count()` should be ≥ 1.
2. **`media_type = 'image'`?** Other types (`video`, `file`) won't show via `$post->image` accessor.
3. **File exists on disk?** The accessor returns the filename, not a guarantee the file is reachable. Check `public/media/default/<filename>` and the configured filesystem disk.

## Scheduled post publishes early

**Symptom:** Post with `posted_at` set to a future time shows up immediately.

Microweber doesn't have a scheduling cron job by default — scheduling is purely "the public blog index hides posts where `posted_at > now()`." If your template doesn't apply that gate, scheduled posts appear immediately. Add the gate to the blog index query:

```php
Post::active()->where('posted_at', '<=', now())->orderBy('posted_at', 'desc')->paginate(10);
```

## Tags module not loaded — `tag()` method missing

**Symptom:** `BadMethodCallException: Call to undefined method tag()`.

The Tags module is a separate package — confirm it's installed:

```bash
composer show conner/tagging
```

If missing, `composer require conner/tagging` and re-run `composer dump-autoload`.

## Post URL collides with another content type's URL

URLs are not globally unique by default — a page and a post can both have `url = 'about'`. The router resolves based on type heuristics. If you need uniqueness, add a check in a `Post::saving` event:

```php
Post::saving(function (Post $post) {
    $collision = \Modules\Content\Models\Content::where('url', $post->url)
        ->where('id', '!=', $post->id)
        ->exists();
    if ($collision) {
        throw new \RuntimeException("URL '{$post->url}' is already in use");
    }
});
```

## Where to file bugs / contribute fixes

- Post module: `Modules/Post/`. Tests live in `Modules/Post/Tests/`.
- Cross-cutting issues (scopes, shared `content` table columns, SEO fields) belong against the Content module.
- File issues on the main Microweber GitHub project. Include:
  - PHP version
  - Microweber commit SHA
  - Active template name
  - A minimal `Post::create([...])` repro
  - Whether the issue reproduces after `composer dump-autoload && php artisan config:clear && php artisan view:clear`
