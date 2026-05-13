# Category Module — API Reference

## REST API

Base URL: `/api/categories`

Routes registered in `Modules/Category/routes/api.php`. The Category module ships two controllers historically — the primary is `CategoriesApiController` (plural). Write methods require Sanctum bearer with admin scope.

### `GET /api/categories` — list

| Param | Type | Default | Notes |
|---|---|---|---|
| `parent_id` | int | — | Filter by parent (0 = top-level) |
| `rel_type` | string | `'content'` | Restrict to a relation type |
| `is_hidden` | int | `0` | Pass `1` to include hidden |
| `limit` | int | `15` | Page size |
| `page` | int | `1` | Page number |
| `search` | string | — | Substring match on title + description |
| `order_by` | string | `position` | Column |
| `order` | string | `asc` | `asc` / `desc` |

Response shape:

```json
{
    "data": [
        {
            "id": 3,
            "title": "Tutorials",
            "description": "How-to guides and walkthroughs.",
            "parent_id": 0,
            "rel_type": "content",
            "is_hidden": 0,
            "position": 1,
            "url": "tutorials",
            "items_count": 27,
            "created_at": "2026-05-13T10:00:00Z",
            "updated_at": "2026-05-13T10:00:00Z"
        }
    ],
    "links": { ... },
    "meta": { ... }
}
```

### `POST /api/categories` — create

Required:

- `title` (string, max 255)

Optional:

- `description`, `content_meta_description`, `parent_id` (default 0), `rel_type` (default 'content'), `is_hidden` (default 0), `position`

```json
{
    "title": "Tutorials",
    "description": "How-to guides.",
    "parent_id": 0,
    "is_hidden": 0
}
```

### `GET /api/categories/{id}` — show

Returns one category with its `items_count` (count of `categories_items` rows). `404` if hidden and caller unauthenticated.

### `PUT /api/categories/{id}` — update

All fields optional. Changing `parent_id` to one of its own descendants is rejected (would create a cycle).

### `DELETE /api/categories/{id}` — destroy

Hard-deletes the category row AND all `categories_items` join rows pointing at it. Content rows themselves are NOT deleted. Returns `204`.

The destroy controller also re-parents children: any category whose `parent_id` was the deleted category gets its `parent_id` set to the deleted category's `parent_id` (so the tree doesn't disconnect).

## Eloquent reference

### `Modules\Category\Models\Category`

Standalone Eloquent model — does NOT extend Content (unlike Page/Post/Product). The `categories` table is its own dedicated table.

#### Attributes

`id`, `title`, `description`, `content_meta_description`, `parent_id`, `rel_type`, `is_hidden`, `position`, `created_at`, `updated_at`.

#### Relations

- `children()` — `hasMany(Category::class, 'parent_id')`
- `parent()` — `belongsTo(Category::class, 'parent_id')`
- `items()` — `hasMany(CategoryItem::class, 'parent_id')` — the join rows

#### Scopes / methods

- `scopeByUrl($query, $url)` — match against `Str::slug($title)`
- `link()` — returns the public URL for this category

### `Modules\Category\Models\CategoryItem`

Pivot model for the `categories_items` join table. Attributes: `id`, `parent_id` (category id), `rel_type`, `rel_id`.

## Helpers

| Helper | Returns |
|---|---|
| `categories_list(array $params)` | array of category rows (legacy) |
| `category_link(int $id)` | URL string |
| `categories_tree(array $opts)` | full HTML tree for menu rendering |

`categories_tree` is the canonical helper for sidebar / menu rendering — it accepts `parent_id`, `rel_type`, `ul_class`, `li_class`, `include_empty`, and `max_depth` options.

## Content-side methods (provided by the Content module)

When Page/Post/Product attach to categories, they use these methods provided by the Content trait/model:

- `setCategories(array $ids)` — replace category attachments
- `categoriesIds()` — return attached category IDs
- `categories()` — Eloquent relation to Category
- `whereCategoryIds($ids)` query scope on the Content model

See the Content module docs (`docs/modules/content/`) for the canonical signatures.

## Filament admin

`Modules\Category\Filament\Resources\CategoryResource` provides:

- Tree view with drag-and-drop reorder + drag-into-parent for re-nesting
- Create / Edit form with title, description, parent picker, is_hidden toggle, SEO meta
- Bulk delete with descendant handling
- Search by title

## Testing

```bash
./vendor/bin/phpunit --filter=CategoryApiControllerTest
```

Test coverage lives in `Modules/Category/Tests/`. Most coverage is integration-style — creating a hierarchy, attaching content, querying via `whereCategoryIds()`, asserting result counts.
