# Page Module — API Reference

## REST API

Base URL: `/api/pages`

All routes are registered in `Modules/Page/routes/api.php` and authenticated through Laravel Sanctum for write operations (POST/PUT/DELETE). Read operations (GET) are public.

### `GET /api/pages` — list

List paginated active pages.

Query parameters:

| Param | Type | Default | Notes |
|---|---|---|---|
| `limit` | int | `15` | Page size (max enforced by controller validation) |
| `page` | int | `1` | Page number |
| `parent` | int | — | Filter by parent ID |
| `search` | string | — | Search title + content_body |

Response shape (`AnonymousResourceCollection`):

```json
{
    "data": [
        {
            "id": 3,
            "title": "About Us",
            "url": "about",
            "link": "https://yoursite.com/about",
            "content_body": "<p>...</p>",
            "is_home": 0,
            "is_active": 1,
            "parent": 0,
            "created_at": "2026-05-13T10:00:00.000000Z",
            "updated_at": "2026-05-13T10:00:00.000000Z"
        }
    ],
    "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
    "meta": { "current_page": 1, "last_page": 5, "per_page": 15, "total": 67 }
}
```

### `POST /api/pages` — create

Requires Sanctum bearer token with admin scope.

Request body:

```json
{
    "title": "New Landing Page",
    "url": "new-landing",
    "content_body": "<p>Hello</p>",
    "parent": 0,
    "is_active": 1,
    "layout_file": "layouts/landing.php"
}
```

Validation rules (see `PageApiController::store`):

- `title` required, string, max 255
- `url` optional, string, max 255 (auto-slugged from title if missing)
- `content_body` optional, string
- `parent` optional, integer, exists in `content`
- `is_active` optional, boolean
- `is_home` optional, boolean (caller must clear other homepage first)
- `layout_file` optional, string

Returns `201 Created` with the new Page resource.

### `GET /api/pages/{id}` — show

Returns one page. `404` if not found or `is_active = 0` for unauthenticated callers.

### `PUT /api/pages/{id}` — update

Same validation rules as `store`; all fields optional. Returns the updated Page resource.

### `DELETE /api/pages/{id}` — destroy

Soft-deletes the page (sets `is_deleted = 1`). Returns `204 No Content`. Children pages are NOT cascaded — they remain in the database with their `parent` field pointing at the deleted ID; the navigation helpers skip rows where the parent is soft-deleted.

## Eloquent reference

### `Modules\Page\Models\Page`

Extends `Modules\Content\Models\Content`. The `__construct` forces `content_type = 'page'` so a newly instantiated Page is always typed correctly even without explicit assignment.

#### Inherited attributes

All Content attributes are available: `id`, `title`, `url`, `content_body`, `content_type`, `subtype`, `parent`, `position`, `is_active`, `is_home`, `is_shop`, `is_deleted`, `layout_file`, `active_site_template`, `created_at`, `updated_at`, plus the SEO/OG/Twitter fields listed in [installation.md](./installation.md).

#### Inherited accessors

- `link` — the public URL via `content_link($id)`
- `editLink()` — admin edit URL
- `liveEditLink()` — live-edit canvas URL

#### Inherited scopes

- `active()` — `WHERE is_active = 1 AND is_deleted = 0`
- Global `PageScope` — `WHERE content_type = 'page'`

#### Factory

`Page::factory()` returns a `PageFactory` instance (Laravel factory pattern). The default state generates a random title + slug + `is_active = 1`.

```php
Page::factory()->count(5)->create();
Page::factory()->create(['is_home' => 1]);
```

## Helpers

Microweber's global content helpers all work with pages:

| Helper | Returns |
|---|---|
| `get_content('content_type=page')` | array of page rows |
| `get_page($id)` | one page row |
| `save_content($data)` | inserts/updates, returns id |
| `content_link($id)` | URL string |
| `content_parents($id)` | parents up the tree |
| `get_content_children($id)` | child rows |
| `pages_tree($options)` | full navigation HTML |

See `Modules/Content/` for the canonical helper signatures and `Modules/Content/docs/` (when present) for the helper-layer architecture decision.

## Filament admin

`Modules\Page\Filament\Resources\PageResource` provides:

- Index page with drag-and-drop reordering (`->reorderable('position')`)
- Create/edit forms with title, URL, content body (Filament Editor / Live Edit launcher), layout file selector, parent picker, SEO panel
- Bulk delete with soft-delete semantics
- Filter by status (active/inactive), parent, search by title

Related Filament page classes:

- `Modules\Page\Filament\Resources\PageResource\Pages\CreatePage`
- `Modules\Page\Filament\Resources\PageResource\Pages\EditPage`

## Events

The Content module's standard Eloquent events fire for pages (since Page extends Content). To target only pages, gate on `content_type`:

```php
\Modules\Content\Models\Content::saving(function ($model) {
    if ($model->content_type !== 'page') {
        return;
    }
    // Page-only logic here
});
```

Or attach directly to `Page`:

```php
\Modules\Page\Models\Page::saving(function (\Modules\Page\Models\Page $page) {
    // Always a page — no type check needed
});
```

## Testing

Test coverage lives at `Modules/Page/Tests/Unit/PageApiControllerTest.php` and exercises every controller method through the Laravel test client. Run with:

```bash
./vendor/bin/phpunit --filter=PageApiControllerTest
```

Or via the project's split-process runner:

```bash
./run-tests.sh
```
