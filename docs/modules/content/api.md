# Content Module — API Reference

## REST API

Base URL: `/api/content`

Routes registered in `Modules/Content/routes/api.php`. Write methods (POST/PUT/DELETE) require Sanctum bearer authentication with admin scope. Read methods (GET) are public.

### `GET /api/content` — list

Generic list across all content types. Filter by `content_type` query param.

| Param | Type | Default | Notes |
|---|---|---|---|
| `content_type` | string | — | `'page'`, `'post'`, `'product'`, `'category'`, or any custom type |
| `parent` | int | — | Filter by parent content ID |
| `limit` | int | `15` | Page size |
| `page` | int | `1` | Page number |
| `search` | string | — | Substring match on title + content_body |
| `is_active` | int | `1` | Pass `0` to include inactive |
| `category` | int / int[] | — | Category filter |
| `order_by` | string | `position` | Sort column |
| `order` | string | `asc` | `asc` or `desc` |

### `POST /api/content` — create

Required:

- `title` (string, max 255)
- `content_type` (string — `'page'` / `'post'` / `'product'` / etc.)

Optional:

- `url`, `subtype`, `parent`, `content_body`, `description`, `is_active`, `posted_at`, `layout_file`, all SEO fields

```json
{
    "title": "New Page",
    "content_type": "page",
    "url": "new-page",
    "is_active": 1
}
```

### `GET /api/content/{id}` — show

Returns a single content row regardless of type. `404` if not found or `is_active = 0` for unauthenticated callers.

### `PUT /api/content/{id}` — update

All fields optional. Cannot change `content_type` to a value that has a different scope without further bookkeeping (don't mutate type fields in place).

### `DELETE /api/content/{id}` — destroy

Soft-deletes (`is_deleted = 1`). Returns `204`. For hard delete, use `Content::forceDelete()` directly via tinker.

## Eloquent reference

### `Modules\Content\Models\Content`

The parent model. All typed content extends this.

#### Important attributes

`id`, `content_type`, `subtype`, `subtype_value`, `parent`, `title`, `url`, `description`, `content_body`, `content`, `posted_at`, `is_home`, `is_shop`, `is_active`, `is_deleted`, `require_login`, `created_by`, `position`, plus all SEO/OG/Twitter fields.

#### Accessors

- `link` — the public URL via `content_link($id)`
- `editLink()` — admin edit URL
- `liveEditLink()` — Live Edit canvas URL
- `image` — first attached media row's filename
- `description` — falls back to a stripped excerpt of `content_body` if `description` is empty

#### Scopes

- `active()` — `is_active = 1 AND is_deleted = 0`
- `inactive()` — `is_active = 0`
- `trashed()` — `is_deleted = 1`
- `published()` — `is_active = 1 AND (posted_at IS NULL OR posted_at <= NOW())`
- `whereCategoryIds($ids)` — filter via `categories_items`

#### Category methods

- `setCategories(array $ids)` — replace category attachments
- `categoriesIds()` — return attached category IDs
- `categories()` — Eloquent relation to `Category`

#### Tag methods (via the Tags module trait)

- `tag(array|string $tags)` / `retag(array $tags)` / `untag(array $tags)`
- `tagNames()` — array of attached tag names
- `withAnyTag(array $tags)` query scope
- `withAllTags(array $tags)` query scope

#### Content-data methods

- `setContentDataByFieldName($key, $value)`
- `getContentDataByFieldName($key)`
- `contentData` accessor — collection of all data rows

#### Translation methods

- `setTranslation($field, $locale, $value)`
- `getTranslation($field, $locale)`

## Lifecycle events

```php
namespace Modules\Content\Events;
```

| Event class | Constructor signature | Fires |
|---|---|---|
| `ContentIsCreating` | `(Content $content, array $attrs)` | Before insert |
| `ContentIsUpdating` | `(Content $content, array $changes)` | Before update |
| `ContentWasCreated` | `(Content $content)` | After insert |
| `ContentWasUpdated` | `(Content $content)` | After update |
| `ContentWasDeleted` | `(Content $content)` | After soft-delete |
| `ContentWasRestored` | `(Content $content)` | After `is_deleted` → 0 |
| `ContentWasDestroyed` | `(Content $content)` | After `forceDelete()` |

Listeners register in any service provider via `Event::listen()`.

## Global helpers

| Helper | Signature | Purpose |
|---|---|---|
| `save_content(array $data)` | returns int $id | insert/update |
| `get_content(array|string $params)` | returns array | typed/untyped list |
| `get_content_by_id(int $id)` | returns array | single row |
| `delete_content(array $params)` | returns bool | soft delete |
| `content_link(int $id)` | returns string | public URL |
| `content_parents(int $id)` | returns array | walk up tree |
| `get_content_children(int $id)` | returns array | direct children |
| `pages_tree(array $opts)` | returns string | full nav HTML |

## Repository

`app('content_manager')` returns the `ContentRepository` singleton (defined at `Modules/Content/Repositories/ContentRepository.php`). Methods of note:

- `getByUrl(string $url)` — single row by URL slug
- `getByTitle(string $title)` — single row by title (rarely useful — titles aren't unique)
- `set_published(array $params)` / `set_unpublished(array $params)` — bulk publish toggle
- `helpers->bulk_assign(array $params)` — bulk category attach
- `helpers->copy(array $params)` — duplicate a content row
- `createDefaultShopPage()` / `createDefaultBlogPage()` — install-time scaffolding

## Filament admin

`Modules\Content\Filament\Resources\ContentResource` is the generic admin resource — typed children (Page, Post, Product) extend it for type-specific filters. Direct use is rare in operator flows.

## Testing

```bash
./vendor/bin/phpunit --filter=ContentApiControllerTest
```

Coverage lives at `Modules/Content/Tests/`. Use the per-typed factory (`Page::factory()`, `Post::factory()`, `Product::factory()`) rather than instantiating `Content` directly — the typed factories ensure `content_type` is set correctly.
