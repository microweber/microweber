# Menu Module — Troubleshooting

## Menu renders empty in the template

1. **Wrong name?** `<module type="menu" name="header" />` — verify the exact name in the `menus` table: `\DB::table('menus')->pluck('name')`.
2. **`is_active = 0`?** Inactive menus are skipped by the render layer. Re-enable in the admin or set directly: `Menu::where('name', 'header')->update(['is_active' => 1])`.
3. **No items?** `MenuItem::where('parent_id', $menuId)->count()` should be ≥ 1. Items are not auto-created with the container.
4. **All items `is_active = 0`?** Same gate applies to items.

## Items appear in the admin but not on the public site

1. **Cache stale** — `app('menu_repository')->flushCache()` or `\Cache::tags(['menu','navigation'])->flush()` then refresh.
2. **`is_active = 0`** — inactive items are excluded by the public render layer (the admin shows them dimmed).
3. **`parent_id` orphan** — an item with `parent_id` pointing at a deleted Menu container is invisible to the renderer. Re-parent:

    ```php
    \Modules\Menu\Models\MenuItem::where('parent_id', $deletedMenuId)->update(['parent_id' => $newMenuId]);
    ```

## Drag-and-drop reorder doesn't persist

1. **Network failure** — devtools network tab. A 419 (CSRF) means the session expired — refresh.
2. **Position field not updating** — confirm `MenusList::reorder()` calls `position` updates on every dragged row, not just the dragged one.
3. **Cache not flushed after the save** — `app('menu_manager')` is the safer interface; direct `\DB::` writes bypass cache invalidation.

## Active-item highlighting wrong

1. **URL match is exact** — `MenuItem.url = '/about'` won't match `MenuItem.url = '/about/'`. Normalize on save.
2. **`content_id` mismatch** — when a Page's id changes (rare — usually only on full DB restore), the menu's `content_id` becomes stale. Re-attach the menu item to the new Page id.
3. **Template's CSS doesn't target `.active`** — verify the rendered HTML actually has `class="menu-item active"` on the current page's item, then check the template's CSS selector.

## Mega menu columns out of order

1. **`column` field set?** `\DB::table('menu_items')->where('parent_id', $megaMenuId)->pluck('column')`. Items with `column = 0` (unset) render in column 1.
2. **`position` within column** — items are ordered by `position` ASC within each column. Renumber if drag-and-drop got confused.
3. **`mega_menu_columns` field on the container** — set to 0 means "not a mega menu, render as flat dropdown." Set to N (1..6) for N-column layout.

## Translations don't apply

1. **`menu_items_translations` row exists?** `\DB::table('menu_items_translations')->where('menu_item_id', $id)->where('locale', 'es')->get()`.
2. **`app()->setLocale('es')` called?** Check the locale before reading `$item->title`.
3. **`TranslateMenu` provider not registered?** Verify in `Modules/Menu/Providers/MenuServiceProvider.php` — the provider should boot on app start.

## REST API returns 0 menus

1. **`name` filter** — case-sensitive; `name=Header` ≠ `name=header`.
2. **`is_active=0`** — public list defaults to active-only. Pass `?is_active=0` to include inactive menus.
3. **Admin scope missing** — write endpoints (POST/PUT/DELETE) require Sanctum admin scope.

## Menu rendered twice in the template

Usually a template-side bug: `header.blade.php` includes the menu module-tag AND a custom `pages_tree()` call. Pick one. The `<module type="menu" />` tag is preferred — `pages_tree()` is for static page trees, not user-editable menus.

## "Menu item not found" error after a soft-delete

`MenuItem` doesn't have a soft-delete column by default — deletes are hard. If you've added `deleted_at`:

```php
MenuItem::withTrashed()->find($id);  // bypass the soft-delete filter
```

## Nested children render flat

The render layer walks children up to a depth set by the template (often 2 or 3). Items deeper than that aren't rendered.

Increase via the helper:

```php
echo menu_render(['name' => 'header', 'depth' => 5]);
```

Or override the template's `menu.blade.php` to recurse without a depth cap.

## Where to file bugs

- Menu module: `Modules/Menu/`. Tests in `Modules/Menu/Tests/`.
- Cross-cutting issues (content_id resolution, category_id resolution) belong against the linked module first.
- Rendering bugs are usually template-side (the active template's `header.blade.php` / `menu.blade.php`) — check those before filing against this module.
