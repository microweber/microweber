# Post Module — API Reference

## REST API

Base URL: `/api/posts`

Routes registered in `Modules/Post/routes/api.php`. Write methods (POST/PUT/DELETE) require Sanctum bearer authentication with admin scope. Read methods (GET) are public.

### `GET /api/posts` — list

List paginated active posts.

Query parameters:

| Param | Type | Default | Notes |
|---|---|---|---|
| `limit` | int | `15` | Page size |
| `page` | int | `1` | Page number |
| `parent` | int | — | Filter by parent blog page ID |
| `category` | int / int[] | — | Filter by category ID(s) |
| `tag` | string[] | — | Filter by tag name(s) |
| `search` | string | — | Substring match on title + content_body |
| `order_by` | string | `posted_at` | Column to order by |
| `order` | string | `desc` | `asc` or `desc` |

Response shape (`AnonymousResourceCollection`):

```json
{
    "data": [
        {
            "id": 42,
            "title": "Getting Started with Microweber",
            "url": "getting-started",
            "link": "https://yoursite.com/blog/getting-started",
            "description": "A quick intro to the CMS.",
            "content_body": "<p>...</p>",
            "parent": 8,
            "posted_at": "2026-05-13T10:00:00.000000Z",
            "is_active": 1,
            "created_by": 1,
            "image": "/media/default/post-hero.jpg",
            "categories": [{"id": 3, "name": "Tutorials"}],
            "tags": ["laravel", "cms"],
            "created_at": "2026-05-13T10:00:00.000000Z",
            "updated_at": "2026-05-13T10:00:00.000000Z"
        }
    ],
    "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
    "meta": { "current_page": 1, "last_page": 5, "per_page": 15, "total": 67 }
}
```

### `POST /api/posts` — create

Requires Sanctum admin bearer token.

```json
{
    "title": "New Post",
    "url": "new-post",
    "description": "Short summary.",
    "content_body": "<p>Full body here.</p>",
    "parent": 8,
    "is_active": 1,
    "posted_at": "2026-05-13T10:00:00Z",
    "categories": [3, 7],
    "tags": ["laravel", "cms"]
}
```

Validation rules (see `PostApiController::store`):

- `title` required, string, max 255
- `url` optional, string, max 255 (auto-slugged if missing)
- `description` optional, string
- `content_body` optional, string
- `parent` optional, integer, exists in `content`
- `is_active` optional, boolean
- `posted_at` optional, ISO 8601 datetime
- `categories` optional, array of integers
- `tags` optional, array of strings

Returns `201 Created` with the new Post resource.

### `GET /api/posts/{id}` — show

Returns one post (with categories + tags eager-loaded). `404` if not found or `is_active = 0` for unauthenticated callers.

### `PUT /api/posts/{id}` — update

Same validation as `store`; all fields optional. `categories` / `tags` arrays REPLACE the existing attachment set.

### `DELETE /api/posts/{id}` — destroy

Soft-deletes (sets `is_deleted = 1`). Returns `204 No Content`. Category/tag attachments stay in the DB but are filtered out by the global PostScope and the active() scope.

## Eloquent reference

### `Modules\Post\Models\Post`

Extends `Modules\Content\Models\Content`. `__construct` forces `content_type = 'post'`.

#### Fillable

`subtype`, `subtype_value`, `content_type`, `parent`, `layout_file`, `active_site_template`, `title`, `url`, `content_meta_title`, `content`, `description`, `content_body`, `content_meta_keywords`, `original_link`, `require_login`, `created_by`, `is_home`, `is_shop`, `is_active`, `is_deleted`, `updated_at`, `created_at`.

#### Inherited accessors

- `link` — public URL via `content_link($id)`
- `editLink()` — admin edit URL
- `liveEditLink()` — Live Edit canvas URL
- `image` — first attached media filename
- `description` — falls back to a stripped excerpt of `content_body` if `description` is empty

#### Inherited scopes

- `active()` — `WHERE is_active = 1 AND is_deleted = 0`
- Global `PostScope` — `WHERE content_type = 'post'`

#### Category methods

- `setCategories(array $ids)` — replace category attachments
- `categoriesIds()` — return attached category IDs
- `categories()` — Eloquent relation to Category model
- `whereCategoryIds($ids)` query scope — filter by category attachment

#### Tag methods (via the Tags module)

- `tag(array|string $tags)` — additive
- `retag(array $tags)` — replace
- `untag(array $tags)` — remove
- `tagNames()` — array of attached tag names
- `withAnyTag(array $tags)` query scope
- `withAllTags(array $tags)` query scope

#### Factory

```php
Post::factory()->create();
Post::factory()->count(10)->create();
Post::factory()->create(['parent' => $blogId, 'is_active' => 1]);
```

`Modules/Post/Database/Factories/PostFactory.php` is the source of truth.

## Helpers

| Helper | Returns |
|---|---|
| `get_content('content_type=post')` | array of post rows |
| `save_content(['content_type' => 'post', ...])` | inserts/updates, returns id |
| `content_link($id)` | URL string |
| `Post::active()->paginate($n)` | Eloquent paginator |

## Filament admin

`Modules\Post\Filament\Resources\PostResource`:

- Index: drag-and-drop reorder, sortable columns (title / posted_at / status), filter chips (status, category)
- Create / Edit: tabs for Content, SEO, Media, Categories + Tags, Settings
- Bulk actions: publish / unpublish / delete
- Live Edit integration via `liveEditLink()` button on the edit form

## Events

Inherited from Content. To target only posts:

```php
\Modules\Post\Models\Post::saving(function (\Modules\Post\Models\Post $post) {
    // Always a post — content_type forced in __construct
});
```

Or gate on `content_type` in a Content-wide listener:

```php
\Modules\Content\Models\Content::saving(function ($model) {
    if ($model->content_type !== 'post') return;
    // Post-only logic
});
```

## Testing

```bash
./vendor/bin/phpunit --filter=PostApiControllerTest
```

Coverage lives at `Modules/Post/Tests/Unit/PostApiControllerTest.php`.
