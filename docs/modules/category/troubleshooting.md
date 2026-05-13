# Category Module — Troubleshooting

## Content not appearing in a category archive

Checklist:

1. **`categories_items` row exists?** Verify directly:

    ```php
    \DB::table('categories_items')
        ->where('parent_id', $categoryId)
        ->where('rel_type', 'content')
        ->where('rel_id', $contentId)
        ->count();  // should be 1
    ```

2. **Content `is_active = 1` AND `is_deleted = 0`?** The archive query (`Post::active()->whereCategoryIds(...)`) filters out inactive/deleted rows.
3. **`posted_at` in the future?** The archive's `where('posted_at', '<=', now())` gate hides scheduled posts.
4. **Wrong `rel_type` on the join row?** Should be `'content'`. Older installs sometimes have `'post'` or namespaced class strings — normalize with:

    ```sql
    UPDATE categories_items SET rel_type = 'content' WHERE rel_type NOT IN ('content');
    ```

## Category appears twice in the tree

**Symptom:** `categories_tree()` renders the same category twice.

Usually indicates a **cycle** in the hierarchy — a category's `parent_id` chain eventually points back at itself.

Detect:

```php
$rows = \DB::table('categories')->pluck('parent_id', 'id')->toArray();

foreach ($rows as $id => $parent) {
    $seen = [$id];
    $cursor = $parent;
    while ($cursor && ! in_array($cursor, $seen)) {
        $seen[] = $cursor;
        $cursor = $rows[$cursor] ?? null;
    }
    if ($cursor !== null && in_array($cursor, $seen)) {
        echo "Cycle detected at category id={$id}\n";
    }
}
```

Fix by setting one of the cycle members to `parent_id = 0`.

## Slug 404 — category not found by URL

**Symptom:** `/categories/tutorials` returns 404.

1. **Slug matches Str::slug(title)?** The slug is derived live — special chars in the title produce different slugs. `\Str::slug($category->title)` should equal the URL segment.
2. **Routing not catching the URL?** Check `routes/web.php` for the category route pattern.
3. **`is_hidden = 1`?** Public-side lookup may filter these out.

## Deleting a category doesn't remove from menus

**Symptom:** Menu items pointing at the deleted category still appear.

Menu items are stored in the `menus` table by `parent_id` — deleting the category leaves orphan menu rows. The destroy controller does NOT cascade to menus. Clean up:

```sql
DELETE FROM menus WHERE parent_id = $categoryId AND rel = 'category';
```

## `setCategories()` doesn't persist

**Symptom:** `$post->setCategories([3])` runs but `$post->categoriesIds()` returns empty immediately after.

1. **`$post` not saved?** Refresh from DB: `$post = $post->fresh()`.
2. **Transaction not committed?** If inside a transaction, the join rows are not visible until commit.
3. **Category ID doesn't exist?** `setCategories` filters unknown IDs silently.

## `whereCategoryIds()` returns 0 rows

**Symptom:** Query produces empty result despite known matches.

1. **Wrong scope chain?** `Post::whereCategoryIds()` alone returns nothing — wrap with `Post::active()->whereCategoryIds()` or `Post::query()->whereCategoryIds()`.
2. **Stale categories_items row pointing at a soft-deleted content?** The join doesn't exclude `is_deleted` rows on its own; the consumer-side scope (`active()`) does.
3. **Wrong `rel_type` on join rows** (see "Content not appearing in archive" above).

## Position not respected on tree render

**Symptom:** `categories_tree()` renders siblings in the wrong order.

1. **`position` set?** Check `categories` table — siblings with `position = 0` may sort alphabetically as a tiebreaker.
2. **Filament drag-and-drop saved?** The drag handler debounces saves — wait a beat then refresh.
3. **`order_by` overridden by the tree helper?** Custom helpers might force `created_at` ordering.

## Translation title not applied

**Symptom:** `app()->setLocale('es')` then read `$category->title` — still English.

1. **`categories_translations` row exists?** `\DB::table('categories_translations')->where('category_id', $id)->where('locale', 'es')->get()`.
2. **Model refresh needed?** Re-fetch the category after the locale switch.
3. **Trait not applied?** Verify `Category` model uses the same Translatable trait other modules use.

## Hierarchy depth exceeded

**Symptom:** Very deep nesting causes slow tree renders or stack overflow.

`categories_tree()` accepts `max_depth` — default is typically unlimited. For sites with deep trees, set it to a reasonable bound:

```php
echo categories_tree(['max_depth' => 4, 'parent_id' => 0]);
```

For programmatic recursion, watch for cycles (see above) AND keep a depth counter to bail at a sane maximum.

## Filament admin shows "no records" but DB has rows

1. **`rel_type` filter applied in the resource?** Some installs filter to `rel_type = 'content'` only — categories with different `rel_type` won't show.
2. **Cache:** `php artisan cache:clear && php artisan filament:cache-components`.
3. **Tenant filter:** if a multi-site / tenant binding is active, the admin may scope to the current tenant only.

## Where to file bugs

- Category module: `Modules/Category/`. Tests live in `Modules/Category/Tests/`.
- Issues with how a typed model uses categories (e.g. "my Post category archive is broken") belong against Post or Content first — the category itself is rarely the bug.
